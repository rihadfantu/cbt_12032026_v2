<?php
namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BankSoal;
use App\Models\Mapel;

class BankSoalController extends Controller
{
    public function index()
    {
        $guru = Auth::guard('guru')->user();
        $relasi = $guru->relasi;
        $mapelIds = $relasi ? ($relasi->mapel_ids ?? []) : [];
        $mapels = Mapel::whereIn('id', $mapelIds)->get();
        $bankSoals = BankSoal::with(['mapel'])->where('guru_id', $guru->id)->where('is_archived', false)->orderByDesc('created_at')->paginate(10);
        return view('guru.bank-soal.index', compact('bankSoals', 'mapels'));
    }

    public function store(Request $request)
    {
        $guru = Auth::guard('guru')->user();
        $request->validate(['mapel_id' => 'required|exists:mapels,id', 'title' => 'required|string|max:200', 'timer' => 'required|integer|min:1']);
        BankSoal::create(['guru_id' => $guru->id, 'mapel_id' => $request->mapel_id, 'title' => $request->title, 'timer' => $request->timer, 'bobot_pg' => $request->bobot_pg ?? 100, 'bobot_essai' => $request->bobot_essai ?? 0, 'bobot_bs' => $request->bobot_bs ?? 0, 'bobot_jodoh' => $request->bobot_jodoh ?? 0, 'soals' => [], 'is_archived' => false]);
        return back()->with('success', 'Bank soal berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $guru = Auth::guard('guru')->user();
        $bank = BankSoal::where('id', $id)->where('guru_id', $guru->id)->firstOrFail();
        $request->validate(['mapel_id' => 'required|exists:mapels,id', 'title' => 'required|string|max:200', 'timer' => 'required|integer|min:1']);
        $bank->update(['mapel_id' => $request->mapel_id, 'title' => $request->title, 'timer' => $request->timer, 'bobot_pg' => $request->bobot_pg ?? 100, 'bobot_essai' => $request->bobot_essai ?? 0, 'bobot_bs' => $request->bobot_bs ?? 0, 'bobot_jodoh' => $request->bobot_jodoh ?? 0]);
        return back()->with('success', 'Bank soal berhasil diupdate.');
    }

    public function destroy($id)
    {
        $guru = Auth::guard('guru')->user();
        BankSoal::where('id', $id)->where('guru_id', $guru->id)->firstOrFail()->delete();
        return back()->with('success', 'Bank soal berhasil dihapus.');
    }

    public function arsip()
    {
        $guru = Auth::guard('guru')->user();
        $bankSoals = BankSoal::with(['mapel'])->where('guru_id', $guru->id)->where('is_archived', true)->orderByDesc('created_at')->paginate(10);
        return view('guru.bank-soal.arsip', compact('bankSoals'));
    }

    public function arsipkan(Request $request)
    {
        $guru = Auth::guard('guru')->user();
        BankSoal::where('id', $request->id)->where('guru_id', $guru->id)->update(['is_archived' => true]);
        return back()->with('success', 'Bank soal diarsipkan.');
    }

    public function aktifkan(Request $request)
    {
        $guru = Auth::guard('guru')->user();
        BankSoal::whereIn('id', $request->ids ?? [])->where('guru_id', $guru->id)->update(['is_archived' => false]);
        return back()->with('success', 'Bank soal diaktifkan.');
    }

    public function hapusPermanenBulk(Request $request)
    {
        $guru = Auth::guard('guru')->user();
        BankSoal::whereIn('id', $request->ids ?? [])->where('guru_id', $guru->id)->delete();
        return back()->with('success', 'Bank soal dihapus permanen.');
    }
}
