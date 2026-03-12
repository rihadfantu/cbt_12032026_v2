<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RuangUjian extends Model {
    protected $fillable = ['name', 'token', 'bank_id', 'login_limit', 'min_time_submit', 'classes', 'start_at', 'end_at', 'random_soal', 'random_ops'];
    protected $casts = ['classes' => 'array', 'start_at' => 'datetime', 'end_at' => 'datetime', 'random_soal' => 'boolean', 'random_ops' => 'boolean'];
    public function bank() { return $this->belongsTo(BankSoal::class, 'bank_id'); }
    public function hasilUjians() { return $this->hasMany(HasilUjian::class, 'ruang_id'); }
}
