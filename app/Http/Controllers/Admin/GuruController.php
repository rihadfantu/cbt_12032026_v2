<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Guru;
use PhpOffice\PhpSpreadsheet\IOFactory;

class GuruController extends Controller
{
    public function index()
    {
        $gurus = Guru::orderBy('name')->paginate(10);
        return view('admin.guru.index', compact('gurus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|unique:gurus,nik',
            'name' => 'required|string|max:200',
            'password' => 'required|min:3',
        ]);
        Guru::create(['nik' => $request->nik, 'name' => $request->name, 'password' => Hash::make($request->password)]);
        return back()->with('success', 'Guru berhasil ditambahkan.');
    }

    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'nik' => 'required|unique:gurus,nik,'.$guru->id,
            'name' => 'required|string|max:200',
        ]);
        $data = ['nik' => $request->nik, 'name' => $request->name];
        if ($request->filled('password')) $data['password'] = Hash::make($request->password);
        $guru->update($data);
        return back()->with('success', 'Guru berhasil diupdate.');
    }

    public function destroy(Guru $guru)
    {
        $guru->delete();
        return back()->with('success', 'Guru berhasil dihapus.');
    }

    public function destroyBulk(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        Guru::whereIn('id', $request->ids)->delete();
        return back()->with('success', 'Guru berhasil dihapus.');
    }

    public function resetPasswordBulk(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'password' => 'required|min:3']);
        Guru::whereIn('id', $request->ids)->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Password berhasil direset.');
    }

    public function importExcel(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,xls']);
        $spreadsheet = IOFactory::load($request->file('file')->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();
        array_shift($rows); // remove header
        foreach ($rows as $row) {
            if (empty($row[0])) continue;
            Guru::updateOrCreate(['nik' => $row[0]], [
                'name' => $row[1] ?? '',
                'password' => Hash::make($row[2] ?? '123'),
            ]);
        }
        return back()->with('success', 'Import berhasil.');
    }

    public function downloadTemplate()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([['NIK', 'Nama', 'Password']], null, 'A1');
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'template_guru.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        $writer->save('php://output');
        exit;
    }
}
