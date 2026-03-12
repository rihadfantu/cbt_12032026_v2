<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function index()
    {
        $examBrowserOnly = Setting::get('exam_browser_only', '0');
        return view('admin.settings.index', compact('examBrowserOnly'));
    }

    public function update(Request $request)
    {
        Setting::set('exam_browser_only', $request->boolean('exam_browser_only') ? '1' : '0');
        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
