<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIWARGA - Sistem Iuran Warga RT')</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:opsz,wght@8..60,400;8..60,500;8..60,600;8..60,700;8..60,800&family=IBM+Plex+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --paper: #F2EEE2;
            --paper-deep: #EAE3D1;
            --ledger-line: #D9CFA8;
            --moss-deep: #2F3B2C;
            --moss: #475943;
            --moss-mid: #5C6E4F;
            --brick: #B5482F;
            --brick-deep: #8E371F;
            --ink: #1C1B17;
            --ink-soft: #5A5648;
            --gold: #A9824E;
        }
        * { box-sizing: border-box; }
        body {
            background-color: var(--paper);
            color: var(--ink);
            font-family: 'IBM Plex Mono', monospace;
            background-image:
                repeating-linear-gradient(to bottom, transparent 0, transparent 35px, rgba(90,86,72,0.08) 35px, rgba(90,86,72,0.08) 36px);
        }
        .font-display { font-family: 'Source Serif 4', serif; }
        .font-mono { font-family: 'IBM Plex Mono', monospace; }
        .stamp {
            position: relative;
            border: 3px solid var(--brick);
            color: var(--brick);
            border-radius: 9999px;
            transform: rotate(-7deg);
            box-shadow: 0 0 0 1px rgba(181,72,47,0.15) inset;
        }
        .stamp::before {
            content: "";
            position: absolute;
            inset: 6px;
            border: 1px solid var(--brick);
            border-radius: 9999px;
            opacity: 0.55;
        }
        .ledger-card {
            background: var(--paper-deep);
            border: 1px solid var(--ledger-line);
            box-shadow: 0 1px 0 rgba(90,86,72,0.06);
        }
        .tab-active {
            background: var(--moss-deep);
            color: var(--paper);
        }
        .status-pending {
            background: repeating-linear-gradient(135deg, rgba(181,72,47,0.12), rgba(181,72,47,0.12) 4px, transparent 4px, transparent 8px);
            border: 1px solid var(--brick);
            color: var(--brick-deep);
        }
        .status-selesai {
            border: 1px solid var(--moss-mid);
            color: var(--moss-deep);
            background: rgba(92,110,79,0.10);
        }
        .ledger-row:hover {
            background: rgba(169,130,78,0.08);
        }
        .col-divider {
            border-left: 1px dashed var(--ledger-line);
        }
        input[type="text"],
        input[type="number"],
        input[type="month"],
        select {
            background: var(--paper);
            border: 1px solid var(--ledger-line);
            color: var(--ink);
            font-family: 'IBM Plex Mono', monospace;
        }
        input:focus,
        select:focus {
            outline: 2px solid var(--moss-mid);
            outline-offset: 1px;
            border-color: var(--moss-mid);
        }
        .btn-brick {
            background: var(--brick);
            color: var(--paper);
            border: 1px solid var(--brick-deep);
        }
        .btn-brick:hover { background: var(--brick-deep); }
        .btn-moss {
            background: var(--moss-deep);
            color: var(--paper);
        }
        .btn-moss:hover { background: var(--moss); }
        .btn-outline {
            border: 1px solid var(--ink-soft);
            color: var(--ink-soft);
            background: transparent;
        }
        .btn-outline:hover {
            border-color: var(--ink);
            color: var(--ink);
        }
        .scrollbar-thin::-webkit-scrollbar { height: 6px; width: 6px; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background: var(--ledger-line); }
        @media (max-width: 768px) {
            .hide-mobile { display: none; }
        }
        .kop-letterhead {
            border-bottom: 4px double var(--moss-deep);
        }
        .perforation {
            background-image: radial-gradient(circle, var(--paper) 2.4px, transparent 2.6px);
            background-size: 14px 14px;
            background-position: center;
        }
    </style>
</head>
<body class="min-h-screen pb-24">
    <!-- Toast Notification -->
    <div x-data="{ show: false, message: '', type: 'success' }"
         x-on:toast.window="show = true; message = $event.detail.message; type = $event.detail.type; setTimeout(() => show = false, 3000)"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         class="fixed top-4 right-4 z-50 max-w-md">
        <div :class="type === 'success' ? 'bg-green-600' : 'bg-red-600'" class="text-white px-6 py-4 rounded-lg shadow-lg flex items-center font-mono">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <template x-if="type === 'success'">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </template>
                <template x-if="type === 'error'">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </template>
            </svg>
            <span x-text="message"></span>
        </div>
    </div>

    <!-- Header / Kop Surat -->
    <header class="kop-letterhead bg-[var(--paper)] sticky top-0 z-30">
        <div class="max-w-6xl mx-auto px-5 md:px-8 pt-6 pb-4 flex items-start justify-between gap-4">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 md:w-14 md:h-14 rounded-full bg-[var(--moss-deep)] flex items-center justify-center shrink-0 mt-1">
                    <span class="font-display text-[var(--paper)] text-lg md:text-xl font-bold">04</span>
                </div>
                <div>
                    <p class="text-[10px] md:text-xs tracking-[0.25em] text-[var(--ink-soft)] uppercase">Pemerintah Lingkungan RT</p>
                    <h1 class="font-display text-2xl md:text-3xl font-bold text-[var(--moss-deep)] leading-tight">SIWARGA</h1>
                    <p class="text-xs text-[var(--ink-soft)] -mt-0.5">Sistem Pencatatan Iuran Warga</p>
                </div>
            </div>
            <div class="hidden sm:flex flex-col items-center gap-1 shrink-0">
                <div class="stamp w-20 h-20 md:w-24 md:h-24 flex flex-col items-center justify-center text-center px-2">
                    <span class="font-display font-bold text-[11px] md:text-xs leading-none uppercase">Kas Sehat</span>
                    <span class="font-mono text-[9px] md:text-[10px] mt-1">{{ date('M') }} '{{ substr(date('y'), -2) }}</span>
                </div>
            </div>
        </div>
        <div class="max-w-6xl mx-auto px-5 md:px-8 pb-3 flex items-center justify-between text-xs">
            <p class="text-[var(--ink-soft)] font-mono">Periode aktif: <span class="text-[var(--ink)]">{{ date('F Y') }}</span></p>
            <p class="text-[var(--ink-soft)] font-mono hide-mobile">Operator: <span class="text-[var(--ink)]">Kepala RT</span></p>
        </div>
        <!-- Navigation -->
        <div class="max-w-6xl mx-auto px-5 md:px-8 pb-3">
            <nav class="flex gap-1 text-sm font-mono">
                <a href="/iuran" class="px-4 py-2 rounded-t-sm border border-[var(--moss-deep)] {{ request()->routeIs('iuran.index') ? 'tab-active' : 'border-[var(--ledger-line)] text-[var(--ink-soft)] hover:text-[var(--ink)] bg-[var(--paper-deep)]' }}">
                    Daftar Iuran
                </a>
                <a href="/iuran/tunggakan" class="px-4 py-2 rounded-t-sm border border-[var(--ledger-line)] {{ request()->routeIs('iuran.tunggakan') ? 'tab-active' : 'text-[var(--ink-soft)] hover:text-[var(--ink)] bg-[var(--paper-deep)]' }}">
                    Laporan Tunggakan
                </a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto px-5 md:px-8 mt-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="max-w-6xl mx-auto px-5 md:px-8 mt-12 pt-4 border-t border-dashed border-[var(--ledger-line)] text-[11px] font-mono text-[var(--ink-soft)] flex justify-between">
        <span>SIWARGA</span>
        <span>Dicetak otomatis oleh sistem · bukan dokumen resmi</span>
    </footer>
</body>
</html>
