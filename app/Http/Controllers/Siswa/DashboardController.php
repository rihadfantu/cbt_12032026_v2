<?php
namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengumuman;

class DashboardController extends Controller
{
    public function index()
    {
        $siswa = Auth::guard('siswa')->user();
        $pengumuman = Pengumuman::where(function($q) use ($siswa) {
            $q->whereJsonContains('target_kelas', $siswa->kelas_id)
              ->orWhereNull('target_kelas')
              ->orWhere('target_kelas', '[]');
        })->orderByDesc('created_at')->get();
        return view('siswa.dashboard', compact('siswa', 'pengumuman'));
    }
}
