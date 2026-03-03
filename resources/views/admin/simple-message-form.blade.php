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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        
        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding: 30px;
            background: #f8fafc;
        }
        
        .stat-card {
            text-align: center;
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 14px;
            color: #64748b;
        }
        
        .form-container {
            padding: 40px;
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
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-input:focus, .form-textarea:focus, .form-select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .recipients-group {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .recipient-option {
            position: relative;
        }
        
        .recipient-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }
        
        .recipient-card {
            padding: 20px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }
        
        .recipient-option input[type="radio"]:checked + .recipient-card {
            border-color: #667eea;
            background: #f0f4ff;
            transform: translateY(-2px);
        }
        
        .recipient-card:hover {
            border-color: #667eea;
        }
        
        .recipient-icon {
            font-size: 24px;
            margin-bottom: 8px;
        }
        
        .recipient-title {
            font-weight: 600;
            color: #374151;
            margin-bottom: 4px;
        }
        
        .recipient-desc {
            font-size: 12px;
            color: #64748b;
        }
        
        .user-search {
            margin-top: 15px;
            display: none;
        }
        
        .user-search.show {
            display: block;
        }
        
        .send-button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }
        
        .send-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        
        .send-button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        
        .char-count {
            text-align: right;
            font-size: 12px;
            color: #64748b;
            margin-top: 5px;
        }
        
        @media (max-width: 768px) {
            .stats {
                grid-template-columns: 1fr;
            }
            
            .recipients-group {
                grid-template-columns: 1fr;
            }
            
            .form-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>📧 Admin Message Center</h1>
            <p>Send custom messages to users for awareness and engagement</p>
        </div>
        
        <!-- Statistics -->
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number">{{ $totalUsers }}</div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $recentUsers }}</div>
                <div class="stat-label">Recent Users (30 days)</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $contributors }}</div>
                <div class="stat-label">Contributors</div>
            </div>
        </div>
        
        <!-- Form Container -->
        <div class="form-container">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success">
                    ✅ {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-error">
                    ❌ {{ session('error') }}
                </div>
            @endif
            
            <!-- Message Form -->
            <form id="messageForm" method="POST" action="{{ route('admin.messages.send') }}">
                @csrf
                
                <!-- Subject -->
                <div class="form-group">
                    <label class="form-label">Subject *</label>
                    <input type="text" 
                           name="subject" 
                           class="form-input" 
                           placeholder="Enter message subject..."
                           maxlength="100"
                           required>
                    <div class="char-count">
                        <span id="subjectCount">0</span>/100 characters
                    </div>
                </div>
                
                <!-- Message Content -->
                <div class="form-group">
                    <label class="form-label">Message *</label>
                    <textarea name="message" 
                              class="form-textarea" 
                              placeholder="Type your message here..."
                              maxlength="2000"
                              required></textarea>
                    <div class="char-count">
                        <span id="messageCount">0</span>/2000 characters
                    </div>
                </div>
                
                <!-- Recipients Selection -->
                <div class="form-group">
                    <label class="form-label">Select Recipients *</label>
                    <div class="recipients-group">
                        <div class="recipient-option">
                            <input type="radio" name="recipients" value="all" id="all" required>
                            <label for="all" class="recipient-card">
                                <div class="recipient-icon">👥</div>
                                <div class="recipient-title">All Users</div>
                                <div class="recipient-desc">{{ $totalUsers }} users</div>
                            </label>
                        </div>
                        
                        <div class="recipient-option">
                            <input type="radio" name="recipients" value="recent" id="recent" required>
                            <label for="recent" class="recipient-card">
                                <div class="recipient-icon">🆕</div>
                                <div class="recipient-title">Recent Users</div>
                                <div class="recipient-desc">{{ $recentUsers }} users (30 days)</div>
                            </label>
                        </div>
                        
                        <div class="recipient-option">
                            <input type="radio" name="recipients" value="individual" id="individual" required>
                            <label for="individual" class="recipient-card">
                                <div class="recipient-icon">👤</div>
                                <div class="recipient-title">Individual User</div>
                                <div class="recipient-desc">Select specific user</div>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Individual User Search -->
                    <div class="user-search" id="userSearch">
                        <label class="form-label">Search and Select User</label>
                        <input type="text" 
                               id="userSearchInput" 
                               class="form-input" 
                               placeholder="Search by name or email...">
                        <select name="user_id" id="userSelect" class="form-select" style="margin-top: 10px;">
                            <option value="">Select a user...</option>
                        </select>
                    </div>
                </div>
                
                <!-- Send Button -->
                <button type="submit" class="send-button" id="sendButton">
                    📤 Send Message
                </button>
            </form>
        </div>
    </div>
    
    <script>
        // Character counters
        document.querySelector('input[name="subject"]').addEventListener('input', function() {
            document.getElementById('subjectCount').textContent = this.value.length;
        });
        
        document.querySelector('textarea[name="message"]').addEventListener('input', function() {
            document.getElementById('messageCount').textContent = this.value.length;
        });
        
        // Show/hide user search
        document.querySelectorAll('input[name="recipients"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const userSearch = document.getElementById('userSearch');
                if (this.value === 'individual') {
                    userSearch.classList.add('show');
                } else {
                    userSearch.classList.remove('show');
                }
            });
        });
        
        // User search functionality
        let searchTimeout;
        document.getElementById('userSearchInput').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value;
            
            if (query.length < 2) {
                document.getElementById('userSelect').innerHTML = '<option value="">Select a user...</option>';
                return;
            }
            
            searchTimeout = setTimeout(() => {
                fetch(`/admin/messages/users?search=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(users => {
                        const select = document.getElementById('userSelect');
                        select.innerHTML = '<option value="">Select a user...</option>';
                        
                        users.forEach(user => {
                            const option = document.createElement('option');
                            option.value = user.id;
                            option.textContent = `${user.name} (${user.email})`;
                            select.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error searching users:', error));
            }, 300);
        });
        
        // Form validation
        document.getElementById('messageForm').addEventListener('submit', function(e) {
            const recipients = document.querySelector('input[name="recipients"]:checked').value;
            const sendButton = document.getElementById('sendButton');
            
            if (recipients === 'individual') {
                const userSelect = document.getElementByById('userSelect');
                if (!userSelect.value) {
                    e.preventDefault();
                    alert('Please select a specific user.');
                    return;
                }
            }
            
            // Disable button during submission
            sendButton.disabled = true;
            sendButton.textContent = '⏳ Sending...';
        });
    </script>
</body>
</html>