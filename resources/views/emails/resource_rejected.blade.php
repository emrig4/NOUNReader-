@component('mail::message')
# Document Review Result

Hello {{ $resource->user->first_name }},

Your submitted document has been reviewed by our admin team.

**Document Title:** {{ $resource->title }}
**Review Date:** {{ $resource->approved_at->format('F d, Y \a\t g:i A') }}

Unfortunately, this document was not approved for publication. 

**Admin Notes:**
{{ $resource->admin_notes ?? 'No additional notes provided.' }}

**You can:**
- Review the feedback and resubmit
- Make necessary improvements
- Contact our support team if you have questions

Thank you for your understanding.

Best regards,
readprojecttopics Team
@endcomponent


