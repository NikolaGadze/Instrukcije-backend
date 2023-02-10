<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\City;
use App\Models\Country;
use App\Models\Subject;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\Permission;
use App\Models\RolePermission;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function registerAsUser(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users',
            'username' => 'required|string|unique:users',
            'password' => 'required|confirmed',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required|string|unique:users',
            'country_name' => 'required|string',
            'city_name' => 'required|string',
        ]);

        $country = Country::where('name', $request->country_name)->first();
        $city = City::where('name', $request->city_name)->first();
        $role = Role::where('name', $request->role_name)->first();

        $user = User::create([
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'country_id' => $country->id,
            'city_id' => $city->id,
        ]);

        $userRole = UserRole::create([
            'user_id' => $user->id,
            'role_id' => 2
        ]);

        return $user;
    }

    public function registerAsInstructor(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users',
            'username' => 'required|string|unique:users',
            'password' => 'required|confirmed',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required|string|unique:users',
            'country_name' => 'required|string',
            'city_name' => 'required|string',
            'subject_name' => 'required|string',
        ]);

        $country = Country::where('name', $request->country_name)->first();
        $city = City::where('name', $request->city_name)->first();
        $role = Role::where('name', $request->role_name)->first();
        $subject = Subject::where('name', $request->subject_name)->first();

        $user = User::create([
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'country_id' => $country->id,
            'city_id' => $city->id,
        ]);

        $userRole = UserRole::create([
            'user_id' => $user->id,
            'role_id' => 3
        ]);

        $schedule = Schedule::create([
            'user_id' => $user->id,
            'subject_id' => $subject->id,
            'role_id' => 3
        ]);

        return $user;
    }
}
