<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Relasi;

class RelasiController extends Controller
{
    public function index()
    {
        $gurus = Guru::with('relasi')->orderBy('name')->get();
        $kelas = Kelas::orderBy('name')->get();
        $mapels = Mapel::orderBy('name')->get();
        return view('admin.relasi.index', compact('gurus', 'kelas', 'mapels'));
    }

    public function update(Request $request, Guru $guru)
    {
        Relasi::updateOrCreate(
            ['guru_id' => $guru->id],
            [
                'kelas_ids' => $request->kelas_ids ?? [],
                'mapel_ids' => $request->mapel_ids ?? [],
            ]
        );
        return back()->with('success', 'Relasi berhasil disimpan.');
    }
}
