<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\BankSoal;
use App\Models\RuangUjian;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'siswa' => Siswa::count(),
            'guru' => Guru::count(),
            'kelas' => Kelas::count(),
            'mapel' => Mapel::count(),
            'bank_soal' => BankSoal::where('is_archived', false)->count(),
            'ruang_ujian' => RuangUjian::count(),
        ];
        return view('admin.dashboard', compact('stats'));
    }
}
