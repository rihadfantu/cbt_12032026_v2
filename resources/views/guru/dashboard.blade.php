@extends('layouts.app')

@section('title', 'Dashboard - Guru')
@section('page-title', 'Dashboard')
@section('user-name', auth('guru')->user()->name)
@section('user-role', 'Guru')
@section('role-badge-class', 'bg-green-100 text-green-800')

@section('sidebar')
    @include('layouts.guru-sidebar')
@endsection

@section('content')
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500">Bank Soal</p>
        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['bank_soal'] }}</p>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500">Ruang Ujian</p>
        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['ruang_ujian'] }}</p>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500">Kelas Diampu</p>
        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['kelas'] }}</p>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500">Mapel Diampu</p>
        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['mapel'] }}</p>
    </div>
</div>
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <h3 class="font-semibold text-gray-800 mb-1">Selamat Datang, {{ $guru->name }}</h3>
    <p class="text-gray-500 text-sm">NIK: {{ $guru->nik }}</p>
</div>
@endsection
