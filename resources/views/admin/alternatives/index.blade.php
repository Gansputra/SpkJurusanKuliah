@extends('layouts.app')

@section('title', 'Data Jurusan')
@section('page-title', 'Data Jurusan')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-black text-gray-900">Manajemen Jurusan</h2>
        <p class="text-gray-500 text-sm mt-1">Kelola daftar jurusan kuliah sebagai alternatif pilihan</p>
    </div>
    <a href="{{ route('admin.alternatives.create') }}"
       class="flex items-center space-x-2 px-4 py-2.5 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        <span>Tambah Jurusan</span>
    </a>
</div>

{{-- Search + Filter --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6 p-4">
    <form method="GET" class="flex flex-wrap gap-3">
        <div class="flex-1 min-w-48 relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari jurusan..."
                   class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500"/>
        </div>
        <select name="active" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Status</option>
            <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Nonaktif</option>
        </select>
        <button type="submit" class="px-4 py-2.5 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 text-sm">Cari</button>
        @if(request()->hasAny(['search', 'active']))
        <a href="{{ route('admin.alternatives.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-600 font-medium rounded-xl hover:bg-gray-200 text-sm">Reset</a>
        @endif
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Kode</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Nama Jurusan</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Deskripsi</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Nilai</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($alternatives as $idx => $alt)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $alternatives->firstItem() + $idx }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 font-bold text-sm">
                            {{ $alt->kode }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-semibold text-gray-900">{{ $alt->nama }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs">
                        <span class="truncate block">{{ $alt->deskripsi ?? '-' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-gray-100 text-gray-600 text-xs font-semibold">
                            {{ $alt->scores_count }} nilai
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($alt->active)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">● Aktif</span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-gray-100 text-gray-500 text-xs font-semibold">○ Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('admin.scores.edit', $alt) }}"
                               class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-semibold hover:bg-blue-100 transition-colors">
                                Nilai
                            </a>
                            <a href="{{ route('admin.alternatives.edit', $alt) }}"
                               class="px-3 py-1.5 bg-amber-50 text-amber-600 rounded-lg text-xs font-semibold hover:bg-amber-100 transition-colors">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.alternatives.destroy', $alt) }}"
                                  onsubmit="return confirm('Hapus jurusan {{ $alt->nama }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs font-semibold hover:bg-red-100 transition-colors">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center">
                        <div class="text-4xl mb-3">🎓</div>
                        <p class="text-gray-500 font-medium">Belum ada jurusan.</p>
                        <a href="{{ route('admin.alternatives.create') }}" class="text-blue-600 text-sm hover:underline mt-1 inline-block">
                            Tambah jurusan pertama
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($alternatives->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $alternatives->links() }}
    </div>
    @endif
</div>
@endsection
