<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $appends = ['image_url'];
    protected $fillable = ['first_name', 'last_name', 'username', 'email', 'password', 'phone', 'image', 'city_id', 'status', 'description'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['email_verified_at' => 'datetime',];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function role()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'user_notifications', 'user_id', 'notification_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'user_id');
    }

    public function getImageUrlAttribute() {
        return Storage::url($this->image);
    }
}
