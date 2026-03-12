@extends('layouts.app')
@section('title', 'Arsip Bank Soal')
@section('page-title', 'Arsip Bank Soal')
@section('user-name', auth('admin')->user()->name)
@section('user-role', 'Administrator')
@section('role-badge-class', 'bg-gray-800 text-white')
@section('sidebar') @include('layouts.admin-sidebar') @endsection

@section('content')
<div class="mb-4"><a href="{{ route('admin.bank-soal.index') }}" class="text-blue-600 text-sm">← Kembali ke Bank Soal Aktif</a></div>
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-5 border-b border-gray-100 flex flex-wrap gap-3 items-center justify-between">
        <h2 class="font-semibold text-gray-800">Arsip Bank Soal</h2>
        <div class="flex gap-2">
            <form method="POST" action="{{ route('admin.bank-soal.aktifkan') }}" id="aktif-form">
                @csrf <div id="aktif-ids"></div>
                <button type="button" onclick="bulkAction('aktif')" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium">Aktifkan Terpilih</button>
            </form>
            <form method="POST" action="{{ route('admin.bank-soal.hapus-permanen') }}" id="hapus-form">
                @csrf <div id="hapus-ids"></div>
                <button type="button" onclick="bulkAction('hapus')" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium">Hapus Permanen</button>
            </form>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left w-10"><input type="checkbox" id="check-all" onchange="toggleAll(this)" class="rounded"></th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Judul</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Guru</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Mapel</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Soal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($bankSoals as $b)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3"><input type="checkbox" value="{{ $b->id }}" class="row-check rounded"></td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $b->title }}</td>
                    <td class="px-4 py-3 text-gray-600 text-xs">{{ $b->guru->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600 text-xs">{{ $b->mapel->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ count($b->soals ?? []) }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Tidak ada bank soal diarsip</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
@section('scripts')
<script>
function toggleAll(cb){ document.querySelectorAll('.row-check').forEach(c=>c.checked=cb.checked); }
function bulkAction(type){
    const checked=document.querySelectorAll('.row-check:checked');
    if(!checked.length){ alert('Pilih minimal 1 item'); return; }
    const msg=type==='aktif'?'Aktifkan '+checked.length+' bank soal?':'Hapus permanen '+checked.length+' bank soal?';
    if(!confirm(msg)) return;
    const formId=type==='aktif'?'aktif-form':'hapus-form';
    const idsId=type==='aktif'?'aktif-ids':'hapus-ids';
    const c=document.getElementById(idsId); c.innerHTML='';
    checked.forEach(x=>{ const i=document.createElement('input'); i.type='hidden'; i.name='ids[]'; i.value=x.value; c.appendChild(i); });
    document.getElementById(formId).submit();
}
</script>
@endsection
