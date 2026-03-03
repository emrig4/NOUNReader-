<?php

namespace App\Mail;

use App\Modules\Resource\Models\Resource;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;

class ResourceApprovalNotification extends Mailable
{
    public $resource;
    public $type; // 'approved' or 'rejected'
    public $reason; // rejection reason (if rejected)

    public function __construct(Resource $resource, $type, $reason = null)
    {
        $this->resource = $resource;
        $this->type = $type;
        $this->reason = $reason;
    }

    public function build()
    {
        if ($this->type === 'approved') {
            return $this->subject('🎉 Your Document Has Been Approved!')
                        ->view('emails.resource-approved-email');
        } else {
            return $this->subject('📋 Document Review Update - Action Required')
                        ->view('emails.resource-rejected-email')
                        ->with(['reason' => $this->reason]);
        }
    }
}