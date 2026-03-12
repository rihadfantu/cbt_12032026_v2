@extends('layouts.app')
@section('title', 'Arsip Bank Soal')
@section('page-title', 'Arsip Bank Soal')
@section('user-name', auth('guru')->user()->name)
@section('user-role', 'Guru')
@section('role-badge-class', 'bg-green-100 text-green-800')
@section('sidebar') @include('layouts.guru-sidebar') @endsection

@section('content')
<div class="mb-4">
    <a href="{{ route('guru.bank-soal.index') }}" class="flex items-center gap-1 text-sm text-gray-600 hover:text-gray-800 w-fit">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Bank Soal
    </a>
</div>
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-5 border-b border-gray-100 flex flex-wrap gap-3 items-center justify-between">
        <h2 class="font-semibold text-gray-800">Arsip Bank Soal</h2>
        <div class="flex gap-2">
            <form method="POST" action="{{ route('guru.bank-soal.aktifkan') }}" id="aktifkan-form">
                @csrf
                <div id="aktifkan-ids"></div>
            </form>
            <form method="POST" action="{{ route('guru.bank-soal.hapus-permanen') }}" id="hapus-form">
                @csrf
                <div id="hapus-ids"></div>
            </form>
            <button onclick="bulkAktifkan()" class="bg-green-600 text-white px-3 py-2 rounded-lg text-xs font-medium">Aktifkan Pilihan</button>
            <button onclick="bulkHapus()" class="bg-red-600 text-white px-3 py-2 rounded-lg text-xs font-medium">Hapus Permanen</button>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-4 py-3 w-10"><input type="checkbox" id="check-all" onchange="toggleAll(this)" class="rounded"></th>
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
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3"><input type="checkbox" class="row-check rounded" value="{{ $b->id }}"></td>
                    <td class="px-4 py-3 text-gray-500">{{ ($bankSoals->currentPage()-1)*$bankSoals->perPage() + $i + 1 }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $b->title }}</td>
                    <td class="px-4 py-3 text-gray-600 text-xs">{{ $b->mapel->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $b->timer }} menit</td>
                    <td class="px-4 py-3 text-gray-600">{{ count($b->soals ?? []) }} soal</td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <button onclick="aktifkanSatu({{ $b->id }})" class="text-green-600 text-xs font-medium">Aktifkan</button>
                        <button onclick="hapusSatu({{ $b->id }})" class="text-red-500 text-xs font-medium">Hapus</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Arsip kosong</td></tr>
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
@endsection

@section('scripts')
<script>
function toggleAll(cb) { document.querySelectorAll('.row-check').forEach(c => c.checked = cb.checked); }
function getChecked() { return [...document.querySelectorAll('.row-check:checked')].map(c => c.value); }

function aktifkanSatu(id) {
    if (!confirm('Aktifkan kembali bank soal ini?')) return;
    addHiddenInputs('aktifkan-ids', [id]);
    document.getElementById('aktifkan-form').submit();
}
function bulkAktifkan() {
    const ids = getChecked();
    if (!ids.length) { alert('Pilih minimal satu data.'); return; }
    if (!confirm('Aktifkan ' + ids.length + ' bank soal terpilih?')) return;
    addHiddenInputs('aktifkan-ids', ids);
    document.getElementById('aktifkan-form').submit();
}
function hapusSatu(id) {
    if (!confirm('Hapus permanen bank soal ini? Data tidak bisa dikembalikan.')) return;
    addHiddenInputs('hapus-ids', [id]);
    document.getElementById('hapus-form').submit();
}
function bulkHapus() {
    const ids = getChecked();
    if (!ids.length) { alert('Pilih minimal satu data.'); return; }
    if (!confirm('Hapus permanen ' + ids.length + ' bank soal? Tidak bisa dikembalikan!')) return;
    addHiddenInputs('hapus-ids', ids);
    document.getElementById('hapus-form').submit();
}
function addHiddenInputs(containerId, ids) {
    const container = document.getElementById(containerId);
    container.innerHTML = '';
    ids.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ids[]';
        input.value = id;
        container.appendChild(input);
    });
}
</script>
@endsection
