<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminBulkMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AdminMessageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Show the simple message form for verified users only
     */
    public function showMessageUsersForm()
    {
        $verifiedUsersCount = User::whereNotNull('email_verified_at')
                                 ->whereNotNull('email')
                                 ->where('email', '!=', '')
                                 ->count();
        
        return view('admin.message-users', compact('verifiedUsersCount'));
    }

    /**
     * Send message to all verified users using QUEUES (for thousands of users)
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
        
        // Get admin name
        $adminName = auth()->user()->name ?? 'Admin';

        // Queue emails instead of sending synchronously
        $queuedCount = 0;
        foreach ($verifiedUsers as $user) {
            try {
                $messageData = [
                    'user' => $user,
                    'subject' => $title,
                    'message' => $messageContent,
                    'type' => 'custom',
                    'personalTouch' => true,
                    'adminName' => $adminName,
                ];

                // Dispatch to queue instead of sending directly
                Mail::to($user->email)->queue(new AdminBulkMessage($messageData));
                $queuedCount++;
                
                // Log progress every 500 emails
                if ($queuedCount % 500 === 0) {
                    Log::info("Admin bulk message: Queued {$queuedCount} emails so far...");
                }
                
            } catch (\Exception $e) {
                Log::error("Failed to queue admin message for user {$user->id}: " . $e->getMessage());
            }
        }

        Log::info("Admin bulk message: Queued {$queuedCount} emails for delivery");

        // Return immediately - emails will be sent in background
        return back()->with('success', "Message queued for {$queuedCount} verified users! Emails will be delivered in the background.");
    }
}