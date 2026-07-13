@extends('layouts.app')

@section('title', 'Data Kriteria')
@section('page-title', 'Data Kriteria')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-black text-gray-900">Manajemen Kriteria</h2>
        <p class="text-gray-500 text-sm mt-1">Kelola kriteria penilaian untuk perhitungan AHP dan TOPSIS</p>
    </div>
    <a href="{{ route('admin.criteria.create') }}"
       class="flex items-center space-x-2 px-4 py-2.5 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        <span>Tambah Kriteria</span>
    </a>
</div>

{{-- Search --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6">
    <div class="p-4">
        <form method="GET" class="flex space-x-3">
            <div class="flex-1 relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari kriteria..."
                       class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"/>
            </div>
            <button type="submit" class="px-4 py-2.5 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors text-sm">
                Cari
            </button>
            @if(request('search'))
            <a href="{{ route('admin.criteria.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-600 font-medium rounded-xl hover:bg-gray-200 transition-colors text-sm">
                Reset
            </a>
            @endif
        </form>
    </div>
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Kriteria</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipe</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Bobot AHP</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Urutan</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($criteria as $idx => $c)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $criteria->firstItem() + $idx }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 font-bold text-sm">
                            {{ $c->kode }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-semibold text-gray-900">{{ $c->nama }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($c->tipe === 'benefit')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                            ↑ Benefit
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                            ↓ Cost
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($c->bobot > 0)
                        <div class="flex items-center space-x-2">
                            <span class="font-mono text-sm text-gray-900">{{ number_format($c->bobot, 4) }}</span>
                            <div class="w-16 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-500 rounded-full" style="width: {{ round($c->bobot * 100) }}%"></div>
                            </div>
                        </div>
                        @else
                        <span class="text-gray-400 text-xs">Belum dihitung</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $c->urutan }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('admin.criteria.edit', $c) }}"
                               class="px-3 py-1.5 bg-amber-50 text-amber-600 rounded-lg text-xs font-semibold hover:bg-amber-100 transition-colors">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.criteria.destroy', $c) }}"
                                  onsubmit="return confirm('Hapus kriteria {{ $c->nama }}?')">
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
                        <div class="text-4xl mb-3">📋</div>
                        <p class="text-gray-500 font-medium">Belum ada kriteria.</p>
                        <a href="{{ route('admin.criteria.create') }}" class="text-blue-600 text-sm hover:underline mt-1 inline-block">
                            Tambah kriteria pertama
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($criteria->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $criteria->links() }}
    </div>
    @endif
</div>

{{-- Info box --}}
<div class="mt-6 bg-blue-50 border border-blue-200 rounded-2xl p-4">
    <div class="flex items-start space-x-3">
        <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
        </svg>
        <div>
            <p class="text-blue-800 font-semibold text-sm">Informasi Bobot</p>
            <p class="text-blue-600 text-xs mt-1">Bobot akan otomatis dihitung setelah Anda menjalankan perhitungan AHP. Pastikan Consistency Ratio (CR) ≤ 0.1.</p>
        </div>
    </div>
</div>
@endsection
