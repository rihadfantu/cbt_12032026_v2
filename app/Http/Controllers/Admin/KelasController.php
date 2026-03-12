<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::withCount('siswas')->orderBy('name')->paginate(10);
        return view('admin.kelas.index', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100']);
        
        if ($request->bulk) {
            $names = array_filter(array_map('trim', explode("\n", $request->name)));
            foreach ($names as $name) {
                if ($name) Kelas::firstOrCreate(['name' => $name]);
            }
        } else {
            Kelas::create(['name' => trim($request->name)]);
        }
        
        return back()->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function update(Request $request, Kelas $kela)
    {
        $request->validate(['name' => 'required|string|max:100']);
        $kela->update(['name' => $request->name]);
        return back()->with('success', 'Kelas berhasil diupdate.');
    }

    public function destroy(Kelas $kela)
    {
        $kela->delete();
        return back()->with('success', 'Kelas berhasil dihapus.');
    }

    public function destroyBulk(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        Kelas::whereIn('id', $request->ids)->delete();
        return back()->with('success', 'Kelas berhasil dihapus.');
    }
}
