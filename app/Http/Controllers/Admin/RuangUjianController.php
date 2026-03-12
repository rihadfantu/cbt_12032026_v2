<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RuangUjian;
use App\Models\BankSoal;
use App\Models\Kelas;
use Illuminate\Support\Str;

class RuangUjianController extends Controller
{
    public function index()
    {
        $ruangs = RuangUjian::with(['bank.mapel', 'bank.guru'])->orderByDesc('created_at')->paginate(10);
        $banks = BankSoal::with(['guru', 'mapel'])->where('is_archived', false)->orderBy('title')->get();
        $kelas = Kelas::orderBy('name')->get();
        return view('admin.ruang-ujian.index', compact('ruangs', 'banks', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'bank_id' => 'required|exists:bank_soals,id',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
        ]);
        RuangUjian::create([
            'name' => $request->name,
            'token' => $request->token ?? strtoupper(Str::random(6)),
            'bank_id' => $request->bank_id,
            'login_limit' => $request->login_limit ?? 3,
            'min_time_submit' => $request->min_time_submit ?? 0,
            'classes' => $request->classes ?? [],
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,
            'random_soal' => $request->boolean('random_soal'),
            'random_ops' => $request->boolean('random_ops'),
        ]);
        return back()->with('success', 'Ruang ujian berhasil ditambahkan.');
    }

    public function update(Request $request, RuangUjian $ruangUjian)
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'bank_id' => 'required|exists:bank_soals,id',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
        ]);
        $ruangUjian->update([
            'name' => $request->name,
            'token' => $request->token ?? $ruangUjian->token,
            'bank_id' => $request->bank_id,
            'login_limit' => $request->login_limit ?? 3,
            'min_time_submit' => $request->min_time_submit ?? 0,
            'classes' => $request->classes ?? [],
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,
            'random_soal' => $request->boolean('random_soal'),
            'random_ops' => $request->boolean('random_ops'),
        ]);
        return back()->with('success', 'Ruang ujian berhasil diupdate.');
    }

    public function destroy(RuangUjian $ruangUjian)
    {
        $ruangUjian->delete();
        return back()->with('success', 'Ruang ujian berhasil dihapus.');
    }
}
