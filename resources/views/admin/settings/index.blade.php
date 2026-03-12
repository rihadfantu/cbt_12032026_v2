@extends('layouts.app')
@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan')
@section('user-name', auth('admin')->user()->name)
@section('user-role', 'Administrator')
@section('role-badge-class', 'bg-gray-800 text-white')
@section('sidebar') @include('layouts.admin-sidebar') @endsection

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-5 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">Pengaturan Sistem</h2>
            <p class="text-sm text-gray-500 mt-0.5">Kelola konfigurasi aplikasi CBT</p>
        </div>

        <form method="POST" action="{{ route('admin.settings.update') }}" class="p-5 space-y-6">
            @csrf

            <!-- Exam Browser Only Toggle -->
            <div class="flex items-start justify-between gap-4 py-4 border-b border-gray-100">
                <div class="flex-1">
                    <h3 class="font-medium text-gray-800 text-sm">Mode Ujian Browser Only (Kiosk)</h3>
                    <p class="text-xs text-gray-500 mt-1">
                        Jika diaktifkan, siswa hanya bisa mengakses halaman ujian melalui browser kiosk yang telah ditentukan.
                        Akses dari browser biasa akan diblokir selama sesi ujian berlangsung.
                    </p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer flex-shrink-0 mt-0.5">
                    <input type="checkbox" name="exam_browser_only" value="1" class="sr-only peer"
                           {{ $examBrowserOnly == '1' ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer
                                peer-checked:after:translate-x-full peer-checked:after:border-white after:content-['']
                                after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300
                                after:border after:rounded-full after:h-5 after:w-5 after:transition-all
                                peer-checked:bg-blue-600"></div>
                </label>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
