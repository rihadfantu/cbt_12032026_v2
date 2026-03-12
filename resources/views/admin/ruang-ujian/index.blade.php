@extends('layouts.app')
@section('title', 'Ruang Ujian')
@section('page-title', 'Ruang Ujian')
@section('user-name', auth('admin')->user()->name)
@section('user-role', 'Administrator')
@section('role-badge-class', 'bg-gray-800 text-white')
@section('sidebar') @include('layouts.admin-sidebar') @endsection

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
                    $isUpcoming = $now->isBefore($ruang->start_at);
                    $ruangKelas = $ruang->classes ?? [];
                @endphp
                <tr class="hover:bg-blue-50 transition-colors">
                    <td class="px-4 py-3 text-gray-500">{{ ($ruangs->currentPage()-1)*$ruangs->perPage() + $i + 1 }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $ruang->name }}</td>
                    <td class="px-4 py-3">
                        <span class="font-mono bg-gray-100 px-2 py-0.5 rounded text-xs font-semibold tracking-wider">{{ $ruang->token }}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        <div>{{ $ruang->bank?->title ?? '-' }}</div>
                        <div class="text-xs text-gray-400">{{ implode(' • ', array_filter([$ruang->bank?->mapel?->name, $ruang->bank?->guru?->name])) }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-1">
                            @if(count($ruangKelas) > 0)
                                @foreach($kelas as $k)
                                    @if(in_array($k->id, $ruangKelas))
                                    <span class="bg-purple-100 text-purple-700 text-xs px-2 py-0.5 rounded-full">{{ $k->name }}</span>
                                    @endif
                                @endforeach
                            @else
                                <span class="text-gray-400 text-xs italic">Semua</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs">
                        <div>{{ $ruang->start_at->format('d/m/Y H:i') }}</div>
                        <div>s/d {{ $ruang->end_at->format('d/m/Y H:i') }}</div>
                    </td>
                    <td class="px-4 py-3">
                        @if($isActive)
                        <span class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full font-medium">Aktif</span>
                        @elseif($isUpcoming)
                        <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-0.5 rounded-full font-medium">Belum Mulai</span>
                        @else
                        <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full font-medium">Selesai</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right whitespace-nowrap">
                        <a href="{{ route('admin.monitoring.index', $ruang->id) }}"
                           class="text-green-600 hover:text-green-800 text-xs font-medium mr-2">Monitor</a>
                        <button onclick="openEdit({{ $ruang->id }}, @js($ruang->name), @js($ruang->token), {{ $ruang->bank_id }}, {{ $ruang->login_limit }}, {{ $ruang->min_time_submit }}, @js($ruangKelas), '{{ $ruang->start_at->format('Y-m-d\TH:i') }}', '{{ $ruang->end_at->format('Y-m-d\TH:i') }}', {{ $ruang->random_soal ? 'true' : 'false' }}, {{ $ruang->random_ops ? 'true' : 'false' }})"
                                class="text-blue-600 hover:text-blue-800 text-xs font-medium mr-2">Edit</button>
                        <form method="POST" action="{{ route('admin.ruang-ujian.destroy', $ruang) }}" class="inline"
                              onsubmit="return confirm('Hapus ruang ujian ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-gray-400">Belum ada ruang ujian</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($ruangs->hasPages())
    <div class="p-4 border-t border-gray-100 flex justify-between items-center text-sm">
        <span class="text-gray-500">Menampilkan {{ $ruangs->firstItem() }}-{{ $ruangs->lastItem() }} dari {{ $ruangs->total() }}</span>
        <div class="flex gap-1">
            @if($ruangs->onFirstPage()) <span class="px-3 py-1 text-gray-300">‹</span>
            @else <a href="{{ $ruangs->previousPageUrl() }}" class="px-3 py-1 rounded border hover:bg-gray-50">‹</a> @endif
            @if($ruangs->hasMorePages()) <a href="{{ $ruangs->nextPageUrl() }}" class="px-3 py-1 rounded border hover:bg-gray-50">›</a>
            @else <span class="px-3 py-1 text-gray-300">›</span> @endif
        </div>
    </div>
    @endif
</div>

<!-- Add Modal -->
<div id="modal-add" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-5 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-semibold text-gray-800">Tambah Ruang Ujian</h3>
            <button onclick="document.getElementById('modal-add').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-xl">✕</button>
        </div>
        <form method="POST" action="{{ route('admin.ruang-ujian.store') }}" class="p-5 space-y-4">
            @csrf
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Ruang Ujian <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: CBT Matematika MTs"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Token <span class="text-gray-400 font-normal text-xs">(kosong = otomatis)</span></label>
                    <input type="text" name="token" placeholder="Contoh: MATH99" maxlength="20"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none uppercase">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bank Soal <span class="text-red-500">*</span></label>
                <select name="bank_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">-- Pilih Bank Soal --</option>
                    @foreach($banks as $bank)
                    <option value="{{ $bank->id }}">{{ $bank->title }} ({{ $bank->mapel?->name ?? '-' }} | {{ $bank->guru?->name ?? '-' }})</option>
                    @endforeach
                </select>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Login Limit</label>
                    <input type="number" name="login_limit" value="3" min="1" max="99"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    <p class="text-xs text-gray-400 mt-1">Maks. berapa kali siswa bisa login ulang</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Min. Waktu Submit (menit)</label>
                    <input type="number" name="min_time_submit" value="0" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    <p class="text-xs text-gray-400 mt-1">0 = bisa submit kapan saja</p>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kelas yang Diizinkan <span class="text-gray-400 font-normal text-xs">(kosong = semua kelas)</span></label>
                <div class="grid grid-cols-3 sm:grid-cols-4 gap-2 border border-gray-200 rounded-lg p-3">
                    @foreach($kelas as $k)
                    <label class="flex items-center gap-2 text-sm cursor-pointer">
                        <input type="checkbox" name="classes[]" value="{{ $k->id }}" class="rounded">
                        {{ $k->name }}
                    </label>
                    @endforeach
                </div>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Mulai <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="start_at" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Selesai <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="end_at" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>
            <div class="flex gap-6">
                <label class="flex items-center gap-2 text-sm cursor-pointer">
                    <input type="checkbox" name="random_soal" value="1" class="rounded">
                    Acak Urutan Soal
                </label>
                <label class="flex items-center gap-2 text-sm cursor-pointer">
                    <input type="checkbox" name="random_ops" value="1" class="rounded">
                    Acak Opsi Jawaban
                </label>
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
            <h3 class="font-semibold text-gray-800">Edit Ruang Ujian</h3>
            <button onclick="document.getElementById('modal-edit').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-xl">✕</button>
        </div>
        <form method="POST" id="edit-form" class="p-5 space-y-4">
            @csrf @method('PUT')
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Ruang Ujian <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="edit-name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Token</label>
                    <input type="text" name="token" id="edit-token" maxlength="20"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none uppercase">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bank Soal <span class="text-red-500">*</span></label>
                <select name="bank_id" id="edit-bank" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">-- Pilih Bank Soal --</option>
                    @foreach($banks as $bank)
                    <option value="{{ $bank->id }}">{{ $bank->title }} ({{ $bank->mapel?->name ?? '-' }} | {{ $bank->guru?->name ?? '-' }})</option>
                    @endforeach
                </select>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Login Limit</label>
                    <input type="number" name="login_limit" id="edit-login-limit" min="1" max="99"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Min. Waktu Submit (menit)</label>
                    <input type="number" name="min_time_submit" id="edit-min-time" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kelas yang Diizinkan <span class="text-gray-400 font-normal text-xs">(kosong = semua kelas)</span></label>
                <div class="grid grid-cols-3 sm:grid-cols-4 gap-2 border border-gray-200 rounded-lg p-3">
                    @foreach($kelas as $k)
                    <label class="flex items-center gap-2 text-sm cursor-pointer">
                        <input type="checkbox" name="classes[]" value="{{ $k->id }}" class="edit-kelas-check rounded" data-id="{{ $k->id }}">
                        {{ $k->name }}
                    </label>
                    @endforeach
                </div>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Mulai <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="start_at" id="edit-start-at" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Selesai <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="end_at" id="edit-end-at" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>
            <div class="flex gap-6">
                <label class="flex items-center gap-2 text-sm cursor-pointer">
                    <input type="checkbox" name="random_soal" value="1" id="edit-random-soal" class="rounded">
                    Acak Urutan Soal
                </label>
                <label class="flex items-center gap-2 text-sm cursor-pointer">
                    <input type="checkbox" name="random_ops" value="1" id="edit-random-ops" class="rounded">
                    Acak Opsi Jawaban
                </label>
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
    document.getElementById('edit-form').action = '/admin/ruang-ujian/' + id;
    document.querySelectorAll('.edit-kelas-check').forEach(cb => {
        cb.checked = classes.includes(parseInt(cb.dataset.id));
    });
    document.getElementById('modal-edit').classList.remove('hidden');
}
</script>
@endsection
