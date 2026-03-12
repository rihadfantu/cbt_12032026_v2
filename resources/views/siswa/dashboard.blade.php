@extends('layouts.app')

@section('title', 'Beranda - Siswa')
@section('page-title', 'Beranda')
@section('user-name', auth('siswa')->user()->name)
@section('user-role', 'Siswa')
@section('role-badge-class', 'bg-blue-100 text-blue-800')

@section('sidebar')
    @include('layouts.siswa-sidebar')
@endsection

@section('content')
@php $s = auth('siswa')->user(); @endphp
<div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl p-6 text-white mb-6">
    <h2 class="text-xl font-bold">Selamat Datang, {{ $s->name }}!</h2>
    <p class="text-blue-200 text-sm mt-1">NISN: {{ $s->nisn }} &bull; Kelas: {{ $s->kelas->name ?? '-' }}</p>
    <a href="{{ route('siswa.ujian') }}" class="inline-flex items-center gap-2 mt-4 bg-white text-blue-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-50 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        Lihat Daftar Ujian
    </a>
</div>

@if($pengumuman->count() > 0)
<h3 class="font-semibold text-gray-800 mb-3">Pengumuman</h3>
<div class="space-y-3">
    @foreach($pengumuman as $p)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h4 class="font-semibold text-gray-800 mb-2">{{ $p->title }}</h4>
        <div class="text-gray-600 text-sm prose prose-sm max-w-none">{!! $p->content !!}</div>
        <p class="text-xs text-gray-400 mt-3">{{ $p->created_at->diffForHumans() }}</p>
    </div>
    @endforeach
</div>
@else
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center">
    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
    </div>
    <p class="text-gray-500 text-sm">Belum ada pengumuman</p>
</div>
@endif
@endsection
