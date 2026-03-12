<?php
namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BankSoal;
use PhpOffice\PhpWord\IOFactory as WordFactory;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SoalController extends Controller
{
    public function edit($id)
    {
        $guru = Auth::guard('guru')->user();
        $bank = BankSoal::with(['mapel'])->where('id', $id)->where('guru_id', $guru->id)->firstOrFail();
        return view('guru.bank-soal.edit-soal', compact('bank'));
    }

    public function save(Request $request, $id)
    {
        $guru = Auth::guard('guru')->user();
        $bank = BankSoal::where('id', $id)->where('guru_id', $guru->id)->firstOrFail();
        $bank->update(['soals' => $request->soals ?? []]);
        return response()->json(['success' => true]);
    }

    public function importWord(Request $request, $id)
    {
        $request->validate(['file' => 'required|file|mimes:docx,doc']);
        $guru = Auth::guard('guru')->user();
        $bank = BankSoal::where('id', $id)->where('guru_id', $guru->id)->firstOrFail();
        
        try {
            $phpWord = WordFactory::load($request->file('file')->getPathname());
            $soals = [];
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if ($element instanceof \PhpOffice\PhpWord\Element\Table) {
                        $rows = $element->getRows();
                        if (count($rows) < 2) continue;
                        $soalText = '';
                        $opsi = ['A' => '', 'B' => '', 'C' => '', 'D' => '', 'E' => ''];
                        $kunci = 'A';
                        foreach ($rows as $rowIdx => $row) {
                            $cells = $row->getCells();
                            if ($rowIdx === 0 && count($cells) >= 2) {
                                $soalText = $this->getCellText($cells[1]);
                            } elseif ($rowIdx >= 1 && count($cells) >= 2) {
                                $label = trim($this->getCellText($cells[0]));
                                $text = $this->getCellText($cells[1]);
                                if (in_array($label, ['A','B','C','D','E'])) $opsi[$label] = $text;
                                elseif (strtolower($label) === 'kunci') $kunci = strtoupper(trim($text));
                            }
                        }
                        if ($soalText) $soals[] = ['tipe' => 'pg', 'soal' => $soalText, 'opsi' => $opsi, 'kunci' => $kunci];
                    }
                }
            }
            $existing = $bank->soals ?? [];
            $bank->update(['soals' => array_merge($existing, $soals)]);
            return back()->with('success', count($soals) . ' soal berhasil diimport dari Word.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    public function importExcel(Request $request, $id)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,xls']);
        $guru = Auth::guard('guru')->user();
        $bank = BankSoal::where('id', $id)->where('guru_id', $guru->id)->firstOrFail();
        
        try {
            $spreadsheet = IOFactory::load($request->file('file')->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
            array_shift($rows);
            $soals = [];
            foreach ($rows as $row) {
                if (empty($row[0])) continue;
                $soals[] = ['tipe' => 'pg', 'soal' => $row[0], 'opsi' => ['A' => $row[1] ?? '', 'B' => $row[2] ?? '', 'C' => $row[3] ?? '', 'D' => $row[4] ?? '', 'E' => $row[5] ?? ''], 'kunci' => strtoupper($row[6] ?? 'A')];
            }
            $existing = $bank->soals ?? [];
            $bank->update(['soals' => array_merge($existing, $soals)]);
            return back()->with('success', count($soals) . ' soal berhasil diimport dari Excel.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    private function getCellText($cell): string
    {
        $text = '';
        foreach ($cell->getElements() as $elem) {
            if ($elem instanceof \PhpOffice\PhpWord\Element\TextRun) {
                foreach ($elem->getElements() as $textElem) {
                    if (method_exists($textElem, 'getText')) $text .= $textElem->getText();
                }
            } elseif (method_exists($elem, 'getText')) {
                $text .= $elem->getText();
            }
        }
        return $text;
    }
}
