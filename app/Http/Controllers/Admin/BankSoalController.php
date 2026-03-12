<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BankSoal;
use App\Models\Guru;
use App\Models\Mapel;

class BankSoalController extends Controller
{
    public function index()
    {
        $bankSoals = BankSoal::with(['guru', 'mapel'])->where('is_archived', false)->orderByDesc('created_at')->paginate(10);
        $gurus = Guru::orderBy('name')->get();
        $mapels = Mapel::orderBy('name')->get();
        return view('admin.bank-soal.index', compact('bankSoals', 'gurus', 'mapels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'guru_id' => 'required|exists:gurus,id',
            'mapel_id' => 'required|exists:mapels,id',
            'title' => 'required|string|max:200',
            'timer' => 'required|integer|min:1',
        ]);
        BankSoal::create([
            'guru_id' => $request->guru_id,
            'mapel_id' => $request->mapel_id,
            'title' => $request->title,
            'timer' => $request->timer,
            'bobot_pg' => $request->bobot_pg ?? 100,
            'bobot_essai' => $request->bobot_essai ?? 0,
            'bobot_bs' => $request->bobot_bs ?? 0,
            'bobot_jodoh' => $request->bobot_jodoh ?? 0,
            'soals' => [],
            'is_archived' => false,
        ]);
        return back()->with('success', 'Bank soal berhasil ditambahkan.');
    }

    public function update(Request $request, BankSoal $bankSoal)
    {
        $request->validate([
            'guru_id' => 'required|exists:gurus,id',
            'mapel_id' => 'required|exists:mapels,id',
            'title' => 'required|string|max:200',
            'timer' => 'required|integer|min:1',
        ]);
        $bankSoal->update([
            'guru_id' => $request->guru_id,
            'mapel_id' => $request->mapel_id,
            'title' => $request->title,
            'timer' => $request->timer,
            'bobot_pg' => $request->bobot_pg ?? 100,
            'bobot_essai' => $request->bobot_essai ?? 0,
            'bobot_bs' => $request->bobot_bs ?? 0,
            'bobot_jodoh' => $request->bobot_jodoh ?? 0,
        ]);
        return back()->with('success', 'Bank soal berhasil diupdate.');
    }

    public function destroy(BankSoal $bankSoal)
    {
        $bankSoal->delete();
        return back()->with('success', 'Bank soal berhasil dihapus.');
    }

    public function arsip()
    {
        $bankSoals = BankSoal::with(['guru', 'mapel'])->where('is_archived', true)->orderByDesc('created_at')->paginate(10);
        return view('admin.bank-soal.arsip', compact('bankSoals'));
    }

    public function arsipkan(Request $request)
    {
        $request->validate(['id' => 'required|exists:bank_soals,id']);
        BankSoal::find($request->id)->update(['is_archived' => true]);
        return back()->with('success', 'Bank soal diarsipkan.');
    }

    public function aktifkan(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        BankSoal::whereIn('id', $request->ids)->update(['is_archived' => false]);
        return back()->with('success', 'Bank soal diaktifkan.');
    }

    public function hapusPermanenBulk(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        BankSoal::whereIn('id', $request->ids)->delete();
        return back()->with('success', 'Bank soal dihapus permanen.');
    }
}
