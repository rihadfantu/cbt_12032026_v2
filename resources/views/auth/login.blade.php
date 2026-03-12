<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CBT MTsN 1 Mesuji</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
    </style>
</head>
<body class="bg-blue-700 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl flex overflow-hidden min-h-[500px]">
        <!-- Left Panel -->
        <div class="hidden md:flex flex-col justify-center items-center bg-gradient-to-b from-blue-700 to-blue-900 text-white p-10 w-1/2">
            <img src="https://e-learning.mtsn1mesuji.sch.id/__statics/img/logo.png" alt="Logo MTsN 1 Mesuji" class="w-24 h-24 rounded-full mb-6 border-4 border-white shadow-lg object-cover" onerror="this.style.display='none'">
            <h1 class="text-2xl font-bold text-center mb-2">CBT MTsN 1 Mesuji</h1>
            <p class="text-blue-200 text-center text-sm">Computer Based Test</p>
            <p class="text-blue-200 text-center text-sm mt-1">Madrasah Tsanawiyah Negeri 1 Mesuji</p>
            <div class="mt-8 space-y-2 text-sm text-blue-200">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Ujian Online Terintegrasi
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Auto-save Jawaban Otomatis
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Laporan Nilai Real-time
                </div>
            </div>
        </div>

        <!-- Right Panel: Login Form -->
        <div class="flex flex-col justify-center p-8 w-full md:w-1/2">
            <div class="md:hidden flex justify-center mb-6">
                <img src="https://e-learning.mtsn1mesuji.sch.id/__statics/img/logo.png" alt="Logo" class="w-16 h-16 rounded-full object-cover" onerror="this.style.display='none'">
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-1 text-center">Selamat Datang</h2>
            <p class="text-gray-500 text-sm mb-6 text-center">Silakan masuk ke akun Anda</p>

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 mb-4 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Tab Selector -->
            <div class="flex bg-gray-100 rounded-lg p-1 mb-6">
                <button type="button" onclick="switchTab('siswa')" id="tab-siswa" class="flex-1 py-2 text-sm rounded-md font-medium transition-all tab-btn active-tab">Siswa</button>
                <button type="button" onclick="switchTab('guru')" id="tab-guru" class="flex-1 py-2 text-sm rounded-md font-medium transition-all tab-btn">Guru</button>
                <button type="button" onclick="switchTab('admin')" id="tab-admin" class="flex-1 py-2 text-sm rounded-md font-medium transition-all tab-btn">Admin</button>
            </div>

            <!-- Siswa Form -->
            <div id="form-siswa">
                <!-- Login Mode Toggle -->
                <div class="flex bg-gray-100 rounded-lg p-1 mb-4">
                    <button type="button" onclick="switchLoginMode('biasa')" id="mode-biasa"
                            class="flex-1 py-1.5 text-xs rounded-md font-medium transition-all mode-btn active-mode">Login Biasa</button>
                    <button type="button" onclick="switchLoginMode('token')" id="mode-token"
                            class="flex-1 py-1.5 text-xs rounded-md font-medium transition-all mode-btn">Login + Token</button>
                </div>
                <form method="POST" action="{{ route('siswa.login') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">NISN</label>
                        <input type="text" name="nisn" required placeholder="Masukkan NISN"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" required placeholder="Masukkan Password"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm">
                    </div>
                    <div id="token-field" class="mb-4 hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Token Ujian</label>
                        <input type="text" name="token" id="token-input" placeholder="Token dari pengawas"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm uppercase">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-medium transition-colors text-sm">
                        Masuk sebagai Siswa
                    </button>
                </form>
            </div>

            <!-- Guru Form -->
            <div id="form-guru" class="hidden">
                <form method="POST" action="{{ route('guru.login') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                        <input type="text" name="nik" required placeholder="Masukkan NIK"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" required placeholder="Masukkan Password"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm">
                    </div>
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-lg font-medium transition-colors text-sm">
                        Masuk sebagai Guru
                    </button>
                </form>
            </div>

            <!-- Admin Form -->
            <div id="form-admin" class="hidden">
                <form method="POST" action="{{ route('admin.login') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" required placeholder="Masukkan Email"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" required placeholder="Masukkan Password"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm">
                    </div>
                    <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white py-2.5 rounded-lg font-medium transition-colors text-sm">
                        Masuk sebagai Admin
                    </button>
                </form>
            </div>
        </div>
    </div>

    <style>
        .active-tab { background-color: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); color: #1d4ed8; }
        .tab-btn { color: #6b7280; }
        .active-mode { background-color: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); color: #1d4ed8; }
        .mode-btn { color: #6b7280; }
    </style>
    <script>
        function switchTab(tab) {
            document.getElementById('form-siswa').classList.add('hidden');
            document.getElementById('form-guru').classList.add('hidden');
            document.getElementById('form-admin').classList.add('hidden');
            document.getElementById('tab-siswa').classList.remove('active-tab');
            document.getElementById('tab-guru').classList.remove('active-tab');
            document.getElementById('tab-admin').classList.remove('active-tab');
            document.getElementById('form-' + tab).classList.remove('hidden');
            document.getElementById('tab-' + tab).classList.add('active-tab');
        }
        function switchLoginMode(mode) {
            const tokenField = document.getElementById('token-field');
            const tokenInput = document.getElementById('token-input');
            document.getElementById('mode-biasa').classList.remove('active-mode');
            document.getElementById('mode-token').classList.remove('active-mode');
            document.getElementById('mode-' + mode).classList.add('active-mode');
            if (mode === 'token') {
                tokenField.classList.remove('hidden');
            } else {
                tokenField.classList.add('hidden');
                tokenInput.value = '';
            }
        }
    </script>
</body>
</html>
