<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class AdminBulkMessage extends Mailable
{
    public $user;
    public $subject;
    public $message;
    public $type;
    public $personalTouch;
    public $adminName;

    public function __construct($messageData)
    {
        $this->user = $messageData['user'];
        $this->subject = $messageData['subject'];
        $this->message = $messageData['message'];
        $this->type = $messageData['type'];
        $this->personalTouch = $messageData['personalTouch'];
        $this->adminName = $messageData['adminName'];
    }

    public function build()
    {
        // Dynamic subject based on type
        $emailSubject = $this->getSubjectByType();
        
        return $this->subject($emailSubject)
                   ->view('emails.admin-bulk-message')
                   ->with([
                       'user' => $this->user,
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