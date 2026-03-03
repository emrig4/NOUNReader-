<?php

namespace App\Modules\Admin\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

use Spatie\Permission\Models\Role;



class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        
        $users = User::latest()->get();
        // dd($users);
        if ($request->ajax()) {
            return Datatables::of($users)
                // ->addIndexColumn()
                ->setRowId('id')
                ->addColumn('action', function($row){
                    
                    $showUrl = route('admin.users.show', $row->id);
                    $editUrl = route('admin.users.edit', $row->id);

                    $btn = "<a href='$showUrl' class='text-xs h-4 btn btn_primary'>View</a>";
                    $btn = $btn."<a href='$editUrl' class='text-xs h-4 btn btn_warning'>Edit</a>";
         
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }else{
            return view('admin.users.index', compact('users'));
        }


    }
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('admin::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        User::create($request->all() );
        return redirect()->back();
    }

    /**
     * Show the specified resource. 
     * @param int $id
     * @return Renderable
     */
    public function show(User $user)
    {   
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(User $user)
    {
        $userRole = $user->roles->pluck('name')->toArray();
        $roles = Role::latest()->get();
        return view('admin::users.edit', compact('user', 'roles', 'userRole'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $user->update($request->validated());
        $user->syncRoles($request->get('role'));

        return redirect()->back()
            ->withSuccess(__('User updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(User $user)
    {
        //
        $user->delete();
        return redirect()->back();
    }
}
