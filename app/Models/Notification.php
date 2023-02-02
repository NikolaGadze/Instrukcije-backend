<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['user_from', 'user_to', 'message', 'datetime', 'opened'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'notification_id');
    }
}
