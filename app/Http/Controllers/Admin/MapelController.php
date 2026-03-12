<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mapel;

class MapelController extends Controller
{
    public function index()
    {
        $mapels = Mapel::orderBy('name')->paginate(10);
        return view('admin.mapel.index', compact('mapels'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100']);
        
        if ($request->bulk) {
            $names = array_filter(array_map('trim', explode("\n", $request->name)));
            foreach ($names as $name) {
                if ($name) Mapel::firstOrCreate(['name' => $name]);
            }
        } else {
            Mapel::create(['name' => trim($request->name)]);
        }
        
        return back()->with('success', 'Mapel berhasil ditambahkan.');
    }

    public function update(Request $request, Mapel $mapel)
    {
        $request->validate(['name' => 'required|string|max:100']);
        $mapel->update(['name' => $request->name]);
        return back()->with('success', 'Mapel berhasil diupdate.');
    }

    public function destroy(Mapel $mapel)
    {
        $mapel->delete();
        return back()->with('success', 'Mapel berhasil dihapus.');
    }

    public function destroyBulk(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        Mapel::whereIn('id', $request->ids)->delete();
        return back()->with('success', 'Mapel berhasil dihapus.');
    }
}
