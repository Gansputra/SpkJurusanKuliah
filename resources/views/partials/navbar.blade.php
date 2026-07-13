<!-- Top Navbar -->
<header class="bg-white border-b border-gray-200 shadow-sm">
    <div class="flex items-center justify-between px-4 sm:px-6 h-16">
        <!-- Mobile hamburger -->
        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <!-- Page title / Breadcrumb -->
        <div class="hidden lg:flex items-center space-x-2">
            <h1 class="text-lg font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
        </div>

        <!-- Right side -->
        <div class="flex items-center space-x-4">
            <!-- Welcome message -->
            <div class="hidden sm:flex items-center space-x-2">
                <span class="text-sm text-gray-500">Selamat datang,</span>
                <span class="text-sm font-semibold text-gray-800">{{ auth()->user()->name ?? 'User' }}</span>
            </div>

            <!-- Avatar dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white font-bold text-sm hover:shadow-md transition-shadow">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </button>
                <div x-show="open" @click.outside="open = false"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                    <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Profil Saya
                    </a>
                    <hr class="my-1"/>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
