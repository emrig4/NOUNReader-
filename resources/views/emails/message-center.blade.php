<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Message Center</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8fafc;
            color: #334155;
            line-height: 1.6;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        .header p {
            opacity: 0.9;
            font-size: 16px;
        }
        .content {
            padding: 40px;
        }
        .message-form {
            background: #f8fafc;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 25px;
        }
        .form-label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .form-input, .form-textarea, .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.2s;
        }
        .form-input:focus, .form-textarea:focus, .form-select:focus {
            outline: none;
            border-color: #3b82f6;
        }
        .form-textarea {
            min-height: 150px;
            resize: vertical;
        }
        .recipient-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        .recipient-option {
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .recipient-option:hover {
            border-color: #3b82f6;
            background: #f0f9ff;
        }
        .recipient-option.active {
            border-color: #3b82f6;
            background: #eff6ff;
        }
        .recipient-option input[type="radio"] {
            margin-right: 10px;
        }
        .message-type {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        .type-card {
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        .type-card:hover {
            border-color: #10b981;
            background: #f0fdf4;
        }
        .type-card.active {
            border-color: #10b981;
            background: #ecfdf5;
        }
        .type-card .icon {
            font-size: 24px;
            margin-bottom: 8px;
        }
        .type-card .title {
            font-weight: 600;
            color: #374151;
        }
        .preview-section {
            background: #f8fafc;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }
        .preview-title {
            font-weight: 600;
            color: #374151;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        .preview-title::before {
            content: "👁️";
            margin-right: 8px;
        }
        .email-preview {
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 20px;
            max-height: 300px;
            overflow-y: auto;
        }
        .send-button {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
            display: flex;
            align-items: center;
            margin: 30px auto 0;
        }
        .send-button:hover {
            transform: translateY(-2px);
        }
        .send-button:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
        }
        .status-section {
            margin-top: 20px;
            padding: 15px;
            border-radius: 8px;
            display: none;
        }
        .status-success {
            background: #d1fae5;
            border: 1px solid #10b981;
            color: #065f46;
        }
        .status-error {
            background: #fee2e2;
            border: 1px solid #ef4444;
            color: #991b1b;
        }
        .char-counter {
            text-align: right;
            font-size: 12px;
            color: #6b7280;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📧 Admin Message Center</h1>
            <p>Send messages to users - individual or bulk communication</p>
        </div>
        
        <div class="content">
            <form class="message-form" id="adminMessageForm">
                <!-- Message Type Selection -->
                <div class="form-group">
                    <label class="form-label">Message Type</label>
                    <div class="message-type">
                        <div class="type-card" data-type="reminder">
                            <div class="icon">⏰</div>
                            <div class="title">Reminder</div>
                        </div>
                        <div class="type-card" data-type="wish">
                            <div class="icon">🎉</div>
                            <div class="title">Wish/Celebration</div>
                        </div>
                        <div class="type-card" data-type="announcement">
                            <div class="icon">📢</div>
                            <div class="title">Announcement</div>
                        </div>
                        <div class="type-card" data-type="custom">
                            <div class="icon">✍️</div>
                            <div class="title">Custom Message</div>
                        </div>
                    </div>
                    <input type="hidden" name="message_type" id="messageType">
                </div>

                <!-- Recipient Selection -->
                <div class="form-group">
                    <label class="form-label">Send To</label>
                    <div class="recipient-options">
                        <div class="recipient-option active" data-recipient="all">
                            <input type="radio" name="recipient" value="all" checked>
                            <strong>🌍 All Users</strong>
                            <p style="margin: 5px 0 0 0; font-size: 12px; color: #6b7280;">Send to all registered users</p>
                        </div>
                        <div class="recipient-option" data-recipient="recent">
                            <input type="radio" name="recipient" value="recent">
                            <strong>📅 Recent Users</strong>
                            <p style="margin: 5px 0 0 0; font-size: 12px; color: #6b7280;">Users who joined in last 30 days</p>
                        </div>
                        <div class="recipient-option" data-recipient="contributors">
                            <input type="radio" name="recipient" value="contributors">
                            <strong>📝 Content Contributors</strong>
                            <p style="margin: 5px 0 0 0; font-size: 12px; color: #6b7280;">Users who have submitted resources</p>
                        </div>
                        <div class="recipient-option" data-recipient="individual">
                            <input type="radio" name="recipient" value="individual">
                            <strong>👤 Individual User</strong>
                            <p style="margin: 5px 0 0 0; font-size: 12px; color: #6b7280;">Send to specific user by email</p>
                        </div>
                    </div>
                </div>

                <!-- Individual User Email (shown when individual is selected) -->
                <div class="form-group" id="individualUserGroup" style="display: none;">
                    <label class="form-label">User Email Address</label>
                    <input type="email" class="form-input" name="individual_email" placeholder="Enter user's email address">
                </div>

                <!-- Subject -->
                <div class="form-group">
                    <label class="form-label">Subject</label>
                    <input type="text" class="form-input" name="subject" id="subject" placeholder="Enter message subject">
                    <div class="char-counter">
                        <span id="subjectCounter">0</span>/100 characters
                    </div>
                </div>

                <!-- Message Content -->
                <div class="form-group">
                    <label class="form-label">Message Content</label>
                    <textarea class="form-textarea" name="message" id="messageContent" placeholder="Write your message here..."></textarea>
                    <div class="char-counter">
                        <span id="messageCounter">0</span>/2000 characters
                    </div>
                </div>

                <!-- Personal Touch -->
                <div class="form-group">
                    <label class="form-label">
                        <input type="checkbox" name="personal_touch" value="1" style="margin-right: 8px;">
                        Add personal greeting with user's name
                    </label>
                </div>

                <!-- Preview Section -->
                <div class="preview-section" id="previewSection" style="display: none;">
                    <div class="preview-title">Email Preview</div>
                    <div class="email-preview" id="emailPreview">
                        <!-- Preview will be populated by JavaScript -->
                    </div>
                </div>

                <!-- Send Button -->
                <button type="submit" class="send-button" id="sendButton">
                    📤 Send Message
                </button>

                <!-- Status Messages -->
                <div class="status-section" id="statusSection"></div>
            </form>
        </div>
    </div>

    <script>
        // Message type cards functionality
        document.querySelectorAll('.type-card').forEach(card => {
            card.addEventListener('click', function() {
                document.querySelectorAll('.type-card').forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                document.getElementById('messageType').value = this.dataset.type;
                updatePreview();
            });
        });

        // Recipient options functionality
        document.querySelectorAll('.recipient-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.recipient-option').forEach(o => o.classList.remove('active'));
                this.classList.add('active');
                this.querySelector('input[type="radio"]').checked = true;
                
                const recipient = this.dataset.recipient;
                const individualGroup = document.getElementById('individualUserGroup');
                if (recipient === 'individual') {
                    individualGroup.style.display = 'block';
                } else {
                    individualGroup.style.display = 'none';
                }
                updatePreview();
            });
        });

        // Character counters
        document.getElementById('subject').addEventListener('input', function() {
            document.getElementById('subjectCounter').textContent = this.value.length;
            updatePreview();
        });

        document.getElementById('messageContent').addEventListener('input', function() {
            document.getElementById('messageCounter').textContent = this.value.length;
            updatePreview();
        });

        // Preview functionality
        function updatePreview() {
            const type = document.getElementById('messageType').value || 'custom';
            const subject = document.getElementById('subject').value || 'Your Message Subject';
            const message = document.getElementById('messageContent').value || 'Your message content will appear here...';
            const recipient = document.querySelector('input[name="recipient"]:checked').value;
            
            const typeConfig = {
                reminder: { icon: '⏰', color: '#f59e0b', title: 'Reminder' },
                wish: { icon: '🎉', color: '#10b981', title: 'Best Wishes' },
                announcement: { icon: '📢', color: '#3b82f6', title: 'Announcement' },
                custom: { icon: '✍️', color: '#6b7280', title: 'Message' }
            };
            
            const config = typeConfig[type] || typeConfig.custom;
            const recipientText = {
                all: 'All Users',
                recent: 'Recent Users',
                contributors: 'Content Contributors',
                individual: 'Individual User'
            }[recipient] || 'Selected Users';

            const previewHTML = `
                <div style="border: 1px solid #d1d5db; border-radius: 8px; overflow: hidden;">
                    <div style="background: ${config.color}; color: white; padding: 20px; text-align: center;">
                        <h2 style="margin: 0; font-size: 20px;">${config.icon} ${config.title}</h2>
                        <p style="margin: 5px 0 0 0; opacity: 0.9;">${subject}</p>
                    </div>
                    <div style="padding: 20px;">
                        <p style="margin-bottom: 15px; color: #6b7280; font-size: 14px;">
                            <strong>To:</strong> ${recipientText}
                        </p>
                        <div style="background: #f9fafb; padding: 15px; border-radius: 6px; border-left: 4px solid ${config.color};">
                            ${message.replace(/\n/g, '<br>')}
                        </div>
                        <p style="margin-top: 20px; color: #6b7280; font-size: 14px;">
                            <strong>Best regards,<br>ReadProjectTopics Admin Team</strong>
                        </p>
                    </div>
                </div>
            `;
            
            document.getElementById('emailPreview').innerHTML = previewHTML;
            document.getElementById('previewSection').style.display = 'block';
        }

        // Form submission
        document.getElementById('adminMessageForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const sendButton = document.getElementById('sendButton');
            const statusSection = document.getElementById('statusSection');
            
            // Disable button and show loading
            sendButton.disabled = true;
            sendButton.innerHTML = '⏳ Sending...';
            
            // Simulate sending (replace with actual AJAX call)
            setTimeout(() => {
                // Show success message
                statusSection.className = 'status-section status-success';
                statusSection.style.display = 'block';
                statusSection.innerHTML = '✅ Message sent successfully! Users will receive the email shortly.';
                
                // Reset button
                sendButton.disabled = false;
                sendButton.innerHTML = '📤 Send Message';
                
                // Clear form
                this.reset();
                document.getElementById('previewSection').style.display = 'none';
                
                // Hide status after 5 seconds
                setTimeout(() => {
                    statusSection.style.display = 'none';
                }, 5000);
                
            }, 2000);
        });

        // Initialize preview
        updatePreview();
    </script>
</body>
</html>