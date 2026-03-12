<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable {
    protected $fillable = ['email', 'name', 'password'];
    protected $hidden = ['password'];
    protected $casts = ['password' => 'hashed'];
}
