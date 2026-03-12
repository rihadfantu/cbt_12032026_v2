<?php
namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\BankSoal;
use App\Models\RuangUjian;
use App\Models\Kelas;
use App\Models\Mapel;

class DashboardController extends Controller
{
    public function index()
    {
        $guru = Auth::guard('guru')->user();
        $relasi = $guru->relasi;
        $kelasIds = $relasi ? ($relasi->kelas_ids ?? []) : [];
        $mapelIds = $relasi ? ($relasi->mapel_ids ?? []) : [];
        
        $stats = [
            'bank_soal' => BankSoal::where('guru_id', $guru->id)->where('is_archived', false)->count(),
            'ruang_ujian' => RuangUjian::whereHas('bank', fn($q) => $q->where('guru_id', $guru->id))->count(),
            'kelas' => count($kelasIds),
            'mapel' => count($mapelIds),
        ];
        return view('guru.dashboard', compact('stats', 'guru'));
    }
}
