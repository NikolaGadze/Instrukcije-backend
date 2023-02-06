<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use Illuminate\Http\Request;

class UserNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return UserNotification::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return UserNotification::create([
           'user_id'=> $request->user_id,
           'notification_id'=> $request->notification_id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserNotification  $userNotification
     * @return \Illuminate\Http\Response
     */
    public function show(UserNotification $userNotification)
    {
        return $userNotification;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserNotification  $userNotification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserNotification $userNotification)
    {
        return $userNotification->update([
            'user_id'=> $request->user_id,
            'notification_id'=> $request->notification_id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserNotification  $userNotification
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserNotification $userNotification)
    {
        return $userNotification->delete();
    }
}
