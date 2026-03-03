@extends('layouts.public', ['title' => 'Submit Your Work'])
@push('css')
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
    <link href="{{ asset('themes/airdgereaders/css/publish.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/airdgereaders/css/tag.css') }}" rel="stylesheet">
    <style>
        .submit-form-container {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(35, 164, 85, 0.1);
            padding: 40px;
            border: 1px solid rgba(35, 164, 85, 0.1);
        }
        
        /* Mobile Responsive Container */
        @media (max-width: 768px) {
            .submit-form-container {
                margin: 0 15px;
                padding: 25px 20px;
                border-radius: 12px;
            }
        }
        
        .file-upload-section {
            background: linear-gradient(135deg, rgba(35, 164, 85, 0.05), rgba(35, 164, 85, 0.02));
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 30px;
            border: 2px dashed rgba(35, 164, 85, 0.3);
            transition: all 0.3s ease;
        }
        
        .file-preview {
            display: none;
            align-items: center;
            padding: 20px;
            background: rgba(35, 164, 85, 0.08);
            border-radius: 12px;
            margin-top: 20px;
            border: 1px solid rgba(35, 164, 85, 0.2);
        }
        
        .file-preview.show {
            display: flex;
        }
        
        .file-icon {
            font-size: 32px;
            margin-right: 15px;
            color: #23A455;
        }
        
        .file-info h4 {
            margin: 0 0 8px 0;
            color: #1f2937;
            font-size: 15px;
            font-weight: 600;
            word-wrap: break-word;
            max-width: 250px;
            line-height: 1.3;
        }
        
        .file-info span {
            color: #6b7280;
            font-size: 14px;
        }
        
        .remove-file {
            margin-left: auto;
            color: #dc3545;
            cursor: pointer;
            padding: 8px;
            font-size: 18px;
            transition: all 0.2s ease;
        }
        
        .remove-file:hover {
            transform: scale(1.1);
            background: rgba(220, 53, 69, 0.1);
            border-radius: 50%;
        }
        
        .form-section {
            margin-bottom: 25px;
        }
        
        .form-section label {
            display: block;
            font-weight: 600;
            margin-bottom: 10px;
            color: #374151;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .form-section input, .form-section select, .form-section textarea {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.2s ease;
            background: #fff;
        }
        
        .form-section input:focus, .form-section select:focus, .form-section textarea:focus {
            outline: none;
            border-color: #23A455;
            box-shadow: 0 0 0 4px rgba(35, 164, 85, 0.1);
            transform: translateY(-1px);
        }
        
        .form-row {
            display: flex;
            gap: 20px;
        }
        
        .form-row .form-section {
            flex: 1;
        }
        
        /* Mobile Responsive Form Rows */
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
                gap: 15px;
            }
        }
        
        .submit-btn {
            background: #23A455;
            color: white;
            padding: 16px 40px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            max-width: 300px;
            margin: 0 auto;
            position: relative;
            overflow: hidden;
        }
        
        .submit-btn:hover {
            background: #1e8f49;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(35, 164, 85, 0.4);
        }
        
        .submit-btn:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
        }
        
        .submit-btn i {
            margin-right: 8px;
        }
        
        .upload-btn {
            background: #23A455;
            color: white;
            padding: 16px 40px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .upload-btn:hover {
            background: #1e8f49;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(35, 164, 85, 0.4);
        }
        
        .upload-btn i {
            font-size: 18px;
        }
        
        .info-box {
            background: linear-gradient(135deg, rgba(35, 164, 85, 0.1), rgba(35, 164, 85, 0.05));
            border: 1px solid rgba(35, 164, 85, 0.3);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .info-box i {
            color: #23A455;
            margin-right: 8px;
        }
        
        .hidden-file-input {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            overflow: hidden;
        }
        
        .form-title {
            font-size: 26px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 10px;
            text-align: center;
            position: relative;
        }
        
        .form-title::after {
            content: '';
            width: 60px;
            height: 4px;
            background: #23A455;
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 2px;
        }
        
        .form-subtitle {
            text-align: center;
            color: #6b7280;
            margin-bottom: 30px;
            font-size: 16px;
        }
        
        .step-indicator {
            display: none;
        }
        
        /* Mobile Optimizations */
        @media (max-width: 768px) {
            .file-preview {
                flex-direction: column;
                text-align: center;
            }
            
            .file-info h4 {
                max-width: 100%;
            }
            
            .file-icon {
                margin-right: 0;
                margin-bottom: 10px;
            }
            
            .remove-file {
                margin-left: 0;
                margin-top: 10px;
            }
            
            .form-section input, .form-section select, .form-section textarea {
                padding: 12px 14px;
            }
            
            .submit-btn, .upload-btn {
                width: 100%;
                padding: 16px 30px;
            }
        }
        
        /* Loading Animation */
        .submit-btn.loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            animation: loading 1.5s infinite;
        }
        
        @keyframes loading {
            0% { left: -100%; }
            100% { left: 100%; }
        }
        
        /* Smooth transitions for all interactive elements */
        * {
            transition: all 0.2s ease;
        }
        
        /* Enhanced focus states */
        .form-section input:focus, .form-section select:focus, .form-section textarea:focus {
            outline: none;
        }
        
        /* Improved button styles */
        .submit-btn:focus, .upload-btn:focus {
            outline: 2px solid #23A455;
            outline-offset: 2px;
        }
    </style>
@endpush

@push('js')
    <script src="https://cdn.ckeditor.com/ckeditor5/29.1.0/classic/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js"></script>
    <script type="text/javascript" src="{{ asset('themes/airdgereaders/js/coauthors.js') }}"></script>
    <script type="text/javascript" src="{{ asset('themes/airdgereaders/js/subfields.js') }}"></script>

    <script>
    $(document).ready(function() {
        // Initialize selectize
        $('.selectize').selectize({ 
            sortField: 'text',
            create: false
        });

        // Initialize CKEditor and store instance
        let overviewEditor;
        ClassicEditor.create(document.querySelector('#overview'))
            .then(editor => {
                overviewEditor = editor;
            })
            .catch(error => console.error(error));

        // File upload handling
        const fileInput = document.getElementById('fileInput');
        const filePreview = document.getElementById('filePreview');
        const submitBtn = document.getElementById('submitBtn');

        // File input change
        fileInput.addEventListener('change', function() {
            if (this.files.length) {
                handleFileSelect(this.files[0]);
            }
        });

        // Handle file selection
        function handleFileSelect(file) {
            const validTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            
            if (!validTypes.includes(file.type)) {
                alert('Please upload a PDF or DOCX file');
                fileInput.value = '';
                return;
            }

            if (file.size > 50 * 1024 * 1024) {
                alert('File size must be less than 50MB');
                fileInput.value = '';
                return;
            }

            // Show preview with wrapped filename
            const fileName = document.getElementById('fileName');
            const fileSize = document.getElementById('fileSize');
            
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            
            // Add word wrapping for long filenames
            if (file.name.length > 30) {
                fileName.style.wordWrap = 'break-word';
                fileName.style.maxWidth = '200px';
            }
            
            filePreview.classList.add('show');
        }

        // Remove file
        document.getElementById('removeFile').addEventListener('click', function(e) {
            e.preventDefault();
            fileInput.value = '';
            filePreview.classList.remove('show');
        });

        // Format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Currency/Price handling
        $('#currency').change(function() {
            if ($(this).val() === '') {
                $('#price').val('').attr('disabled', 'disabled');
            } else {
                $('#price').removeAttr('disabled');
            }
        });

        // Fetch subfields on field change
        $('#field').on('change', function() {
            let subfieldslist = $("#subfieldslist");
            subfieldslist.empty();
            let selectedfield = $(this).val();
            
            $.ajax({
                type: "GET",
                url: "/api/subfields?field=" + selectedfield,
                success: function(data) {
                    for (let i in data) {
                        let subfield = data[i];
                        var o = new Option(subfield.title, subfield.slug);
                        $(o).html(subfield.title);
                        subfieldslist.append(o);
                    }
                }
            });
        });

        // Form submission - sync CKEditor content before submit
        $('#submitForm').on('submit', function(e) {
            // Sync CKEditor content to textarea FIRST
            if (overviewEditor) {
                $('#overview').val(overviewEditor.getData());
            }
            
            // Validate file is selected
            if (!fileInput.files || fileInput.files.length === 0) {
                e.preventDefault();
                alert('Please select a file to upload');
                return false;
            }
            
            // Validate required fields
            if (!$('#title').val().trim()) {
                e.preventDefault();
                alert('Please enter a document title');
                $('#title').focus();
                return false;
            }
            
            // Validate overview/abstract is filled (from CKEditor)
            var overviewContent = $('#overview').val();
            if (!overviewContent || overviewContent.trim() === '' || overviewContent === '<p>&nbsp;</p>') {
                e.preventDefault();
                alert('Please enter an overview/abstract for your document');
                if (overviewEditor) {
                    overviewEditor.editing.view.focus();
                }
                return false;
            }
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
            submitBtn.classList.add('loading');
        });

        // Enhanced mobile experience
        if (window.innerWidth <= 768) {
            // Improve touch targets
            $('.submit-btn').css('min-height', '48px');
            
            // Better mobile form spacing
            $('.form-section').css('margin-bottom', '20px');
        }

        // Auto-save draft functionality (optional)
        let draftTimer;
        $('input, textarea, select').on('input change', function() {
            clearTimeout(draftTimer);
            draftTimer = setTimeout(function() {
                // Save form data to localStorage for draft recovery
                localStorage.setItem('submitFormDraft', JSON.stringify($('#submitForm').serialize()));
            }, 2000);
        });

        // Load draft on page load
        const savedDraft = localStorage.getItem('submitFormDraft');
        if (savedDraft) {
            try {
                const draftData = JSON.parse(savedDraft);
                // Auto-fill form with draft data
                Object.keys(draftData).forEach(key => {
                    const element = $(`[name="${key}"]`);
                    if (element.length) {
                        element.val(draftData[key]);
                    }
                });
            } catch (e) {
                console.log('Could not load draft data');
            }
        }
    });
    </script>
@endpush

@section('content')
<div class="ereaders-main-section ereaders-counterfull">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('partials.fancy_title', ['title' => 'Submit Your Work', 'description' => 'Upload and publish your document for review'])
            </div>

            <div class="col-md-12">
                @include('partials.flash')
                
                @if ($errors->any())
                    <div class="alert alert-warning alert-dismissable mb-4" role="alert" style="background: linear-gradient(135deg, #fff3cd, #ffeaa7); border: 1px solid #ffeaa7; border-radius: 10px;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="alert-heading">Please fix the following errors:</h4>
                        @foreach ($errors->all() as $error)
                            <p class="mb-0">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="col-md-12">
                <div class="submit-form-container">
                    <!-- Info Box -->
                    <div class="info-box">
                        <i class="fas fa-info-circle"></i>
                        <strong>Submission Process:</strong> Upload your document (PDF or DOCX), fill in the details, and submit. 
                        Your submission will be reviewed by our admin team before being published.
                    </div>

                    <form id="submitForm" method="POST" action="{{ route('resources.submit.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- File Upload Section -->
                        <div class="file-upload-section">
                            <div class="form-section">
                                <label><i class="fas fa-cloud-upload-alt"></i> Upload Your Document *</label>
                                
                                <!-- Button-based file upload -->
                                <div style="text-align: center; margin: 20px 0;">
                                    <button type="button" class="upload-btn" onclick="document.getElementById('fileInput').click()">
                                        <i class="fas fa-file-upload"></i>
                                        Choose File to Upload
                                    </button>
                                    <p class="text-muted small" style="font-size: 13px; margin-top: 10px;">
                                        Accepted formats: PDF, DOCX (Max 50MB)
                                    </p>
                                </div>
                                
                                <input type="file" id="fileInput" name="file" accept=".pdf,.docx,.doc" class="hidden-file-input" required>
                                
                                <div class="file-preview" id="filePreview">
                                    <i class="fas fa-file-alt file-icon"></i>
                                    <div class="file-info">
                                        <h4 id="fileName">document.pdf</h4>
                                        <span id="fileSize">2.5 MB</span>
                                    </div>
                                    <i class="fas fa-times remove-file" id="removeFile" title="Remove file"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Document Details -->
                        <div class="form-section">
                            <h3 class="form-title">Document Details</h3>
                            <p class="form-subtitle">Provide information about your document</p>
                        </div>

                        <!-- Title -->
                        <div class="form-section">
                            <label for="title"><i class="fas fa-heading"></i> Document Title *</label>
                            <input type="text" id="title" name="title" placeholder="Enter the title of your document" required value="{{ old('title') }}">
                        </div>

                        <!-- Type and Field -->
                        <div class="form-row">
                            <div class="form-section">
                                <label for="type"><i class="fas fa-file-alt"></i> Document Type *</label>
                                <select id="type" name="type" class="selectize" required>
                                    <option value="">Select document type</option>
                                    @foreach($resourceTypes as $type)
                                        <option value="{{ $type->slug }}" {{ old('type') == $type->slug ? 'selected' : '' }}>{{ $type->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-section">
                                <label for="field"><i class="fas fa-bookmark"></i> Field/Category *</label>
                                <select id="field" name="field" class="selectize" required>
                                    <option value="">Select field</option>
                                    @foreach($resourceFields as $field)
                                        <option value="{{ $field->slug }}" {{ old('field') == $field->slug ? 'selected' : '' }}>{{ $field->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Sub Fields -->
                        <div class="form-section">
                            <label><i class="fas fa-tags"></i> Sub Fields/Topics</label>
                            <div class="tag-wrapper">
                                <div class="tag-container" id="subfieldscontainer">
                                    <input list="subfieldslist" type="text" placeholder="Select or type topics (press Enter to add)"/>
                                </div>
                            </div>
                            <input type="hidden" id="subfields" name="sub_fields" value="{{ old('sub_fields') }}">
                            <datalist id="subfieldslist"></datalist>
                        </div>

                        <!-- Co-Authors -->
                        <div class="form-section">
                            <label><i class="fas fa-users"></i> Co-Author(s) (Optional)</label>
                            <div class="tag-wrapper">
                                <div class="tag-container" id="coauthorscontainer">
                                    <input list="authors" type="text" placeholder="Add co-authors (press Enter to add)"/>
                                </div>
                            </div>
                            <input type="hidden" id="coauthors" name="coauthors" value="{{ old('coauthors') }}">
                            <datalist id="authors"></datalist>
                        </div>

                        <!-- Overview -->
                        <div class="form-section">
                            <label for="overview"><i class="fas fa-align-left"></i> Overview/Abstract *</label>
                            <textarea id="overview" name="overview" rows="5" placeholder="Enter the abstract or table of contents">{{ old('overview') }}</textarea>
                        </div>

                        <!-- Pricing and Page Info -->
                        <div class="form-row">
                            <div class="form-section">
                                <label><i class="fas fa-money-bill"></i> Pricing</label>
                                <select id="currency" name="currency">
                                    <option value="">Free</option>
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->code }}" {{ old('currency') == $currency->code ? 'selected' : '' }}>{{ $currency->name }}</option>
                                    @endforeach
                                </select>
                                <input type="number" id="price" name="price" placeholder="Enter price" disabled value="{{ old('price') }}" style="margin-top: 10px;">
                            </div>
                            <div class="form-section">
                                <label><i class="fas fa-file"></i> Page Information</label>
                                <input type="number" name="page_count" placeholder="Number of pages" value="{{ old('page_count') }}">
                                <input type="number" name="preview_limit" placeholder="Preview page limit" value="{{ old('preview_limit') }}" style="margin-top: 10px;">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-section text-center">
                            <button type="submit" class="submit-btn" id="submitBtn">
                                <i class="fas fa-paper-plane"></i>
                                Submit for Review
                            </button>
                            <p class="text-sm text-muted mt-3">
                                Your submission will be reviewed by our admin team before being published.
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection