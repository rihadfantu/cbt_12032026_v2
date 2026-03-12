<?php
namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RuangUjian;
use App\Models\BankSoal;
use App\Models\Kelas;
use Illuminate\Support\Str;

class RuangUjianController extends Controller
{
    public function index()
    {
        $guru = Auth::guard('guru')->user();
        $ruangs = RuangUjian::with(['bank.mapel'])->whereHas('bank', fn($q) => $q->where('guru_id', $guru->id))->orderByDesc('created_at')->paginate(10);
        $banks = BankSoal::with(['mapel'])->where('guru_id', $guru->id)->where('is_archived', false)->get();
        $relasi = $guru->relasi;
        $kelasIds = $relasi ? ($relasi->kelas_ids ?? []) : [];
        $kelas = Kelas::whereIn('id', $kelasIds)->get();
        return view('guru.ruang-ujian.index', compact('ruangs', 'banks', 'kelas'));
    }

    public function store(Request $request)
    {
        $guru = Auth::guard('guru')->user();
        $request->validate(['name' => 'required', 'bank_id' => 'required|exists:bank_soals,id', 'start_at' => 'required|date', 'end_at' => 'required|date|after:start_at']);
        RuangUjian::create(['name' => $request->name, 'token' => $request->token ?? strtoupper(Str::random(6)), 'bank_id' => $request->bank_id, 'login_limit' => $request->login_limit ?? 3, 'min_time_submit' => $request->min_time_submit ?? 0, 'classes' => $request->classes ?? [], 'start_at' => $request->start_at, 'end_at' => $request->end_at, 'random_soal' => $request->boolean('random_soal'), 'random_ops' => $request->boolean('random_ops')]);
        return back()->with('success', 'Ruang ujian berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $guru = Auth::guard('guru')->user();
        $ruang = RuangUjian::whereHas('bank', fn($q) => $q->where('guru_id', $guru->id))->findOrFail($id);
        $request->validate(['name' => 'required', 'bank_id' => 'required|exists:bank_soals,id', 'start_at' => 'required|date', 'end_at' => 'required|date|after:start_at']);
        $ruang->update(['name' => $request->name, 'token' => $request->token ?? $ruang->token, 'bank_id' => $request->bank_id, 'login_limit' => $request->login_limit ?? 3, 'min_time_submit' => $request->min_time_submit ?? 0, 'classes' => $request->classes ?? [], 'start_at' => $request->start_at, 'end_at' => $request->end_at, 'random_soal' => $request->boolean('random_soal'), 'random_ops' => $request->boolean('random_ops')]);
        return back()->with('success', 'Ruang ujian berhasil diupdate.');
    }

    public function destroy($id)
    {
        $guru = Auth::guard('guru')->user();
        RuangUjian::whereHas('bank', fn($q) => $q->where('guru_id', $guru->id))->findOrFail($id)->delete();
        return back()->with('success', 'Ruang ujian berhasil dihapus.');
    }
}
