<?php

namespace App\Mail;

use App\Models\Document;
use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\URL;

class DocumentNotificationMail extends Mailable
{
    public $user;
    public $type;
    public $document;
    public $message;
    public $actionUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, string $type, Document $document, string $message)
    {
        $this->user = $user;
        $this->type = $type;
        $this->document = $document;
        $this->message = $message;
        
        // Set action URL based on notification type
        $this->actionUrl = $this->getActionUrl();
    }

    /**
     * Get the action URL based on notification type
     */
    private function getActionUrl(): string
    {
        return match($this->type) {
            'submitted' => route('dashboard'), // User dashboard
            'admin_submitted' => route('admin.documents.index'), // Admin documents page
            'approved' => route('dashboard'), // User dashboard
            'rejected' => route('dashboard'), // User dashboard
            default => route('dashboard'),
        };
    }

    /**
     * Get the email subject based on type
     */
    public function getSubject(): string
    {
        return match($this->type) {
            'submitted' => 'Document Submitted - Awaiting Review',
            'admin_submitted' => 'New Document Submitted - Review Required',
            'approved' => 'Document Approved Successfully',
            'rejected' => 'Document Review Result',
            default => 'Document Notification',
        };
    }

    /**
     * Get the email title based on type
     */
    public function getTitle(): string
    {
        return match($this->type) {
            'submitted' => 'Document Submitted Successfully',
            'admin_submitted' => 'New Document Pending Review',
            'approved' => 'Congratulations! Your Document is Approved',
            'rejected' => 'Document Review Update',
            default => 'Document Notification',
        };
    }

    /**
     * Get the background color based on type for styling
     */
    public function getBgColor(): string
    {
        return match($this->type) {
            'submitted' => '#3B82F6', // Blue
            'admin_submitted' => '#F59E0B', // Amber
            'approved' => '#10B981', // Green
            'rejected' => '#EF4444', // Red
            default => '#6B7280', // Gray
        };
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->getSubject())
                    ->view('emails.document-notification')
                    ->with([
                        'user' => $this->user,
                        'document' => $this->document,
                        'message' => $this->message,
                        'actionUrl' => $this->actionUrl,
                        'subject' => $this->getSubject(),
                        'title' => $this->getTitle(),
                        'bgColor' => $this->getBgColor(),
                        'type' => $this->type,
                    ]);
    }
}