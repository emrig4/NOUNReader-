@extends('layouts.admin')

@section('content')
<section class="container">
    <!-- Header -->
    <div class="card lg:flex p-4 mb-10">
        <div class="flex items-center">
            <a href="{{ route('admin.resources.pending') }}" class="btn btn_secondary mr-4">
                <i class="la la-arrow-left"></i> Back to Pending
            </a>
            <div>
                <h2 class="text-xl leading-tight">Resource Review</h2>
                <p class="text-gray-600 mt-1">ID: {{ $resource->id }} | Status: 
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        {{ ucfirst($resource->approval_status) }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <div class="card p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Resource Details</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Title</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $resource->title }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Type</label>
                        <p class="mt-1 text-sm text-gray-900">{{ ucfirst($resource->type) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Field</label>
                        <p class="mt-1 text-sm text-gray-900">{{ ucfirst($resource->field) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Sub Fields</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $resource->sub_fields }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Price</label>
                        <p class="mt-1 text-sm text-gray-900">
                            @if($resource->price)
                                {{ $resource->price }} {{ $resource->currency }}
                            @else
                                Free
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Pages</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $resource->page_count ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="text-sm font-medium text-gray-700">Overview</label>
                    <div class="mt-1 text-sm text-gray-900 bg-gray-50 p-4 rounded border">
                        {!! $resource->overview !!}
                    </div>
                </div>

                <div class="mb-6">
                    <label class="text-sm font-medium text-gray-700">Authors</label>
                    <div class="mt-2 space-y-2">
                        @foreach($resource->authors as $author)
                            <div class="flex items-center justify-between bg-gray-50 p-3 rounded border">
                                <span class="text-sm text-gray-900">{{ $author->fullname }}</span>
                                @if($author->is_lead)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Lead Author
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Document Preview -->
            @if($mainFile)
            <div class="card p-6">
                <h3 class="text-lg font-semibold mb-4">Document Preview</h3>
                
                <div class="border rounded-lg overflow-hidden mb-4">
                    <div class="bg-gray-50 px-4 py-2 border-b">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="la la-file-pdf text-red-500 text-xl mr-2"></i>
                                <span class="text-sm font-medium">{{ $mainFile->filename }}</span>
                            </div>
                            <div class="flex space-x-2">
                                <a href="#" class="text-blue-600 hover:text-blue-800 text-sm">
                                    <i class="la la-download"></i> Download
                                </a>
                                @if($mainFile->extension !== 'pdf')
                                <form method="POST" action="{{ route('admin.resources.convert-pdf', $resource->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-800 text-sm">
                                        <i class="la la-file-pdf"></i> Convert to PDF
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="p-4 bg-gray-100 min-h-64 flex items-center justify-center">
                        <div class="text-center text-gray-500">
                            <i class="la la-file-pdf la-3x mb-2"></i>
                            <p>PDF Preview would be displayed here</p>
                            <p class="text-sm">File: {{ $mainFile->filename }} ({{ number_format($mainFile->size / 1024, 2) }} KB)</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Author Information -->
            <div class="card p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Author Information</h3>
                
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Name</label>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ $resource->user->first_name }} {{ $resource->user->last_name }}
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Email</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $resource->user->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Username</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $resource->user->username }}</p>
                    </div>
                </div>
            </div>

            <!-- Submission Information -->
            <div class="card p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Submission Information</h3>
                
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Submitted</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $resource->submitted_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Status</label>
                        <p class="mt-1">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                {{ ucfirst($resource->approval_status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Review Actions -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold mb-4">Review Actions</h3>
                
                <div class="space-y-3">
                    <!-- Approve -->
                    <form method="POST" action="{{ route('admin.resources.approve', $resource->id) }}" class="w-full">
                        @csrf
                        <div class="mb-3">
                            <label class="text-sm font-medium text-gray-700">Admin Notes (Optional)</label>
                            <textarea name="admin_notes" class="form-control mt-1" rows="2" 
                                      placeholder="Add approval notes..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-full"
                                onclick="return confirm('Are you sure you want to approve this resource?')">
                            <i class="la la-check"></i> Approve & Publish
                        </button>
                    </form>

                    <hr class="my-4">

                    <!-- Reject -->
                    <button type="button" class="btn btn-danger w-full" 
                            onclick="showRejectModal({{ $resource->id }}, '{{ addslashes($resource->title) }}')">
                        <i class="la la-times"></i> Reject Resource
                    </button>
                </div>

                @if($resource->admin_notes)
                <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded">
                    <label class="text-sm font-medium text-blue-800">Previous Review Notes:</label>
                    <p class="text-sm text-blue-700 mt-1">{{ $resource->admin_notes }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Reject Modal -->
<div class="modal" id="rejectModal" data-animations="bounceInDown, bounceOutUp" data-static-backdrop>
    <div class="modal-dialog modal-dialog_centered max-w-2xl">
        <form method="POST" id="rejectForm" class="modal-content" style="min-width: 400px;">
            @csrf
            <div class="modal-header">
                <h2 class="modal-title">Reject Resource</h2>
                <button type="button" class="btn-icon close la la-times" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="w-full mb-4">
                    <label class="label block mb-2" for="admin_notes">
                        <strong>Reason for Rejection:</strong>
                    </label>
                    <textarea name="admin_notes" id="admin_notes" class="form-control" rows="4" 
                              placeholder="Please provide a clear reason for rejecting this resource..."
                              required></textarea>
                    <small class="block mt-2 text-gray-600">
                        This message will be sent to the author via email.
                    </small>
                </div>
                <div class="bg-yellow-50 border border-yellow-200 rounded p-3">
                    <p class="text-sm text-yellow-800">
                        <i class="la la-exclamation-triangle"></i>
                        <strong>Warning:</strong> Rejecting this resource will notify the author and they will need to resubmit if they wish to publish it.
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <div class="flex ml-auto">
                    <button type="button" class="btn btn_secondary mr-2" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="la la-times"></i> Reject Resource
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('js')
<script>
    function showRejectModal(resourceId, resourceTitle) {
        $('#rejectForm').attr('action', '/admin/resources/' + resourceId + '/reject');
        $('#rejectModal .modal-title').text('Reject Resource: ' + resourceTitle);
        $('#rejectModal').modal('show');
    }
</script>
@endpush
