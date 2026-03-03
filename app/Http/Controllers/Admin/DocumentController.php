<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Services\DocumentNotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DocumentController extends Controller
{
    protected $notificationService;

    public function __construct(DocumentNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
        
        // Middleware for admin access
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a listing of pending documents for admin review
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Document::with('user:id,first_name,last_name,email')
                ->orderBy('created_at', 'desc');

            // Filter by status if provided
            if ($request->has('status') && in_array($request->status, ['pending_review', 'approved', 'rejected'])) {
                $query->where('status', $request->status);
            }

            $documents = $query->paginate(15);

            $formattedDocuments = $documents->getCollection()->map(function ($document) {
                return [
                    'id' => $document->id,
                    'filename' => $document->original_filename,
                    'status' => $document->status,
                    'status_name' => $document->status_name,
                    'user' => [
                        'id' => $document->user->id,
                        'name' => $document->user->first_name . ' ' . $document->user->last_name,
                        'email' => $document->user->email,
                    ],
                    'original_url' => $document->original_url,
                    'approved_url' => $document->approved_url,
                    'rejection_reason' => $document->rejection_reason,
                    'submitted_at' => $document->created_at->format('M d, Y \a\t g:i A'),
                    'updated_at' => $document->updated_at->format('M d, Y \a\t g:i A'),
                ];
            });

            return response()->json([
                'success' => true,
                'documents' => $formattedDocuments,
                'pagination' => [
                    'current_page' => $documents->currentPage(),
                    'last_page' => $documents->lastPage(),
                    'per_page' => $documents->perPage(),
                    'total' => $documents->total(),
                    'from' => $documents->firstItem(),
                    'to' => $documents->lastItem(),
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to fetch admin documents', [
                'admin_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch documents.'
            ], 500);
        }
    }

    /**
     * Download the original document from S3
     */
    public function downloadOriginal(Document $document): \Illuminate\Http\Response
    {
        try {
            // Check admin permissions
            if (!Auth::user()->can('admin') && !Auth::user()->can('publisher')) {
                abort(403, 'Unauthorized access to download document.');
            }

            if (!Storage::disk('s3')->exists($document->original_path)) {
                abort(404, 'Original file not found.');
            }

            $fileContents = Storage::disk('s3')->get($document->original_path);
            $mimeType = Storage::disk('s3')->mimeType($document->original_path);
            
            return response($fileContents)
                ->header('Content-Type', $mimeType)
                ->header('Content-Disposition', 'attachment; filename="' . $document->original_filename . '"');

        } catch (\Exception $e) {
            \Log::error('Original document download failed', [
                'document_id' => $document->id,
                'admin_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            abort(500, 'Failed to download original document.');
        }
    }

    /**
     * Upload approved PDF document
     */
    public function uploadApproved(Request $request, Document $document): JsonResponse
    {
        $request->validate([
            'approved_document' => 'required|file|mimes:pdf|max:10240', // 10MB max, PDF only
        ]);

        try {
            // Check admin permissions
            if (!Auth::user()->can('admin') && !Auth::user()->can('publisher')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access.'
                ], 403);
            }

            $file = $request->file('approved_document');
            
            // Generate approved file path: /documents/{user_id}/approved/{same_name}.pdf
            $originalName = pathinfo($document->original_filename, PATHINFO_FILENAME);
            $approvedFilename = $originalName . '.pdf';
            $approvedPath = "documents/{$document->user_id}/approved/{$approvedFilename}";

            // Upload approved PDF to S3
            $uploadedFile = Storage::disk('s3')->putFileAs(
                "documents/{$document->user_id}/approved",
                $file,
                $approvedFilename
            );

            if (!$uploadedFile) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload approved document.'
                ], 500);
            }

            // Update document with approved file info
            $document->update([
                'approved_path' => $approvedPath,
                'approved_filename' => $approvedFilename,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Approved document uploaded successfully.',
                'document' => [
                    'id' => $document->id,
                    'approved_filename' => $document->approved_filename,
                    'approved_url' => $document->approved_url,
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Approved document upload failed', [
                'document_id' => $document->id,
                'admin_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to upload approved document.'
            ], 500);
        }
    }

    /**
     * Approve document
     */
    public function approve(Request $request, Document $document): JsonResponse
    {
        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            // Check admin permissions
            if (!Auth::user()->can('admin') && !Auth::user()->can('publisher')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access.'
                ], 403);
            }

            // Check if approved document exists
            if (!$document->approved_path) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please upload the approved PDF document first.'
                ], 400);
            }

            // Update document status
            $document->update([
                'status' => 'approved',
            ]);

            // Send notification to user
            $this->notificationService->notifyDocumentApproved($document);

            \Log::info('Document approved by admin', [
                'document_id' => $document->id,
                'user_id' => $document->user_id,
                'admin_id' => Auth::id(),
                'notes' => $request->notes
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Document approved successfully.',
                'document' => [
                    'id' => $document->id,
                    'status' => $document->fresh()->status,
                    'status_name' => $document->fresh()->status_name,
                    'updated_at' => $document->fresh()->updated_at->format('M d, Y \a\t g:i A'),
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Document approval failed', [
                'document_id' => $document->id,
                'admin_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to approve document.'
            ], 500);
        }
    }

    /**
     * Reject document
     */
    public function reject(Request $request, Document $document): JsonResponse
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            // Check admin permissions
            if (!Auth::user()->can('admin') && !Auth::user()->can('publisher')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access.'
                ], 403);
            }

            // Update document status and rejection reason
            $document->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
            ]);

            // Send notification to user
            $this->notificationService->notifyDocumentRejected($document, $request->rejection_reason);

            \Log::info('Document rejected by admin', [
                'document_id' => $document->id,
                'user_id' => $document->user_id,
                'admin_id' => Auth::id(),
                'rejection_reason' => $request->rejection_reason,
                'notes' => $request->notes
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Document rejected successfully.',
                'document' => [
                    'id' => $document->id,
                    'status' => $document->fresh()->status,
                    'status_name' => $document->fresh()->status_name,
                    'rejection_reason' => $document->fresh()->rejection_reason,
                    'updated_at' => $document->fresh()->updated_at->format('M d, Y \a\t g:i A'),
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Document rejection failed', [
                'document_id' => $document->id,
                'admin_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to reject document.'
            ], 500);
        }
    }

    /**
     * Get document statistics for admin dashboard
     */
    public function statistics(): JsonResponse
    {
        try {
            // Check admin permissions
            if (!Auth::user()->can('admin') && !Auth::user()->can('publisher')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access.'
                ], 403);
            }

            $stats = [
                'total' => Document::count(),
                'pending_review' => Document::pendingReview()->count(),
                'approved' => Document::approved()->count(),
                'rejected' => Document::rejected()->count(),
                'today_submissions' => Document::whereDate('created_at', today())->count(),
                'this_week_submissions' => Document::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            ];

            return response()->json([
                'success' => true,
                'statistics' => $stats
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to fetch document statistics', [
                'admin_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch statistics.'
            ], 500);
        }
    }
}