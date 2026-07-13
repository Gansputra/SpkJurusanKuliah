@extends('layouts.app')

@section('title', 'Detail Riwayat')
@section('page-title', 'Detail Rekomendasi')

@section('content')
<div class="mb-6">
    <a href="{{ route('user.history.index') }}" class="inline-flex items-center space-x-2 text-gray-500 hover:text-blue-600 text-sm font-medium mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        <span>Kembali ke Riwayat</span>
    </a>
    <h2 class="text-2xl font-black text-gray-900">{{ $recommendation->session_name ?? 'Rekomendasi #' . $recommendation->id }}</h2>
    <p class="text-gray-500 text-sm">{{ $recommendation->created_at->format('d F Y, H:i') }}</p>
</div>

{{-- Top Result --}}
@php $top = $recommendation->details->first(); @endphp
@if($top)
<div class="bg-gradient-to-r from-yellow-400 to-amber-400 rounded-2xl p-5 mb-6">
    <div class="flex items-center space-x-3">
        <span class="text-4xl">🥇</span>
        <div>
            <p class="text-amber-900 text-sm font-semibold">Rekomendasi Terbaik</p>
            <p class="text-amber-900 font-black text-xl">{{ $top->alternative->nama }}</p>
            <p class="text-amber-800 text-sm">Nilai Preferensi: {{ number_format($top->nilai_preferensi * 100, 2) }}%</p>
        </div>
    </div>
</div>
@endif

{{-- Ranking --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100">
        <h3 class="font-bold text-gray-900">Ranking Jurusan</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Rank</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Jurusan</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Vi</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">D⁺</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">D⁻</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kesesuaian</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($recommendation->details as $d)
                <tr class="hover:bg-gray-50 {{ $d->ranking === 1 ? 'bg-yellow-50/50' : '' }}">
                    <td class="px-5 py-3">
                        @if($d->ranking <= 3)
                        <span class="text-xl">{{ ['🥇','🥈','🥉'][$d->ranking-1] }}</span>
                        @else
                        <span class="w-7 h-7 inline-flex items-center justify-center bg-gray-100 text-gray-600 rounded-full font-bold text-sm">{{ $d->ranking }}</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 font-semibold text-gray-900">{{ $d->alternative->nama }}</td>
                    <td class="px-5 py-3 text-center font-black text-blue-600">{{ number_format($d->nilai_preferensi, 4) }}</td>
                    <td class="px-5 py-3 text-center text-xs font-mono text-red-500">{{ number_format($d->d_plus, 4) }}</td>
                    <td class="px-5 py-3 text-center text-xs font-mono text-green-500">{{ number_format($d->d_minus, 4) }}</td>
                    <td class="px-5 py-3 w-32">
                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-500 rounded-full" style="width: {{ round($d->nilai_preferensi * 100, 1) }}%"></div>
                        </div>
                        <p class="text-xs text-gray-400 mt-0.5">{{ round($d->nilai_preferensi * 100, 1) }}%</p>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
