<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RuangUjian;
use App\Models\Siswa;

class SiswaLoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'nisn' => 'required',
            'password' => 'required',
        ]);

        $credentials = ['nisn' => $request->nisn, 'password' => $request->password];

        if (Auth::guard('siswa')->attempt($credentials)) {
            $request->session()->regenerate();

            // If token provided, redirect directly to exam
            if ($request->filled('token')) {
                $ruang = RuangUjian::where('token', strtoupper($request->token))->first();
                if ($ruang) {
                    return redirect()->route('siswa.ujian.start', $ruang->id);
                }
            }

            return redirect()->route('siswa.dashboard');
        }

        return back()->with('error', 'NISN atau password salah.');
    }
}
