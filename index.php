<!DOCTYPE html>
<html lang="id" class="">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Domain Vault</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        // Konfigurasi Tailwind (dark mode + font)
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
                    colors: {
                        brand: {
                            50:  '#eef2ff', 100: '#e0e7ff', 200: '#c7d2fe',
                            300: '#a5b4fc', 400: '#818cf8', 500: '#6366f1',
                            600: '#4f46e5', 700: '#4338ca', 800: '#3730a3',
                            900: '#312e81',
                        }
                    },
                    animation: {
                        'fade-in':  'fadeIn .4s ease-out',
                        'slide-up': 'slideUp .4s ease-out',
                        'pop-in':   'popIn .3s cubic-bezier(.34,1.56,.64,1)',
                    },
                    keyframes: {
                        fadeIn:  { '0%': {opacity:0}, '100%': {opacity:1} },
                        slideUp: { '0%': {opacity:0, transform:'translateY(12px)'}, '100%': {opacity:1, transform:'translateY(0)'} },
                        popIn:   { '0%': {opacity:0, transform:'scale(.9)'}, '100%': {opacity:1, transform:'scale(1)'} },
                    }
                }
            }
        };

        // Terapkan tema tersimpan sebelum render untuk hindari flash
        (function () {
            const t = localStorage.getItem('theme');
            if (t === 'dark' || (!t && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background-image:
                radial-gradient(at 0% 0%,   rgba(99,102,241,.12) 0, transparent 50%),
                radial-gradient(at 100% 0%, rgba(168,85,247,.10) 0, transparent 50%),
                radial-gradient(at 100% 100%, rgba(59,130,246,.10) 0, transparent 50%);
            background-attachment: fixed;
        }
        html.dark body {
            background-image:
                radial-gradient(at 0% 0%,   rgba(99,102,241,.18) 0, transparent 50%),
                radial-gradient(at 100% 0%, rgba(168,85,247,.15) 0, transparent 50%),
                radial-gradient(at 100% 100%, rgba(59,130,246,.15) 0, transparent 50%);
        }

        /* Scrollbar halus */
        ::-webkit-scrollbar { width: 10px; height: 10px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(148,163,184,.4); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(148,163,184,.7); }

        /* Efek glass untuk card login */
        .glass {
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
        }

        /* Animasi shake untuk error */
        @keyframes shake {
            0%,100% { transform: translateX(0); }
            25%     { transform: translateX(-6px); }
            75%     { transform: translateX(6px); }
        }
        .shake { animation: shake .35s ease-in-out; }

        /* Toggle mode gelap/terang */
        .theme-toggle { transition: transform .4s ease; }
        .theme-toggle:hover { transform: rotate(20deg) scale(1.1); }

        /* Password dotted saat hidden */
        input.pass-hidden {
            -webkit-text-security: disc;
            text-security: disc;
            font-family: 'Courier New', monospace;
        }
    </style>
</head>

<body class="text-slate-800 dark:text-slate-100 min-h-screen bg-slate-50 dark:bg-slate-950 transition-colors duration-300">

    <!-- ===================== LOGIN ===================== -->
    <div id="loginSection" class="min-h-screen flex items-center justify-center p-4 animate-fade-in">
        <div class="glass bg-white/80 dark:bg-slate-900/70 p-8 rounded-3xl shadow-2xl max-w-md w-full border border-white/60 dark:border-slate-700/50 animate-pop-in">

            <!-- Theme toggle pojok -->
            <div class="flex justify-end -mt-2 -mr-2">
                <button onclick="toggleTheme()" aria-label="Ganti tema"
                    class="theme-toggle text-slate-500 hover:text-brand-600 dark:text-slate-400 dark:hover:text-brand-400 p-2 rounded-full">
                    <i class="fa-solid fa-sun text-lg hidden dark:inline"></i>
                    <i class="fa-solid fa-moon text-lg inline dark:hidden"></i>
                </button>
            </div>

            <div class="text-center mb-8 -mt-4">
                <div class="bg-gradient-to-br from-brand-500 to-purple-600 text-white w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-5 text-3xl shadow-lg shadow-brand-500/30 rotate-3 hover:rotate-0 transition-transform">
                    <i class="fa-solid fa-vault"></i>
                </div>
                <h1 class="text-3xl font-extrabold bg-gradient-to-r from-brand-600 to-purple-600 dark:from-brand-400 dark:to-purple-400 bg-clip-text text-transparent">Domain Vault</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1.5">Login untuk mengakses kredensial Anda</p>
            </div>

            <form id="loginForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Username</label>
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" id="loginUsername" autocomplete="username"
                            class="w-full pl-11 pr-4 py-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all text-sm"
                            placeholder="Masukkan username">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Password</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="password" id="loginPassword" autocomplete="current-password"
                            class="w-full pl-11 pr-11 py-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all text-sm"
                            placeholder="Masukkan password">
                        <button type="button" onclick="togglePwdInput('loginPassword', this)"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div id="loginError" class="text-red-500 text-sm text-center hidden font-medium bg-red-50 dark:bg-red-500/10 py-2 rounded-lg">
                    Username atau Password salah!
                </div>
                <button type="submit" id="loginBtn"
                    class="w-full bg-gradient-to-r from-brand-600 to-purple-600 hover:from-brand-700 hover:to-purple-700 text-white font-semibold py-3 rounded-xl shadow-lg shadow-brand-500/30 transition-all mt-2 active:scale-[0.98]">
                    <span id="loginBtnText"><i class="fa-solid fa-right-to-bracket mr-2"></i>Login</span>
                </button>
            </form>
        </div>
    </div>

    <!-- ===================== DASHBOARD ===================== -->
    <div id="dashboardSection" class="hidden min-h-screen pb-12 animate-fade-in">
        <nav class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 sticky top-0 z-30 shadow-sm">
            <div class="max-w-6xl mx-auto px-4 py-3.5 flex justify-between items-center">
                <div class="font-extrabold text-xl flex items-center gap-2.5">
                    <div class="bg-gradient-to-br from-brand-500 to-purple-600 text-white w-9 h-9 rounded-lg flex items-center justify-center shadow-md shadow-brand-500/30">
                        <i class="fa-solid fa-vault text-sm"></i>
                    </div>
                    <span class="bg-gradient-to-r from-brand-600 to-purple-600 dark:from-brand-400 dark:to-purple-400 bg-clip-text text-transparent">Domain Vault</span>
                </div>
                <div class="flex items-center gap-2">
                    <span id="navUser" class="hidden sm:inline text-sm text-slate-500 dark:text-slate-400 mr-2">
                        <i class="fa-regular fa-user mr-1"></i><span id="navUsername"></span>
                    </span>
                    <button onclick="toggleTheme()" aria-label="Ganti tema"
                        class="theme-toggle text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
                        <i class="fa-solid fa-sun hidden dark:inline"></i>
                        <i class="fa-solid fa-moon inline dark:hidden"></i>
                    </button>
                    <button onclick="handleLogout()"
                        class="bg-slate-100 hover:bg-red-50 hover:text-red-600 dark:bg-slate-800 dark:hover:bg-red-500/20 dark:hover:text-red-400 text-slate-700 dark:text-slate-300 px-3 sm:px-4 py-2 rounded-lg text-sm font-medium transition-all flex items-center gap-2">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span class="hidden sm:inline">Logout</span>
                    </button>
                </div>
            </div>
        </nav>

        <div class="max-w-6xl mx-auto px-4 mt-8 flex flex-col md:flex-row gap-6">

            <!-- ======= FORM KIRI ======= -->
            <div class="w-full md:w-[380px] md:flex-shrink-0">
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 md:sticky md:top-24 animate-slide-up">
                    <div class="flex items-center gap-2.5 mb-5 pb-4 border-b border-slate-200 dark:border-slate-800">
                        <div class="bg-brand-100 dark:bg-brand-500/20 text-brand-600 dark:text-brand-400 w-9 h-9 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                        <h2 class="text-base font-bold text-slate-800 dark:text-slate-100">Tambah Kredensial</h2>
                    </div>
                    <form id="dataForm" class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nama Domain / Web</label>
                            <div class="relative">
                                <i class="fa-solid fa-globe absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                <input type="text" id="inputDomain" required
                                    class="w-full pl-10 pr-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-brand-500 focus:bg-white dark:focus:bg-slate-950 outline-none transition-all text-sm"
                                    placeholder="cth: google.com">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Alamat Email</label>
                            <div class="relative">
                                <i class="fa-regular fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                <input type="email" id="inputEmail" required
                                    class="w-full pl-10 pr-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-brand-500 focus:bg-white dark:focus:bg-slate-950 outline-none transition-all text-sm"
                                    placeholder="cth: admin@gmail.com">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Password</label>
                            <div class="relative">
                                <i class="fa-solid fa-key absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                <input type="text" id="inputPass" required
                                    class="w-full pl-10 pr-10 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-brand-500 focus:bg-white dark:focus:bg-slate-950 outline-none transition-all text-sm font-mono"
                                    placeholder="Masukkan password">
                                <button type="button" onclick="generatePassword()" title="Generate password acak"
                                    class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 p-1.5 rounded">
                                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-brand-600 to-purple-600 hover:from-brand-700 hover:to-purple-700 text-white font-semibold py-2.5 rounded-lg shadow-md shadow-brand-500/30 transition-all active:scale-[0.98]">
                            <i class="fa-solid fa-floppy-disk mr-2"></i>Simpan Data
                        </button>
                    </form>
                </div>
            </div>

            <!-- ======= DAFTAR KANAN ======= -->
            <div class="w-full md:flex-1 min-w-0">
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 animate-slide-up">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5 pb-4 border-b border-slate-200 dark:border-slate-800">
                        <div class="flex items-center gap-2.5">
                            <div class="bg-purple-100 dark:bg-purple-500/20 text-purple-600 dark:text-purple-400 w-9 h-9 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-folder"></i>
                            </div>
                            <h2 class="text-base font-bold text-slate-800 dark:text-slate-100">Daftar Tersimpan</h2>
                            <span id="countBadge" class="bg-brand-100 dark:bg-brand-500/20 text-brand-700 dark:text-brand-300 text-xs font-semibold px-2 py-0.5 rounded-full">0</span>
                        </div>
                        <div class="relative flex-1 sm:max-w-xs">
                            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="text" id="searchInput" oninput="renderData()"
                                class="w-full pl-10 pr-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-brand-500 focus:bg-white dark:focus:bg-slate-950 outline-none text-sm transition-all"
                                placeholder="Cari domain / email...">
                        </div>
                    </div>

                    <div id="dataList" class="grid grid-cols-1 lg:grid-cols-2 gap-4"></div>

                    <div id="emptyState" class="text-center py-14 text-slate-500 dark:text-slate-400 hidden">
                        <div class="inline-flex bg-slate-100 dark:bg-slate-800 w-20 h-20 rounded-full items-center justify-center mb-4">
                            <i class="fa-solid fa-folder-open text-3xl text-slate-400 dark:text-slate-500"></i>
                        </div>
                        <p class="font-medium" id="emptyText">Belum ada domain yang disimpan.</p>
                        <p class="text-xs mt-1 text-slate-400 dark:text-slate-500">Mulai tambahkan kredensial pertama Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== MODAL EDIT ===================== -->
    <div id="editModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 animate-fade-in">
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-md w-full p-6 border border-slate-200 dark:border-slate-800 animate-pop-in">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-brand-600 dark:text-brand-400"></i> Edit Kredensial
                </h3>
                <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xl">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form id="editForm" class="space-y-4">
                <input type="hidden" id="editId">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nama Domain / Web</label>
                    <input type="text" id="editDomain" required
                        class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-brand-500 outline-none text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Email</label>
                    <input type="email" id="editEmail" required
                        class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-brand-500 outline-none text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Password</label>
                    <input type="text" id="editPass" required
                        class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-brand-500 outline-none text-sm font-mono">
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="button" onclick="closeEditModal()"
                        class="flex-1 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium py-2.5 rounded-lg transition-all">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 bg-gradient-to-r from-brand-600 to-purple-600 hover:from-brand-700 hover:to-purple-700 text-white font-semibold py-2.5 rounded-lg shadow-md shadow-brand-500/30 transition-all">
                        <i class="fa-solid fa-check mr-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===================== MODAL KONFIRMASI HAPUS ===================== -->
    <div id="confirmModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 animate-fade-in">
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-sm w-full p-6 border border-slate-200 dark:border-slate-800 text-center animate-pop-in">
            <div class="bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400 w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Hapus Kredensial?</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Data yang dihapus tidak bisa dikembalikan.</p>
            <div class="flex gap-2 mt-6">
                <button onclick="closeConfirmModal()"
                    class="flex-1 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium py-2.5 rounded-lg transition-all">
                    Batal
                </button>
                <button id="confirmDeleteBtn"
                    class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 rounded-lg shadow-md shadow-red-500/30 transition-all">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>

    <!-- ===================== TOAST ===================== -->
    <div id="toastContainer" class="fixed bottom-6 right-6 z-[60] flex flex-col gap-2 pointer-events-none"></div>

    <script>
        // ============ THEME ============
        function toggleTheme() {
            const html = document.documentElement;
            html.classList.toggle('dark');
            localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
        }

        // ============ TOAST ============
        function toast(msg, type = 'success') {
            const colors = {
                success: 'bg-emerald-500',
                error:   'bg-red-500',
                info:    'bg-brand-600',
            };
            const icons = {
                success: 'fa-circle-check',
                error:   'fa-circle-xmark',
                info:    'fa-circle-info',
            };
            const el = document.createElement('div');
            el.className = `${colors[type]} text-white px-4 py-3 rounded-xl shadow-xl flex items-center gap-2.5 text-sm font-medium animate-slide-up pointer-events-auto max-w-xs`;
            el.innerHTML = `<i class="fa-solid ${icons[type]}"></i><span>${msg}</span>`;
            document.getElementById('toastContainer').appendChild(el);
            setTimeout(() => {
                el.style.transition = 'all .3s ease';
                el.style.opacity = 0;
                el.style.transform = 'translateX(20px)';
                setTimeout(() => el.remove(), 300);
            }, 2800);
        }

        // ============ API HELPER ============
        async function api(action, body = null) {
            const opts = { method: body ? 'POST' : 'GET' };
            if (body) {
                const fd = new FormData();
                Object.entries(body).forEach(([k, v]) => fd.append(k, v));
                opts.body = fd;
            }
            const res = await fetch(`api.php?action=${action}`, opts);
            return res.json();
        }

        // ============ ELEMENTS ============
        const loginSection     = document.getElementById('loginSection');
        const dashboardSection = document.getElementById('dashboardSection');
        const loginError       = document.getElementById('loginError');
        const dataForm         = document.getElementById('dataForm');
        const dataList         = document.getElementById('dataList');
        const emptyState       = document.getElementById('emptyState');
        const countBadge       = document.getElementById('countBadge');
        const searchInput      = document.getElementById('searchInput');

        let vaultData = [];
        let pendingDeleteId = null;

        // ============ SESSION CHECK ============
        (async function init() {
            const r = await api('check');
            if (r.logged_in) {
                showDashboard(r.username);
            }
        })();

        // ============ LOGIN ============
        document.getElementById('loginForm').addEventListener('submit', async e => {
            e.preventDefault();
            const username = document.getElementById('loginUsername').value;
            const password = document.getElementById('loginPassword').value;
            const btn = document.getElementById('loginBtnText');
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Memproses...';

            const r = await api('login', { username, password });

            if (r.success) {
                loginError.classList.add('hidden');
                document.getElementById('loginForm').reset();
                showDashboard(r.username);
            } else {
                loginError.textContent = r.message || 'Login gagal';
                loginError.classList.remove('hidden');
                const card = document.querySelector('#loginSection > div');
                card.classList.add('shake');
                setTimeout(() => card.classList.remove('shake'), 400);
            }
            btn.innerHTML = '<i class="fa-solid fa-right-to-bracket mr-2"></i>Login';
        });

        async function handleLogout() {
            await api('logout');
            dashboardSection.classList.add('hidden');
            loginSection.classList.remove('hidden');
            toast('Berhasil logout', 'info');
        }

        async function showDashboard(username) {
            loginSection.classList.add('hidden');
            dashboardSection.classList.remove('hidden');
            if (username) {
                document.getElementById('navUsername').textContent = username;
                document.getElementById('navUser').classList.remove('hidden');
            }
            await loadData();
        }

        // ============ DATA ============
        async function loadData() {
            const r = await api('get_data');
            if (r.success) {
                vaultData = r.data;
                renderData();
            }
        }

        dataForm.addEventListener('submit', async e => {
            e.preventDefault();
            const domain = document.getElementById('inputDomain').value;
            const email  = document.getElementById('inputEmail').value;
            const password = document.getElementById('inputPass').value;

            const r = await api('add_data', { domain, email, password });
            if (r.success) {
                dataForm.reset();
                toast('Kredensial berhasil disimpan');
                await loadData();
            } else {
                toast(r.message || 'Gagal menyimpan', 'error');
            }
        });

        function askDelete(id) {
            pendingDeleteId = id;
            document.getElementById('confirmModal').classList.remove('hidden');
        }
        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.add('hidden');
            pendingDeleteId = null;
        }
        document.getElementById('confirmDeleteBtn').addEventListener('click', async () => {
            if (!pendingDeleteId) return;
            const r = await api('delete_data', { id: pendingDeleteId });
            closeConfirmModal();
            if (r.success) {
                toast('Kredensial dihapus', 'info');
                await loadData();
            } else {
                toast('Gagal menghapus', 'error');
            }
        });

        // ============ EDIT ============
        function openEditModal(id) {
            const item = vaultData.find(x => +x.id === +id);
            if (!item) return;
            document.getElementById('editId').value     = item.id;
            document.getElementById('editDomain').value = item.domain;
            document.getElementById('editEmail').value  = item.email;
            document.getElementById('editPass').value   = item.password;
            document.getElementById('editModal').classList.remove('hidden');
        }
        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
        document.getElementById('editForm').addEventListener('submit', async e => {
            e.preventDefault();
            const id       = document.getElementById('editId').value;
            const domain   = document.getElementById('editDomain').value;
            const email    = document.getElementById('editEmail').value;
            const password = document.getElementById('editPass').value;
            const r = await api('update_data', { id, domain, email, password });
            if (r.success) {
                closeEditModal();
                toast('Perubahan disimpan');
                await loadData();
            } else {
                toast(r.message || 'Gagal update', 'error');
            }
        });

        // ============ PASSWORD UTIL ============
        function togglePwdInput(inputId, btn) {
            const el = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            if (el.type === 'password') {
                el.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                el.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        function togglePassword(id) {
            const el = document.getElementById(`pass-${id}`);
            const icon = document.getElementById(`icon-${id}`);
            if (el.classList.contains('pass-hidden')) {
                el.classList.remove('pass-hidden');
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                el.classList.add('pass-hidden');
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        async function copyPassword(id, password) {
            try {
                await navigator.clipboard.writeText(password);
                toast('Password disalin ke clipboard');
            } catch {
                toast('Gagal menyalin', 'error');
            }
        }

        function generatePassword() {
            const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789!@#$%^&*';
            let pwd = '';
            const arr = new Uint32Array(16);
            crypto.getRandomValues(arr);
            arr.forEach(n => pwd += chars[n % chars.length]);
            document.getElementById('inputPass').value = pwd;
            toast('Password acak dibuat', 'info');
        }

        // ============ RENDER ============
        function escapeHtml(s) {
            return String(s).replace(/[&<>"']/g, m =>
                ({ '&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#39;' })[m]);
        }

        function renderData() {
            const q = (searchInput?.value || '').toLowerCase().trim();
            const filtered = q
                ? vaultData.filter(x =>
                    x.domain.toLowerCase().includes(q) || x.email.toLowerCase().includes(q))
                : vaultData;

            countBadge.textContent = vaultData.length;
            dataList.innerHTML = '';

            if (filtered.length === 0) {
                emptyState.classList.remove('hidden');
                document.getElementById('emptyText').textContent = vaultData.length === 0
                    ? 'Belum ada domain yang disimpan.'
                    : 'Tidak ada hasil yang cocok.';
                return;
            }

            emptyState.classList.add('hidden');

            filtered.forEach((item, idx) => {
                const safeDomain = escapeHtml(item.domain);
                const safeEmail  = escapeHtml(item.email);
                const safePass   = escapeHtml(item.password);

                const card = document.createElement('div');
                card.className = 'group bg-gradient-to-br from-slate-50 to-white dark:from-slate-800 dark:to-slate-800/60 border border-slate-200 dark:border-slate-700 p-4 rounded-xl relative hover:shadow-lg hover:border-brand-300 dark:hover:border-brand-500 transition-all animate-slide-up';
                card.style.animationDelay = `${Math.min(idx, 10) * 30}ms`;

                card.innerHTML = `
                    <div class="flex items-start justify-between gap-2 mb-3">
                        <div class="flex items-center gap-2.5 min-w-0 flex-1">
                            <div class="bg-gradient-to-br from-brand-500 to-purple-600 text-white w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 shadow-md shadow-brand-500/20">
                                <i class="fa-solid fa-globe text-sm"></i>
                            </div>
                            <h3 class="font-bold text-slate-800 dark:text-slate-100 truncate" title="${safeDomain}">${safeDomain}</h3>
                        </div>
                        <div class="flex items-center gap-1 opacity-60 group-hover:opacity-100 transition-opacity">
                            <button onclick="openEditModal(${item.id})" title="Edit"
                                class="text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-brand-50 dark:hover:bg-brand-500/10 w-7 h-7 rounded-md flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                            </button>
                            <button onclick="askDelete(${item.id})" title="Hapus"
                                class="text-slate-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 w-7 h-7 rounded-md flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-2 text-sm">
                        <div class="flex items-center gap-2 text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-900/50 px-3 py-2 rounded-lg border border-slate-100 dark:border-slate-700/50">
                            <i class="fa-regular fa-envelope text-slate-400 text-xs w-4"></i>
                            <span class="truncate" title="${safeEmail}">${safeEmail}</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-900/50 px-3 py-2 rounded-lg border border-slate-100 dark:border-slate-700/50">
                            <i class="fa-solid fa-key text-slate-400 text-xs w-4"></i>
                            <input id="pass-${item.id}" value="${safePass}" readonly
                                class="pass-hidden bg-transparent border-none outline-none text-slate-600 dark:text-slate-300 flex-1 min-w-0 font-mono text-xs pointer-events-none">
                            <button onclick="togglePassword(${item.id})" title="Tampilkan/Sembunyikan"
                                class="text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 w-6 h-6 flex items-center justify-center">
                                <i id="icon-${item.id}" class="fa-solid fa-eye text-xs"></i>
                            </button>
                            <button onclick="copyPassword(${item.id}, '${safePass.replace(/'/g, "\\'")}')" title="Salin"
                                class="text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 w-6 h-6 flex items-center justify-center">
                                <i class="fa-regular fa-copy text-xs"></i>
                            </button>
                        </div>
                    </div>
                `;
                dataList.appendChild(card);
            });
        }

        // Tutup modal dengan ESC
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeEditModal();
                closeConfirmModal();
            }
        });
    </script>
</body>

</html>
