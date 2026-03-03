<?php

namespace App\Services;

use App\Models\Document;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class DocumentNotificationService
{
    /**
     * Send notification to user when document is submitted
     */
    public function notifyDocumentSubmitted(Document $document): void
    {
        try {
            $user = $document->user;
            
            Mail::to($user->email)->send(new \App\Mail\DocumentNotificationMail(
                $user,
                'submitted',
                $document,
                "Your document has been submitted and is awaiting review."
            ));
            
            Log::info('Document submitted notification sent', [
                'document_id' => $document->id,
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send document submitted notification', [
                'document_id' => $document->id,
                'user_id' => $document->user_id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send notification to admin when new document is submitted
     */
    public function notifyAdminDocumentSubmitted(Document $document): void
    {
        try {
            $adminEmail = 'emrig4@gmail.com';
            $adminUser = User::where('email', $adminEmail)->first();
            
            if ($adminUser) {
                Mail::to($adminEmail)->send(new \App\Mail\DocumentNotificationMail(
                    $adminUser,
                    'admin_submitted',
                    $document,
                    "A user has submitted a new document and is awaiting review."
                ));
                
                Log::info('Admin notification sent for new document submission', [
                    'document_id' => $document->id,
                    'user_id' => $document->user_id,
                    'admin_email' => $adminEmail
                ]);
            } else {
                Log::warning('Admin user not found for document notification', [
                    'document_id' => $document->id,
                    'admin_email' => $adminEmail
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Failed to send admin notification for document submission', [
                'document_id' => $document->id,
                'user_id' => $document->user_id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send notification to user when document is approved
     */
    public function notifyDocumentApproved(Document $document): void
    {
        try {
            $user = $document->user;
            
            Mail::to($user->email)->send(new \App\Mail\DocumentNotificationMail(
                $user,
                'approved',
                $document,
                "Your document has been approved."
            ));
            
            Log::info('Document approved notification sent', [
                'document_id' => $document->id,
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send document approved notification', [
                'document_id' => $document->id,
                'user_id' => $document->user_id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send notification to user when document is rejected
     */
    public function notifyDocumentRejected(Document $document, string $reason = null): void
    {
        try {
            $user = $document->user;
            
            $message = "Your document has been rejected.";
            if ($reason) {
                $message .= " Reason: " . $reason;
            }
            
            Mail::to($user->email)->send(new \App\Mail\DocumentNotificationMail(
                $user,
                'rejected',
                $document,
                $message
            ));
            
            Log::info('Document rejected notification sent', [
                'document_id' => $document->id,
                'user_id' => $user->id,
                'user_email' => $user->email,
                'reason' => $reason
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send document rejected notification', [
                'document_id' => $document->id,
                'user_id' => $document->user_id,
                'error' => $e->getMessage()
            ]);
        }
    }
}