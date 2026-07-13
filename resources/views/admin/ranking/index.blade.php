@extends('layouts.app')

@section('title', 'Ranking Jurusan')
@section('page-title', 'Ranking Jurusan')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-black text-gray-900">🏆 Ranking Jurusan</h2>
        <p class="text-gray-500 text-sm mt-1">Hasil akhir berdasarkan metode AHP + TOPSIS</p>
    </div>
    @if($result)
    <div class="flex space-x-2">
        <a href="{{ route('admin.topsis.calculate') }}" class="px-4 py-2.5 bg-indigo-100 text-indigo-700 font-semibold rounded-xl hover:bg-indigo-200 text-sm transition-colors">
            Hitung Ulang
        </a>
        <a href="{{ route('admin.ranking.export-pdf') }}"
           class="flex items-center space-x-2 px-4 py-2.5 bg-red-500 text-white font-semibold rounded-xl hover:bg-red-600 text-sm transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span>Export PDF</span>
        </a>
    </div>
    @endif
</div>

@if(!$result)
<div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-8 text-center">
    <div class="text-5xl mb-4">⚠️</div>
    <h3 class="text-yellow-800 font-bold text-lg mb-2">
        @if(!$latestAhp)
        Perhitungan AHP belum dilakukan
        @elseif(!$latestAhp->is_consistent)
        Bobot AHP tidak konsisten (CR > 0.1)
        @endif
    </h3>
    <p class="text-yellow-600 mb-4 text-sm">
        Harap selesaikan perhitungan AHP dengan CR ≤ 0.1 terlebih dahulu.
    </p>
    <a href="{{ route('admin.ahp.matrix') }}"
       class="px-6 py-2.5 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 text-sm">
        Mulai Perhitungan AHP
    </a>
</div>
@else

{{-- Top 3 Podium --}}
@php
    $top3 = collect($result['results'])->take(3);
@endphp
<div class="grid grid-cols-3 gap-4 mb-8">
    {{-- 2nd --}}
    @if($top3->count() >= 2)
    @php $second = $top3[1]; @endphp
    <div class="bg-gradient-to-b from-gray-100 to-gray-50 rounded-2xl p-4 text-center border border-gray-200 mt-8">
        <div class="text-3xl mb-2">🥈</div>
        <div class="font-bold text-gray-900 text-sm">{{ $second['alternative']['nama'] }}</div>
        <div class="text-gray-500 text-xs mt-1">{{ $second['alternative']['kode'] }}</div>
        <div class="mt-2 font-black text-gray-700">{{ number_format($second['nilai_preferensi'] * 100, 2) }}%</div>
    </div>
    @endif

    {{-- 1st --}}
    @php $first = $top3[0]; @endphp
    <div class="bg-gradient-to-b from-yellow-50 to-amber-50 rounded-2xl p-4 text-center border-2 border-yellow-400 shadow-lg">
        <div class="text-4xl mb-2">🥇</div>
        <div class="font-black text-gray-900">{{ $first['alternative']['nama'] }}</div>
        <div class="text-gray-500 text-xs mt-1">{{ $first['alternative']['kode'] }}</div>
        <div class="mt-2 font-black text-yellow-600 text-xl">{{ number_format($first['nilai_preferensi'] * 100, 2) }}%</div>
        <span class="mt-2 inline-block px-3 py-1 bg-yellow-400 text-yellow-900 rounded-full text-xs font-bold">TERBAIK</span>
    </div>

    {{-- 3rd --}}
    @if($top3->count() >= 3)
    @php $third = $top3[2]; @endphp
    <div class="bg-gradient-to-b from-orange-50 to-amber-50 rounded-2xl p-4 text-center border border-orange-200 mt-8">
        <div class="text-3xl mb-2">🥉</div>
        <div class="font-bold text-gray-900 text-sm">{{ $third['alternative']['nama'] }}</div>
        <div class="text-gray-500 text-xs mt-1">{{ $third['alternative']['kode'] }}</div>
        <div class="mt-2 font-black text-orange-600">{{ number_format($third['nilai_preferensi'] * 100, 2) }}%</div>
    </div>
    @endif
</div>

{{-- Chart --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6 p-6">
    <h3 class="font-bold text-gray-900 mb-4">Grafik Nilai Preferensi</h3>
    <div class="relative h-72">
        <canvas id="rankingChart"></canvas>
    </div>
</div>

{{-- Full Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="font-bold text-gray-900">Tabel Ranking Lengkap</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Rank</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Jurusan</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Vi (Preferensi)</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">D⁺</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">D⁻</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Progress</th>
                    @foreach($criteria as $c)
                    <th class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase">{{ $c->kode }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($result['results'] as $r)
                <tr class="hover:bg-gray-50 transition-colors {{ $r['ranking'] === 1 ? 'bg-yellow-50/60' : '' }}">
                    <td class="px-5 py-4">
                        @if($r['ranking'] <= 3)
                        <span class="text-2xl">{{ ['🥇','🥈','🥉'][$r['ranking']-1] }}</span>
                        @else
                        <span class="w-8 h-8 inline-flex items-center justify-center bg-gray-100 text-gray-600 rounded-full font-bold text-sm">{{ $r['ranking'] }}</span>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <div class="font-semibold text-gray-900">{{ $r['alternative']['nama'] }}</div>
                        <div class="text-gray-400 text-xs">{{ $r['alternative']['kode'] }}</div>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <span class="font-black text-blue-600 text-base">{{ number_format($r['nilai_preferensi'], 4) }}</span>
                    </td>
                    <td class="px-5 py-4 text-center font-mono text-red-500 text-xs">{{ number_format($r['d_plus'], 4) }}</td>
                    <td class="px-5 py-4 text-center font-mono text-green-500 text-xs">{{ number_format($r['d_minus'], 4) }}</td>
                    <td class="px-5 py-4 w-32">
                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full"
                                 style="width: {{ round($r['nilai_preferensi'] * 100, 1) }}%"></div>
                        </div>
                        <p class="text-xs text-gray-400 mt-0.5">{{ round($r['nilai_preferensi'] * 100, 1) }}%</p>
                    </td>
                    @foreach($criteria as $c)
                    @php
                        $altModel = \App\Models\Alternative::find($r['alternative']['id']);
                        $score = $altModel?->getScoreForCriteria($c->id) ?? 0;
                    @endphp
                    <td class="px-3 py-4 text-center text-xs font-mono text-gray-600">{{ number_format($score, 1) }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection

@push('scripts')
@if($result)
<script>
const ctx = document.getElementById('rankingChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'Nilai Preferensi (%)',
            data: @json($chartData),
            backgroundColor: {!! json_encode(array_map(fn($i) => $i === 0 ? 'rgba(234, 179, 8, 0.85)' : ($i === 1 ? 'rgba(107, 114, 128, 0.85)' : ($i === 2 ? 'rgba(180, 83, 9, 0.85)' : 'rgba(59, 130, 246, 0.7)')), range(0, count($chartLabels)))) !!},
            borderRadius: 10,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ` ${ctx.raw}%` }}},
        scales: {
            y: { beginAtZero: true, max: 100, grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { callback: v => v+'%', font: { size: 11 }}},
            x: { grid: { display: false }, ticks: { font: { size: 10 }, maxRotation: 30 }}
        }
    }
});
</script>
@endif
@endpush
