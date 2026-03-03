<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Services\DocumentNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class DocumentController extends Controller
{
    protected $notificationService;

    public function __construct(DocumentNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Upload and submit document for review
     */
    public function uploadAndSubmit(Request $request): JsonResponse
    {
        $request->validate([
            'document' => 'required|file|mimes:doc,docx,pdf|max:10240', // 10MB max
        ]);

        try {
            $user = Auth::user();
            $file = $request->file('document');
            
            // Generate S3 path: /documents/{user_id}/{original_filename}
            $originalFilename = $file->getClientOriginalName();
            $fileExtension = $file->getClientOriginalExtension();
            $cleanFilename = pathinfo($originalFilename, PATHINFO_FILENAME);
            
            // Create unique filename to avoid conflicts
            $uniqueFilename = $cleanFilename . '_' . time() . '.' . $fileExtension;
            $s3Path = "documents/{$user->id}/{$uniqueFilename}";

            // Upload file to S3
            $uploadedFile = Storage::disk('s3')->putFileAs(
                "documents/{$user->id}",
                $file,
                $uniqueFilename
            );

            if (!$uploadedFile) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload file to storage.'
                ], 500);
            }

            // Create document record
            $document = Document::create([
                'user_id' => $user->id,
                'original_path' => $s3Path,
                'original_filename' => $originalFilename,
                'status' => 'pending_review',
            ]);

            // Send notifications
            $this->notificationService->notifyDocumentSubmitted($document);
            $this->notificationService->notifyAdminDocumentSubmitted($document);

            return response()->json([
                'success' => true,
                'message' => 'Document uploaded successfully and is awaiting review.',
                'document' => [
                    'id' => $document->id,
                    'filename' => $document->original_filename,
                    'status' => $document->status_name,
                    'submitted_at' => $document->created_at->format('M d, Y \a\t g:i A')
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Document upload failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while uploading the document. Please try again.'
            ], 500);
        }
    }

    /**
     * Get user's documents
     */
    public function userDocuments(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $documents = $user->documents()
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($document) {
                    return [
                        'id' => $document->id,
                        'filename' => $document->original_filename,
                        'status' => $document->status,
                        'status_name' => $document->status_name,
                        'original_url' => $document->original_url,
                        'approved_url' => $document->approved_url,
                        'rejection_reason' => $document->rejection_reason,
                        'submitted_at' => $document->created_at->format('M d, Y \a\t g:i A'),
                        'updated_at' => $document->updated_at->format('M d, Y \a\t g:i A'),
                    ];
                });

            return response()->json([
                'success' => true,
                'documents' => $documents
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to fetch user documents', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch documents.'
            ], 500);
        }
    }

    /**
     * Download user's document
     */
    public function downloadDocument(Document $document): \Illuminate\Http\Response
    {
        try {
            // Check if user owns this document or has permission
            if ($document->user_id !== Auth::id() && !Auth::user()->can('admin')) {
                abort(403, 'Unauthorized access to document.');
            }

            // Determine which file to download
            $filePath = $document->approved_path ?: $document->original_path;
            $fileName = $document->approved_filename ?: $document->original_filename;

            if (!Storage::disk('s3')->exists($filePath)) {
                abort(404, 'File not found.');
            }

            $fileContents = Storage::disk('s3')->get($filePath);
            
            return response($fileContents)
                ->header('Content-Type', Storage::disk('s3')->mimeType($filePath))
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        } catch (\Exception $e) {
            \Log::error('Document download failed', [
                'document_id' => $document->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            abort(500, 'Failed to download document.');
        }
    }
}