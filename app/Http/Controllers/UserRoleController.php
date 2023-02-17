<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {

        return UserRole::all();

        /*$query = DB::table('users AS u')
            ->select('u.first_name', 'u.last_name', 'u.email', 'u.username', 'r.name AS role_name',)
            ->join('user_roles AS ur', 'u.id', '=', 'ur.user_id')
            ->join('roles AS r', 'r.id', '=', 'ur.role_id')
            ->where('ur.role_id', '=', 2)
            ->orWhere('ur.role_id', '=',3);

        $data = $query->paginate(10);

        return response()->json($data);*/
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return UserRole::create([
            'user_id'=> $request->user_id,
            'role_id'=> $request->role_id,
            'status'=> $request->status
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function show(UserRole $userRole)
    {
        return $userRole;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserRole $userRole)
    {
        return $userRole->update([
            'user_id'=> $request->user_id,
            'role_id'=> $request->role_id,
            'status'=> $request->status
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserRole $userRole)
    {
        return $userRole->delete();
    }
}
