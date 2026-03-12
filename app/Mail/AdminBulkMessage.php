<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdminBulkMessage extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $userName;
    public $subject;
    public $message;
    public $type;
    public $personalTouch;
    public $adminName;

    public function __construct($messageData)
    {
        $this->user = $messageData['user'];
        // Ensure userName is always set
        $this->userName = $messageData['user']->name ?? $messageData['user']->first_name ?? 'User';
        $this->subject = $messageData['subject'];
        $this->message = $messageData['message'];
        $this->type = $messageData['type'];
        $this->personalTouch = $messageData['personalTouch'];
        $this->adminName = $messageData['adminName'];
        
        // Set queue connection
        $this->onQueue('emails');
    }

    public function build()
    {
        $emailSubject = $this->getSubjectByType();
        
        return $this->subject($emailSubject)
                   ->view('emails.admin-bulk-message')
                   ->with([
                       'user' => $this->user,
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