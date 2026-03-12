@extends('layouts.app')
@section('title', 'Relasi Guru')
@section('page-title', 'Relasi Guru')
@section('user-name', auth('admin')->user()->name)
@section('user-role', 'Administrator')
@section('role-badge-class', 'bg-gray-800 text-white')
@section('sidebar') @include('layouts.admin-sidebar') @endsection

@section('content')
<div class="space-y-4">
    @forelse($gurus as $guru)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-semibold text-gray-800">{{ $guru->name }}</h3>
                <p class="text-xs text-gray-500">NIK: {{ $guru->nik }}</p>
            </div>
            <button onclick="toggleRelasi({{ $guru->id }})" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                Edit Relasi
            </button>
        </div>

        @php $relasi = $guru->relasi; $kelasIds = $relasi ? ($relasi->kelas_ids ?? []) : []; $mapelIds = $relasi ? ($relasi->mapel_ids ?? []): []; @endphp

        <div class="flex flex-wrap gap-2 mb-2">
            <span class="text-xs text-gray-500 font-medium">Kelas:</span>
            @foreach($kelas as $k)
                @if(in_array($k->id, $kelasIds))
                <span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded">{{ $k->name }}</span>
                @endif
            @endforeach
            @if(empty($kelasIds)) <span class="text-xs text-gray-400 italic">Belum ada</span> @endif
        </div>
        <div class="flex flex-wrap gap-2">
            <span class="text-xs text-gray-500 font-medium">Mapel:</span>
            @foreach($mapels as $m)
                @if(in_array($m->id, $mapelIds))
                <span class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded">{{ $m->name }}</span>
                @endif
            @endforeach
            @if(empty($mapelIds)) <span class="text-xs text-gray-400 italic">Belum ada</span> @endif
        </div>

        <!-- Inline Edit Form -->
        <div id="relasi-form-{{ $guru->id }}" class="hidden mt-4 border-t border-gray-100 pt-4">
            <form method="POST" action="{{ route('admin.relasi.update', $guru) }}">
                @csrf
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                        <div class="space-y-1 max-h-40 overflow-y-auto border border-gray-200 rounded-lg p-3">
                            @foreach($kelas as $k)
                            <label class="flex items-center gap-2 text-sm cursor-pointer">
                                <input type="checkbox" name="kelas_ids[]" value="{{ $k->id }}" {{ in_array($k->id, $kelasIds) ? 'checked' : '' }} class="rounded">
                                {{ $k->name }}
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mata Pelajaran</label>
                        <div class="space-y-1 max-h-40 overflow-y-auto border border-gray-200 rounded-lg p-3">
                            @foreach($mapels as $m)
                            <label class="flex items-center gap-2 text-sm cursor-pointer">
                                <input type="checkbox" name="mapel_ids[]" value="{{ $m->id }}" {{ in_array($m->id, $mapelIds) ? 'checked' : '' }} class="rounded">
                                {{ $m->name }}
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium">Simpan Relasi</button>
                    <button type="button" onclick="toggleRelasi({{ $guru->id }})" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm">Batal</button>
                </div>
            </form>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl p-8 text-center text-gray-400">Belum ada data guru</div>
    @endforelse
</div>
@endsection
@section('scripts')
<script>
function toggleRelasi(id) { document.getElementById('relasi-form-'+id).classList.toggle('hidden'); }
</script>
@endsection
