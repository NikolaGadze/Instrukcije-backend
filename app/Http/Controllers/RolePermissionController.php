<?php

namespace App\Http\Controllers;

use App\Models\RolePermission;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return RolePermission::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return RolePermission::create([
            'permission_id'=> $request->permission_id,
            'role_id'=> $request->role_id,
            'status'=> $request->status
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RolePermission  $rolePermission
     * @return \Illuminate\Http\Response
     */
    public function show(RolePermission $rolePermission)
    {
        return $rolePermission;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RolePermission  $rolePermission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RolePermission $rolePermission)
    {
        return $rolePermission->update([
            'permission_id'=> $request->permission_id,
            'role_id'=> $request->role_id,
            'status'=> $request->status
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RolePermission  $rolePermission
     * @return \Illuminate\Http\Response
     */
    public function destroy(RolePermission $rolePermission)
    {
        return $rolePermission->delete();
    }
}
