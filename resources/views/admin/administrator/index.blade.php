@extends('layouts.app')
@section('title', 'Data Administrator')
@section('page-title', 'Data Administrator')
@section('user-name', auth('admin')->user()->name)
@section('user-role', 'Administrator')
@section('role-badge-class', 'bg-gray-800 text-white')
@section('sidebar') @include('layouts.admin-sidebar') @endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-5 border-b border-gray-100 flex justify-between items-center">
        <h2 class="font-semibold text-gray-800">Data Administrator</h2>
        <button onclick="document.getElementById('modal-add').classList.remove('hidden')" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium">+ Tambah Admin</button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">No</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Email</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Nama</th>
                    <th class="px-4 py-3 text-right text-gray-600 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($admins as $i => $a)
                <tr class="hover:bg-blue-50">
                    <td class="px-4 py-3 text-gray-500">{{ ($admins->currentPage()-1)*$admins->perPage() + $i + 1 }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $a->email }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $a->name }}</td>
                    <td class="px-4 py-3 text-right">
                        <button onclick="openEdit({{ $a->id }}, '{{ $a->email }}', '{{ addslashes($a->name) }}')" class="text-blue-600 text-xs font-medium mr-2">Edit</button>
                        <form method="POST" action="{{ route('admin.administrator.destroy', $a) }}" class="inline" onsubmit="return confirm('Hapus admin ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 text-xs font-medium">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Belum ada data admin</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<div id="modal-add" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
        <div class="p-5 border-b border-gray-100 flex justify-between"><h3 class="font-semibold">Tambah Admin</h3><button onclick="document.getElementById('modal-add').classList.add('hidden')" class="text-gray-400">✕</button></div>
        <form method="POST" action="{{ route('admin.administrator.store') }}" class="p-5 space-y-4">
            @csrf
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Email *</label><input type="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Nama *</label><input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Password *</label><input type="text" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none"></div>
            <div class="flex gap-2 justify-end pt-2">
                <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')" class="px-4 py-2 text-sm bg-gray-100 rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-600 rounded-lg font-medium">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="modal-edit" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
        <div class="p-5 border-b border-gray-100 flex justify-between"><h3 class="font-semibold">Edit Admin</h3><button onclick="document.getElementById('modal-edit').classList.add('hidden')" class="text-gray-400">✕</button></div>
        <form method="POST" id="edit-form" class="p-5 space-y-4">
            @csrf @method('PUT')
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Email *</label><input type="email" name="email" id="edit-email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Nama *</label><input type="text" name="name" id="edit-name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Password Baru <span class="text-gray-400 font-normal">(kosongkan jika tidak diubah)</span></label><input type="text" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none"></div>
            <div class="flex gap-2 justify-end pt-2">
                <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')" class="px-4 py-2 text-sm bg-gray-100 rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-600 rounded-lg font-medium">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script>
function openEdit(id, email, name) { document.getElementById('edit-email').value=email; document.getElementById('edit-name').value=name; document.getElementById('edit-form').action='/admin/administrator/'+id; document.getElementById('modal-edit').classList.remove('hidden'); }
</script>
@endsection
