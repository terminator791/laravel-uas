<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Iuran Warga RT')</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
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
        <div :class="type === 'success' ? 'bg-green-500' : 'bg-red-500'" class="text-white px-6 py-4 rounded-lg shadow-lg flex items-center">
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

    <!-- Navigation -->
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('iuran.index') }}" class="flex items-center">
                        <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="text-xl font-bold text-gray-800">Iuran Warga RT</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('iuran.index') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md font-medium {{ request()->routeIs('iuran.index') ? 'text-blue-600 bg-blue-50' : '' }}">
                        Daftar Iuran
                    </a>
                    <a href="{{ route('iuran.tunggakan') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md font-medium {{ request()->routeIs('iuran.tunggakan') ? 'text-blue-600 bg-blue-50' : '' }}">
                        Laporan Tunggakan
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <p class="text-center text-gray-500 text-sm">
                &copy; {{ date('Y') }} Sistem Informasi Iuran Warga RT
            </p>
        </div>
    </footer>
</body>
</html>
