<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showForm()
    {
        if (Auth::guard('admin')->check()) return redirect()->route('admin.dashboard');
        if (Auth::guard('guru')->check()) return redirect()->route('guru.dashboard');
        if (Auth::guard('siswa')->check()) return redirect()->route('siswa.dashboard');
        return view('auth.login');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        Auth::guard('guru')->logout();
        Auth::guard('siswa')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
