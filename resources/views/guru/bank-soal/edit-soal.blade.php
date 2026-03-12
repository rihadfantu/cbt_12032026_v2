<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Soal - {{ $bank->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        .editor-toolbar button { padding: 4px 8px; border-radius: 4px; font-size: 12px; }
        .editor-toolbar button:hover { background: #e5e7eb; }
        [contenteditable] { min-height: 60px; outline: none; }
        [contenteditable]:focus { border-color: #3b82f6; }
    </style>
</head>
<body class="bg-gray-100">
<div class="max-w-7xl mx-auto p-4">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('guru.bank-soal.index') }}" class="text-gray-500 hover:text-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="font-semibold text-gray-800">{{ $bank->title }}</h1>
                <p class="text-xs text-gray-500">{{ $bank->mapel->name ?? '' }}</p>
            </div>
        </div>
        <button onclick="saveAll()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium" id="save-btn">Simpan Semua</button>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-4">
        <div class="flex border-b border-gray-100">
            <button onclick="switchTab('manual')" id="tab-manual" class="px-5 py-3 text-sm font-medium border-b-2 border-blue-600 text-blue-600">Manual</button>
            <button onclick="switchTab('import-excel')" id="tab-import-excel" class="px-5 py-3 text-sm font-medium text-gray-500 hover:text-gray-700">Import Excel</button>
        </div>

        <!-- Manual Tab -->
        <div id="content-manual" class="p-4">
            <div class="mb-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Navigasi Soal (<span id="total-soal">{{ count($bank->soals ?? []) }}</span> soal)</span>
                    <button onclick="addSoal()" class="bg-green-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium">+ Tambah Soal</button>
                </div>
                <div id="soal-grid" class="flex flex-wrap gap-2"></div>
            </div>
            <!-- Soal Editor -->
            <div id="soal-editor" class="hidden">
                <div class="border border-gray-200 rounded-xl p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-medium text-gray-800" id="editor-title">Soal #1</h3>
                        <div class="flex gap-2">
                            <select id="soal-tipe" class="text-xs border border-gray-300 rounded px-2 py-1" onchange="updateTipe()">
                                <option value="pg">Pilihan Ganda</option>
                                <option value="essay">Essay</option>
                                <option value="bs">Benar/Salah</option>
                            </select>
                            <button onclick="deleteSoal()" class="text-red-500 hover:text-red-700 text-xs font-medium bg-red-50 px-3 py-1 rounded">Hapus</button>
                        </div>
                    </div>
                    <!-- Toolbar -->
                    <div class="editor-toolbar flex flex-wrap gap-1 mb-2 p-2 bg-gray-50 rounded-lg">
                        <button type="button" onclick="fmt('bold')" class="font-bold">B</button>
                        <button type="button" onclick="fmt('italic')" class="italic">I</button>
                        <button type="button" onclick="fmt('underline')" class="underline">U</button>
                        <button type="button" onclick="fmt('strikeThrough')" class="line-through">S</button>
                        <button type="button" onclick="fmt('superscript')">x²</button>
                        <button type="button" onclick="fmt('subscript')">x₂</button>
                        <button type="button" onclick="insertImg()">🖼</button>
                    </div>
                    <div id="soal-text" contenteditable="true" class="border border-gray-200 rounded-lg p-3 text-sm mb-4 focus:border-blue-500 transition-colors" placeholder="Tulis soal di sini..."></div>

                    <!-- PG Options -->
                    <div id="pg-options">
                        <div class="space-y-2">
                            @foreach(['A','B','C','D','E'] as $opt)
                            <div class="flex items-start gap-2">
                                <label class="flex items-center gap-1 mt-2 text-xs">
                                    <input type="radio" name="kunci-radio" value="{{ $opt }}" class="kunci-radio" onchange="setKunci('{{ $opt }}')">
                                    <span class="font-medium text-gray-700">{{ $opt }}</span>
                                </label>
                                <div id="opt-{{ $opt }}" contenteditable="true" class="flex-1 border border-gray-200 rounded-lg p-2 text-sm focus:border-blue-500 outline-none min-h-[36px]"></div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- Essay -->
                    <div id="essay-options" class="hidden">
                        <p class="text-xs text-gray-500 italic">Soal essay tidak memiliki pilihan jawaban. Kunci jawaban diisi manual.</p>
                        <div class="mt-2">
                            <label class="text-sm font-medium text-gray-700">Kunci Jawaban:</label>
                            <div id="essay-kunci" contenteditable="true" class="mt-1 border border-gray-200 rounded-lg p-2 text-sm outline-none min-h-[60px]"></div>
                        </div>
                    </div>
                    <!-- BS -->
                    <div id="bs-options" class="hidden">
                        <div class="flex gap-4 mt-2">
                            <label class="flex items-center gap-2 text-sm"><input type="radio" name="kunci-radio" value="Benar" class="kunci-radio" onchange="setKunci('Benar')"> Benar</label>
                            <label class="flex items-center gap-2 text-sm"><input type="radio" name="kunci-radio" value="Salah" class="kunci-radio" onchange="setKunci('Salah')"> Salah</label>
                        </div>
                    </div>
                </div>
            </div>
            <div id="empty-state" class="text-center py-12 text-gray-400">
                <p class="text-lg">Belum ada soal</p>
                <p class="text-sm mt-1">Klik "+ Tambah Soal" untuk mulai</p>
            </div>
        </div>

        <!-- Import Excel Tab -->
        <div id="content-import-excel" class="hidden p-5">
            <form method="POST" action="{{ route('guru.soal.import-excel', $bank->id) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm text-blue-800">
                    <strong>Format Excel:</strong> Kolom A: Soal, B: Opsi A, C: Opsi B, D: Opsi C, E: Opsi D, F: Opsi E, G: Kunci (A/B/C/D/E)
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload File .xlsx</label>
                    <input type="file" name="file" accept=".xlsx,.xls" required class="w-full text-sm border border-gray-300 rounded-lg p-2">
                </div>
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm font-medium">Import</button>
            </form>
        </div>
    </div>

    <!-- Toast -->
    <div id="toast" class="hidden fixed top-4 right-4 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg text-sm z-50"></div>
</div>

<script>
let soals = @json($bank->soals ?? []);
let currentIdx = null;

function renderGrid() {
    const grid = document.getElementById('soal-grid');
    grid.innerHTML = '';
    soals.forEach((s, i) => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.textContent = i + 1;
        btn.className = 'w-9 h-9 rounded-lg text-sm font-medium border transition-colors ' + (i === currentIdx ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-200 hover:border-blue-400');
        btn.onclick = () => selectSoal(i);
        grid.appendChild(btn);
    });
    document.getElementById('total-soal').textContent = soals.length;
    const empty = document.getElementById('empty-state');
    const editor = document.getElementById('soal-editor');
    if (soals.length === 0) { empty.classList.remove('hidden'); editor.classList.add('hidden'); }
    else { empty.classList.add('hidden'); if (currentIdx !== null) editor.classList.remove('hidden'); }
}

function selectSoal(idx) {
    currentIdx = idx;
    const s = soals[idx];
    document.getElementById('editor-title').textContent = 'Soal #' + (idx + 1);
    document.getElementById('soal-tipe').value = s.tipe || 'pg';
    document.getElementById('soal-text').innerHTML = s.soal || '';
    ['A','B','C','D','E'].forEach(k => { const el = document.getElementById('opt-' + k); if(el) el.innerHTML = (s.opsi && s.opsi[k]) ? s.opsi[k] : ''; });
    const essKunci = document.getElementById('essay-kunci'); if(essKunci) essKunci.innerHTML = s.kunci || '';
    document.querySelectorAll('.kunci-radio').forEach(r => r.checked = (r.value === s.kunci));
    updateTipe();
    renderGrid();
}

function updateTipe() {
    const tipe = document.getElementById('soal-tipe').value;
    document.getElementById('pg-options').classList.toggle('hidden', tipe !== 'pg');
    document.getElementById('essay-options').classList.toggle('hidden', tipe !== 'essay');
    document.getElementById('bs-options').classList.toggle('hidden', tipe !== 'bs');
    document.getElementById('soal-editor').classList.remove('hidden');
}

function setKunci(val) { if (currentIdx !== null) soals[currentIdx].kunci = val; }

function saveCurrent() {
    if (currentIdx === null) return;
    const tipe = document.getElementById('soal-tipe').value;
    const soalText = document.getElementById('soal-text').innerHTML;
    const opsi = {};
    if (tipe === 'pg') {
        ['A','B','C','D','E'].forEach(k => { opsi[k] = document.getElementById('opt-' + k).innerHTML; });
        const checked = document.querySelector('.kunci-radio:checked');
        soals[currentIdx] = { tipe, soal: soalText, opsi, kunci: checked ? checked.value : 'A' };
    } else if (tipe === 'essay') {
        soals[currentIdx] = { tipe, soal: soalText, kunci: document.getElementById('essay-kunci').innerHTML };
    } else {
        const checked = document.querySelector('.kunci-radio:checked');
        soals[currentIdx] = { tipe, soal: soalText, kunci: checked ? checked.value : 'Benar' };
    }
}

function addSoal() {
    if (currentIdx !== null) saveCurrent();
    soals.push({ tipe: 'pg', soal: '', opsi: { A:'', B:'', C:'', D:'', E:'' }, kunci: 'A' });
    selectSoal(soals.length - 1);
}

function deleteSoal() {
    if (currentIdx === null || !confirm('Hapus soal ini?')) return;
    soals.splice(currentIdx, 1);
    currentIdx = soals.length > 0 ? Math.min(currentIdx, soals.length - 1) : null;
    if (currentIdx !== null) selectSoal(currentIdx);
    else renderGrid();
}

async function saveAll() {
    if (currentIdx !== null) saveCurrent();
    const btn = document.getElementById('save-btn');
    btn.textContent = 'Menyimpan...'; btn.disabled = true;
    const res = await fetch('{{ route("guru.soal.save", $bank->id) }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
        body: JSON.stringify({ soals })
    });
    btn.textContent = 'Simpan Semua'; btn.disabled = false;
    if (res.ok) showToast('Soal berhasil disimpan!');
    else showToast('Gagal menyimpan!', 'error');
}

function fmt(cmd) { document.execCommand(cmd, false, null); }
function insertImg() {
    const url = prompt('URL gambar:');
    if (!url) return;
    // Basic URL validation
    if (!url.startsWith('http://') && !url.startsWith('https://') && !url.startsWith('data:image/')) {
        alert('URL tidak valid. Gunakan URL http/https atau data:image base64.');
        return;
    }
    document.execCommand('insertImage', false, url);
}

function showToast(msg, type = 'success') {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.className = 'fixed top-4 right-4 text-white px-4 py-3 rounded-lg shadow-lg text-sm z-50 ' + (type === 'error' ? 'bg-red-500' : 'bg-green-500');
    t.classList.remove('hidden');
    setTimeout(() => t.classList.add('hidden'), 2000);
}

function switchTab(tab) {
    ['manual','import-excel'].forEach(t => {
        document.getElementById('content-' + t).classList.toggle('hidden', t !== tab);
        document.getElementById('tab-' + t).className = 'px-5 py-3 text-sm font-medium ' + (t === tab ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700');
    });
}

// Init
renderGrid();
if (soals.length > 0) selectSoal(0);
</script>
</body>
</html>
