<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;

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
}