<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SimpleAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function pending()
    {
        $pendingResources = DB::table('resources')
            ->where('approval_status', 'pending')
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('admin.simple-pending', compact('pendingResources'));
    }

    public function approve($id)
    {
        DB::table('resources')
            ->where('id', $id)
            ->update([
                'approval_status' => 'approved',
                'is_published' => 1,
                'approved_at' => now(),
                'approved_by' => auth()->id(),
            ]);

        return redirect()->back()->with('success', "Resource #{$id} approved successfully!");
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        DB::table('resources')
            ->where('id', $id)
            ->update([
                'approval_status' => 'rejected',
                'admin_notes' => $request->admin_notes,
                'rejected_at' => now(),
                'rejected_by' => auth()->id(),
            ]);

        return redirect()->back()->with('success', "Resource #{$id} rejected successfully!");
    }

    
}
