<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Siswa;
use App\Models\Kelas;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SiswaController extends Controller
{
    public function index()
    {
        $siswas = Siswa::with('kelas')->orderBy('name')->paginate(10);
        $kelas = Kelas::orderBy('name')->get();
        return view('admin.siswa.index', compact('siswas', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nisn' => 'required|unique:siswas,nisn',
            'name' => 'required|string|max:200',
            'kelas_id' => 'required|exists:kelas,id',
            'password' => 'required|min:3',
        ]);
        Siswa::create(['nisn' => $request->nisn, 'name' => $request->name, 'kelas_id' => $request->kelas_id, 'password' => Hash::make($request->password)]);
        return back()->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nisn' => 'required|unique:siswas,nisn,'.$siswa->id,
            'name' => 'required|string|max:200',
            'kelas_id' => 'required|exists:kelas,id',
        ]);
        $data = ['nisn' => $request->nisn, 'name' => $request->name, 'kelas_id' => $request->kelas_id];
        if ($request->filled('password')) $data['password'] = Hash::make($request->password);
        $siswa->update($data);
        return back()->with('success', 'Siswa berhasil diupdate.');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return back()->with('success', 'Siswa berhasil dihapus.');
    }

    public function destroyBulk(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        Siswa::whereIn('id', $request->ids)->delete();
        return back()->with('success', 'Siswa berhasil dihapus.');
    }

    public function resetPasswordBulk(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'password' => 'required|min:3']);
        Siswa::whereIn('id', $request->ids)->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Password berhasil direset.');
    }

    public function importExcel(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,xls']);
        $kelas = Kelas::pluck('id', 'name')->toArray();
        $spreadsheet = IOFactory::load($request->file('file')->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();
        array_shift($rows);
        foreach ($rows as $row) {
            if (empty($row[0])) continue;
            $kelasId = $kelas[$row[2] ?? ''] ?? null;
            if (!$kelasId) continue;
            Siswa::updateOrCreate(['nisn' => $row[0]], [
                'name' => $row[1] ?? '',
                'kelas_id' => $kelasId,
                'password' => Hash::make($row[3] ?? '123'),
            ]);
        }
        return back()->with('success', 'Import berhasil.');
    }

    public function downloadTemplate()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([['NISN', 'Nama', 'Kelas', 'Password']], null, 'A1');
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="template_siswa.xlsx"');
        $writer->save('php://output');
        exit;
    }
}
