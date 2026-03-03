@extends('admin.layouts.app')

@section('title', 'Subfields Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">
                    <i class="fas fa-list-alt text-primary"></i> 
                    Subfields Management
                </h2>
                <div>
                    <button type="button" class="btn btn-warning" id="clearAllBtn">
                        <i class="fas fa-trash-alt"></i> Clear All Subfields
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="row mb-4">
        <div class="col-md-8">
            <form method="GET" class="form-inline">
                <div class="input-group mr-3">
                    <input type="text" 
                           name="search" 
                           value="{{ $search ?? '' }}" 
                           class="form-control" 
                           placeholder="Search subfields..."
                           id="searchInput">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-4">
            <form method="GET">
                <select name="parent_field" class="form-control" onchange="this.form.submit()">
                    <option value="">All Parent Fields</option>
                    @foreach($parentFields as $field)
                        <option value="{{ $field }}" {{ ($parentField ?? '') == $field ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $field)) }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <!-- Bulk Actions -->
    @if($subfields->count() > 0)
    <div class="row mb-3">
        <div class="col-12">
            <div class="card border-left-warning">
                <div class="card-body py-2">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                                <label class="form-check-label" for="selectAll">
                                    Select All ({{ $subfields->total() }} subfields)
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" 
                                    class="btn btn-danger btn-sm" 
                                    id="bulkDeleteBtn" 
                                    disabled>
                                <i class="fas fa-trash"></i> Delete Selected
                            </button>
                            <span class="text-muted ml-2" id="selectedCount">0 selected</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Subfields Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($subfields->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="50">
                                            <input type="checkbox" id="selectAllTable">
                                        </th>
                                        <th>Title</th>
                                        <th>Slug</th>
                                        <th>Parent Field</th>
                                        <th>Created</th>
                                        <th width="200">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subfields as $subfield)
                                        <tr id="row-{{ $subfield->id }}">
                                            <td>
                                                <input type="checkbox" 
                                                       class="subfield-checkbox" 
                                                       value="{{ $subfield->id }}">
                                            </td>
                                            <td>
                                                <strong>{{ $subfield->title }}</strong>
                                            </td>
                                            <td>
                                                <code>{{ $subfield->slug }}</code>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">
                                                    {{ ucfirst(str_replace('_', ' ', $subfield->parent_field)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $subfield->created_at->format('M d, Y') }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" 
                                                            class="btn btn-info btn-sm"
                                                            onclick="showUsage({{ $subfield->id }})"
                                                            title="Show Usage">
                                                        <i class="fas fa-info-circle"></i>
                                                    </button>
                                                    <button type="button" 
                                                            class="btn btn-warning btn-sm"
                                                            onclick="editSubfield({{ $subfield->id }})"
                                                            title="Edit Subfield">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" 
                                                            class="btn btn-danger btn-sm"
                                                            onclick="deleteSubfield({{ $subfield->id }}, '{{ $subfield->title }}')"
                                                            title="Delete Subfield">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $subfields->links() }}
                        </div>

                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-list-alt fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">No subfields found</h4>
                            <p class="text-muted">
                                @if($search || $parentField)
                                    Try adjusting your search or filter criteria.
                                @else
                                    No subfields have been created yet.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Subfield Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-edit"></i>
                    Edit Subfield
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="editForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editTitle">Title <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control" 
                               id="editTitle" 
                               name="title" 
                               required
                               maxlength="255">
                        <div class="invalid-feedback" id="editTitleError"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="editSlug">Slug <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control" 
                               id="editSlug" 
                               name="slug" 
                               required
                               maxlength="255">
                        <div class="invalid-feedback" id="editSlugError"></div>
                        <small class="form-text text-muted">URL-friendly version of the title</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="editParentField">Parent Field <span class="text-danger">*</span></label>
                        <select class="form-control" id="editParentField" name="parent_field" required>
                            <option value="">Select Parent Field</option>
                            <!-- Options will be loaded via AJAX -->
                        </select>
                        <div class="invalid-feedback" id="editParentFieldError"></div>
                    </div>
                    
                    <input type="hidden" id="editSubfieldId" name="subfield_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveEditBtn">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Usage Modal -->
<div class="modal fade" id="usageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Subfield Usage Information</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="usageModalBody">
                <!-- Content will be loaded via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Clear All Confirmation Modal -->
<div class="modal fade" id="clearAllModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    Clear All Subfields
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <strong>Warning!</strong> This action will permanently delete ALL subfields and clear all references from resources. This action cannot be undone.
                </div>
                <p>Are you sure you want to proceed?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" onclick="confirmClearAll()">
                    <i class="fas fa-trash-alt"></i> Yes, Clear All
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-trash"></i>
                    Confirm Deletion
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="deleteModalBody">
                    <!-- Content will be loaded dynamically -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let selectedSubfields = new Set();
let deleteSubfieldId = null;
let editSubfieldId = null;

// Initialize when page loads
$(document).ready(function() {
    // Search functionality with debounce
    let searchTimeout;
    $('#searchInput').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            // Auto-submit form after 500ms of no typing
            $('form').first().submit();
        }, 500);
    });

    // Select all functionality
    $('#selectAll, #selectAllTable').change(function() {
        const isChecked = $(this).is(':checked');
        $('.subfield-checkbox, #selectAll, #selectAllTable').prop('checked', isChecked);
        
        selectedSubfields.clear();
        if (isChecked) {
            $('.subfield-checkbox').each(function() {
                selectedSubfields.add(parseInt($(this).val()));
            });
        }
        
        updateBulkActions();
    });

    // Individual checkbox change
    $('.subfield-checkbox').change(function() {
        const id = parseInt($(this).val());
        if ($(this).is(':checked')) {
            selectedSubfields.add(id);
        } else {
            selectedSubfields.delete(id);
        }
        
        updateBulkActions();
        updateSelectAllState();
    });

    // Bulk delete button
    $('#bulkDeleteBtn').click(function() {
        if (selectedSubfields.size === 0) return;
        
        const count = selectedSubfields.size;
        $('#deleteModalBody').html(`
            <p>Are you sure you want to delete <strong>${count} subfield(s)</strong>?</p>
            <p class="text-muted">This action cannot be undone.</p>
        `);
        
        $('#deleteModal').modal('show');
    });

    // Confirm delete button
    $('#confirmDeleteBtn').click(function() {
        performBulkDelete();
    });

    // Clear all button
    $('#clearAllBtn').click(function() {
        $('#clearAllModal').modal('show');
    });

    // Edit form submission
    $('#editForm').submit(function(e) {
        e.preventDefault();
        saveEdit();
    });

    // Auto-generate slug from title
    $('#editTitle').on('input', function() {
        const title = $(this).val();
        const slug = title.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
        $('#editSlug').val(slug);
    });
});

function updateBulkActions() {
    const count = selectedSubfields.size;
    $('#selectedCount').text(`${count} selected`);
    $('#bulkDeleteBtn').prop('disabled', count === 0);
}

function updateSelectAllState() {
    const totalCheckboxes = $('.subfield-checkbox').length;
    const checkedCheckboxes = $('.subfield-checkbox:checked').length;
    
    $('#selectAll, #selectAllTable').prop('indeterminate', checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes);
    $('#selectAll, #selectAllTable').prop('checked', checkedCheckboxes === totalCheckboxes);
}

function editSubfield(id) {
    editSubfieldId = id;
    
    // Load subfield data
    $.get(`/admin/subfields/${id}/edit`)
        .done(function(response) {
            if (response.success) {
                // Populate form
                $('#editSubfieldId').val(response.subfield.id);
                $('#editTitle').val(response.subfield.title);
                $('#editSlug').val(response.subfield.slug);
                
                // Populate parent field dropdown
                const parentFieldSelect = $('#editParentField');
                parentFieldSelect.empty().append('<option value="">Select Parent Field</option>');
                
                response.parent_fields.forEach(function(field) {
                    const option = $('<option></option>')
                        .val(field)
                        .text(field.charAt(0).toUpperCase() + field.slice(1).replace('_', ' '));
                    
                    if (field === response.subfield.parent_field) {
                        option.attr('selected', true);
                    }
                    
                    parentFieldSelect.append(option);
                });
                
                // Clear any previous errors
                $('.invalid-feedback').hide();
                $('.form-control').removeClass('is-invalid');
                
                $('#editModal').modal('show');
            } else {
                showAlert('danger', response.message || 'Failed to load subfield for editing.');
            }
        })
        .fail(function() {
            showAlert('danger', 'Failed to load subfield for editing.');
        });
}

function saveEdit() {
    const formData = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        title: $('#editTitle').val(),
        slug: $('#editSlug').val(),
        parent_field: $('#editParentField').val()
    };
    
    // Clear previous errors
    $('.invalid-feedback').hide();
    $('.form-control').removeClass('is-invalid');
    
    // Disable save button
    $('#saveEditBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
    
    $.ajax({
        url: `/admin/subfields/${editSubfieldId}`,
        type: 'PUT',
        data: formData,
        success: function(response) {
            if (response.success) {
                $('#editModal').modal('hide');
                showAlert('success', response.message);
                
                // Update the row in the table
                const row = $(`#row-${editSubfieldId}`);
                row.find('td:eq(1) strong').text(formData.title);
                row.find('td:eq(2) code').text(formData.slug);
                row.find('td:eq(3) .badge').text(formData.parent_field.charAt(0).toUpperCase() + formData.parent_field.slice(1).replace('_', ' '));
                
                editSubfieldId = null;
            } else {
                showAlert('danger', response.message || 'Failed to update subfield.');
            }
        },
        error: function(xhr) {
            const response = xhr.responseJSON;
            
            if (response.errors) {
                // Show validation errors
                Object.keys(response.errors).forEach(function(field) {
                    $(`#edit${field.charAt(0).toUpperCase() + field.slice(1)}`).addClass('is-invalid');
                    $(`#edit${field.charAt(0).toUpperCase() + field.slice(1)}Error`).text(response.errors[field][0]).show();
                });
            } else {
                showAlert('danger', response.message || 'Failed to update subfield.');
            }
        },
        complete: function() {
            // Re-enable save button
            $('#saveEditBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Save Changes');
        }
    });
}

function deleteSubfield(id, title) {
    deleteSubfieldId = id;
    $('#deleteModalBody').html(`
        <p>Are you sure you want to delete the subfield <strong>"${title}"</strong>?</p>
        <p class="text-muted">This action cannot be undone.</p>
    `);
    $('#deleteModal').modal('show');
}

function showUsage(id) {
    $('#usageModalBody').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
    $('#usageModal').modal('show');

    $.get(`/admin/subfields/${id}/usage`)
        .done(function(response) {
            if (response.success) {
                let content = `
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Subfield Details:</strong><br>
                            <small>
                                <strong>Title:</strong> ${response.subfield.title}<br>
                                <strong>Slug:</strong> <code>${response.subfield.slug}</code><br>
                                <strong>Parent Field:</strong> ${response.subfield.parent_field}
                            </small>
                        </div>
                        <div class="col-md-6 text-right">
                            <span class="badge badge-info badge-lg">
                                ${response.usage.total} Resource(s)
                            </span>
                        </div>
                    </div>
                `;

                if (response.usage.total > 0) {
                    content += '<hr><strong>Assigned Resources:</strong><br>';

                    if (response.usage.new_format.length > 0) {
                        content += '<h6>Current Format:</h6><ul>';
                        response.usage.new_format.forEach(function(resource) {
                            content += `<li>${resource.title}</li>`;
                        });
                        content += '</ul>';
                    }

                    if (response.usage.old_format.length > 0) {
                        content += '<h6>Legacy Format:</h6><ul>';
                        response.usage.old_format.forEach(function(resource) {
                            content += `<li>${resource.title}</li>`;
                        });
                        content += '</ul>';
                    }
                } else {
                    content += '<div class="alert alert-success">This subfield is not currently used by any resources.</div>';
                }

                $('#usageModalBody').html(content);
            } else {
                $('#usageModalBody').html('<div class="alert alert-danger">Failed to load usage information.</div>');
            }
        })
        .fail(function() {
            $('#usageModalBody').html('<div class="alert alert-danger">Failed to load usage information.</div>');
        });
}

function confirmClearAll() {
    $.post('/admin/subfields/clear-all', {
        _token: $('meta[name="csrf-token"]').attr('content'),
        confirm_clear: true
    })
    .done(function(response) {
        if (response.success) {
            $('#clearAllModal').modal('hide');
            showAlert('success', response.message);
            setTimeout(() => location.reload(), 2000);
        } else {
            showAlert('danger', response.message);
        }
    })
    .fail(function(xhr) {
        const response = xhr.responseJSON;
        showAlert('danger', response.message || 'Failed to clear subfields.');
    });
}

function performBulkDelete() {
    const ids = Array.from(selectedSubfields);
    
    $.ajax({
        url: '/admin/subfields/bulk-delete',
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            subfield_ids: ids
        },
        success: function(response) {
            if (response.success) {
                $('#deleteModal').modal('hide');
                showAlert('success', response.message);
                
                // Remove deleted rows
                ids.forEach(function(id) {
                    $(`#row-${id}`).fadeOut(300, function() {
                        $(this).remove();
                    });
                });
                
                selectedSubfields.clear();
                updateBulkActions();
                updateSelectAllState();
                
                // Reload page if all rows are deleted
                if ($('.subfield-checkbox:visible').length === 0) {
                    setTimeout(() => location.reload(), 2000);
                }
            } else {
                if (response.in_use) {
                    let message = response.message + '<br><br><strong>Safe to delete:</strong> ' + response.safe_to_delete.length + ' subfields.';
                    $('#deleteModalBody').html(`<div class="alert alert-warning">${message}</div>`);
                } else {
                    showAlert('danger', response.message);
                }
            }
        },
        error: function(xhr) {
            const response = xhr.responseJSON;
            showAlert('danger', response.message || 'Failed to delete subfields.');
        }
    });
}

// Single delete function
$(document).on('click', '#confirmDeleteBtn[data-single="true"]', function() {
    $.ajax({
        url: `/admin/subfields/${deleteSubfieldId}`,
        type: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $('#deleteModal').modal('hide');
                showAlert('success', response.message);
                
                $(`#row-${deleteSubfieldId}`).fadeOut(300, function() {
                    $(this).remove();
                });
                
                deleteSubfieldId = null;
            } else {
                showAlert('danger', response.message);
            }
        },
        error: function(xhr) {
            const response = xhr.responseJSON;
            showAlert('danger', response.message || 'Failed to delete subfield.');
        }
    });
});

// Modify delete function to use single delete
function deleteSubfield(id, title) {
    deleteSubfieldId = id;
    $('#deleteModalBody').html(`
        <p>Are you sure you want to delete the subfield <strong>"${title}"</strong>?</p>
        <p class="text-muted">This action cannot be undone.</p>
    `);
    $('#confirmDeleteBtn').attr('data-single', 'true');
    $('#deleteModal').modal('show');
}

// Alert function
function showAlert(type, message) {
    const alertId = 'alert-' + Date.now();
    const alertHtml = `
        <div id="${alertId}" class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    `;
    
    $('.container-fluid').prepend(alertHtml);
    
    setTimeout(function() {
        $(`#${alertId}`).alert('close');
    }, 5000);
}
</script>
@endpush

@push('styles')
<style>
.badge-lg {
    font-size: 1.2em;
    padding: 0.5em 1em;
}

.card.border-left-warning {
    border-left: 4px solid #ffc107 !important;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.subfield-checkbox {
    transform: scale(1.2);
}

#selectedCount {
    font-weight: 500;
}

.modal-lg {
    max-width: 800px;
}

code {
    background-color: #f8f9fa;
    padding: 2px 4px;
    border-radius: 3px;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.invalid-feedback {
    display: none;
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.form-control.is-invalid {
    border-color: #dc3545;
}
</style>
@endpush