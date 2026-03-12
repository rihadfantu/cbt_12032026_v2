@extends('layouts.app')
@section('title', 'Monitoring - ' . $ruang->name)
@section('page-title', 'Monitoring: ' . $ruang->name)
@section('user-name', auth('guru')->user()->name)
@section('user-role', 'Guru')
@section('role-badge-class', 'bg-green-100 text-green-800')
@section('sidebar') @include('layouts.guru-sidebar') @endsection

@section('content')
<div class="mb-4 flex flex-wrap gap-2 items-center justify-between">
    <a href="{{ route('guru.ruang-ujian.index') }}" class="flex items-center gap-1 text-sm text-gray-600 hover:text-gray-800">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali
    </a>
    <div class="flex gap-2">
        <a href="{{ route('guru.monitoring.export-excel', $ruang->id) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Export Excel
        </a>
        <a href="{{ route('guru.monitoring.export-analisis', $ruang->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Analisis Soal
        </a>
    </div>
</div>

<!-- Info Cards -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
    @php
        $total = $results->count();
        $selesai = $results->where('status', 'selesai')->count();
        $sedang = $results->where('status', 'sedang')->count();
        $avgNilai = $selesai > 0 ? round($results->where('status', 'selesai')->avg('nilai'), 1) : 0;
    @endphp
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500">Total Peserta</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $total }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500">Sedang Mengerjakan</p>
        <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $sedang }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500">Selesai</p>
        <p class="text-2xl font-bold text-green-600 mt-1">{{ $selesai }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500">Rata-rata Nilai</p>
        <p class="text-2xl font-bold text-blue-600 mt-1">{{ $avgNilai }}</p>
    </div>
</div>

<!-- Filter -->
<form method="GET" class="mb-4 flex flex-wrap gap-2 items-end">
    <div>
        <label class="block text-xs text-gray-500 mb-1">Filter Kelas</label>
        <select name="kelas_id" class="px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Kelas</option>
            @foreach($kelas as $k)
            <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs text-gray-500 mb-1">Cari Siswa</label>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama / NISN..."
               class="px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500 w-48">
    </div>
    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">Filter</button>
    @if(request('kelas_id') || request('search'))
    <a href="{{ route('guru.monitoring.index', $ruang->id) }}" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm">Reset</a>
    @endif
</form>

<!-- Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="font-semibold text-gray-800">Daftar Peserta</h2>
        <span class="text-xs text-gray-500">Token: <span class="font-mono font-semibold bg-gray-100 px-2 py-0.5 rounded">{{ $ruang->token }}</span></span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium w-10">No</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Nama Siswa</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">NISN</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Kelas</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Status</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Sisa Waktu</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Nilai</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Benar / Salah</th>
                    <th class="px-4 py-3 text-right text-gray-600 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($results as $i => $h)
                <tr class="hover:bg-blue-50 transition-colors">
                    <td class="px-4 py-3 text-gray-500">{{ $i + 1 }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $h->siswa->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600 font-mono text-xs">{{ $h->siswa->nisn ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $h->siswa->kelas->name ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @if($h->status === 'selesai')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Selesai</span>
                        @elseif($h->status === 'sedang')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Mengerjakan</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Belum</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        @php
                            $sisa = $h->sisa_waktu ?? 0;
                            $jam = floor($sisa / 3600);
                            $menit = floor(($sisa % 3600) / 60);
                            $detik = $sisa % 60;
                        @endphp
                        {{ sprintf('%02d:%02d:%02d', $jam, $menit, $detik) }}
                    </td>
                    <td class="px-4 py-3 font-semibold {{ $h->nilai >= 75 ? 'text-green-700' : ($h->nilai >= 60 ? 'text-yellow-700' : 'text-red-600') }}">
                        {{ $h->nilai ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        <span class="text-green-600 font-medium">{{ $h->benar ?? 0 }}</span>
                        <span class="text-gray-400">/</span>
                        <span class="text-red-500 font-medium">{{ $h->salah ?? 0 }}</span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <form method="POST" action="{{ route('guru.monitoring.reset', $ruang->id) }}" class="inline"
                              onsubmit="return confirm('Reset siswa {{ addslashes($h->siswa->name ?? '') }}? Data ujian akan dihapus.')">
                            @csrf
                            <input type="hidden" name="siswa_id" value="{{ $h->siswa_id }}">
                            <button type="submit" class="text-xs text-orange-600 hover:text-orange-800 font-medium bg-orange-50 px-2.5 py-1 rounded">Reset</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-4 py-12 text-center">
                        <div class="flex flex-col items-center gap-2 text-gray-400">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <p class="text-sm">Belum ada peserta ujian</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
