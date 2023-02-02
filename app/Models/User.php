<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

abstract class User extends Model implements Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['first_name', 'last_name', 'username', 'email', 'password', 'image', 'city_id', 'status'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['email_verified_at' => 'datetime',];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function role()
    {
        return $this->belongsToMany(Role::class, 'User_roles', 'user_id', 'roles_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'UserNotifications', 'user_id', 'notification_id');
    }

    public function schedule()
    {
        return $this->hasMany(Schedule::class, 'user_id');
    }
}
