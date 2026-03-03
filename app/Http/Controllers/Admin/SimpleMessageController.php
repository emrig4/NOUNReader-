<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SimpleMessageController extends Controller
{
    /**
     * Show the admin message form with user statistics
     */
    public function index()
    {
        // Get user statistics from database
        $totalUsers = User::count();
        $recentUsers = User::where('created_at', '>=', now()->subDays(30))->count();
        $contributors = User::whereHas('resources')->count();

        return view('admin.simple-message-form', [
            'totalUsers' => $totalUsers,
            'recentUsers' => $recentUsers,
            'contributors' => $contributors
        ]);
    }

    /**
     * Handle message sending
     */
    public function sendMessage(Request $request)
    {
        // Validate input
        $request->validate([
            'subject' => 'required|string|max:100',
            'message' => 'required|string|max:2000',
            'recipient_type' => 'required|in:all_users,recent_users,individual_user',
            'user_id' => 'required_if:recipient_type,individual_user|nullable|exists:users,id'
        ]);

        try {
            // Determine recipients based on type
            $recipients = $this->getRecipients($request->recipient_type, $request->user_id);

            if ($recipients->isEmpty()) {
                return redirect()->back()
                    ->with('error', 'No recipients found for the selected criteria.');
            }

            // Send email to each recipient
            $sentCount = 0;
            foreach ($recipients as $user) {
                Mail::to($user->email)->send(new \App\Mail\AdminMessageNotification(
                    $request->subject,
                    $request->message,
                    $user
                ));
                $sentCount++;
            }

            return redirect()->back()
                ->with('success', "Message sent successfully to {$sentCount} recipients!");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error sending messages: ' . $e->getMessage());
        }
    }

    /**
     * Get recipients based on type
     */
    private function getRecipients($type, $userId = null)
    {
        switch ($type) {
            case 'all_users':
                return User::select('id', 'name', 'email')->get();
                
            case 'recent_users':
                return User::select('id', 'name', 'email')
                    ->where('created_at', '>=', now()->subDays(30))
                    ->get();
                
            case 'individual_user':
                return User::select('id', 'name', 'email')
                    ->where('id', $userId)
                    ->get();
                
            default:
                return collect();
        }
    }

    /**
     * Search users for individual selection
     */
    public function searchUsers(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $users = User::select('id', 'name', 'email', 'created_at')
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->limit(10)
            ->get();

        return response()->json($users);
    }
}