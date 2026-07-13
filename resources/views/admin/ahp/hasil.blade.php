@extends('layouts.app')

@section('title', 'Hasil Perhitungan AHP')
@section('page-title', 'Perhitungan AHP')

@section('content')
{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-black text-gray-900">Hasil Perhitungan AHP</h2>
        <p class="text-gray-500 text-sm mt-1">Analytic Hierarchy Process — Detail step-by-step</p>
    </div>
    <div class="flex space-x-2">
        <a href="{{ route('admin.ahp.matrix') }}" class="px-4 py-2.5 bg-gray-100 text-gray-600 font-semibold rounded-xl hover:bg-gray-200 transition-colors text-sm">
            Edit Matriks
        </a>
        @if($result['is_consistent'])
        <a href="{{ route('admin.topsis.calculate') }}" class="px-4 py-2.5 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-colors text-sm">
            Hitung TOPSIS →
        </a>
        @endif
    </div>
</div>

{{-- Consistency Status --}}
<div class="mb-6 p-5 rounded-2xl border {{ $result['is_consistent'] ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
    <div class="flex items-start space-x-4">
        <div class="text-4xl">{{ $result['is_consistent'] ? '✅' : '❌' }}</div>
        <div class="flex-1">
            <h3 class="font-bold text-lg {{ $result['is_consistent'] ? 'text-green-800' : 'text-red-800' }}">
                {{ $result['is_consistent'] ? 'Matriks Konsisten — Bobot Dapat Digunakan!' : 'Matriks Tidak Konsisten — Perlu Diperbaiki!' }}
            </h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-3">
                <div class="bg-white/70 rounded-xl p-3 text-center">
                    <p class="text-xs text-gray-500 mb-1">λmax</p>
                    <p class="font-black text-gray-900 text-lg">{{ number_format($result['lambda_max'], 4) }}</p>
                </div>
                <div class="bg-white/70 rounded-xl p-3 text-center">
                    <p class="text-xs text-gray-500 mb-1">CI</p>
                    <p class="font-black text-gray-900 text-lg">{{ number_format($result['ci'], 4) }}</p>
                </div>
                <div class="bg-white/70 rounded-xl p-3 text-center">
                    <p class="text-xs text-gray-500 mb-1">RI (n={{ $result['n'] }})</p>
                    <p class="font-black text-gray-900 text-lg">{{ number_format($result['ri'], 2) }}</p>
                </div>
                <div class="bg-white/70 rounded-xl p-3 text-center">
                    <p class="text-xs text-gray-500 mb-1">CR</p>
                    <p class="font-black text-lg {{ $result['is_consistent'] ? 'text-green-600' : 'text-red-600' }}">
                        {{ number_format($result['cr'], 4) }}
                    </p>
                    <p class="text-xs {{ $result['is_consistent'] ? 'text-green-500' : 'text-red-500' }}">
                        {{ $result['is_consistent'] ? '≤ 0.1 ✓' : '> 0.1 ✗' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Step 1: Matriks Perbandingan --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-blue-50">
        <h3 class="font-bold text-blue-900">Langkah 1 — Matriks Perbandingan Berpasangan</h3>
    </div>
    <div class="p-4 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-2 text-gray-500 font-semibold text-left">Kriteria</th>
                    @foreach($result['criteria'] as $c)
                    <th class="px-4 py-2 text-center text-gray-700 font-bold">{{ $c->kode }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($result['criteria'] as $i => $c1)
                <tr class="border-t border-gray-50 {{ $i % 2 ? 'bg-gray-50/50' : '' }}">
                    <td class="px-4 py-2 font-semibold text-gray-700">{{ $c1->kode }}</td>
                    @foreach($result['criteria'] as $j => $c2)
                    <td class="px-4 py-2 text-center font-mono {{ $i === $j ? 'bg-blue-50 font-black text-blue-700' : '' }}">
                        {{ \App\Services\AHPService::toSaatiFraction($result['matrix'][$i][$j]) }}
                    </td>
                    @endforeach
                </tr>
                @endforeach
                {{-- Jumlah Kolom --}}
                <tr class="border-t-2 border-gray-200 bg-yellow-50">
                    <td class="px-4 py-2 font-bold text-gray-700 text-sm">Σ Kolom</td>
                    @foreach($result['col_sums'] as $sum)
                    <td class="px-4 py-2 text-center font-mono font-bold text-yellow-700">{{ number_format($sum, 4) }}</td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
</div>

{{-- Step 2: Matriks Normalisasi --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-indigo-50">
        <h3 class="font-bold text-indigo-900">Langkah 2 — Normalisasi Matriks (dibagi jumlah kolom)</h3>
    </div>
    <div class="p-4 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-2 text-gray-500 font-semibold text-left">Kriteria</th>
                    @foreach($result['criteria'] as $c)
                    <th class="px-4 py-2 text-center text-gray-700 font-bold">{{ $c->kode }}</th>
                    @endforeach
                    <th class="px-4 py-2 text-center text-gray-700 font-bold bg-blue-50">Priority Vector</th>
                </tr>
            </thead>
            <tbody>
                @foreach($result['criteria'] as $i => $c)
                <tr class="border-t border-gray-50 {{ $i % 2 ? 'bg-gray-50/50' : '' }}">
                    <td class="px-4 py-2 font-semibold text-gray-700">{{ $c->kode }}</td>
                    @foreach($result['normalized_matrix'][$i] as $val)
                    <td class="px-4 py-2 text-center font-mono text-gray-600">{{ number_format($val, 4) }}</td>
                    @endforeach
                    <td class="px-4 py-2 text-center font-mono font-black text-blue-700 bg-blue-50">
                        {{ number_format($result['priority_vector'][$i], 4) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Step 3: Bobot Kriteria --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-green-50">
        <h3 class="font-bold text-green-900">Langkah 3 — Bobot Kriteria (Priority Vector)</h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-4">
            @foreach($result['weights'] as $weight)
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-4 text-center">
                <div class="text-blue-600 font-bold text-xs mb-1">{{ $weight['kode'] }}</div>
                <div class="text-2xl font-black text-gray-900">{{ number_format($weight['bobot'] * 100, 2) }}%</div>
                <div class="text-gray-500 text-xs mt-1 leading-tight">{{ $weight['nama'] }}</div>
                <div class="mt-2 h-2 bg-blue-100 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-500 rounded-full" style="width: {{ round($weight['bobot'] * 100) }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
        {{-- Bobot Chart --}}
        <canvas id="bobotChart" height="200"></canvas>
    </div>
</div>

{{-- Step 4-6: Konsistensi --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6 p-6">
    <h3 class="font-bold text-gray-900 mb-4">Langkah 4-6 — Uji Konsistensi</h3>
    <div class="space-y-3">
        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-xl">
            <span class="text-blue-600 font-mono font-bold w-32">λmax</span>
            <span class="text-gray-500">=</span>
            <span class="font-mono font-bold text-gray-900">{{ number_format($result['lambda_max'], 6) }}</span>
        </div>
        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-xl">
            <span class="text-blue-600 font-mono font-bold w-32">CI</span>
            <span class="text-gray-500">=</span>
            <span class="font-mono text-gray-600 text-sm">(λmax - n) / (n - 1) = ({{ number_format($result['lambda_max'], 4) }} - {{ $result['n'] }}) / ({{ $result['n'] }} - 1)</span>
            <span class="text-gray-500">=</span>
            <span class="font-mono font-bold text-gray-900">{{ number_format($result['ci'], 6) }}</span>
        </div>
        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-xl">
            <span class="text-blue-600 font-mono font-bold w-32">RI</span>
            <span class="text-gray-500">=</span>
            <span class="font-mono font-bold text-gray-900">{{ number_format($result['ri'], 2) }}</span>
            <span class="text-gray-400 text-xs">(Tabel Random Index untuk n={{ $result['n'] }})</span>
        </div>
        <div class="flex items-center space-x-3 p-3 {{ $result['is_consistent'] ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }} rounded-xl">
            <span class="{{ $result['is_consistent'] ? 'text-green-600' : 'text-red-600' }} font-mono font-bold w-32">CR</span>
            <span class="text-gray-500">=</span>
            <span class="text-gray-500 text-sm font-mono">CI / RI = {{ number_format($result['ci'], 4) }} / {{ number_format($result['ri'], 2) }}</span>
            <span class="text-gray-500">=</span>
            <span class="font-mono font-bold text-xl {{ $result['is_consistent'] ? 'text-green-600' : 'text-red-600' }}">
                {{ number_format($result['cr'], 6) }}
            </span>
            <span class="text-sm font-bold {{ $result['is_consistent'] ? 'text-green-600' : 'text-red-600' }}">
                {{ $result['is_consistent'] ? '≤ 0.1 → KONSISTEN ✓' : '> 0.1 → TIDAK KONSISTEN ✗' }}
            </span>
        </div>
    </div>
</div>

@if(!$result['is_consistent'])
<div class="bg-red-50 border border-red-200 rounded-2xl p-4">
    <p class="text-red-700 font-medium">⚠️ Perbandingan tidak konsisten (CR > 0.1). Silakan perbaiki matriks perbandingan berpasangan.</p>
    <a href="{{ route('admin.ahp.matrix') }}" class="mt-2 inline-block text-red-600 font-semibold hover:underline">
        Kembali ke Input Matriks →
    </a>
</div>
@endif
@endsection

@push('scripts')
<script>
const ctx = document.getElementById('bobotChart').getContext('2d');
new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: @json(collect($result['weights'])->pluck('kode')->toArray()),
        datasets: [{
            data: @json(collect($result['weights'])->pluck('bobot')->map(fn($v) => round($v * 100, 2))->toArray()),
            backgroundColor: ['#3b82f6','#6366f1','#8b5cf6','#10b981','#f59e0b'],
            borderWidth: 0,
            hoverOffset: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'right' },
            tooltip: {
                callbacks: { label: ctx => ` ${ctx.label}: ${ctx.raw}%` }
            }
        }
    }
});
</script>
@endpush
