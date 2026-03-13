<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminBulkMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AdminMessageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Show the admin message form
     */
    public function index()
    {
        return view('admin.message-center');
    }

    /**
     * Show the simple message form for verified users only
     */
    public function showMessageUsersForm()
    {
        // Get count of verified users for display
        $verifiedUsersCount = User::whereNotNull('email_verified_at')
                                 ->whereNotNull('email')
                                 ->where('email', '!=', '')
                                 ->count();
        
        return view('admin.message-users', compact('verifiedUsersCount'));
    }

    /**
     * Send message to all verified users - QUEUED for thousands of users
     */
    public function sendToVerifiedUsers(Request $request)
    {
        // Validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        // Get all verified users only
        $verifiedUsers = User::whereNotNull('email_verified_at')
                           ->whereNotNull('email')
                           ->where('email', '!=', '')
                           ->get();

        // Check if there are any verified users
        if ($verifiedUsers->isEmpty()) {
            return back()->with('error', 'No verified users found to send message to.');
        }

        $title = $request->input('title');
        $messageContent = $request->input('message');
        $sentCount = 0;
        $failedCount = 0;

        // Get admin name
        $adminName = auth()->user()->name ?? 'Admin';

        // Send email to each verified user using QUEUE
        foreach ($verifiedUsers as $user) {
            try {
                // Create simple data array - NO User objects!
                // This prevents serialization errors in the queue
                $messageData = [
                    'userEmail' => $user->email,
                    'userName' => $user->name ?? $user->first_name ?? 'User',
                    'subject' => $title,
                    'message' => $messageContent,
                    'type' => 'custom',
                    'personalTouch' => true,
                    'adminName' => $adminName,
                ];

                // Use queue() instead of send() for thousands of users
                Mail::to($user->email)->queue(new AdminBulkMessage($messageData));
                $sentCount++;
                
                // Log every 100 emails to track progress
                if ($sentCount % 100 === 0) {
                    Log::info("Admin bulk message to verified users: Queued {$sentCount} emails so far...");
                }
                
            } catch (\Exception $e) {
                $failedCount++;
                Log::error("Failed to queue admin message for verified user {$user->id}: " . $e->getMessage());
            }
        }

        // Log the completion
        Log::info("Admin bulk message to verified users queued: {$sentCount} queued, {$failedCount} failed out of {$verifiedUsers->count()} total");

        // Redirect with appropriate message
        if ($failedCount === 0) {
            return back()->with('success', "Message successfully queued for {$sentCount} verified users! Emails will be sent in the background.");
        } else {
            return back()->with('warning', "Message queued for {$sentCount} users. {$failedCount} failed to queue.");
        }
    }

    /**
     * Send bulk/admin messages to users - QUEUED version
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message_type' => 'required|in:reminder,wish,announcement,custom',
            'recipient' => 'required|in:all,recent,contributors,individual',
            'subject' => 'required|string|max:100',
            'message' => 'required|string|max:2000',
            'individual_email' => 'nullable|email',
            'personal_touch' => 'nullable|boolean',
        ]);

        try {
            // Get target users based on recipient type
            $users = $this->getTargetUsers($request->recipient, $request->individual_email);
            
            if ($users->isEmpty()) {
                return redirect()->back()
                    ->with('error', 'No users found with the specified criteria.')
                    ->withInput();
            }

            $sentCount = 0;
            $failedCount = 0;

            // Send messages to each user using QUEUE
            foreach ($users as $user) {
                try {
                    // Prepare message data with simple strings - NO objects!
                    $messageData = [
                        'userEmail' => $user->email,
                        'userName' => $user->name ?? $user->first_name ?? 'User',
                        'subject' => $request->subject,
                        'message' => $request->message,
                        'type' => $request->message_type,
                        'personalTouch' => $request->has('personal_touch'),
                        'adminName' => auth()->user()->name ?? 'Admin',
                    ];

                    // Use queue() instead of send() for better performance
                    Mail::to($user->email)->queue(new AdminBulkMessage($messageData));
                    $sentCount++;

                    // Log the message
                    Log::info("Admin message queued for user {$user->id} ({$user->email})", [
                        'type' => $request->message_type,
                        'subject' => $request->subject,
                        'admin_id' => auth()->id(),
                    ]);

                } catch (\Exception $e) {
                    $failedCount++;
                    Log::error("Failed to queue admin message for user {$user->id}: " . $e->getMessage());
                }
            }

            // Return success response
            $message = "Message queued successfully! {$sentCount} users will receive the email.";
            if ($failedCount > 0) {
                $message .= " {$failedCount} users failed to queue.";
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error("Admin message queuing failed: " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to queue messages. Please try again.')
                ->withInput();
        }
    }

    /**
     * Get target users based on recipient criteria
     */
    private function getTargetUsers($recipient, $individualEmail = null)
    {
        switch ($recipient) {
            case 'all':
                return User::where('email', '!=', null)->get();
                
            case 'recent':
                return User::where('email', '!=', null)
                    ->where('created_at', '>=', now()->subDays(30))
                    ->get();
                
            case 'contributors':
                // Get users who have submitted resources
                return User::whereHas('resources', function ($query) {
                    $query->whereNotNull('id');
                })->get();
                
            case 'individual':
                if (!$individualEmail) {
                    return collect();
                }
                return User::where('email', $individualEmail)->get();
                
            default:
                return collect();
        }
    }

    /**
     * Get user statistics for message targeting
     */
    public function getUserStats()
    {
        $stats = [
            'total_users' => User::count(),
            'recent_users' => User::where('created_at', '>=', now()->subDays(30))->count(),
            'contributors' => User::whereHas('resources')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Test message sending (for development)
     */
    public function testMessage(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);

        try {
            // Use simple data, not object
            $messageData = [
                'userEmail' => $request->email,
                'userName' => 'Test User',
                'subject' => $request->subject,
                'message' => $request->message,
                'type' => 'test',
                'personalTouch' => true,
                'adminName' => 'Admin Test',
            ];

            Mail::to($request->email)->queue(new AdminBulkMessage($messageData));

            return response()->json([
                'success' => true,
                'message' => 'Test message queued successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to queue test message: ' . $e->getMessage()
            ], 500);
        }
    }
}