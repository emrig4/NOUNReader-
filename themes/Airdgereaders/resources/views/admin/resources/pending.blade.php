@extends('layouts.admin')
@push('css')
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush

@section('content')
<section class="container">
    <!-- Header -->
    <div class="card lg:flex p-4 mb-10">
        <h2 class="text-xl leading-tight">Pending Resources for Review</h2>
        <div class="flex items-center space-x-0 ml-auto mt-5 lg:mt-0">
            <div class="w-1/2 mt-0">
                <div class="ml-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        {{ $pendingResources->count() }} Pending Review
                    </span>
                </div>
            </div>
        </div>
    </div>
        
    <div class="card mt-5 p-5">
        <table class="table yajra-dt table_bordered mt-3 w-full">
            <thead>
                <tr class="text-sm">
                    <th class="text-left uppercase">ID</th>
                    <th class="text-left uppercase">Title</th>
                    <th class="text-left uppercase">Author</th>
                    <th class="text-left uppercase">Type</th>
                    <th class="text-left uppercase">Field</th>
                    <th class="text-left uppercase">Submitted</th>
                    <th class="text-left uppercase">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingResources as $resource)
                <tr>
                    <td>{{ $resource->id }}</td>
                    <td>
                        <div class="font-medium">{{ Str::limit($resource->title, 50) }}</div>
                        @if($resource->admin_notes)
                            <div class="text-xs text-gray-500 mt-1">
                                <i class="la la-sticky-note"></i> Previous review notes available
                            </div>
                        @endif
                    </td>
                    <td>{{ $resource->user->first_name }} {{ $resource->user->last_name }}</td>
                    <td>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ ucfirst($resource->type) }}
                        </span>
                    </td>
                    <td>{{ ucfirst($resource->field) }}</td>
                    <td>
                        <div class="text-sm">{{ $resource->submitted_at->format('M d, Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $resource->submitted_at->format('H:i') }}</div>
                    </td>
                    <td>
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.resources.show', $resource->id) }}" 
                               class="btn btn-sm btn-primary">
                                <i class="la la-eye"></i> Review
                            </a>
                            <form method="POST" action="{{ route('admin.resources.approve', $resource->id) }}" 
                                  class="inline" onsubmit="return confirm('Are you sure you want to approve this resource?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="la la-check"></i> Approve
                                </button>
                            </form>
                            <button type="button" class="btn btn-sm btn-danger" 
                                    onclick="showRejectModal({{ $resource->id }}, '{{ $resource->title }}')">
                                <i class="la la-times"></i> Reject
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-8">
                        <div class="text-gray-500">
                            <i class="la la-inbox la-3x mb-4"></i>
                            <p class="text-lg">No pending resources</p>
                            <p>All resources have been reviewed</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<!-- Reject Modal -->
<div class="modal" id="rejectModal" data-animations="bounceInDown, bounceOutUp" data-static-backdrop>
    <div class="modal-dialog modal-dialog_centered max-w-2xl">
        <form method="POST" id="rejectForm" class="modal-content" style="min-width: 400px;">
            @csrf
            @method('POST')
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
    $(function () {
        var table = $('.yajra-dt').DataTable({
            processing: true,
            serverSide: false,
            ajax: false,
            columns: [
                {data: 'id', name: 'id'},
                {data: 'title', name: 'title'},
                {data: 'author', name: 'author'},
                {data: 'type', name: 'type'},
                {data: 'field', name: 'field'},
                {data: 'submitted', name: 'submitted'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        $('#DataTables_Table_0_filter input').addClass('form-control mb-4')
        $('#DataTables_Table_0_length').addClass('my-4')
        $('#DataTables_Table_0_length select').addClass('form-control w-40')

    });

    function showRejectModal(resourceId, resourceTitle) {
        $('#rejectForm').attr('action', '/admin/resources/' + resourceId + '/reject');
        $('#rejectModal .modal-title').text('Reject Resource: ' + resourceTitle);
        $('#rejectModal').modal('show');
    }
</script>
@endpush
