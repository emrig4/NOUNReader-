<?php

namespace App\Modules\Admin\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {

    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   

        $roles = Role::with('permissions')->select('roles.*');
        $permissions = Permission::get();

        if ($request->ajax()) {
            return Datatables::of($roles)
                // ->addIndexColumn()
                ->setRowId('id')

                ->addColumn('permissions', function ($row) {
                    return $row->permissions->map(function($permission) {
                        return $permission->name;
                    })->implode(', ');
                })

                ->addColumn('action', function($row){
                    
                    $showUrl = route('admin.roles.show', $row->id);
                    $editUrl = route('admin.roles.edit', $row->id);

                    $btn = "<a href='$showUrl' class='text-xs h-4 btn btn_primary'>View</a>";
                    $btn = $btn."<a href='$editUrl' class='text-xs h-4 btn btn_warning'>Edit</a>";
         
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

       
        return view('admin.roles.index',compact('roles', 'permissions'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::get();
        return view('roles.create', compact('permissions'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $role = Role::create(['name' => $request->get('name')]);
        $role->syncPermissions($request->get('permission'));
        notify()->success('success','Role created successfully');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $role = $role;
        $rolePermissions = $role->permissions;
        $permissions = Permission::get();
        return view('admin.roles.show', compact('role', 'rolePermissions', 'permissions'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $role = $role;
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        $permissions = Permission::get();
    
        return view('admin.roles.edit', compact('role', 'rolePermissions', 'permissions'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Role $role, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);
        
        $role->update($request->only('name'));
    
        $role->syncPermissions($request->get('permission'));


        notify()->success('success','Role updated successfully');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('roles.index')
                        ->with('success','Role deleted successfully');
    }
}