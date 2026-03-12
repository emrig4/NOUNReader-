<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminBulkMessage;

class AdminController extends Controller
{
    public function __construct()
    {
        // Only allow access if user is admin
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || !auth()->user()->is_admin) {
                abort(403, 'Access denied. Admin privileges required.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $search = request('search');
        
        $query = User::where('id', '!=', auth()->id())
                     ->orderBy('created_at', 'desc');
        
        // Add search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('email', 'like', '%' . $search . '%')
                  ->orWhere('name', 'like', '%' . $search . '%');
            });
        }
        
        $users = $query->paginate(15)->withQueryString();

        return view('admin.users', compact('users', 'search'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent self-deletion
        if ($user->id == auth()->id()) {
            return back()->with('error', 'You cannot delete your own account!');
        }

        // Prevent deletion of other admins
        if ($user->is_admin) {
            return back()->with('error', 'Cannot delete admin users!');
        }

        try {
            // STEP 1: Disable foreign key checks (same as your manual script)
            \DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            // STEP 2: Delete the user (all related data will be automatically cleaned)
            $user->delete();
            
            // STEP 3: Re-enable foreign key checks (same as your manual script)
            \DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            return back()->with('success', 'User deleted successfully! All related data cleaned up.');
            
        } catch (\Exception $e) {
            // Make sure foreign key checks are re-enabled even if something goes wrong
            \DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            return back()->with('error', 'Failed to delete user. Please try again.');
        }
    }

    /**
     * Show the form for sending message to all verified users
     */
    public function showMessageUsersForm()
    {
        // Get count of verified users
        $verifiedUsersCount = User::whereNotNull('email_verified_at')
                                  ->where('status', 'active')
                                  ->count();
        
        return view('admin.message-users', compact('verifiedUsersCount'));
    }

    /**
     * Send message to all verified users
     */
    public function sendMessageToUsers(Request $request)
    {
        // Validate the request
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ], [
            'subject.required' => 'Please enter a subject for the email.',
            'message.required' => 'Please enter your message.',
            'message.min' => 'Message must be at least 10 characters long.',
        ]);

        $subject = $request->input('subject');
        $messageBody = $request->input('message');

        // Get all verified users (users who have verified their email)
        $verifiedUsers = User::whereNotNull('email_verified_at')
                            ->where('status', 'active')
                            ->get();

        if ($verifiedUsers->isEmpty()) {
            return back()->with('error', 'No verified users found to send message to.');
        }

        $sentCount = 0;
        $failedCount = 0;

        // Get admin name
        $adminName = auth()->user()->first_name ?? 'Admin';

        // Send email to each verified user
        foreach ($verifiedUsers as $user) {
            try {
                // Prepare message data matching the existing AdminBulkMessage format
                $messageData = [
                    'user' => $user,
                    'subject' => $subject,
                    'message' => $messageBody,
                    'type' => 'custom', // Using custom type for admin messages
                    'personalTouch' => true,
                    'adminName' => $adminName,
                ];
                
                Mail::to($user->email)->send(new AdminBulkMessage($messageData));
                $sentCount++;
            } catch (\Exception $e) {
                $failedCount++;
                \Log::error('Failed to send bulk message to user: ' . $user->email, [
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Return appropriate message based on results
        if ($failedCount === 0) {
            return redirect()->route('admin.message-users')
                ->with('success', "Message sent successfully to {$sentCount} verified users!");
        } else {
            return redirect()->route('admin.message-users')
                ->with('warning', "Message sent to {$sentCount} users. {$failedCount} failed.");
        }
    }
}