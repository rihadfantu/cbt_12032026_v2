<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Siswa extends Authenticatable {
    protected $fillable = ['nisn', 'name', 'kelas_id', 'password'];
    protected $hidden = ['password'];
    protected $casts = ['password' => 'hashed'];

    public function kelas() { return $this->belongsTo(Kelas::class); }
    public function hasilUjians() { return $this->hasMany(HasilUjian::class); }
}
