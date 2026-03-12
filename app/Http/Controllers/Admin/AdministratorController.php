<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdministratorController extends Controller
{
    public function index()
    {
        $admins = Admin::orderBy('name')->paginate(10);
        return view('admin.administrator.index', compact('admins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:admins,email',
            'name' => 'required|string|max:200',
            'password' => 'required|min:3',
        ]);
        Admin::create(['email' => $request->email, 'name' => $request->name, 'password' => Hash::make($request->password)]);
        return back()->with('success', 'Admin berhasil ditambahkan.');
    }

    public function update(Request $request, Admin $administrator)
    {
        $request->validate([
            'email' => 'required|email|unique:admins,email,'.$administrator->id,
            'name' => 'required|string|max:200',
        ]);
        $data = ['email' => $request->email, 'name' => $request->name];
        if ($request->filled('password')) $data['password'] = Hash::make($request->password);
        $administrator->update($data);
        return back()->with('success', 'Admin berhasil diupdate.');
    }

    public function destroy(Admin $administrator)
    {
        if (Admin::count() <= 1) return back()->with('error', 'Minimal harus ada 1 admin.');
        $administrator->delete();
        return back()->with('success', 'Admin berhasil dihapus.');
    }
}
