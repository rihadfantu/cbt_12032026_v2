<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruLoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'password' => 'required',
        ]);

        if (Auth::guard('guru')->attempt(['nik' => $request->nik, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->route('guru.dashboard');
        }

        return back()->with('error', 'NIK atau password salah.');
    }
}
