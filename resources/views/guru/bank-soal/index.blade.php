@extends('layouts.app')
@section('title', 'Bank Soal')
@section('page-title', 'Bank Soal')
@section('user-name', auth('guru')->user()->name)
@section('user-role', 'Guru')
@section('role-badge-class', 'bg-green-100 text-green-800')
@section('sidebar') @include('layouts.guru-sidebar') @endsection

@section('content')
<div class="mb-4 flex gap-2">
    <a href="{{ route('guru.bank-soal.arsip') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">Arsip Bank Soal</a>
</div>
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-5 border-b border-gray-100 flex flex-wrap gap-3 items-center justify-between">
        <h2 class="font-semibold text-gray-800">Daftar Bank Soal</h2>
        <button onclick="document.getElementById('modal-add').classList.remove('hidden')" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium">+ Tambah Bank Soal</button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">No</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Judul</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Mapel</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Timer</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Soal</th>
                    <th class="px-4 py-3 text-right text-gray-600 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($bankSoals as $i => $b)
                <tr class="hover:bg-blue-50">
                    <td class="px-4 py-3 text-gray-500">{{ ($bankSoals->currentPage()-1)*$bankSoals->perPage() + $i + 1 }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $b->title }}</td>
                    <td class="px-4 py-3 text-gray-600 text-xs">{{ $b->mapel->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $b->timer }} menit</td>
                    <td class="px-4 py-3 text-gray-600">{{ count($b->soals ?? []) }} soal</td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('guru.soal.edit', $b->id) }}" class="text-green-600 hover:text-green-800 text-xs font-medium">Edit Soal</a>
                        <button onclick="openEdit({{ $b->id }}, {{ $b->mapel_id }}, '{{ addslashes($b->title) }}', {{ $b->timer }}, {{ $b->bobot_pg }}, {{ $b->bobot_essai }}, {{ $b->bobot_bs }}, {{ $b->bobot_jodoh }})" class="text-blue-600 text-xs font-medium">Edit</button>
                        <button onclick="arsipkan({{ $b->id }})" class="text-yellow-600 text-xs font-medium">Arsip</button>
                        <form method="POST" action="{{ route('guru.bank-soal.destroy', $b) }}" class="inline" onsubmit="return confirm('Hapus bank soal ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 text-xs font-medium">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada bank soal</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($bankSoals->hasPages())
    <div class="p-4 border-t border-gray-100 flex justify-between items-center text-sm">
        <span class="text-gray-500">{{ $bankSoals->firstItem() }}-{{ $bankSoals->lastItem() }} dari {{ $bankSoals->total() }}</span>
        <div class="flex gap-1">
            @if(!$bankSoals->onFirstPage()) <a href="{{ $bankSoals->previousPageUrl() }}" class="px-3 py-1 rounded border">‹</a> @endif
            @if($bankSoals->hasMorePages()) <a href="{{ $bankSoals->nextPageUrl() }}" class="px-3 py-1 rounded border">›</a> @endif
        </div>
    </div>
    @endif
</div>

<!-- Add Modal -->
<div id="modal-add" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg max-h-screen overflow-y-auto">
        <div class="p-5 border-b border-gray-100 flex justify-between sticky top-0 bg-white">
            <h3 class="font-semibold">Tambah Bank Soal</h3>
            <button onclick="document.getElementById('modal-add').classList.add('hidden')" class="text-gray-400">✕</button>
        </div>
        <form method="POST" action="{{ route('guru.bank-soal.store') }}" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran *</label>
                <select name="mapel_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">Pilih Mapel</option>
                    @foreach($mapels as $m) <option value="{{ $m->id }}">{{ $m->name }}</option> @endforeach
                </select>
            </div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Judul *</label><input type="text" name="title" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Timer (menit) *</label><input type="number" name="timer" value="60" min="1" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none"></div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-xs font-medium text-gray-700 mb-1">Bobot PG (%)</label><input type="number" name="bobot_pg" value="100" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none"></div>
                <div><label class="block text-xs font-medium text-gray-700 mb-1">Bobot Essay (%)</label><input type="number" name="bobot_essai" value="0" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none"></div>
            </div>
            <div class="flex gap-2 justify-end pt-2">
                <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')" class="px-4 py-2 text-sm bg-gray-100 rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-600 rounded-lg font-medium">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="modal-edit" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg max-h-screen overflow-y-auto">
        <div class="p-5 border-b border-gray-100 flex justify-between sticky top-0 bg-white">
            <h3 class="font-semibold">Edit Bank Soal</h3>
            <button onclick="document.getElementById('modal-edit').classList.add('hidden')" class="text-gray-400">✕</button>
        </div>
        <form method="POST" id="edit-form" class="p-5 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran *</label>
                <select name="mapel_id" id="edit-mapel" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">Pilih Mapel</option>
                    @foreach($mapels as $m) <option value="{{ $m->id }}">{{ $m->name }}</option> @endforeach
                </select>
            </div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Judul *</label><input type="text" name="title" id="edit-title" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Timer (menit) *</label><input type="number" name="timer" id="edit-timer" min="1" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none"></div>
            <div class="grid grid-cols-2 gap-3">
                <div><label class="block text-xs font-medium text-gray-700 mb-1">Bobot PG (%)</label><input type="number" name="bobot_pg" id="edit-bobot-pg" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none"></div>
                <div><label class="block text-xs font-medium text-gray-700 mb-1">Bobot Essay (%)</label><input type="number" name="bobot_essai" id="edit-bobot-essai" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none"></div>
            </div>
            <div class="flex gap-2 justify-end pt-2">
                <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')" class="px-4 py-2 text-sm bg-gray-100 rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-600 rounded-lg font-medium">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Arsip Form (hidden) -->
<form method="POST" id="arsip-form" action="{{ route('guru.bank-soal.arsipkan') }}">
    @csrf
    <input type="hidden" name="ids[]" id="arsip-id">
</form>

@endsection

@section('scripts')
<script>
function openEdit(id, mapelId, title, timer, bobotPg, bobotEssai, bobotBs, bobotJodoh) {
    document.getElementById('edit-mapel').value = mapelId;
    document.getElementById('edit-title').value = title;
    document.getElementById('edit-timer').value = timer;
    document.getElementById('edit-bobot-pg').value = bobotPg;
    document.getElementById('edit-bobot-essai').value = bobotEssai;
    document.getElementById('edit-form').action = '/guru/bank-soal/' + id;
    document.getElementById('modal-edit').classList.remove('hidden');
}
function arsipkan(id) {
    if (confirm('Arsipkan bank soal ini?')) {
        document.getElementById('arsip-id').value = id;
        document.getElementById('arsip-form').submit();
    }
}
</script>
@endsection
