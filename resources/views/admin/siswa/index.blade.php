@extends('layouts.app')
@section('title', 'Data Siswa')
@section('page-title', 'Data Siswa')
@section('user-name', auth('admin')->user()->name)
@section('user-role', 'Administrator')
@section('role-badge-class', 'bg-gray-800 text-white')
@section('sidebar') @include('layouts.admin-sidebar') @endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-5 border-b border-gray-100 flex flex-wrap gap-3 items-center justify-between">
        <h2 class="font-semibold text-gray-800">Daftar Siswa</h2>
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('admin.siswa.template') }}" class="bg-green-100 text-green-700 px-3 py-2 rounded-lg text-sm font-medium">Template</a>
            <button onclick="document.getElementById('modal-import').classList.remove('hidden')" class="bg-yellow-100 text-yellow-700 px-3 py-2 rounded-lg text-sm font-medium">Import Excel</button>
            <button onclick="document.getElementById('modal-add').classList.remove('hidden')" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium">+ Tambah Siswa</button>
        </div>
    </div>
    <div id="bulk-actions" class="hidden p-3 bg-blue-50 border-b border-blue-100 flex flex-wrap gap-2 items-center">
        <span class="text-sm text-blue-700" id="selected-count">0 dipilih</span>
        <button onclick="openResetPassword()" class="bg-yellow-500 text-white px-3 py-1.5 rounded-lg text-xs font-medium">Reset Password</button>
        <form method="POST" action="{{ route('admin.siswa.bulk-delete') }}" id="bulk-delete-form" class="inline">
            @csrf <div id="bulk-ids-container"></div>
            <button type="button" onclick="confirmBulkDelete()" class="bg-red-500 text-white px-3 py-1.5 rounded-lg text-xs font-medium">Hapus Terpilih</button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left w-10"><input type="checkbox" id="check-all" onchange="toggleAll(this)" class="rounded"></th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">No</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">NISN</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Nama</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Kelas</th>
                    <th class="px-4 py-3 text-right text-gray-600 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($siswas as $i => $s)
                <tr class="hover:bg-blue-50">
                    <td class="px-4 py-3"><input type="checkbox" value="{{ $s->id }}" class="row-check rounded" onchange="updateBulk()"></td>
                    <td class="px-4 py-3 text-gray-500">{{ ($siswas->currentPage()-1)*$siswas->perPage() + $i + 1 }}</td>
                    <td class="px-4 py-3 text-gray-600 font-mono text-xs">{{ $s->nisn }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $s->name }}</td>
                    <td class="px-4 py-3"><span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs">{{ $s->kelas->name ?? '-' }}</span></td>
                    <td class="px-4 py-3 text-right">
                        <button onclick="openEdit({{ $s->id }}, '{{ $s->nisn }}', '{{ addslashes($s->name) }}', {{ $s->kelas_id }})" class="text-blue-600 text-xs font-medium mr-2">Edit</button>
                        <form method="POST" action="{{ route('admin.siswa.destroy', $s) }}" class="inline" onsubmit="return confirm('Hapus siswa ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 text-xs font-medium">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada data siswa</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($siswas->hasPages())
    <div class="p-4 border-t border-gray-100 flex justify-between items-center text-sm">
        <span class="text-gray-500">{{ $siswas->firstItem() }}-{{ $siswas->lastItem() }} dari {{ $siswas->total() }}</span>
        <div class="flex gap-1">
            @if(!$siswas->onFirstPage()) <a href="{{ $siswas->previousPageUrl() }}" class="px-3 py-1 rounded border hover:bg-gray-50">‹</a> @endif
            @if($siswas->hasMorePages()) <a href="{{ $siswas->nextPageUrl() }}" class="px-3 py-1 rounded border hover:bg-gray-50">›</a> @endif
        </div>
    </div>
    @endif
</div>

<!-- Add Modal -->
<div id="modal-add" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md max-h-screen overflow-y-auto">
        <div class="p-5 border-b border-gray-100 flex justify-between items-center sticky top-0 bg-white">
            <h3 class="font-semibold">Tambah Siswa</h3>
            <button onclick="document.getElementById('modal-add').classList.add('hidden')" class="text-gray-400">✕</button>
        </div>
        <form method="POST" action="{{ route('admin.siswa.store') }}" class="p-5 space-y-4">
            @csrf
            <div><label class="block text-sm font-medium text-gray-700 mb-1">NISN *</label><input type="text" name="nisn" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Nama *</label><input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Kelas *</label>
                <select name="kelas_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">Pilih Kelas</option>
                    @foreach($kelas as $k) <option value="{{ $k->id }}">{{ $k->name }}</option> @endforeach
                </select>
            </div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Password *</label><input type="text" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none"></div>
            <div class="flex gap-2 justify-end pt-2">
                <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-600 rounded-lg font-medium">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="modal-edit" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md max-h-screen overflow-y-auto">
        <div class="p-5 border-b border-gray-100 flex justify-between items-center sticky top-0 bg-white">
            <h3 class="font-semibold">Edit Siswa</h3>
            <button onclick="document.getElementById('modal-edit').classList.add('hidden')" class="text-gray-400">✕</button>
        </div>
        <form method="POST" id="edit-form" class="p-5 space-y-4">
            @csrf @method('PUT')
            <div><label class="block text-sm font-medium text-gray-700 mb-1">NISN *</label><input type="text" name="nisn" id="edit-nisn" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Nama *</label><input type="text" name="name" id="edit-name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Kelas *</label>
                <select name="kelas_id" id="edit-kelas" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    @foreach($kelas as $k) <option value="{{ $k->id }}">{{ $k->name }}</option> @endforeach
                </select>
            </div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Password Baru <span class="text-gray-400 font-normal">(kosongkan jika tidak diubah)</span></label><input type="text" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none"></div>
            <div class="flex gap-2 justify-end pt-2">
                <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-600 rounded-lg font-medium">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Import Modal -->
<div id="modal-import" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
        <div class="p-5 border-b border-gray-100 flex justify-between">
            <h3 class="font-semibold">Import Siswa dari Excel</h3>
            <button onclick="document.getElementById('modal-import').classList.add('hidden')" class="text-gray-400">✕</button>
        </div>
        <form method="POST" action="{{ route('admin.siswa.import') }}" enctype="multipart/form-data" class="p-5">
            @csrf
            <p class="text-sm text-gray-600 mb-4">Kolom: NISN, Nama, Kelas (nama kelas), Password. <a href="{{ route('admin.siswa.template') }}" class="text-blue-600 underline">Template</a></p>
            <input type="file" name="file" accept=".xlsx,.xls" required class="w-full text-sm mb-4">
            <div class="flex gap-2 justify-end">
                <button type="button" onclick="document.getElementById('modal-import').classList.add('hidden')" class="px-4 py-2 text-sm bg-gray-100 rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-600 rounded-lg font-medium">Import</button>
            </div>
        </form>
    </div>
</div>

<!-- Reset Password Modal -->
<div id="modal-reset" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
        <div class="p-5 border-b border-gray-100 flex justify-between">
            <h3 class="font-semibold">Reset Password Massal</h3>
            <button onclick="document.getElementById('modal-reset').classList.add('hidden')" class="text-gray-400">✕</button>
        </div>
        <form method="POST" action="{{ route('admin.siswa.reset-password') }}" id="reset-form" class="p-5">
            @csrf <div id="reset-ids-container"></div>
            <div class="mb-4"><label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label><input type="text" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none"></div>
            <div class="flex gap-2 justify-end">
                <button type="button" onclick="document.getElementById('modal-reset').classList.add('hidden')" class="px-4 py-2 text-sm bg-gray-100 rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm text-white bg-yellow-500 rounded-lg font-medium">Reset</button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script>
function openEdit(id, nisn, name, kelasId) { document.getElementById('edit-nisn').value=nisn; document.getElementById('edit-name').value=name; document.getElementById('edit-kelas').value=kelasId; document.getElementById('edit-form').action='/admin/siswa/'+id; document.getElementById('modal-edit').classList.remove('hidden'); }
function toggleAll(cb) { document.querySelectorAll('.row-check').forEach(c => c.checked=cb.checked); updateBulk(); }
function updateBulk() { const n=document.querySelectorAll('.row-check:checked').length; document.getElementById('selected-count').textContent=n+' dipilih'; document.getElementById('bulk-actions').classList.toggle('hidden', n===0); }
function openResetPassword() {
    const checked=document.querySelectorAll('.row-check:checked'); if(!checked.length) return;
    const c=document.getElementById('reset-ids-container'); c.innerHTML='';
    checked.forEach(x => { const i=document.createElement('input'); i.type='hidden'; i.name='ids[]'; i.value=x.value; c.appendChild(i); });
    document.getElementById('modal-reset').classList.remove('hidden');
}
function confirmBulkDelete() {
    const checked=document.querySelectorAll('.row-check:checked');
    if(!checked.length||!confirm('Hapus '+checked.length+' siswa?')) return;
    const c=document.getElementById('bulk-ids-container'); c.innerHTML='';
    checked.forEach(x => { const i=document.createElement('input'); i.type='hidden'; i.name='ids[]'; i.value=x.value; c.appendChild(i); });
    document.getElementById('bulk-delete-form').submit();
}
</script>
@endsection
