<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /*$search = $request->search;
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

        $data = $query->paginate(10);

        if ($data->count() == 0) {
            return response()->json(['message' => 'No results found for ' . $search], 404);
        }

        return response()->json($data);*/

        return Schedule::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Schedule::create([
           'user_id'=> $request->user_id,
           'subject_id'=> $request->subject_id,
           'classroom_id'=> $request->classroom_id,
           'role_id'=> $request->role_id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function show(Schedule $schedule)
    {
        return $schedule;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Schedule $schedule)
    {
        return $schedule->update([
            'user_id'=> $request->user_id,
            'subject_id'=> $request->subject_id,
            'classroom_id'=> $request->classroom_id,
            'role_id'=> $request->role_id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schedule $schedule)
    {
        return $schedule->delete();
    }
}
