<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = ['first_name', 'last_name', 'username', 'email', 'password', 'image', 'city_id', 'status'];
}
