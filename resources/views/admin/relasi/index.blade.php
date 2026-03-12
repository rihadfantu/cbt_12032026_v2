@extends('layouts.app')
@section('title', 'Relasi Guru')
@section('page-title', 'Relasi Guru')
@section('user-name', auth('admin')->user()->name)
@section('user-role', 'Administrator')
@section('role-badge-class', 'bg-gray-800 text-white')
@section('sidebar') @include('layouts.admin-sidebar') @endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-5 border-b border-gray-100">
        <h2 class="font-semibold text-gray-800">Daftar Relasi Guru</h2>
        <p class="text-sm text-gray-500 mt-0.5">Kelola relasi kelas dan mata pelajaran untuk setiap guru</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium w-10">No</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">NIK</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Nama Guru</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Jml Kelas</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Jml Mapel</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Kelas</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Mapel</th>
                    <th class="px-4 py-3 text-right text-gray-600 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($gurus as $i => $guru)
                @php
                    $relasi = $guru->relasi;
                    $kelasIds = $relasi ? ($relasi->kelas_ids ?? []) : [];
                    $mapelIds = $relasi ? ($relasi->mapel_ids ?? []) : [];
                @endphp
                <tr class="hover:bg-blue-50 transition-colors">
                    <td class="px-4 py-3 text-gray-500">{{ $i + 1 }}</td>
                    <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ $guru->nik }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $guru->name }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full font-medium">{{ count($kelasIds) }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full font-medium">{{ count($mapelIds) }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-1">
                            @foreach($kelas as $k)
                                @if(in_array($k->id, $kelasIds))
                                <span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded">{{ $k->name }}</span>
                                @endif
                            @endforeach
                            @if(empty($kelasIds))
                            <span class="text-gray-400 text-xs italic">Belum ada</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-1">
                            @foreach($mapels as $m)
                                @if(in_array($m->id, $mapelIds))
                                <span class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded">{{ $m->name }}</span>
                                @endif
                            @endforeach
                            @if(empty($mapelIds))
                            <span class="text-gray-400 text-xs italic">Belum ada</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <button onclick="openModal({{ $guru->id }}, @js($guru->name), @js($kelasIds), @js($mapelIds))"
                                class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-1.5 rounded-lg font-medium transition-colors">
                            Edit Relasi
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-gray-400">Belum ada data guru</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Relasi Modal -->
<div id="modal-relasi" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-5 border-b border-gray-100 flex justify-between items-center">
            <div>
                <h3 class="font-semibold text-gray-800">Edit Relasi Guru</h3>
                <p class="text-sm text-gray-500 mt-0.5" id="modal-guru-name"></p>
            </div>
            <button onclick="document.getElementById('modal-relasi').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600 text-xl">✕</button>
        </div>
        <form method="POST" id="relasi-form" class="p-5">
            @csrf
            <div class="grid md:grid-cols-2 gap-5">
                <!-- Pilih Kelas -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Pilih Kelas</label>
                    <div class="space-y-2 max-h-52 overflow-y-auto border border-gray-200 rounded-lg p-3">
                        @foreach($kelas as $k)
                        <label class="flex items-center gap-2 text-sm cursor-pointer hover:bg-gray-50 rounded px-1 py-0.5">
                            <input type="checkbox" name="kelas_ids[]" value="{{ $k->id }}"
                                   class="modal-kelas-check rounded" data-id="{{ $k->id }}">
                            {{ $k->name }}
                        </label>
                        @endforeach
                        @if($kelas->isEmpty())
                        <p class="text-xs text-gray-400 italic">Belum ada data kelas</p>
                        @endif
                    </div>
                </div>
                <!-- Pilih Mapel -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Pilih Mata Pelajaran</label>
                    <div class="space-y-2 max-h-52 overflow-y-auto border border-gray-200 rounded-lg p-3">
                        @foreach($mapels as $m)
                        <label class="flex items-center gap-2 text-sm cursor-pointer hover:bg-gray-50 rounded px-1 py-0.5">
                            <input type="checkbox" name="mapel_ids[]" value="{{ $m->id }}"
                                   class="modal-mapel-check rounded" data-id="{{ $m->id }}">
                            {{ $m->name }}
                        </label>
                        @endforeach
                        @if($mapels->isEmpty())
                        <p class="text-xs text-gray-400 italic">Belum ada data mapel</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex gap-2 justify-end mt-5 pt-4 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('modal-relasi').classList.add('hidden')"
                        class="px-4 py-2 text-sm text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium">Batal</button>
                <button type="submit"
                        class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium">Simpan Relasi</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openModal(guruId, guruName, kelasIds, mapelIds) {
    document.getElementById('modal-guru-name').textContent = guruName;
    document.getElementById('relasi-form').action = '/admin/relasi/' + guruId;
    document.querySelectorAll('.modal-kelas-check').forEach(cb => {
        cb.checked = kelasIds.includes(parseInt(cb.dataset.id));
    });
    document.querySelectorAll('.modal-mapel-check').forEach(cb => {
        cb.checked = mapelIds.includes(parseInt(cb.dataset.id));
    });
    document.getElementById('modal-relasi').classList.remove('hidden');
}
</script>
@endsection
