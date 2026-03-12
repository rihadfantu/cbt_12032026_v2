<?php
namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function update(Request $request)
    {
        $request->validate(['current_password' => 'required', 'password' => 'required|min:3|confirmed']);
        $user = Auth::guard('siswa')->user();
        if (!Hash::check($request->current_password, $user->password)) return back()->with('error', 'Password lama tidak sesuai.');
        $user->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Password berhasil diubah.');
    }
}
