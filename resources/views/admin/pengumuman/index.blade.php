@extends('layouts.app')
@section('title', 'Pengumuman')
@section('page-title', 'Pengumuman')
@section('user-name', auth('admin')->user()->name)
@section('user-role', 'Administrator')
@section('role-badge-class', 'bg-gray-800 text-white')
@section('sidebar') @include('layouts.admin-sidebar') @endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-5 border-b border-gray-100 flex flex-wrap gap-3 items-center justify-between">
        <h2 class="font-semibold text-gray-800">Daftar Pengumuman</h2>
        <button onclick="document.getElementById('modal-add').classList.remove('hidden')"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Pengumuman
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium w-10">No</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Judul</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Target Kelas</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Tanggal</th>
                    <th class="px-4 py-3 text-right text-gray-600 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($pengumuman as $i => $p)
                <tr class="hover:bg-blue-50 transition-colors">
                    <td class="px-4 py-3 text-gray-500">{{ ($pengumuman->currentPage()-1)*$pengumuman->perPage() + $i + 1 }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $p->title }}</td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-1">
                            @if($p->target_kelas && count($p->target_kelas) > 0)
                                @foreach($kelas as $k)
                                    @if(in_array($k->id, $p->target_kelas))
                                    <span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full">{{ $k->name }}</span>
                                    @endif
                                @endforeach
                            @else
                                <span class="text-gray-400 text-xs italic">Semua Kelas</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $p->created_at->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 text-right">
                        <button onclick="openEdit({{ $p->id }}, @js($p->title), @js($p->content), @js($p->target_kelas ?? []))"
                                class="text-blue-600 hover:text-blue-800 text-xs font-medium mr-3">Edit</button>
                        <form method="POST" action="{{ route('admin.pengumuman.destroy', $p) }}" class="inline"
                              onsubmit="return confirm('Hapus pengumuman ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-400">Belum ada pengumuman</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pengumuman->hasPages())
    <div class="p-4 border-t border-gray-100 flex justify-between items-center text-sm">
        <span class="text-gray-500">Menampilkan {{ $pengumuman->firstItem() }}-{{ $pengumuman->lastItem() }} dari {{ $pengumuman->total() }}</span>
        <div class="flex gap-1">
            @if($pengumuman->onFirstPage()) <span class="px-3 py-1 text-gray-300">‹</span>
            @else <a href="{{ $pengumuman->previousPageUrl() }}" class="px-3 py-1 rounded border hover:bg-gray-50">‹</a> @endif
            @if($pengumuman->hasMorePages()) <a href="{{ $pengumuman->nextPageUrl() }}" class="px-3 py-1 rounded border hover:bg-gray-50">›</a>
            @else <span class="px-3 py-1 text-gray-300">›</span> @endif
        </div>
    </div>
    @endif
</div>

<!-- Add Modal -->
<div id="modal-add" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-5 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-semibold text-gray-800">Tambah Pengumuman</h3>
            <button onclick="document.getElementById('modal-add').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-xl">✕</button>
        </div>
        <form method="POST" action="{{ route('admin.pengumuman.store') }}" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul <span class="text-red-500">*</span></label>
                <input type="text" name="title" required placeholder="Judul pengumuman"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Isi Pengumuman <span class="text-red-500">*</span></label>
                <div id="editor-add"
                     contenteditable="true"
                     class="w-full min-h-[120px] px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none"
                     oninput="document.getElementById('content-add').value = this.innerHTML"></div>
                <input type="hidden" name="content" id="content-add">
                <div class="flex gap-2 mt-1">
                    <button type="button" onclick="document.execCommand('bold')" class="px-2 py-1 border rounded text-xs font-bold">B</button>
                    <button type="button" onclick="document.execCommand('italic')" class="px-2 py-1 border rounded text-xs italic">I</button>
                    <button type="button" onclick="document.execCommand('underline')" class="px-2 py-1 border rounded text-xs underline">U</button>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Target Kelas <span class="text-gray-400 font-normal text-xs">(kosong = semua kelas)</span></label>
                <div class="grid grid-cols-3 sm:grid-cols-4 gap-2 border border-gray-200 rounded-lg p-3">
                    @foreach($kelas as $k)
                    <label class="flex items-center gap-2 text-sm cursor-pointer">
                        <input type="checkbox" name="target_kelas[]" value="{{ $k->id }}" class="rounded">
                        {{ $k->name }}
                    </label>
                    @endforeach
                </div>
            </div>
            <div class="flex gap-2 justify-end pt-2">
                <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')"
                        class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="modal-edit" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-5 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-semibold text-gray-800">Edit Pengumuman</h3>
            <button onclick="document.getElementById('modal-edit').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-xl">✕</button>
        </div>
        <form method="POST" id="edit-form" class="p-5 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="edit-title" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Isi Pengumuman <span class="text-red-500">*</span></label>
                <div id="editor-edit"
                     contenteditable="true"
                     class="w-full min-h-[120px] px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none"
                     oninput="document.getElementById('content-edit').value = this.innerHTML"></div>
                <input type="hidden" name="content" id="content-edit">
                <div class="flex gap-2 mt-1">
                    <button type="button" onclick="document.execCommand('bold')" class="px-2 py-1 border rounded text-xs font-bold">B</button>
                    <button type="button" onclick="document.execCommand('italic')" class="px-2 py-1 border rounded text-xs italic">I</button>
                    <button type="button" onclick="document.execCommand('underline')" class="px-2 py-1 border rounded text-xs underline">U</button>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Target Kelas <span class="text-gray-400 font-normal text-xs">(kosong = semua kelas)</span></label>
                <div class="grid grid-cols-3 sm:grid-cols-4 gap-2 border border-gray-200 rounded-lg p-3" id="edit-kelas-container">
                    @foreach($kelas as $k)
                    <label class="flex items-center gap-2 text-sm cursor-pointer">
                        <input type="checkbox" name="target_kelas[]" value="{{ $k->id }}" class="edit-kelas-check rounded" data-id="{{ $k->id }}">
                        {{ $k->name }}
                    </label>
                    @endforeach
                </div>
            </div>
            <div class="flex gap-2 justify-end pt-2">
                <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')"
                        class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openEdit(id, title, content, targetKelas) {
    document.getElementById('edit-title').value = title;
    const editor = document.getElementById('editor-edit');
    editor.innerHTML = content;
    document.getElementById('content-edit').value = content;
    document.getElementById('edit-form').action = '/admin/pengumuman/' + id;
    document.querySelectorAll('.edit-kelas-check').forEach(cb => {
        cb.checked = targetKelas.includes(parseInt(cb.dataset.id));
    });
    document.getElementById('modal-edit').classList.remove('hidden');
}
</script>
@endsection
