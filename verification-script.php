<?php

/*
|--------------------------------------------------------------------------
| ADMIN MESSAGE SYSTEM - INSTALLATION VERIFICATION SCRIPT
|--------------------------------------------------------------------------
| Run this script to verify all files are properly installed
|
| Usage: 
| 1. Create this file in your Laravel project root
| 2. Visit: http://localhost:8080/verification-script.php
| 3. Check results below
*/

echo "<!DOCTYPE html>";
echo "<html><head><title>Admin Message System - Verification</title>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
    .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .success { color: #10b981; background: #ecfdf5; padding: 10px; border-radius: 5px; margin: 10px 0; }
    .error { color: #ef4444; background: #fef2f2; padding: 10px; border-radius: 5px; margin: 10px 0; }
    .warning { color: #f59e0b; background: #fffbeb; padding: 10px; border-radius: 5px; margin: 10px 0; }
    .info { color: #3b82f6; background: #eff6ff; padding: 10px; border-radius: 5px; margin: 10px 0; }
    .file-path { font-family: monospace; background: #f3f4f6; padding: 5px; border-radius: 3px; }
    h1 { color: #1f2937; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px; }
    h2 { color: #374151; margin-top: 30px; }
    .test-button { background: #3b82f6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px; display: inline-block; }
    .test-button:hover { background: #2563eb; }
</style>";
echo "</head><body>";

echo "<div class='container'>";
echo "<h1>📧 Admin Message System - Installation Verification</h1>";
echo "<p><strong>Generated:</strong> " . date('Y-m-d H:i:s') . "</p>";

// ========================================================================
// FILE EXISTENCE CHECKS
// ========================================================================

echo "<h2>📁 File Installation Checks</h2>";

$files_to_check = [
    'app/Http/Controllers/SimpleMessageController.php' => 'SimpleMessageController',
    'app/Mail/AdminMessageNotification.php' => 'AdminMessageNotification Mail Class',
    'resources/views/emails/admin-message.blade.php' => 'Email Template',
    'resources/views/admin/simple-message-form.blade.php' => 'Admin Form View'
];

$all_files_exist = true;

foreach ($files_to_check as $file => $description) {
    $file_path = __DIR__ . '/' . $file;
    if (file_exists($file_path)) {
        echo "<div class='success'>✅ <strong>{$description}</strong><br>";
        echo "<span class='file-path'>{$file}</span> - EXISTS</div>";
    } else {
        echo "<div class='error'>❌ <strong>{$description}</strong><br>";
        echo "<span class='file-path'>{$file}</span> - MISSING</div>";
        $all_files_exist = false;
    }
}

// ========================================================================
// CONTROLLER CLASS CHECK
// ========================================================================

echo "<h2>🏗️ Controller Class Verification</h2>";

try {
    $controller_path = __DIR__ . '/app/Http/Controllers/SimpleMessageController.php';
    
    if (file_exists($controller_path)) {
        $controller_content = file_get_contents($controller_path);
        
        if (strpos($controller_content, 'class SimpleMessageController') !== false) {
            echo "<div class='success'>✅ SimpleMessageController class definition found</div>";
            
            // Check for required methods
            $required_methods = ['index', 'sendMessage', 'searchUsers'];
            foreach ($required_methods as $method) {
                if (strpos($controller_content, "public function {$method}") !== false) {
                    echo "<div class='success'>✅ Method <strong>{$method}()</strong> exists</div>";
                } else {
                    echo "<div class='error'>❌ Method <strong>{$method}()</strong> missing</div>";
                }
            }
        } else {
            echo "<div class='error'>❌ SimpleMessageController class not found in file</div>";
        }
    } else {
        echo "<div class='error'>❌ Controller file does not exist</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>❌ Error checking controller: " . $e->getMessage() . "</div>";
}

// ========================================================================
// MAIL CLASS CHECK
// ========================================================================

echo "<h2>📧 Mail Class Verification</h2>";

try {
    $mail_path = __DIR__ . '/app/Mail/AdminMessageNotification.php';
    
    if (file_exists($mail_path)) {
        $mail_content = file_get_contents($mail_path);
        
        if (strpos($mail_content, 'class AdminMessageNotification') !== false) {
            echo "<div class='success'>✅ AdminMessageNotification mail class found</div>";
            
            if (strpos($mail_content, 'extends Mailable') !== false) {
                echo "<div class='success'>✅ Extends Mailable class correctly</div>";
            } else {
                echo "<div class='warning'>⚠️ Does not extend Mailable class</div>";
            }
        } else {
            echo "<div class='error'>❌ AdminMessageNotification class not found</div>";
        }
    } else {
        echo "<div class='error'>❌ Mail class file does not exist</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>❌ Error checking mail class: " . $e->getMessage() . "</div>";
}

// ========================================================================
// ROUTES CHECK
// ========================================================================

echo "<h2>🛣️ Routes Verification</h2>";

try {
    $routes_path = __DIR__ . '/routes/web.php';
    
    if (file_exists($routes_path)) {
        $routes_content = file_get_contents($routes_path);
        
        $required_routes = [
            "admin/messages" => "GET - Display admin message form",
            "admin/messages/send" => "POST - Send messages",
            "admin/messages/users/search" => "GET - User search API"
        ];
        
        foreach ($required_routes as $route => $description) {
            if (strpos($routes_content, $route) !== false) {
                echo "<div class='success'>✅ Route <strong>{$route}</strong> ({$description})</div>";
            } else {
                echo "<div class='error'>❌ Route <strong>{$route}</strong> missing</div>";
            }
        }
        
        if (strpos($routes_content, 'SimpleMessageController') !== false) {
            echo "<div class='success'>✅ SimpleMessageController referenced in routes</div>";
        } else {
            echo "<div class='error'>❌ SimpleMessageController not referenced in routes</div>";
        }
    } else {
        echo "<div class='error'>❌ routes/web.php file not found</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>❌ Error checking routes: " . $e->getMessage() . "</div>";
}

// ========================================================================
// VIEW FILES CHECK
// ========================================================================

echo "<h2>👁️ View Files Verification</h2>";

try {
    // Check admin form view
    $admin_form_path = __DIR__ . '/resources/views/admin/simple-message-form.blade.php';
    if (file_exists($admin_form_path)) {
        echo "<div class='success'>✅ Admin form view exists</div>";
        
        $admin_form_content = file_get_contents($admin_form_path);
        if (strpos($admin_form_content, 'Admin Message Center') !== false) {
            echo "<div class='success'>✅ Admin form contains expected content</div>";
        } else {
            echo "<div class='warning'>⚠️ Admin form may not contain expected content</div>";
        }
    } else {
        echo "<div class='error'>❌ Admin form view missing</div>";
    }
    
    // Check email template
    $email_template_path = __DIR__ . '/resources/views/emails/admin-message.blade.php';
    if (file_exists($email_template_path)) {
        echo "<div class='success'>✅ Email template exists</div>";
        
        $email_content = file_get_contents($email_template_path);
        if (strpos($email_content, 'ReadProjectTopics') !== false) {
            echo "<div class='success'>✅ Email template contains expected branding</div>";
        } else {
            echo "<div class='warning'>⚠️ Email template may not contain expected branding</div>";
        }
    } else {
        echo "<div class='error'>❌ Email template missing</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>❌ Error checking views: " . $e->getMessage() . "</div>";
}

// ========================================================================
// DATABASE CONNECTION CHECK
// ========================================================================

echo "<h2>💾 Database Connection Check</h2>";

try {
    // Check if we can connect to database and query users table
    $pdo = new PDO("mysql:host=localhost;dbname=authoranmain10", "root", "");
    $stmt = $pdo->query("SELECT COUNT(*) as total_users FROM users");
    $result = $stmt->fetch();
    
    echo "<div class='success'>✅ Database connection successful</div>";
    echo "<div class='info'>📊 Total users in database: <strong>{$result['total_users']}</strong></div>";
    
    // Check if resources table exists (for contributors count)
    $stmt = $pdo->query("SHOW TABLES LIKE 'resources'");
    if ($stmt->rowCount() > 0) {
        echo "<div class='success'>✅ Resources table exists (for contributors count)</div>";
    } else {
        echo "<div class='warning'>⚠️ Resources table not found - contributors count may not work</div>";
    }
    
} catch (Exception $e) {
    echo "<div class='error'>❌ Database connection failed: " . $e->getMessage() . "</div>";
    echo "<div class='warning'>⚠️ Admin form will show 0 users until database connection is fixed</div>";
}

// ========================================================================
// FINAL VERDICT
// ========================================================================

echo "<h2>🎯 Installation Status</h2>";

if ($all_files_exist) {
    echo "<div class='success'>";
    echo "<h3>✅ INSTALLATION APPEARS COMPLETE!</h3>";
    echo "<p><strong>Next Steps:</strong></p>";
    echo "<ol>";
    echo "<li>Test the real admin route: <a href='/admin/messages' class='test-button'>http://localhost:8080/admin/messages</a></li>";
    echo "<li>Test the route comparison: <a href='/test-admin-messages' class='test-button'>http://localhost:8080/test-admin-messages</a></li>";
    echo "<li>Try sending a test message to verify email functionality</li>";
    echo "</ol>";
    echo "</div>";
} else {
    echo "<div class='error'>";
    echo "<h3>❌ INSTALLATION INCOMPLETE</h3>";
    echo "<p><strong>Missing files detected. Please:</strong></p>";
    echo "<ol>";
    echo "<li>Copy all required files to their correct locations</li>";
    echo "<li>Run <code>composer dump-autoload</code></li>";
    echo "<li>Clear Laravel caches: <code>php artisan route:clear</code></li>";
    echo "<li>Refresh this verification page</li>";
    echo "</ol>";
    echo "</div>";
}

echo "<hr>";
echo "<p><small>Generated by Admin Message System Verification Script</small></p>";
echo "</div></body></html>";
?>