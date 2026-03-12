<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $ruang->name }} - CBT MTsN 1 Mesuji</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        .nav-btn { width: 36px; height: 36px; border-radius: 8px; font-size: 13px; font-weight: 600; border: 1px solid #e5e7eb; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.15s; }
        .nav-btn.answered { background: #16a34a; color: white; border-color: #16a34a; }
        .nav-btn.current { border: 2px solid #2563eb; }
        .nav-btn:not(.answered):not(.current) { background: white; color: #374151; }
        .nav-btn:hover:not(.answered) { background: #eff6ff; border-color: #2563eb; }
        #timer.warning { animation: pulse 1s infinite; color: #ef4444; }
        @keyframes pulse { 0%,100% { opacity: 1; } 50% { opacity: 0.5; } }
        [contenteditable=false] img { max-width: 100%; height: auto; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

<!-- Top Bar -->
<header class="bg-white shadow-sm sticky top-0 z-30 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
        <div class="flex items-center gap-3 min-w-0">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="min-w-0">
                <h1 class="font-semibold text-gray-800 text-sm truncate">{{ $ruang->name }}</h1>
                <p class="text-xs text-gray-500">{{ auth('siswa')->user()->name }}</p>
            </div>
        </div>

        <!-- Timer -->
        <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span id="timer" class="font-mono font-bold text-gray-800 text-lg">00:00:00</span>
        </div>

        <button onclick="showSubmitModal()" id="submit-btn"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium flex items-center gap-2 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="hidden sm:inline">Selesai</span>
        </button>
    </div>
    <div class="max-w-7xl mx-auto px-4 pb-1 text-right">
        <span id="save-indicator" class="text-xs text-gray-400"></span>
    </div>
</header>

<div class="max-w-7xl mx-auto px-4 py-4 flex gap-4">

    <!-- Main Question Area -->
    <div class="flex-1 min-w-0">
        <div id="question-container" class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 md:p-6">
            <!-- Question will be rendered here -->
        </div>

        <!-- Navigation Buttons -->
        <div class="flex gap-3 mt-4 justify-between">
            <button onclick="prevQuestion()" id="btn-prev"
                    class="flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 hover:border-blue-400 rounded-lg text-sm text-gray-700 font-medium transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Sebelumnya
            </button>
            <span class="self-center text-sm text-gray-500">
                <span id="current-num">1</span> / <span id="total-num">{{ count($soals) }}</span>
            </span>
            <button onclick="nextQuestion()" id="btn-next"
                    class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
                Selanjutnya
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </div>
    </div>

    <!-- Sidebar: Question Navigator -->
    <div class="hidden md:block w-64 flex-shrink-0">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sticky top-[72px]">
            <h3 class="font-semibold text-gray-700 text-sm mb-3">Navigasi Soal</h3>
            <div id="nav-grid" class="flex flex-wrap gap-1.5 mb-4">
                <!-- buttons rendered by JS -->
            </div>
            <div class="border-t border-gray-100 pt-3 space-y-1.5">
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <div class="w-5 h-5 rounded bg-green-600"></div> Terjawab
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <div class="w-5 h-5 rounded bg-white border-2 border-blue-600"></div> Saat ini
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <div class="w-5 h-5 rounded bg-white border border-gray-200"></div> Belum dijawab
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <p class="text-xs text-gray-500">Terjawab: <span id="answered-count" class="font-semibold text-green-600">0</span> / {{ count($soals) }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Navigator (bottom) -->
<div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-3 z-20">
    <div class="overflow-x-auto">
        <div id="nav-grid-mobile" class="flex gap-1.5 pb-1">
            <!-- rendered by JS -->
        </div>
    </div>
</div>

<!-- Submit Confirmation Modal -->
<div id="submit-modal" class="hidden fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Submit</h3>
        </div>
        <div id="submit-warning" class="hidden mb-4 bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-700"></div>
        <p class="text-gray-600 text-sm mb-2">
            Anda akan mengakhiri ujian. Pastikan semua soal telah dijawab.
        </p>
        <div class="bg-gray-50 rounded-lg p-3 mb-4 text-sm">
            <div class="flex justify-between"><span class="text-gray-500">Terjawab:</span> <span class="font-semibold text-green-600" id="modal-answered">0</span></div>
            <div class="flex justify-between mt-1"><span class="text-gray-500">Belum dijawab:</span> <span class="font-semibold text-red-500" id="modal-unanswered">0</span></div>
            <div class="flex justify-between mt-1"><span class="text-gray-500">Total soal:</span> <span class="font-semibold">{{ count($soals) }}</span></div>
        </div>
        <div class="flex gap-3 justify-end">
            <button onclick="closeSubmitModal()" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium">Batal</button>
            <button onclick="doSubmit()" id="confirm-submit-btn"
                    class="px-5 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Ya, Submit
            </button>
        </div>
    </div>
</div>

<!-- Result Modal -->
<div id="result-modal" class="hidden fixed inset-0 bg-black bg-opacity-70 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-8 text-center">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-1">Ujian Selesai!</h2>
        <p class="text-gray-500 text-sm mb-6">Jawaban Anda berhasil disimpan</p>
        <div class="bg-blue-50 rounded-xl p-5 mb-6">
            <p class="text-sm text-gray-500 mb-1">Nilai Anda</p>
            <p class="text-5xl font-bold text-blue-600" id="result-nilai">0</p>
        </div>
        <div class="grid grid-cols-2 gap-3 mb-6 text-sm">
            <div class="bg-green-50 rounded-lg p-3">
                <p class="text-gray-500 text-xs">Benar</p>
                <p class="text-xl font-bold text-green-600" id="result-benar">0</p>
            </div>
            <div class="bg-red-50 rounded-lg p-3">
                <p class="text-gray-500 text-xs">Salah/Kosong</p>
                <p class="text-xl font-bold text-red-500" id="result-salah">0</p>
            </div>
        </div>
        <a href="{{ route('siswa.ujian') }}" class="block w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
            Kembali ke Daftar Ujian
        </a>
    </div>
</div>

<script>
// Data from PHP
const soals = @json($soals);
const hasilId = {{ $hasil->id }};
const ruangId = {{ $ruang->id }};
const csrfToken = document.querySelector('meta[name=csrf-token]').content;
let remainingSeconds = {{ $hasil->sisa_waktu ?? ($bank->timer * 60) }};
const minTimeSubmit = {{ $ruang->min_time_submit ?? 0 }} * 60;
const totalSoals = soals.length;

// State
let currentIdx = 0;
let answers = @json($hasil->answers ?? []);
let timerInterval = null;
let autoSubmitted = false;

// ==================
// Timer
// ==================
function formatTime(secs) {
    const h = Math.floor(secs / 3600);
    const m = Math.floor((secs % 3600) / 60);
    const s = secs % 60;
    return (h > 0 ? String(h).padStart(2,'0') + ':' : '') + String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
}

function startTimer() {
    const timerEl = document.getElementById('timer');
    timerInterval = setInterval(() => {
        remainingSeconds--;
        timerEl.textContent = formatTime(remainingSeconds);
        if (remainingSeconds <= 300) timerEl.classList.add('warning');
        else timerEl.classList.remove('warning');

        // Auto-save timer every 10s
        if (remainingSeconds % 10 === 0) {
            saveCurrentAnswer(true);
        }

        if (remainingSeconds <= 0) {
            clearInterval(timerInterval);
            autoSubmit();
        }
    }, 1000);
    timerEl.textContent = formatTime(remainingSeconds);
}

function autoSubmit() {
    if (autoSubmitted) return;
    autoSubmitted = true;
    document.getElementById('timer').textContent = '00:00';
    submitUjian(true);
}

// ==================
// Render Question
// ==================
function renderQuestion(idx) {
    const s = soals[idx];
    const tipe = s.tipe || 'pg';
    const soalHtml = s.soal || '';
    const currentAnswer = answers[idx] !== undefined ? answers[idx] : null;

    let optionsHtml = '';
    if (tipe === 'pg') {
        const opsi = s.opsi || {};
        optionsHtml = '<div class="space-y-2.5 mt-4">';
        ['A','B','C','D','E'].forEach(k => {
            if (!opsi[k] && opsi[k] !== '0') return;
            const checked = currentAnswer === k ? 'checked' : '';
            optionsHtml += `
            <label class="flex items-start gap-3 p-3.5 border rounded-xl cursor-pointer transition-all hover:bg-blue-50 hover:border-blue-300 ${currentAnswer === k ? 'bg-blue-50 border-blue-400' : 'border-gray-200'}">
                <input type="radio" name="answer" value="${k}" ${checked} onchange="selectAnswer('${k}')"
                       class="mt-0.5 accent-blue-600 flex-shrink-0">
                <div class="flex items-start gap-2 flex-1">
                    <span class="font-bold text-gray-700 w-5 flex-shrink-0">${k}.</span>
                    <div class="text-sm text-gray-700">${opsi[k]}</div>
                </div>
            </label>`;
        });
        optionsHtml += '</div>';
    } else if (tipe === 'essay') {
        const val = currentAnswer || '';
        optionsHtml = `
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Jawaban Anda:</label>
            <textarea rows="6" oninput="selectAnswer(this.value)"
                      class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm resize-y focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none"
                      placeholder="Tulis jawaban Anda di sini...">${escapeHtml(val)}</textarea>
        </div>`;
    } else if (tipe === 'bs') {
        const checkedB = currentAnswer === 'Benar' ? 'checked' : '';
        const checkedS = currentAnswer === 'Salah' ? 'checked' : '';
        optionsHtml = `
        <div class="flex gap-4 mt-4">
            <label class="flex items-center gap-2 p-4 border rounded-xl cursor-pointer flex-1 justify-center transition-all hover:bg-blue-50 ${currentAnswer === 'Benar' ? 'bg-blue-50 border-blue-400' : 'border-gray-200'}">
                <input type="radio" name="answer" value="Benar" ${checkedB} onchange="selectAnswer('Benar')" class="accent-blue-600">
                <span class="font-medium text-green-700">Benar</span>
            </label>
            <label class="flex items-center gap-2 p-4 border rounded-xl cursor-pointer flex-1 justify-center transition-all hover:bg-blue-50 ${currentAnswer === 'Salah' ? 'bg-red-50 border-red-400' : 'border-gray-200'}">
                <input type="radio" name="answer" value="Salah" ${checkedS} onchange="selectAnswer('Salah')" class="accent-red-500">
                <span class="font-medium text-red-600">Salah</span>
            </label>
        </div>`;
    }

    document.getElementById('question-container').innerHTML = `
        <div class="flex items-center gap-2 mb-4">
            <span class="bg-blue-600 text-white text-xs font-bold px-2.5 py-1 rounded-lg">${idx + 1}</span>
            <span class="text-xs text-gray-400 font-medium uppercase tracking-wide">${tipe === 'pg' ? 'Pilihan Ganda' : tipe === 'essay' ? 'Essay' : 'Benar/Salah'}</span>
        </div>
        <div class="text-gray-800 text-sm leading-relaxed">${soalHtml}</div>
        ${optionsHtml}
    `;

    document.getElementById('current-num').textContent = idx + 1;
    document.getElementById('btn-prev').disabled = idx === 0;
    document.getElementById('btn-prev').classList.toggle('opacity-50', idx === 0);
    document.getElementById('btn-next').textContent = idx === totalSoals - 1 ? 'Selesai ✓' : 'Selanjutnya →';

    updateNavGrid();
}

function escapeHtml(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ==================
// Navigation
// ==================
function updateNavGrid() {
    const answeredCount = Object.keys(answers).filter(k => answers[k] !== null && answers[k] !== '').length;
    document.getElementById('answered-count').textContent = answeredCount;

    ['nav-grid','nav-grid-mobile'].forEach(id => {
        const container = document.getElementById(id);
        if (!container) return;
        container.innerHTML = '';
        soals.forEach((_, i) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.textContent = i + 1;
            btn.className = 'nav-btn' +
                (i === currentIdx ? ' current' : '') +
                (answers[i] !== undefined && answers[i] !== null && answers[i] !== '' ? ' answered' : '');
            btn.onclick = () => gotoQuestion(i);
            container.appendChild(btn);
        });
    });
}

function gotoQuestion(idx) {
    currentIdx = idx;
    renderQuestion(currentIdx);
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function prevQuestion() {
    if (currentIdx > 0) gotoQuestion(currentIdx - 1);
}

function nextQuestion() {
    if (currentIdx < totalSoals - 1) gotoQuestion(currentIdx + 1);
    else showSubmitModal();
}

// ==================
// Auto-save Answer
// ==================
function selectAnswer(value) {
    answers[currentIdx] = value;
    updateNavGrid();
    saveCurrentAnswer();
}

let saveTimeout = null;
function saveCurrentAnswer(silent = false) {
    const jawaban = answers[currentIdx];
    clearTimeout(saveTimeout);
    saveTimeout = setTimeout(() => {
        fetch('/siswa/ujian/' + ruangId + '/save-answer', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ soal_index: currentIdx, jawaban: jawaban, sisa_waktu: remainingSeconds })
        }).catch(() => {
            // Show subtle warning on auto-save failure
            const ind = document.getElementById('save-indicator');
            if (ind) { ind.textContent = '⚠ Gagal simpan'; ind.className = 'text-xs text-red-500'; }
        }).then(res => {
            if (res && res.ok) {
                const ind = document.getElementById('save-indicator');
                if (ind) { ind.textContent = '✓ Tersimpan'; ind.className = 'text-xs text-green-500'; }
            }
        });
    }, 300);
}

// ==================
// Submit
// ==================
function showSubmitModal() {
    const answeredCount = Object.keys(answers).filter(k => answers[k] !== null && answers[k] !== '').length;
    const unansweredCount = totalSoals - answeredCount;
    document.getElementById('modal-answered').textContent = answeredCount;
    document.getElementById('modal-unanswered').textContent = unansweredCount;

    const warning = document.getElementById('submit-warning');
    const elapsedSeconds = ({{ $bank->timer * 60 }} - remainingSeconds);
    if (minTimeSubmit > 0 && elapsedSeconds < minTimeSubmit) {
        const remaining = Math.ceil((minTimeSubmit - elapsedSeconds) / 60);
        warning.textContent = `Anda harus mengerjakan minimal ${Math.ceil(minTimeSubmit/60)} menit. Tunggu ${remaining} menit lagi.`;
        warning.classList.remove('hidden');
        document.getElementById('confirm-submit-btn').disabled = true;
        document.getElementById('confirm-submit-btn').classList.add('opacity-50');
    } else {
        warning.classList.add('hidden');
        document.getElementById('confirm-submit-btn').disabled = false;
        document.getElementById('confirm-submit-btn').classList.remove('opacity-50');
    }

    document.getElementById('submit-modal').classList.remove('hidden');
}

function closeSubmitModal() {
    document.getElementById('submit-modal').classList.add('hidden');
}

function doSubmit() {
    submitUjian(false);
}

async function submitUjian(auto = false) {
    clearInterval(timerInterval);
    document.getElementById('submit-modal').classList.add('hidden');

    const btn = document.getElementById('confirm-submit-btn');
    if (btn) { btn.disabled = true; btn.textContent = 'Menyimpan...'; }
    document.getElementById('submit-btn').disabled = true;

    try {
        const res = await fetch('/siswa/ujian/' + ruangId + '/submit', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ sisa_waktu: remainingSeconds, answers })
        });
        const data = await res.json();
        if (data.success || data.nilai !== undefined) {
            document.getElementById('result-nilai').textContent = data.nilai ?? 0;
            document.getElementById('result-benar').textContent = data.benar ?? 0;
            document.getElementById('result-salah').textContent = data.salah ?? 0;
            document.getElementById('result-modal').classList.remove('hidden');
        }
    } catch(e) {
        if (!auto) alert('Gagal submit. Coba lagi.');
    }
}

// ==================
// Init
// ==================
startTimer();
renderQuestion(0);
</script>
</body>
</html>
