@extends('layouts.app')

@section('title', 'Hasil Rekomendasi')
@section('page-title', 'Rekomendasi Jurusan')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-black text-gray-900">🎯 Hasil Rekomendasi Jurusan</h2>
        <p class="text-gray-500 text-sm mt-1">Berdasarkan metode AHP + TOPSIS</p>
    </div>
    <div class="flex space-x-2">
        <a href="{{ route('user.scores.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-600 font-semibold rounded-xl hover:bg-gray-200 text-sm">
            Ubah Nilai
        </a>
        @if(isset($result))
        <a href="{{ route('user.history.index') }}" class="px-4 py-2.5 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 text-sm">
            Riwayat
        </a>
        @endif
    </div>
</div>

@if(isset($error) && $error)
<div class="bg-red-50 border border-red-200 rounded-2xl p-6 text-center">
    <div class="text-4xl mb-3">❌</div>
    <p class="text-red-700 font-semibold">{{ $error }}</p>
    <a href="{{ route('user.scores.index') }}" class="mt-3 inline-block text-blue-600 hover:underline text-sm">Input Nilai →</a>
</div>

@elseif(isset($result) && $result)

{{-- Top Result Highlight --}}
@php $topResult = collect($result['results'])->firstWhere('ranking', 1); @endphp
@if($topResult)
<div class="bg-gradient-to-r from-yellow-400 to-amber-400 rounded-2xl p-6 mb-6 text-amber-900">
    <div class="flex items-center space-x-4">
        <div class="text-5xl">🥇</div>
        <div>
            <p class="text-sm font-semibold opacity-75">Jurusan Terbaik Untukmu</p>
            <h3 class="text-2xl font-black">{{ $topResult['alternative']['nama'] }}</h3>
            <p class="text-sm mt-1">Nilai Preferensi: <strong>{{ number_format($topResult['nilai_preferensi'] * 100, 2) }}%</strong></p>
        </div>
    </div>
</div>
@endif

{{-- Chart + Summary --}}
<div class="grid lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <h3 class="font-bold text-gray-900 mb-4">Grafik Ranking</h3>
        <div class="relative h-64">
            <canvas id="userRankChart"></canvas>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <h3 class="font-bold text-gray-900 mb-4">Bobot Kriteria (AHP)</h3>
        <div class="space-y-3">
            @foreach($criteria as $c)
            <div>
                <div class="flex justify-between items-center mb-1">
                    <span class="text-sm font-medium text-gray-700">{{ $c->kode }} - {{ $c->nama }}</span>
                    <span class="text-blue-600 font-bold text-sm">{{ number_format($c->bobot * 100, 1) }}%</span>
                </div>
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-500 rounded-full" style="width: {{ round($c->bobot * 100, 1) }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Ranking Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100">
        <h3 class="font-bold text-gray-900">Ranking Lengkap Semua Jurusan</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Rank</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Jurusan</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Nilai Preferensi</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kesesuaian</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($result['results'] as $r)
                <tr class="hover:bg-gray-50 transition-colors {{ $r['ranking'] === 1 ? 'bg-yellow-50/60' : '' }}">
                    <td class="px-5 py-4">
                        @if($r['ranking'] <= 3)
                        <span class="text-2xl">{{ ['🥇','🥈','🥉'][$r['ranking']-1] }}</span>
                        @else
                        <span class="w-7 h-7 inline-flex items-center justify-center bg-gray-100 text-gray-600 rounded-full font-bold text-sm">{{ $r['ranking'] }}</span>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <div class="font-semibold text-gray-900">{{ $r['alternative']['nama'] }}</div>
                        <div class="text-gray-400 text-xs">{{ $r['alternative']['kode'] }}</div>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <span class="font-black text-blue-600">{{ number_format($r['nilai_preferensi'], 4) }}</span>
                    </td>
                    <td class="px-5 py-4 w-40">
                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all
                                {{ $r['ranking'] === 1 ? 'bg-gradient-to-r from-yellow-400 to-amber-400' : 'bg-gradient-to-r from-blue-500 to-indigo-500' }}"
                                 style="width: {{ round($r['nilai_preferensi'] * 100, 1) }}%"></div>
                        </div>
                        <p class="text-xs text-gray-400 mt-0.5">{{ round($r['nilai_preferensi'] * 100, 1) }}%</p>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<div class="bg-gray-50 border border-gray-200 rounded-2xl p-8 text-center">
    <div class="text-4xl mb-3">📝</div>
    <p class="text-gray-600 font-medium mb-4">Anda belum mengisi nilai. Silakan input nilai terlebih dahulu.</p>
    <a href="{{ route('user.scores.index') }}" class="px-6 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700">
        Input Nilai Sekarang
    </a>
</div>
@endif
@endsection

@push('scripts')
@if(isset($result) && $result)
<script>
new Chart(document.getElementById('userRankChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'Preferensi (%)',
            data: @json($chartData),
            backgroundColor: {!! json_encode(array_map(fn($i) => $i === 0 ? 'rgba(234,179,8,0.85)' : ($i === 1 ? 'rgba(107,114,128,0.85)' : ($i === 2 ? 'rgba(180,83,9,0.85)' : 'rgba(59,130,246,0.7)')), range(0, count($chartLabels)))) !!},
            borderRadius: 8,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false }},
        scales: {
            y: { beginAtZero: true, max: 100, ticks: { callback: v => v+'%' }},
            x: { grid: { display: false }, ticks: { font: { size: 9 }, maxRotation: 30 }}
        }
    }
});
</script>
@endif
@endpush
