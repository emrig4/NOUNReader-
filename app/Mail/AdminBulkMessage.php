<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminBulkMessage extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    // Use simple strings, NOT objects for queue compatibility
    public $userEmail;
    public $userName;
    public $subject;
    public $message;
    public $type;
    public $personalTouch;
    public $adminName;

    public function __construct($messageData)
    {
        // Store simple strings, NOT objects - this prevents serialization errors
        $this->userEmail = $messageData['userEmail'] ?? '';
        $this->userName = $messageData['userName'] ?? 'User';
        $this->subject = $messageData['subject'] ?? '';
        $this->message = $messageData['message'] ?? '';
        $this->type = $messageData['type'] ?? 'custom';
        $this->personalTouch = $messageData['personalTouch'] ?? true;
        $this->adminName = $messageData['adminName'] ?? 'Admin';
        
        // Specify queue for better processing
        $this->onQueue('emails');
    }

    public function build()
    {
        // Dynamic subject based on type
        $emailSubject = $this->getSubjectByType();
        
        return $this->subject($emailSubject)
                   ->view('emails.admin-bulk-message')
                   ->with([
                       'userEmail' => $this->userEmail,
                       'userName' => $this->userName,
                       'subject' => $this->subject,
                       'message' => $this->message,
                       'type' => $this->type,
                       'personalTouch' => $this->personalTouch,
                       'adminName' => $this->adminName,
                   ]);
    }

    private function getSubjectByType()
    {
        $typePrefixes = [
            'reminder' => '⏰ Reminder: ',
            'wish' => '🎉 Best Wishes: ',
            'announcement' => '📢 Announcement: ',
            'test' => '🧪 Test Message: ',
            'custom' => '📧 Message: '
        ];

        $prefix = $typePrefixes[$this->type] ?? '📧 ';
        return $prefix . $this->subject;
    }
}