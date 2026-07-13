<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="description" content="SPK Pemilihan Jurusan Kuliah - Sistem Pendukung Keputusan menggunakan metode AHP dan TOPSIS"/>
    <title>@yield('title', 'SPK Jurusan Kuliah') - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-full bg-gray-50 font-inter" x-data="{ sidebarOpen: false }">

    <div class="flex h-full">
        <!-- Sidebar -->
        <div class="hidden lg:flex lg:flex-shrink-0">
            <div class="flex flex-col w-64">
                @include('partials.sidebar')
            </div>
        </div>

        <!-- Mobile sidebar overlay -->
        <div x-show="sidebarOpen" class="fixed inset-0 z-40 lg:hidden" @click="sidebarOpen = false">
            <div class="fixed inset-0 bg-gray-600 bg-opacity-75"></div>
            <div class="relative flex flex-col w-64 bg-white h-full shadow-xl">
                @include('partials.sidebar')
            </div>
        </div>

        <!-- Main content -->
        <div class="flex flex-col min-w-0 flex-1 overflow-hidden">
            <!-- Top navbar -->
            @include('partials.navbar')

            <!-- Page content -->
            <main class="flex-1 overflow-y-auto focus:outline-none">
                <div class="py-6">
                    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">

                        <!-- Flash Messages -->
                        @if(session('success'))
                        <div id="flash-success" class="mb-4 flex items-center p-4 bg-green-50 border border-green-200 rounded-xl">
                            <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-green-700 text-sm font-medium">{{ session('success') }}</p>
                            <button onclick="document.getElementById('flash-success').remove()" class="ml-auto text-green-500 hover:text-green-700">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </button>
                        </div>
                        @endif

                        @if(session('error'))
                        <div id="flash-error" class="mb-4 flex items-center p-4 bg-red-50 border border-red-200 rounded-xl">
                            <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-red-700 text-sm font-medium">{{ session('error') }}</p>
                            <button onclick="document.getElementById('flash-error').remove()" class="ml-auto text-red-500 hover:text-red-700">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </button>
                        </div>
                        @endif

                        @if(session('info'))
                        <div id="flash-info" class="mb-4 flex items-center p-4 bg-blue-50 border border-blue-200 rounded-xl">
                            <svg class="w-5 h-5 text-blue-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-blue-700 text-sm font-medium">{{ session('info') }}</p>
                        </div>
                        @endif

                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @stack('scripts')
</body>
</html>
