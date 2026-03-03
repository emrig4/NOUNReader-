<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminBulkMessage;
use App\Modules\User\Models\User;
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
     * Send bulk/admin messages to users
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

            // Send messages to each user
            foreach ($users as $user) {
                try {
                    // Prepare message data
                    $messageData = [
                        'user' => $user,
                        'subject' => $request->subject,
                        'message' => $request->message,
                        'type' => $request->message_type,
                        'personalTouch' => $request->has('personal_touch'),
                        'adminName' => auth()->user()->name ?? 'Admin',
                    ];

                    // Send email
                    Mail::to($user->email)->send(new AdminBulkMessage($messageData));
                    $sentCount++;

                    // Log the message
                    Log::info("Admin message sent to user {$user->id} ({$user->email})", [
                        'type' => $request->message_type,
                        'subject' => $request->subject,
                        'admin_id' => auth()->id(),
                    ]);

                } catch (\Exception $e) {
                    $failedCount++;
                    Log::error("Failed to send admin message to user {$user->id}: " . $e->getMessage());
                }
            }

            // Return success response
            $message = "Message sent successfully! {$sentCount} users received the email.";
            if ($failedCount > 0) {
                $message .= " {$failedCount} users failed to receive the email.";
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error("Admin message sending failed: " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to send messages. Please try again.')
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
            $testUser = (object)[
                'name' => 'Test User',
                'email' => $request->email,
            ];

            $messageData = [
                'user' => $testUser,
                'subject' => $request->subject,
                'message' => $request->message,
                'type' => 'test',
                'personalTouch' => true,
                'adminName' => 'Admin Test',
            ];

            Mail::to($request->email)->send(new AdminBulkMessage($messageData));

            return response()->json([
                'success' => true,
                'message' => 'Test message sent successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test message: ' . $e->getMessage()
            ], 500);
        }
    }
}