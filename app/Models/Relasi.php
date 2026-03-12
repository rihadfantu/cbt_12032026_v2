<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Relasi extends Model {
    protected $fillable = ['guru_id', 'kelas_ids', 'mapel_ids'];
    protected $casts = ['kelas_ids' => 'array', 'mapel_ids' => 'array'];
    public function guru() { return $this->belongsTo(Guru::class); }
}
