<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BankSoal extends Model {
    protected $fillable = ['guru_id', 'mapel_id', 'title', 'timer', 'bobot_pg', 'bobot_essai', 'bobot_bs', 'bobot_jodoh', 'soals', 'is_archived'];
    protected $casts = ['soals' => 'array', 'is_archived' => 'boolean'];
    public function guru() { return $this->belongsTo(Guru::class); }
    public function mapel() { return $this->belongsTo(Mapel::class); }
    public function ruangUjians() { return $this->hasMany(RuangUjian::class, 'bank_id'); }
}
