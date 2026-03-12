@extends('layouts.app')
@section('title', 'Ubah Password')
@section('page-title', 'Ubah Password')
@section('user-name', auth('guru')->user()->name)
@section('user-role', 'Guru')
@section('role-badge-class', 'bg-green-100 text-green-800')
@section('sidebar') @include('layouts.guru-sidebar') @endsection

@section('content')
<div class="max-w-lg">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-5 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">Ubah Password</h2>
            <p class="text-sm text-gray-500 mt-0.5">Ganti password akun Guru Anda</p>
        </div>
        <form method="POST" action="{{ route('guru.password.update') }}" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password Lama <span class="text-red-500">*</span></label>
                <input type="password" name="current_password" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru <span class="text-red-500">*</span></label>
                <input type="password" name="password" required minlength="3"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation" required minlength="3"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="flex justify-end pt-2">
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
