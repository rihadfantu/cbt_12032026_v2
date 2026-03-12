@extends('layouts.app')
@section('title', 'Mata Pelajaran')
@section('page-title', 'Mata Pelajaran')
@section('user-name', auth('admin')->user()->name)
@section('user-role', 'Administrator')
@section('role-badge-class', 'bg-gray-800 text-white')
@section('sidebar') @include('layouts.admin-sidebar') @endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-5 border-b border-gray-100 flex flex-wrap gap-3 items-center justify-between">
        <h2 class="font-semibold text-gray-800">Daftar Mata Pelajaran</h2>
        <button onclick="document.getElementById('modal-add').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Mapel
        </button>
    </div>
    <div id="bulk-actions" class="hidden p-3 bg-blue-50 border-b border-blue-100 flex gap-2 items-center">
        <span class="text-sm text-blue-700" id="selected-count">0 dipilih</span>
        <form method="POST" action="{{ route('admin.mapel.bulk-delete') }}" id="bulk-delete-form" class="ml-2">
            @csrf
            <div id="bulk-ids-container"></div>
            <button type="button" onclick="confirmBulkDelete()" class="bg-red-500 text-white px-3 py-1.5 rounded-lg text-xs font-medium">Hapus Terpilih</button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left w-10"><input type="checkbox" id="check-all" onchange="toggleAll(this)" class="rounded"></th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">No</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Nama Mapel</th>
                    <th class="px-4 py-3 text-right text-gray-600 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($mapels as $i => $m)
                <tr class="hover:bg-blue-50">
                    <td class="px-4 py-3"><input type="checkbox" value="{{ $m->id }}" class="row-check rounded" onchange="updateBulk()"></td>
                    <td class="px-4 py-3 text-gray-500">{{ ($mapels->currentPage()-1)*$mapels->perPage() + $i + 1 }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $m->name }}</td>
                    <td class="px-4 py-3 text-right">
                        <button onclick="openEdit({{ $m->id }}, '{{ $m->name }}')" class="text-blue-600 hover:text-blue-800 text-xs font-medium mr-2">Edit</button>
                        <form method="POST" action="{{ route('admin.mapel.destroy', $m) }}" class="inline" onsubmit="return confirm('Hapus mapel ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Belum ada data mapel</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($mapels->hasPages())
    <div class="p-4 border-t border-gray-100 flex justify-between items-center text-sm">
        <span class="text-gray-500">{{ $mapels->firstItem() }}-{{ $mapels->lastItem() }} dari {{ $mapels->total() }}</span>
        <div class="flex gap-1">
            @if(!$mapels->onFirstPage()) <a href="{{ $mapels->previousPageUrl() }}" class="px-3 py-1 rounded border hover:bg-gray-50">‹</a> @endif
            @if($mapels->hasMorePages()) <a href="{{ $mapels->nextPageUrl() }}" class="px-3 py-1 rounded border hover:bg-gray-50">›</a> @endif
        </div>
    </div>
    @endif
</div>

<!-- Add Modal -->
<div id="modal-add" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
        <div class="p-5 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-semibold">Tambah Mapel</h3>
            <button onclick="document.getElementById('modal-add').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">✕</button>
        </div>
        <form method="POST" action="{{ route('admin.mapel.store') }}" class="p-5">
            @csrf
            <div class="mb-3">
                <div class="flex gap-4 mb-3">
                    <label class="flex items-center gap-2 text-sm"><input type="radio" name="mode" value="single" checked onchange="toggleBulk(false)"> Satu</label>
                    <label class="flex items-center gap-2 text-sm"><input type="radio" name="mode" value="bulk" onchange="toggleBulk(true)"> Masal</label>
                </div>
                <div id="single-input">
                    <input type="text" name="name" placeholder="Nama mapel" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div id="bulk-input" class="hidden">
                    <textarea name="name" rows="5" placeholder="Matematika&#10;Bahasa Indonesia&#10;IPA" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
                    <input type="hidden" name="bulk" value="1">
                </div>
            </div>
            <div class="flex gap-2 justify-end">
                <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="modal-edit" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
        <div class="p-5 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-semibold">Edit Mapel</h3>
            <button onclick="document.getElementById('modal-edit').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">✕</button>
        </div>
        <form method="POST" id="edit-form" class="p-5">
            @csrf @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Mapel</label>
                <input type="text" name="name" id="edit-name" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="flex gap-2 justify-end">
                <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script>
function openEdit(id, name) { document.getElementById('edit-name').value = name; document.getElementById('edit-form').action = '/admin/mapel/' + id; document.getElementById('modal-edit').classList.remove('hidden'); }
function toggleBulk(b) { document.getElementById('single-input').classList.toggle('hidden', b); document.getElementById('bulk-input').classList.toggle('hidden', !b); }
function toggleAll(cb) { document.querySelectorAll('.row-check').forEach(c => c.checked = cb.checked); updateBulk(); }
function updateBulk() { const n = document.querySelectorAll('.row-check:checked').length; document.getElementById('selected-count').textContent = n + ' dipilih'; document.getElementById('bulk-actions').classList.toggle('hidden', n === 0); }
function confirmBulkDelete() {
    const checked = document.querySelectorAll('.row-check:checked');
    if (!checked.length || !confirm('Hapus ' + checked.length + ' mapel?')) return;
    const c = document.getElementById('bulk-ids-container'); c.innerHTML = '';
    checked.forEach(x => { const i = document.createElement('input'); i.type='hidden'; i.name='ids[]'; i.value=x.value; c.appendChild(i); });
    document.getElementById('bulk-delete-form').submit();
}
</script>
@endsection
