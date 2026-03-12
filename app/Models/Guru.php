<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Guru extends Authenticatable {
    protected $fillable = ['nik', 'name', 'password'];
    protected $hidden = ['password'];
    protected $casts = ['password' => 'hashed'];

    public function relasi() { return $this->hasOne(Relasi::class); }
    public function bankSoals() { return $this->hasMany(BankSoal::class); }
}
