<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengumuman;
use App\Models\Kelas;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumuman = Pengumuman::orderByDesc('created_at')->paginate(10);
        $kelas = Kelas::orderBy('name')->get();
        return view('admin.pengumuman.index', compact('pengumuman', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate(['title' => 'required|string|max:200', 'content' => 'required']);
        Pengumuman::create(['title' => $request->title, 'content' => $request->content, 'target_kelas' => $request->target_kelas ?? []]);
        return back()->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    public function update(Request $request, Pengumuman $pengumuman)
    {
        $request->validate(['title' => 'required|string|max:200', 'content' => 'required']);
        $pengumuman->update(['title' => $request->title, 'content' => $request->content, 'target_kelas' => $request->target_kelas ?? []]);
        return back()->with('success', 'Pengumuman berhasil diupdate.');
    }

    public function destroy(Pengumuman $pengumuman)
    {
        $pengumuman->delete();
        return back()->with('success', 'Pengumuman berhasil dihapus.');
    }
}
