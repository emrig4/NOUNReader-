@extends('layouts.account', ['title' => 'Bulk Update Resources'])
@push('css')
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link href="{{ asset('themes/airdgereaders/css/publish.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="ereaders-main-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="ereaders-error-message">
                        <div class="ereaders-error-message-head">
                            <i class="fa fa-refresh"></i>
                            <h2>Bulk Update Existing Resources</h2>
                            <p>Update existing resources by matching title (Column B) and updating metadata</p>
                        </div>
                        <div class="ereaders-error-message-tabs">
                            <div class="tab-content">
                                <div class="tab-pane active" id="home">
                                    <div class="ereaders-change-password">
                                        
                                        @if(session()->has('error'))
                                        <div class="alert alert-danger">{{ session()->get('error') }}</div>
                                        @endif

                                        @if(session()->has('success'))
                                        <div class="alert alert-success">{{ session()->get('success') }}</div>
                                        @endif

                                        <form action="{{ route('admin.resources.bulk-update') }}" method="POST" enctype="multipart/form-data" class="upload-form">
                                            @csrf
                                            
                                            <div class="alert alert-info">
                                                <h4><i class="fa fa-info-circle"></i> How to use:</h4>
                                                <ol style="margin-left: 20px; margin-top: 10px;">
                                                    <li>Upload an Excel file (.xlsx or .xls)</li>
                                                    <li><strong>Column A (0):</strong> New filename (optional - leave empty to keep existing)</li>
                                                    <li><strong>Column B (1):</strong> Title (REQUIRED - used to match existing records)</li>
                                                    <li><strong>Columns C-P:</strong> Other fields to update (optional)</li>
                                                </ol>
                                                <p style="margin-top: 10px;"><strong>Important:</strong> The title (Column B) must match an existing resource to update it.</p>
                                            </div>

                                            <div class="form-group">
                                                <label for="sheet">Select Excel File:</label>
                                                <input type="file" name="sheet" id="sheet" class="form-control" accept=".xlsx,.xls" required>
                                                <p class="help-block">Only .xlsx and .xls files are accepted</p>
                                            </div>

                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary btn-lg">
                                                    <i class="fa fa-refresh"></i> Start Bulk Update
                                                </button>
                                                <a href="{{ route('admin.resources.index') }}" class="btn btn-default btn-lg" style="margin-left: 10px;">Cancel</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    $('.upload-form').on('submit', function() {
        $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
    });
});
</script>
@endpush