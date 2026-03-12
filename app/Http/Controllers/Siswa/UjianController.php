<?php
namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RuangUjian;
use App\Models\HasilUjian;
use App\Models\BankSoal;
use Carbon\Carbon;

class UjianController extends Controller
{
    public function index()
    {
        $siswa = Auth::guard('siswa')->user();
        $now = Carbon::now();
        $ruangs = RuangUjian::with(['bank.mapel'])
            ->whereJsonContains('classes', $siswa->kelas_id)
            ->where('start_at', '<=', $now)
            ->where('end_at', '>=', $now)
            ->get();

        foreach ($ruangs as $ruang) {
            $hasil = HasilUjian::where('ruang_id', $ruang->id)->where('siswa_id', $siswa->id)->first();
            $ruang->hasil = $hasil;
            $ruang->status_ujian = $hasil ? $hasil->status : 'belum';
        }

        return view('siswa.ujian.index', compact('ruangs'));
    }

    public function verifyToken(Request $request, $id)
    {
        $request->validate(['token' => 'required']);
        $ruang = RuangUjian::findOrFail($id);
        if (strtoupper($request->token) !== strtoupper($ruang->token)) {
            return back()->with('error', 'Token tidak valid.');
        }
        return redirect()->route('siswa.ujian.start', $id);
    }

    public function start($id)
    {
        $siswa = Auth::guard('siswa')->user();
        $ruang = RuangUjian::with('bank')->findOrFail($id);
        $now = Carbon::now();
        
        if ($now->lt($ruang->start_at) || $now->gt($ruang->end_at)) {
            return redirect()->route('siswa.ujian')->with('error', 'Ujian tidak dalam waktu yang ditentukan.');
        }

        // Check if already submitted
        $hasil = HasilUjian::where('ruang_id', $id)->where('siswa_id', $siswa->id)->first();
        if ($hasil && $hasil->status === 'selesai') {
            return redirect()->route('siswa.ujian')->with('info', 'Anda sudah menyelesaikan ujian ini.');
        }

        $bank = $ruang->bank;
        $soals = $bank->soals ?? [];
        
        // Randomize soals if needed
        if ($ruang->random_soal) {
            $indices = range(0, count($soals) - 1);
            shuffle($indices);
            $shuffledSoals = [];
            foreach ($indices as $idx) {
                $shuffledSoals[] = array_merge($soals[$idx], ['original_index' => $idx]);
            }
            $soals = $shuffledSoals;
        } else {
            foreach ($soals as $i => &$s) {
                $s['original_index'] = $i;
            }
            unset($s);
        }

        // Randomize options if needed
        if ($ruang->random_ops) {
            foreach ($soals as &$s) {
                if ($s['tipe'] === 'pg' && isset($s['opsi'])) {
                    $opsiKeys = array_keys($s['opsi']);
                    $oldKunci = $s['kunci'];
                    $oldVal = $s['opsi'][$oldKunci] ?? '';
                    $vals = array_values($s['opsi']);
                    shuffle($vals);
                    $newOpsi = [];
                    foreach ($opsiKeys as $k => $key) {
                        $newOpsi[$key] = $vals[$k];
                    }
                    $newKunci = array_search($oldVal, $newOpsi);
                    $s['opsi'] = $newOpsi;
                    $s['kunci'] = $newKunci ?: $oldKunci;
                }
            }
            unset($s);
        }

        // Create or get hasil ujian
        if (!$hasil) {
            $hasil = HasilUjian::create([
                'ruang_id' => $id,
                'siswa_id' => $siswa->id,
                'status' => 'sedang',
                'sisa_waktu' => $bank->timer * 60,
                'answers' => [],
            ]);
        }

        // Store soal order in session
        session(['ujian_'.$id.'_soals' => $soals]);

        return view('siswa.ujian.start', compact('ruang', 'bank', 'soals', 'hasil'));
    }

    public function saveAnswer(Request $request, $id)
    {
        $siswa = Auth::guard('siswa')->user();
        $hasil = HasilUjian::where('ruang_id', $id)->where('siswa_id', $siswa->id)->firstOrFail();
        
        if ($hasil->status === 'selesai') {
            return response()->json(['error' => 'Ujian sudah selesai']);
        }

        $answers = $hasil->answers ?? [];
        $answers[$request->soal_index] = $request->jawaban;
        $hasil->update(['answers' => $answers, 'sisa_waktu' => $request->sisa_waktu ?? $hasil->sisa_waktu]);

        return response()->json(['success' => true]);
    }

    public function submit(Request $request, $id)
    {
        $siswa = Auth::guard('siswa')->user();
        $ruang = RuangUjian::with('bank')->findOrFail($id);
        $hasil = HasilUjian::where('ruang_id', $id)->where('siswa_id', $siswa->id)->firstOrFail();
        
        if ($hasil->status === 'selesai') {
            return response()->json(['error' => 'Sudah selesai', 'nilai' => $hasil->nilai, 'benar' => $hasil->benar, 'salah' => $hasil->salah]);
        }

        $soals = session('ujian_'.$id.'_soals') ?? $ruang->bank->soals ?? [];
        $answers = $hasil->answers ?? [];
        
        $benar = 0;
        $salah = 0;
        
        foreach ($soals as $i => $soal) {
            $jawaban = $answers[$i] ?? null;
            if ($jawaban === null) { $salah++; continue; }
            if (isset($soal['kunci']) && $jawaban === $soal['kunci']) $benar++;
            else $salah++;
        }
        
        $total = count($soals);
        $nilai = $total > 0 ? round(($benar / $total) * 100, 2) : 0;
        
        $hasil->update([
            'status' => 'selesai',
            'benar' => $benar,
            'salah' => $salah,
            'nilai' => $nilai,
            'sisa_waktu' => $request->sisa_waktu ?? 0,
        ]);

        return response()->json(['success' => true, 'nilai' => $hasil->nilai, 'benar' => $benar, 'salah' => $salah, 'total' => $total]);
    }

    public function timeSync($id)
    {
        $siswa = Auth::guard('siswa')->user();
        $hasil = HasilUjian::where('ruang_id', $id)->where('siswa_id', $siswa->id)->first();
        return response()->json(['sisa_waktu' => $hasil ? $hasil->sisa_waktu : 0]);
    }
}
