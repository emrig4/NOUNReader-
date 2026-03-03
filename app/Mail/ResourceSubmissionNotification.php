<?php

namespace App\Mail;

use App\Modules\Resource\Models\Resource;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;

class ResourceSubmissionNotification extends Mailable
{
    public $resource;
    public $type; // 'user' or 'admin'

    public function __construct(Resource $resource, $type)
    {
        $this->resource = $resource;
        $this->type = $type;
    }

    public function build()
    {
        if ($this->type === 'admin') {
            return $this->subject('New Document Submitted for Review')
                        ->to('emrig4@gmail.com')
                        ->markdown('emails.resource_submission_admin');
        } else {
            return $this->subject('Document Submission Received')
                        ->markdown('emails.resource_submission_user');
        }
    }
}
