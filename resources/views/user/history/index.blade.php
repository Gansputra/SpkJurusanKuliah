@extends('layouts.app')

@section('title', 'Riwayat Rekomendasi')
@section('page-title', 'Riwayat')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-black text-gray-900">Riwayat Rekomendasi</h2>
    <p class="text-gray-500 text-sm mt-1">Semua sesi rekomendasi jurusan Anda</p>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    @forelse($recommendations as $rec)
    <div class="p-5 border-b border-gray-50 hover:bg-gray-50 transition-colors">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 font-bold text-lg flex-shrink-0">
                    {{ $rec->id }}
                </div>
                <div>
                    <p class="font-semibold text-gray-900">{{ $rec->session_name ?? 'Sesi Rekomendasi #' . $rec->id }}</p>
                    <div class="flex items-center space-x-3 mt-1">
                        <p class="text-gray-400 text-xs">{{ $rec->created_at->format('d F Y, H:i') }}</p>
                        @if($rec->details->first())
                        <span class="text-xs text-blue-600 font-medium">
                            🥇 {{ $rec->details->first()->alternative->nama ?? '-' }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                @if($rec->details->first())
                <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-bold">
                    {{ number_format($rec->details->first()->nilai_preferensi * 100, 1) }}%
                </span>
                @endif
                <a href="{{ route('user.history.show', $rec) }}"
                   class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-xs font-semibold hover:bg-blue-700 transition-colors">
                    Detail
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="py-16 text-center">
        <div class="text-5xl mb-4">📜</div>
        <h3 class="font-semibold text-gray-700 mb-2">Belum ada riwayat</h3>
        <p class="text-gray-500 text-sm mb-4">Lakukan rekomendasi pertama Anda</p>
        <a href="{{ route('user.scores.index') }}" class="px-5 py-2 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 text-sm">
            Mulai Sekarang
        </a>
    </div>
    @endforelse
    @if($recommendations->hasPages())
    <div class="p-5 border-t border-gray-100">
        {{ $recommendations->links() }}
    </div>
    @endif
</div>
@endsection
