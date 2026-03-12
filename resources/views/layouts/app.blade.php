<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CBT MTsN 1 Mesuji')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        .sidebar-link { display: flex; align-items: center; gap: 10px; padding: 10px 16px; border-radius: 8px; color: #9ca3af; text-decoration: none; transition: all 0.2s; font-size: 14px; }
        .sidebar-link:hover { background-color: #1f2937; color: white; }
        .sidebar-link.active { background-color: #1f2937; color: #22c55e; border-left: 3px solid #22c55e; }
        .sidebar-link svg { width: 18px; height: 18px; flex-shrink: 0; }
        #sidebar { transition: transform 0.3s ease; }
        @media (max-width: 768px) { #sidebar { transform: translateX(-100%); position: fixed; z-index: 50; height: 100vh; } #sidebar.open { transform: translateX(0); } }
    </style>
</head>
<body class="bg-gray-100 flex">

    <!-- Sidebar Overlay (mobile) -->
    <div id="sidebar-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="w-64 bg-gray-900 min-h-screen flex flex-col fixed md:static top-0 left-0">
        <!-- Logo -->
        <div class="p-5 border-b border-gray-800">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-green-500 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <div class="text-white font-bold text-sm">CBT MTsN 1</div>
                    <div class="text-green-400 text-xs">Mesuji</div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 p-3 overflow-y-auto space-y-1">
            @yield('sidebar')
        </nav>

        <!-- Logout -->
        <div class="p-3 border-t border-gray-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-h-screen md:ml-0">
        <!-- Header -->
        <header class="bg-white shadow-sm px-4 md:px-6 py-3 flex items-center justify-between sticky top-0 z-30">
            <div class="flex items-center gap-3">
                <!-- Mobile menu button -->
                <button onclick="toggleSidebar()" class="md:hidden p-2 rounded-lg hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <h1 class="font-semibold text-gray-800 text-base md:text-lg">@yield('page-title', 'Dashboard')</h1>
            </div>
            <div class="flex items-center gap-2 md:gap-3">
                <div class="text-right hidden sm:block">
                    <div class="text-sm font-medium text-gray-800">@yield('user-name')</div>
                    <div class="text-xs text-gray-500">@yield('user-role')</div>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium @yield('role-badge-class', 'bg-blue-100 text-blue-800')">
                    @yield('user-role')
                </span>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-4 md:p-6">
            <!-- Flash Messages -->
            @if(session('success'))
                <div id="flash-success" class="mb-4 flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div id="flash-error" class="mb-4 flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    {{ session('error') }}
                </div>
            @endif
            @if(session('info'))
                <div id="flash-info" class="mb-4 flex items-center gap-2 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg text-sm">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('info') }}
                </div>
            @endif
            @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <!-- Global Confirm Dialog -->
    <div id="confirm-dialog" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-sm w-full p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2" id="confirm-title">Konfirmasi</h3>
            <p class="text-gray-600 text-sm mb-6" id="confirm-message">Apakah Anda yakin?</p>
            <div class="flex gap-3 justify-end">
                <button onclick="closeConfirm()" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium">Batal</button>
                <button id="confirm-ok" class="px-4 py-2 text-sm text-white bg-red-600 hover:bg-red-700 rounded-lg font-medium">Hapus</button>
            </div>
        </div>
    </div>

    <script>
        // Sidebar toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('hidden');
        }

        // Toast notification
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const colors = { success: 'bg-green-500', error: 'bg-red-500', info: 'bg-blue-500' };
            const toast = document.createElement('div');
            toast.className = `${colors[type] || colors.success} text-white px-4 py-3 rounded-lg shadow-lg text-sm flex items-center gap-2 transform translate-x-full transition-transform duration-300`;
            toast.innerHTML = `<span>${message}</span>`;
            container.appendChild(toast);
            requestAnimationFrame(() => { requestAnimationFrame(() => { toast.classList.remove('translate-x-full'); }); });
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => toast.remove(), 300);
            }, 1500);
        }

        // Auto dismiss flash messages
        setTimeout(() => {
            ['flash-success','flash-error','flash-info'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.style.display = 'none';
            });
        }, 3000);

        // Confirm dialog
        let confirmCallback = null;
        function showConfirm(title, message, callback, btnText = 'Hapus', btnClass = 'bg-red-600 hover:bg-red-700') {
            document.getElementById('confirm-title').textContent = title;
            document.getElementById('confirm-message').textContent = message;
            document.getElementById('confirm-ok').textContent = btnText;
            document.getElementById('confirm-ok').className = `px-4 py-2 text-sm text-white ${btnClass} rounded-lg font-medium`;
            confirmCallback = callback;
            document.getElementById('confirm-dialog').classList.remove('hidden');
        }
        function closeConfirm() {
            document.getElementById('confirm-dialog').classList.add('hidden');
            confirmCallback = null;
        }
        document.getElementById('confirm-ok').addEventListener('click', () => {
            if (confirmCallback) confirmCallback();
            closeConfirm();
        });
    </script>

    @yield('scripts')
</body>
</html>
