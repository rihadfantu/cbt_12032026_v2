@extends('layouts.app')
@section('title', 'Ruang Ujian')
@section('page-title', 'Ruang Ujian')
@section('user-name', auth('guru')->user()->name)
@section('user-role', 'Guru')
@section('role-badge-class', 'bg-green-100 text-green-800')
@section('sidebar') @include('layouts.guru-sidebar') @endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-5 border-b border-gray-100 flex flex-wrap gap-3 items-center justify-between">
        <h2 class="font-semibold text-gray-800">Daftar Ruang Ujian</h2>
        <button onclick="document.getElementById('modal-add').classList.remove('hidden')"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Ruang Ujian
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium w-10">No</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Nama Ruang</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Token</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Bank Soal</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Kelas</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Waktu</th>
                    <th class="px-4 py-3 text-left text-gray-600 font-medium">Status</th>
                    <th class="px-4 py-3 text-right text-gray-600 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($ruangs as $i => $ruang)
                @php
                    $now = now();
                    $isActive = $now->between($ruang->start_at, $ruang->end_at);
                    $isEnded = $now->isAfter($ruang->end_at);
                    $ruangKelas = $ruang->classes ?? [];
                @endphp
                <tr class="hover:bg-blue-50 transition-colors">
                    <td class="px-4 py-3 text-gray-500">{{ ($ruangs->currentPage()-1)*$ruangs->perPage() + $i + 1 }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $ruang->name }}</td>
                    <td class="px-4 py-3">
                        <span class="font-mono bg-gray-100 px-2 py-0.5 rounded text-xs font-semibold tracking-wider">{{ $ruang->token }}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-600 text-xs">{{ $ruang->bank->title ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600 text-xs">
                        @if(empty($ruangKelas))
                            <span class="text-gray-400">Semua</span>
                        @else
                            @foreach($kelas->whereIn('id', $ruangKelas) as $k)
                                <span class="inline-flex bg-blue-50 text-blue-700 px-1.5 py-0.5 rounded text-xs mr-1">{{ $k->name }}</span>
                            @endforeach
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-600 text-xs">
                        <div>{{ $ruang->start_at->format('d/m/Y H:i') }}</div>
                        <div class="text-gray-400">s/d {{ $ruang->end_at->format('d/m/Y H:i') }}</div>
                    </td>
                    <td class="px-4 py-3">
                        @if($isActive)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                        @elseif($isEnded)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Selesai</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Belum Mulai</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('guru.monitoring.index', $ruang->id) }}" class="text-xs font-medium text-purple-600 hover:text-purple-800">Monitoring</a>
                        <button onclick="openEdit({{ $ruang->id }}, '{{ addslashes($ruang->name) }}', '{{ $ruang->token }}', {{ $ruang->bank_id }}, {{ $ruang->login_limit }}, {{ $ruang->min_time_submit }}, {{ json_encode($ruang->classes ?? []) }}, '{{ $ruang->start_at->format('Y-m-d\TH:i') }}', '{{ $ruang->end_at->format('Y-m-d\TH:i') }}', {{ $ruang->random_soal ? 'true' : 'false' }}, {{ $ruang->random_ops ? 'true' : 'false' }})"
                                class="text-xs font-medium text-blue-600 hover:text-blue-800">Edit</button>
                        <form method="POST" action="{{ route('guru.ruang-ujian.destroy', $ruang->id) }}" class="inline"
                              onsubmit="return confirm('Hapus ruang ujian ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs font-medium text-red-500 hover:text-red-700">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-12 text-center">
                        <div class="flex flex-col items-center gap-2 text-gray-400">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2h-2"></path></svg>
                            <p class="text-sm">Belum ada ruang ujian</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($ruangs->hasPages())
    <div class="p-4 border-t border-gray-100 flex justify-between items-center text-sm">
        <span class="text-gray-500">{{ $ruangs->firstItem() }}-{{ $ruangs->lastItem() }} dari {{ $ruangs->total() }}</span>
        <div class="flex gap-1">
            @if(!$ruangs->onFirstPage()) <a href="{{ $ruangs->previousPageUrl() }}" class="px-3 py-1 rounded border">‹</a> @endif
            @if($ruangs->hasMorePages()) <a href="{{ $ruangs->nextPageUrl() }}" class="px-3 py-1 rounded border">›</a> @endif
        </div>
    </div>
    @endif
</div>

<!-- Add Modal -->
<div id="modal-add" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-xl max-h-[90vh] overflow-y-auto">
        <div class="p-5 border-b border-gray-100 flex justify-between sticky top-0 bg-white">
            <h3 class="font-semibold">Tambah Ruang Ujian</h3>
            <button onclick="document.getElementById('modal-add').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-xl">✕</button>
        </div>
        <form method="POST" action="{{ route('guru.ruang-ujian.store') }}" class="p-5 space-y-4">
            @csrf
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Ruang Ujian <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Token <span class="text-gray-400 font-normal text-xs">(kosong = auto)</span></label>
                    <input type="text" name="token" maxlength="20" placeholder="Auto generate" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none uppercase">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bank Soal <span class="text-red-500">*</span></label>
                <select name="bank_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">-- Pilih Bank Soal --</option>
                    @foreach($banks as $bank)
                    <option value="{{ $bank->id }}">{{ $bank->title }} ({{ $bank->mapel?->name ?? '-' }})</option>
                    @endforeach
                </select>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Login Limit</label>
                    <input type="number" name="login_limit" value="3" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Min. Waktu Submit (menit)</label>
                    <input type="number" name="min_time_submit" value="0" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kelas yang Diizinkan <span class="text-gray-400 font-normal text-xs">(kosong = semua)</span></label>
                <div class="grid grid-cols-3 sm:grid-cols-4 gap-2 border border-gray-200 rounded-lg p-3">
                    @foreach($kelas as $k)
                    <label class="flex items-center gap-2 text-sm cursor-pointer">
                        <input type="checkbox" name="classes[]" value="{{ $k->id }}" class="rounded"> {{ $k->name }}
                    </label>
                    @endforeach
                </div>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Mulai <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="start_at" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Selesai <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="end_at" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none">
                </div>
            </div>
            <div class="flex gap-6">
                <label class="flex items-center gap-2 text-sm cursor-pointer">
                    <input type="checkbox" name="random_soal" value="1" class="rounded"> Acak Urutan Soal
                </label>
                <label class="flex items-center gap-2 text-sm cursor-pointer">
                    <input type="checkbox" name="random_ops" value="1" class="rounded"> Acak Opsi Jawaban
                </label>
            </div>
            <div class="flex gap-2 justify-end pt-2">
                <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')" class="px-4 py-2 text-sm bg-gray-100 rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="modal-edit" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-xl max-h-[90vh] overflow-y-auto">
        <div class="p-5 border-b border-gray-100 flex justify-between sticky top-0 bg-white">
            <h3 class="font-semibold">Edit Ruang Ujian</h3>
            <button onclick="document.getElementById('modal-edit').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-xl">✕</button>
        </div>
        <form method="POST" id="edit-form" class="p-5 space-y-4">
            @csrf @method('PUT')
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Ruang Ujian <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="edit-name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Token</label>
                    <input type="text" name="token" id="edit-token" maxlength="20" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none uppercase">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bank Soal <span class="text-red-500">*</span></label>
                <select name="bank_id" id="edit-bank" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">-- Pilih Bank Soal --</option>
                    @foreach($banks as $bank)
                    <option value="{{ $bank->id }}">{{ $bank->title }} ({{ $bank->mapel?->name ?? '-' }})</option>
                    @endforeach
                </select>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Login Limit</label>
                    <input type="number" name="login_limit" id="edit-login-limit" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Min. Waktu Submit (menit)</label>
                    <input type="number" name="min_time_submit" id="edit-min-time" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kelas yang Diizinkan</label>
                <div class="grid grid-cols-3 sm:grid-cols-4 gap-2 border border-gray-200 rounded-lg p-3">
                    @foreach($kelas as $k)
                    <label class="flex items-center gap-2 text-sm cursor-pointer">
                        <input type="checkbox" name="classes[]" value="{{ $k->id }}" class="edit-kelas-check rounded" data-id="{{ $k->id }}"> {{ $k->name }}
                    </label>
                    @endforeach
                </div>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Mulai <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="start_at" id="edit-start-at" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Selesai <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="end_at" id="edit-end-at" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm outline-none">
                </div>
            </div>
            <div class="flex gap-6">
                <label class="flex items-center gap-2 text-sm cursor-pointer">
                    <input type="checkbox" name="random_soal" value="1" id="edit-random-soal" class="rounded"> Acak Urutan Soal
                </label>
                <label class="flex items-center gap-2 text-sm cursor-pointer">
                    <input type="checkbox" name="random_ops" value="1" id="edit-random-ops" class="rounded"> Acak Opsi Jawaban
                </label>
            </div>
            <div class="flex gap-2 justify-end pt-2">
                <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openEdit(id, name, token, bankId, loginLimit, minTime, classes, startAt, endAt, randomSoal, randomOps) {
    document.getElementById('edit-name').value = name;
    document.getElementById('edit-token').value = token;
    document.getElementById('edit-bank').value = bankId;
    document.getElementById('edit-login-limit').value = loginLimit;
    document.getElementById('edit-min-time').value = minTime;
    document.getElementById('edit-start-at').value = startAt;
    document.getElementById('edit-end-at').value = endAt;
    document.getElementById('edit-random-soal').checked = randomSoal;
    document.getElementById('edit-random-ops').checked = randomOps;
    document.getElementById('edit-form').action = '/guru/ruang-ujian/' + id;
    document.querySelectorAll('.edit-kelas-check').forEach(cb => {
        cb.checked = classes.includes(parseInt(cb.dataset.id));
    });
    document.getElementById('modal-edit').classList.remove('hidden');
}
</script>
@endsection
