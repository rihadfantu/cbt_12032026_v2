<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class HasilUjian extends Model {
    protected $fillable = ['ruang_id', 'siswa_id', 'status', 'sisa_waktu', 'benar', 'salah', 'nilai', 'answers'];
    protected $casts = ['answers' => 'array'];
    public function ruang() { return $this->belongsTo(RuangUjian::class, 'ruang_id'); }
    public function siswa() { return $this->belongsTo(Siswa::class); }
}
