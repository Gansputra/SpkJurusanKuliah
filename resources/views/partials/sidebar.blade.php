<!-- Sidebar -->
<div class="flex flex-col h-full bg-gradient-to-b from-blue-900 to-blue-800 overflow-y-auto">
    <!-- Logo -->
    <div class="flex items-center flex-shrink-0 px-6 py-5 border-b border-blue-700">
        <div class="flex items-center space-x-3">
            <div class="w-9 h-9 bg-white rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <div>
                <p class="text-white font-bold text-sm leading-tight">SPK Jurusan</p>
                <p class="text-blue-300 text-xs">AHP & TOPSIS</p>
            </div>
        </div>
    </div>

    <!-- User Info -->
    <div class="px-4 py-4 border-b border-blue-700">
        <div class="flex items-center space-x-3">
            <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </div>
            <div class="min-w-0">
                <p class="text-white text-sm font-medium truncate">{{ auth()->user()->name ?? 'User' }}</p>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ auth()->user()->isAdmin() ? 'bg-yellow-500 text-yellow-900' : 'bg-blue-500 text-white' }}">
                    {{ auth()->user()->isAdmin() ? 'Admin' : 'User' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-4 space-y-1">
        @if(auth()->user()->isAdmin())
            <!-- Admin Menu -->
            <p class="px-3 text-xs font-semibold text-blue-400 uppercase tracking-wider mb-2">Menu Utama</p>

            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <p class="px-3 text-xs font-semibold text-blue-400 uppercase tracking-wider mt-4 mb-2">Data Master</p>

            <a href="{{ route('admin.criteria.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.criteria.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span>Kriteria</span>
            </a>

            <a href="{{ route('admin.alternatives.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.alternatives.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span>Jurusan</span>
            </a>

            <a href="{{ route('admin.scores.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.scores.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Nilai Alternatif</span>
            </a>

            <p class="px-3 text-xs font-semibold text-blue-400 uppercase tracking-wider mt-4 mb-2">Perhitungan</p>

            <a href="{{ route('admin.ahp.matrix') }}"
               class="sidebar-link {{ request()->routeIs('admin.ahp.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                <span>Metode AHP</span>
            </a>

            <a href="{{ route('admin.topsis.calculate') }}"
               class="sidebar-link {{ request()->routeIs('admin.topsis.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                <span>Metode TOPSIS</span>
            </a>

            <a href="{{ route('admin.ranking.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.ranking.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span>Ranking</span>
            </a>

        @else
            <!-- User Menu -->
            <p class="px-3 text-xs font-semibold text-blue-400 uppercase tracking-wider mb-2">Menu</p>

            <a href="{{ route('user.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('user.scores.index') }}"
               class="sidebar-link {{ request()->routeIs('user.scores.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <span>Input Nilai</span>
            </a>

            <a href="{{ route('user.recommendation.index') }}"
               class="sidebar-link {{ request()->routeIs('user.recommendation.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span>Rekomendasi</span>
            </a>

            <a href="{{ route('user.history.index') }}"
               class="sidebar-link {{ request()->routeIs('user.history.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Riwayat</span>
            </a>
        @endif
    </nav>

    <!-- Logout -->
    <div class="px-3 py-4 border-t border-blue-700">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-link w-full text-red-300 hover:text-white hover:bg-red-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span>Logout</span>
            </button>
        </form>
    </div>
</div>

<style>
.sidebar-link {
    display: flex;
    align-items: center;
    column-gap: 0.75rem;
    padding: 0.625rem 0.75rem;
    border-radius: 0.75rem;
    color: #bfdbfe; /* blue-200 */
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s;
    text-decoration: none;
    width: 100%;
}
.sidebar-link:hover {
    background-color: #1d4ed8; /* blue-700 */
    color: #ffffff;
}
.sidebar-link.active {
    background-color: #ffffff;
    color: #1e40af; /* blue-800 */
    box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
}
</style>
