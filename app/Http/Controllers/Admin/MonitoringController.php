<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RuangUjian;
use App\Models\HasilUjian;
use App\Models\Kelas;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class MonitoringController extends Controller
{
    public function index(Request $request, $id)
    {
        $ruang = RuangUjian::with('bank')->findOrFail($id);
        $query = HasilUjian::with('siswa.kelas')->where('ruang_id', $id);
        
        if ($request->kelas_id) $query->whereHas('siswa', fn($q) => $q->where('kelas_id', $request->kelas_id));
        if ($request->search) $query->whereHas('siswa', fn($q) => $q->where('name', 'like', '%'.$request->search.'%')->orWhere('nisn', 'like', '%'.$request->search.'%'));
        
        $results = $query->get();
        $kelas = Kelas::whereIn('id', $ruang->classes ?? [])->get();
        
        return view('admin.monitoring.index', compact('ruang', 'results', 'kelas'));
    }

    public function resetSiswa(Request $request, $id)
    {
        if ($request->ids) {
            HasilUjian::whereIn('id', $request->ids)->where('ruang_id', $id)->delete();
        } elseif ($request->siswa_id) {
            HasilUjian::where('ruang_id', $id)->where('siswa_id', $request->siswa_id)->delete();
        }
        return back()->with('success', 'Ujian siswa berhasil direset.');
    }

    public function exportExcel($id)
    {
        $ruang = RuangUjian::with('bank')->findOrFail($id);
        $results = HasilUjian::with('siswa.kelas')->where('ruang_id', $id)->get();
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekap Nilai');
        
        $headers = ['No', 'NISN', 'Nama Siswa', 'Kelas', 'Status', 'Benar', 'Salah', 'Nilai'];
        $sheet->fromArray($headers, null, 'A1');
        
        $headerStyle = ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']]];
        $sheet->getStyle('A1:H1')->applyFromArray($headerStyle);
        
        $row = 2;
        foreach ($results as $i => $h) {
            $sheet->fromArray([$i+1, $h->siswa->nisn, $h->siswa->name, $h->siswa->kelas->name ?? '', ucfirst($h->status), $h->benar, $h->salah, $h->nilai], null, 'A'.$row);
            $row++;
        }
        
        foreach (range('A', 'H') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);
        
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="rekap_nilai_'.Str::slug($ruang->name).'.xlsx"');
        $writer->save('php://output');
        exit;
    }

    public function exportAnalisis($id)
    {
        $ruang = RuangUjian::with('bank')->findOrFail($id);
        $results = HasilUjian::with('siswa')->where('ruang_id', $id)->get();
        $soals = $ruang->bank->soals ?? [];
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Analisis Soal');
        
        $headers = ['No', 'NISN', 'Nama'];
        foreach ($soals as $i => $s) $headers[] = 'Soal ' . ($i+1);
        $sheet->fromArray($headers, null, 'A1');
        $sheet->getStyle('A1:'.chr(65+count($headers)-1).'1')->applyFromArray(['font' => ['bold' => true]]);
        
        $row = 2;
        foreach ($results as $idx => $h) {
            $answers = $h->answers ?? [];
            $rowData = [$idx+1, $h->siswa->nisn, $h->siswa->name];
            foreach ($soals as $i => $s) {
                $ans = $answers[$i] ?? null;
                $rowData[] = $ans ?? '-';
            }
            $sheet->fromArray($rowData, null, 'A'.$row);
            
            foreach ($soals as $i => $s) {
                $ans = $answers[$i] ?? null;
                $col = chr(68 + $i); // D onwards
                if ($ans !== null) {
                    $correct = isset($s['kunci']) && $ans === $s['kunci'];
                    $color = $correct ? '00B050' : 'FF0000';
                    $sheet->getStyle($col.$row)->applyFromArray(['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $color]]]);
                }
            }
            $row++;
        }
        
        foreach (range('A', chr(67+count($soals))) as $col) $sheet->getColumnDimension($col)->setAutoSize(true);
        
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="analisis_soal_'.Str::slug($ruang->name).'.xlsx"');
        $writer->save('php://output');
        exit;
    }
}
