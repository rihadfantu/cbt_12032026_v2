<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model {
    protected $fillable = ['title', 'content', 'target_kelas'];
    protected $casts = ['target_kelas' => 'array'];
}
