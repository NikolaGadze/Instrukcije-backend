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
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Storage;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('pziToken')->plainTextToken;

        return $token;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer'
        ]);
    }




        public function user(Request $request)
    {
        $user = Auth::user();
        $user->load('role');
        return $user;
    }

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
            'image' => 'nullable|image',
            'description'=> 'required|string'
        ]);

        $country = Country::where('name', $request->country_name)->first();
        $city = City::where('name', $request->city_name)->first();
        $role = Role::where('name', $request->role_name)->first();

        $image_path = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_path = Storage::put('uploads', $image);
           // $image_path = $image->store('public/uploads');
        }

        $user = User::create([
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'country_id' => $country->id,
            'city_id' => $city->id,
            'image' => $image_path,
            'description' => $request->description
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
            'image' => 'nullable|image'
        ]);

        $country = Country::where('name', $request->country_name)->first();
        $city = City::where('name', $request->city_name)->first();
        $role = Role::where('name', $request->role_name)->first();
        $subject = Subject::where('name', $request->subject_name)->first();

        $image_path = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_path = $image->store('public/uploads');
        }




        $user = User::create([
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'country_id' => $country->id,
            'city_id' => $city->id,
            'image' => $image_path,
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

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response('Success');
    }


    public function filterInstructors(Request $request){
        $user = Auth::user();

        if ($user) {
            $userRoleUser = DB::table('user_roles')->where('user_id', $user->id)->where('role_id', 2)->first();
            $userRoleInstructor = DB::table('user_roles')->where('user_id', $user->id)->where('role_id', 3)->first();
            $userRoleAdmin = DB::table('user_roles')->where('user_id', $user->id)->where('role_id', 4)->first();
            if ($userRoleUser || $userRoleInstructor || $userRoleAdmin) {
                $city = $request->city;
                $country = $request->country;
                $subject = $request->subject;
                $first_name = $request->first_name;
                $last_name = $request->last_name;

                $query = DB::table('users as u')
                    ->join('cities as cy', 'u.city_id', '=', 'cy.id')
                    ->join('countries as cut', 'cy.country_id', '=', 'cut.id')
                    ->join('schedules as sch', 'u.id', '=', 'sch.user_id')
                    ->join('subjects as sub', 'sch.subject_id', '=', 'sub.id')
                    ->join('user_roles as ur', 'u.id', '=', 'ur.user_id')
                    ->select('u.first_name', 'u.last_name', 'u.email', 'u.username', 'u.phone', 'u.image','cy.name as city_name', 'cut.name as country_name', 'sub.name as subject_name')
                    ->where('ur.role_id', 3)
                    ->where('u.status', 'Active');
                if ($city != "")
                    $query->where('cy.name', 'like', "%$city%");
                if ($country != "")
                    $query->where('cut.name', 'like', "%$country%");
                if ($subject != "")
                    $query->where('sub.name', 'like', "%$subject%");
                if ($first_name != "")
                    $query->where('u.first_name', 'like', "%$first_name%");
                if ($last_name != "")
                    $query->where('u.last_name', 'like', "%$last_name%");


                $data = $query->paginate(9);

                if ($data->count() == 0) {
                    return response()->json(['message' => 'No results found'], 404);
                }

                return response()->json($data);
            }
        } else {
            abort(401);
        }
    }

    public function searchInstructors(Request $request) {
        $user = Auth::user();
        if ($user) {
            $userRoleUser = DB::table('user_roles')->where('user_id', $user->id)->where('role_id', 2)->first();
            $userRoleInstructor = DB::table('user_roles')->where('user_id', $user->id)->where('role_id', 3)->first();
            $userRoleAdmin = DB::table('user_roles')->where('user_id', $user->id)->where('role_id', 4)->first();

            if ($userRoleUser || $userRoleInstructor || $userRoleAdmin) {
                $search = $request->search;
                $query = DB::table('users as u')
                    ->join('cities as cy', 'u.city_id', '=', 'cy.id')
                    ->join('countries as cut', 'cy.country_id', '=', 'cut.id')
                    ->join('schedules as sch', 'u.id', '=', 'sch.user_id')
                    ->join('subjects as sub', 'sch.subject_id', '=', 'sub.id')
                    ->join('user_roles as ur', 'u.id', '=', 'ur.user_id')
                    ->select('u.first_name', 'u.last_name', 'u.email', 'u.username', 'u.phone', 'u.image','cy.name as city_name', 'cut.name as country_name', 'sub.name as subject_name')
                    ->where('ur.role_id', 3)
                    ->where('u.status', 'Active')
                    ->where(function ($q) use ($search) {
                        $q->where('u.first_name', 'like', "%$search%")
                            ->orWhere('u.last_name', 'like', "%$search%")
                            ->orWhere('cy.name', 'like', "%$search%")
                            ->orWhere('cut.name', 'like', "%$search%")
                            ->orWhere('sub.name', 'like', "%$search%");
                    });

                $data = $query->paginate(9);

                if ($data->count() == 0) {
                    return response()->json(['message' => 'No results found for ' . $search], 404);
                }

                return response()->json($data);
            }
        } else {
                abort(401);
        }
    }


    public function filterUsers(Request $request){
        $user = Auth::user();

        if ($user) {
            $userRoleUser = DB::table('user_roles')->where('user_id', $user->id)->where('role_id', 2)->first();
            $userRoleInstructor = DB::table('user_roles')->where('user_id', $user->id)->where('role_id', 3)->first();
            $userRoleAdmin = DB::table('user_roles')->where('user_id', $user->id)->where('role_id', 4)->first();
            if ($userRoleUser || $userRoleInstructor || $userRoleAdmin) {
                $city = $request->city;
                $country = $request->country;
                $description = $request->description;
                $first_name = $request->first_name;
                $last_name = $request->last_name;

                $query = DB::table('users as u')
                    ->join('cities as cy', 'u.city_id', '=', 'cy.id')
                    ->join('countries as cut', 'cy.country_id', '=', 'cut.id')
                    ->join('user_roles as ur', 'u.id', '=', 'ur.user_id')
                    ->select('u.first_name', 'u.last_name', 'u.email', 'u.username', 'u.phone', 'u.image', 'u.description','cy.name as city_name', 'cut.name as country_name')
                    ->where('ur.role_id', 2)
                    ->where('u.status', 'Active');
                if ($city != "")
                    $query->where('cy.name', 'like', "%$city%");
                if ($country != "")
                    $query->where('cut.name', 'like', "%$country%");
                if ($description != "")
                    $query->where('u.description', 'like', "%$description%");
                if ($first_name != "")
                    $query->where('u.first_name', 'like', "%$first_name%");
                if ($last_name != "")
                    $query->where('u.last_name', 'like', "%$last_name%");


                $data = $query->paginate(9);

                if ($data->count() == 0) {
                    return response()->json(['message' => 'No results found'], 404);
                }

                return response()->json($data);
            }
        } else {
            abort(401);
        }
    }


    public function searchUsers(Request $request) {
        $user = Auth::user();
        if ($user) {
            $userRoleUser = DB::table('user_roles')->where('user_id', $user->id)->where('role_id', 2)->first();
            $userRoleInstructor = DB::table('user_roles')->where('user_id', $user->id)->where('role_id', 3)->first();
            $userRoleAdmin = DB::table('user_roles')->where('user_id', $user->id)->where('role_id', 4)->first();

            if ($userRoleUser || $userRoleInstructor || $userRoleAdmin) {
                $search = $request->search;
                $query = DB::table('users as u')
                    ->join('cities as cy', 'u.city_id', '=', 'cy.id')
                    ->join('countries as cut', 'cy.country_id', '=', 'cut.id')
                    ->join('user_roles as ur', 'u.id', '=', 'ur.user_id')
                    ->select('u.first_name', 'u.last_name', 'u.email', 'u.username', 'u.phone', 'u.image', 'u.description','cy.name as city_name', 'cut.name as country_name')
                    ->where('ur.role_id', 2)
                    ->where('u.status', 'Active')
                    ->where(function ($q) use ($search) {
                        $q->where('u.first_name', 'like', "%$search%")
                            ->orWhere('u.last_name', 'like', "%$search%")
                            ->orWhere('cy.name', 'like', "%$search%")
                            ->orWhere('cut.name', 'like', "%$search%")
                            ->orWhere('u.description', 'like', "%$search%");
                    });

                $data = $query->paginate(9);

                if ($data->count() == 0) {
                    return response()->json(['message' => 'No results found '], 404);
                }

                return response()->json($data);
            }
        } else {
            abort(401);
        }
    }



    public function showProfile(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $userRoleUser = DB::table('user_roles')->where('user_id', $user->id)->where('role_id', 2)->first();
            $userRoleInstructor = DB::table('user_roles')->where('user_id', $user->id)->where('role_id', 3)->first();
            $userRoleAdmin = DB::table('user_roles')->where('user_id', $user->id)->where('role_id', 4)->first();

            if ($userRoleInstructor) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'email' => $user->email,
                        'username' => $user->username,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'phone' => $user->phone,
                        'country_name' => $user->city->country->name,
                        'city_name' => $user->city->name,
                        'subjects' => $user->schedules->load("subject"),
                        'image' => $user->image,
                    ]
                ], 200);
            } else if ($userRoleUser) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'email' => $user->email,
                        'username' => $user->username,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'phone' => $user->phone,
                        'country_name' => $user->city->country->name,
                        'city_name' => $user->city->name,
                        'image' => $user->image,
                        'description' => $user->description
                    ]
                ], 200);
            } else if ($userRoleAdmin) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'email' => $user->email,
                        'username' => $user->username,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'phone' => $user->phone,
                        'country_name' => $user->city->country->name,
                        'city_name' => $user->city->name,
                        'image' => $user->image
                    ]
                ], 200);
            }
        }
        else {
            abort(401);
        }
    }



    public function getAllUserRoles(Request $request) {
        $user = Auth::user();

        if ($user) {
            $userRoleAdmin = DB::table('user_roles')->where('user_id', $user->id)->where('role_id', 4)->first();

            if($userRoleAdmin) {
                $query = DB::table('users AS u')
                    ->select('u.id As user_id', 'u.first_name', 'u.last_name', 'u.email', 'u.username', 'r.name AS role_name',)
                    ->join('user_roles AS ur', 'u.id', '=', 'ur.user_id')
                    ->join('roles AS r', 'r.id', '=', 'ur.role_id')
                    ->where('u.status', 'Active')
                    ->where('ur.role_id', '=', 2)
                    ->orWhere('ur.role_id', '=',3);

                $data = $query->paginate(9);

                return response()->json($data);
            } else {
                abort(401);
            }

        } else {
            abort(403);
        }
    }

    public function deleteUsers($userId)
    {
        $user = Auth::user();

        if ($user) {
            $userRoleAdmin = DB::table('user_roles')->where('user_id', $user->id)->where('role_id', 4)->first();

            if($userRoleAdmin) {

                // Delete user data from user_roles table
                DB::table('user_roles')->where('user_id', $userId)->delete();

                // Delete user data from schedules table
                DB::table('schedules')->where('user_id', $userId)->delete();

                // Delete user data from users table
                DB::table('users')->where('id', $userId)->delete();

                return response()->json(['message' => 'User has been deleted successfully.'], 200);

            } else {
                abort(401);
            }

        } else {
            abort(403);
        }
    }


    public function updateProfile(Request $request) {
        $user = Auth::user();

        $validated = $request->validate([

            'email' => 'required|email|unique:users',
            'username' => 'required|string|unique:users',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required|string|unique:users',

        ]);
        return $user->update([
            'email' => $request->email,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'phone' => $request->phone,

        ]);


    }




}
