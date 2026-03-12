@extends('layouts.app')
@section('title', 'Daftar Ujian')
@section('page-title', 'Daftar Ujian')
@section('user-name', auth('siswa')->user()->name)
@section('user-role', 'Siswa')
@section('role-badge-class', 'bg-blue-100 text-blue-800')
@section('sidebar') @include('layouts.siswa-sidebar') @endsection

@section('content')
@php $siswa = auth('siswa')->user(); @endphp

<div class="mb-4 bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl p-5 text-white">
    <h2 class="text-lg font-bold">Daftar Ujian Tersedia</h2>
    <p class="text-blue-200 text-sm mt-0.5">{{ $siswa->name }} &bull; {{ $siswa->kelas->name ?? '-' }}</p>
</div>

@if($ruangs->count() > 0)
<div class="space-y-3">
    @foreach($ruangs as $ruang)
    @php
        $hasil = $ruang->hasil ?? null;
        $statusUjian = $ruang->status_ujian ?? 'belum';
    @endphp
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex flex-col md:flex-row md:items-center gap-4 justify-between">
            <div class="flex-1">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 text-base">{{ $ruang->name }}</h3>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $ruang->bank->mapel->name ?? '-' }} &bull; Timer: {{ $ruang->bank->timer ?? 0 }} menit</p>
                        <div class="flex flex-wrap gap-3 mt-2 text-xs text-gray-500">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $ruang->start_at->format('d/m/Y H:i') }} - {{ $ruang->end_at->format('d/m/Y H:i') }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                Token: <span class="font-mono font-semibold text-gray-700">{{ $ruang->token }}</span>
                            </span>
                            <span class="flex items-center gap-1">{{ count($ruang->bank->soals ?? []) }} soal</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0">
                @if($statusUjian === 'selesai')
                    <div class="text-right">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Selesai
                        </span>
                        @if($hasil && $hasil->nilai !== null)
                        <p class="text-sm mt-1 text-gray-600">Nilai: <span class="font-bold text-blue-700">{{ $hasil->nilai }}</span></p>
                        @endif
                    </div>
                @elseif($statusUjian === 'sedang')
                    <a href="{{ route('siswa.ujian.start', $ruang->id) }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Lanjutkan
                    </a>
                @else
                    <a href="{{ route('siswa.ujian.start', $ruang->id) }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Mulai Ujian
                    </a>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
    <div class="flex flex-col items-center gap-3 text-gray-400">
        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </div>
        <div>
            <p class="font-medium text-gray-500">Tidak ada ujian tersedia</p>
            <p class="text-sm mt-1">Belum ada ujian yang terbuka untuk kelas Anda saat ini</p>
        </div>
    </div>
</div>
@endif
@endsection
