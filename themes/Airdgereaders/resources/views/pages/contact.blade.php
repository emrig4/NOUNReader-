@extends('layouts.public', ['title' => 'Hire a Writer - Contact Us'])

@push('meta')
    <meta name="description" content="Hire a professional writer for your project. Get quality project topics, research materials, and academic writing services. Contact us today for fast delivery."/>
    <meta property="title" content="Hire a Writer | ReadProjectTopics.com">
    <meta name="keywords" content="hire writer, project writing, research materials, academic writing, thesis writing, dissertation help">
    <meta property="og:title" content="Hire a Writer | ReadProjectTopics.com">
    <meta property="og:description" content="Hire a professional writer for your project. Get quality project topics and research materials.">
@endpush

@push('css')
    <style>
        .contact-form-wrapper {
            background: #fff;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        }
        .contact-info-card {
            background: linear-gradient(135deg, #37a5d8 0%, #2c8cb8 100%);
            color: #fff;
            border-radius: 8px;
            padding: 30px;
            margin-bottom: 20px;
        }
        .contact-info-card i {
            font-size: 32px;
            margin-bottom: 15px;
        }
        .contact-info-card h4 {
            margin-bottom: 10px;
            font-weight: 600;
        }
        .contact-info-card p {
            margin: 0;
            opacity: 0.9;
            font-size: 14px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }
        .form-control {
            height: 50px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 10px 15px;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #37a5d8;
            box-shadow: 0 0 0 3px rgba(55, 165, 216, 0.1);
        }
        textarea.form-control {
            height: 150px;
            resize: vertical;
        }
        select.form-control {
            height: 50px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 10px 15px;
            font-size: 15px;
            background: #fff;
        }
        select.form-control:focus {
            border-color: #37a5d8;
            box-shadow: 0 0 0 3px rgba(55, 165, 216, 0.1);
        }
        .has-error .form-control {
            border-color: #dc3545;
        }
        .help-block {
            color: #dc3545;
            font-size: 13px;
            margin-top: 5px;
        }
        .alert {
            padding: 15px 20px;
            border-radius: 6px;
            margin-bottom: 25px;
        }
        .alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .alert-danger {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        .submit-btn {
            background: #37a5d8;
            color: #fff;
            border: none;
            padding: 14px 35px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }
        .submit-btn:hover {
            background: #2c8cb8;
            transform: translateY(-2px);
        }
        .section-title {
            text-align: center;
            margin-bottom: 40px;
        }
        .section-title h2 {
            font-size: 32px;
            font-weight: 700;
            color: #333;
            margin-bottom: 15px;
        }
        .section-title p {
            color: #666;
            font-size: 16px;
            max-width: 600px;
            margin: 0 auto;
        }
        .hire-writer-badge {
            display: inline-block;
            background: linear-gradient(135deg, #37a5d8 0%, #2c8cb8 100%);
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .form-section-title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin: 30px 0 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #37a5d8;
        }
        .form-section-title:first-of-type {
            margin-top: 0;
        }
        .field-hint {
            font-size: 12px;
            color: #888;
            margin-top: 4px;
        }
        @media (max-width: 768px) {
            .contact-form-wrapper {
                padding: 20px;
            }
            .section-title h2 {
                font-size: 24px;
            }
        }
    </style>
@endpush

@section('content')
<!--// Main Section \\-->
<div class="ereaders-main-section ereaders-counterfull">
    <div class="container" style="width: 100%">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title">
                    <span class="hire-writer-badge">📝 Hire a Writer</span>
                    <h2>Start Your Project</h2>
                    <p>Tell us about your project needs and we will connect you with a professional writer. Get quality project topics, research materials, and academic writing assistance.</p>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Contact Info Sidebar -->
            <div class="col-md-4">
                <div class="contact-info-card">
                    <i class="fa fa-envelope"></i>
                    <h4>Email Us</h4>
                    <p>emrig4@gmail.com</p>
                </div>
                <div class="contact-info-card">
                    <i class="fa fa-phone"></i>
                    <h4>Phone</h4>
                    <p>+234 XXX XXXX XXX</p>
                </div>
                <div class="contact-info-card">
                    <i class="fa fa-clock-o"></i>
                    <h4>Working Hours</h4>
                    <p>Mon - Fri: 9AM - 6PM</p>
                </div>
                <div class="contact-info-card" style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);">
                    <i class="fa fa-check-circle"></i>
                    <h4>Why Hire Us?</h4>
                    <p>✓ Quality Guaranteed<br>✓ Fast Delivery<br>✓ 24/7 Support<br>✓ Plagiarism Free</p>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-md-8">
                <div class="contact-form-wrapper">
                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fa fa-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif

                    <!-- Error Message -->
                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST" id="contactForm">
                        @csrf

                        <!-- Personal Information -->
                        <div class="form-section-title">Personal Information</div>
                        
                        <div class="row">
                            <!-- Name Field -->
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                    <label for="name">Full Name <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control"
                                           id="name"
                                           name="name"
                                           value="{{ old('name') }}"
                                           placeholder="Enter your full name"
                                           required>
                                    @if($errors->has('name'))
                                        <span class="help-block">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Email Field -->
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                    <label for="email">Email Address <span class="text-danger">*</span></label>
                                    <input type="email"
                                           class="form-control"
                                           id="email"
                                           name="email"
                                           value="{{ old('email') }}"
                                           placeholder="Enter your email address"
                                           required>
                                    @if($errors->has('email'))
                                        <span class="help-block">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Phone Field -->
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
                                    <label for="phone">Phone Number <span class="text-muted">(Optional)</span></label>
                                    <input type="tel"
                                           class="form-control"
                                           id="phone"
                                           name="phone"
                                           value="{{ old('phone') }}"
                                           placeholder="Enter your phone number">
                                    @if($errors->has('phone'))
                                        <span class="help-block">{{ $errors->first('phone') }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Subject/Inquiry Type Field -->
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('subject') ? 'has-error' : '' }}">
                                    <label for="subject">Inquiry Type <span class="text-danger">*</span></label>
                                    <select class="form-control"
                                            id="subject"
                                            name="subject"
                                            required>
                                        <option value="">Select inquiry type</option>
                                        <option value="hire_writer" {{ old('subject') == 'hire_writer' ? 'selected' : '' }}>
                                            Hire a Writer
                                        </option>
                                        <option value="buy_credit" {{ old('subject') == 'buy_credit' ? 'selected' : '' }}>
                                            Buy Credit / Payment
                                        </option>
                                        <option value="inquiry_partnership" {{ old('subject') == 'inquiry_partnership' ? 'selected' : '' }}>
                                            Partnership Inquiry
                                        </option>
                                        <option value="data_analysis" {{ old('subject') == 'data_analysis' ? 'selected' : '' }}>
                                            Data Analysis Services
                                        </option>
                                    </select>
                                    @if($errors->has('subject'))
                                        <span class="help-block">{{ $errors->first('subject') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Project Details (Show when Hire a Writer is selected) -->
                        <div class="form-section-title">Project Details</div>
                        
                        <div class="row">
                            <!-- Service Type Field -->
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('service_type') ? 'has-error' : '' }}">
                                    <label for="service_type">Service Type</label>
                                    <select class="form-control"
                                            id="service_type"
                                            name="service_type">
                                        <option value="">Select service type</option>
                                        <option value="Project Topics" {{ old('service_type') == 'Project Topics' ? 'selected' : '' }}>
                                            Project Topics
                                        </option>
                                        <option value="Research Material" {{ old('service_type') == 'Research Material' ? 'selected' : '' }}>
                                            Research Material
                                        </option>
                                        <option value="Thesis/Dissertation" {{ old('service_type') == 'Thesis/Dissertation' ? 'selected' : '' }}>
                                            Thesis/Dissertation
                                        </option>
                                        <option value="Essay/Article" {{ old('service_type') == 'Essay/Article' ? 'selected' : '' }}>
                                            Essay/Article
                                        </option>
                                        <option value="Case Study" {{ old('service_type') == 'Case Study' ? 'selected' : '' }}>
                                            Case Study
                                        </option>
                                        <option value="Data Analysis" {{ old('service_type') == 'Data Analysis' ? 'selected' : '' }}>
                                            Data Analysis
                                        </option>
                                        <option value="Literature Review" {{ old('service_type') == 'Literature Review' ? 'selected' : '' }}>
                                            Literature Review
                                        </option>
                                        <option value="Other" {{ old('service_type') == 'Other' ? 'selected' : '' }}>
                                            Other
                                        </option>
                                    </select>
                                    <p class="field-hint">What type of writing service do you need?</p>
                                    @if($errors->has('service_type'))
                                        <span class="help-block">{{ $errors->first('service_type') }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Budget Field -->
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('budget') ? 'has-error' : '' }}">
                                    <label for="budget">Project Budget</label>
                                    <select class="form-control"
                                            id="budget"
                                            name="budget">
                                        <option value="">Select budget range</option>
                                        <option value="Under ₦5,000" {{ old('budget') == 'Under ₦5,000' ? 'selected' : '' }}>
                                            Under ₦5,000
                                        </option>
                                        <option value="₦5,000 - ₦10,000" {{ old('budget') == '₦5,000 - ₦10,000' ? 'selected' : '' }}>
                                            ₦5,000 - ₦10,000
                                        </option>
                                        <option value="₦10,000 - ₦20,000" {{ old('budget') == '₦10,000 - ₦20,000' ? 'selected' : '' }}>
                                            ₦10,000 - ₦20,000
                                        </option>
                                        <option value="₦20,000 - ₦50,000" {{ old('budget') == '₦20,000 - ₦50,000' ? 'selected' : '' }}>
                                            ₦20,000 - ₦50,000
                                        </option>
                                        <option value="₦50,000+" {{ old('budget') == '₦50,000+' ? 'selected' : '' }}>
                                            ₦50,000+
                                        </option>
                                        <option value="Negotiable" {{ old('budget') == 'Negotiable' ? 'selected' : '' }}>
                                            Negotiable
                                        </option>
                                    </select>
                                    <p class="field-hint">What is your budget for this project?</p>
                                    @if($errors->has('budget'))
                                        <span class="help-block">{{ $errors->first('budget') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Deadline Field -->
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('deadline') ? 'has-error' : '' }}">
                                    <label for="deadline">Deadline</label>
                                    <input type="text"
                                           class="form-control"
                                           id="deadline"
                                           name="deadline"
                                           value="{{ old('deadline') }}"
                                           placeholder="e.g., 2 weeks, 1 month, Urgent">
                                    <p class="field-hint">When do you need the completed project?</p>
                                    @if($errors->has('deadline'))
                                        <span class="help-block">{{ $errors->first('deadline') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Message Field -->
                        <div class="form-group {{ $errors->has('message') ? 'has-error' : '' }}">
                            <label for="message">Project Details / Message <span class="text-danger">*</span></label>
                            <textarea class="form-control"
                                      id="message"
                                      name="message"
                                      rows="6"
                                      placeholder="Please describe your project requirements, topic, area of study, number of pages/chapters, and any specific instructions..."
                                      required>{{ old('message') }}</textarea>
                            <p class="field-hint">Be as detailed as possible to help us understand your needs better.</p>
                            @if($errors->has('message'))
                                <span class="help-block">{{ $errors->first('message') }}</span>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group text-center">
                            <button type="submit" class="submit-btn">
                                <i class="fa fa-paper-plane"></i> Submit Inquiry
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--// Main Section \\-->
@endsection

@push('js')
<script>
    $(document).ready(function() {
        // Form validation enhancement
        $('#contactForm').on('submit', function(e) {
            var isValid = true;

            // Check required fields
            $(this).find('[required]').each(function() {
                if (!$(this).val().trim()) {
                    $(this).closest('.form-group').addClass('has-error');
                    if (!$(this).closest('.form-group').find('.help-block').length) {
                        $('<span class="help-block">This field is required.</span>').appendTo($(this).closest('.form-group'));
                    }
                    isValid = false;
                } else {
                    $(this).closest('.form-group').removeClass('has-error');
                    $(this).closest('.form-group').find('.help-block').remove();
                }
            });

            // Email validation
            var emailField = $('#email');
            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailField.val() && !emailPattern.test(emailField.val())) {
                emailField.closest('.form-group').addClass('has-error');
                if (!emailField.closest('.form-group').find('.help-block').length) {
                    $('<span class="help-block">Please enter a valid email address.</span>').appendTo(emailField.closest('.form-group'));
                }
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: $('.has-error').first().offset().top - 100
                }, 500);
            }
        });

        // Remove error state on input
        $('#contactForm .form-control').on('input focus', function() {
            $(this).closest('.form-group').removeClass('has-error');
            $(this).closest('.form-group').find('.help-block').remove();
        });

        // Show/hide project details based on inquiry type
        $('#subject').change(function() {
            if ($(this).val() === 'hire_writer') {
                // Highlight the project details section
                $('.form-section-title').eq(1).css('color', '#37a5d8');
            }
        });
    });
</script>
@endpush
