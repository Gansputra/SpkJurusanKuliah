@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')

{{-- Stats Grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
    @php
        $statCards = [
            ['label' => 'Total Jurusan', 'value' => $stats['total_jurusan'], 'icon' => '🎓', 'color' => 'blue', 'link' => route('admin.alternatives.index')],
            ['label' => 'Total Kriteria', 'value' => $stats['total_criteria'], 'icon' => '📋', 'color' => 'indigo', 'link' => route('admin.criteria.index')],
            ['label' => 'Total User', 'value' => $stats['total_users'], 'icon' => '👥', 'color' => 'purple', 'link' => '#'],
            ['label' => 'Total Rekomendasi', 'value' => $stats['total_recommendations'], 'icon' => '📊', 'color' => 'green', 'link' => '#'],
        ];
    @endphp

    @foreach($statCards as $card)
    <a href="{{ $card['link'] }}" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-200 group">
        <div class="flex items-center justify-between mb-4">
            <div class="text-3xl">{{ $card['icon'] }}</div>
            <div class="w-9 h-9 bg-{{ $card['color'] }}-50 rounded-xl flex items-center justify-center group-hover:bg-{{ $card['color'] }}-100 transition-colors">
                <svg class="w-4 h-4 text-{{ $card['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-black text-gray-900">{{ $card['value'] }}</p>
        <p class="text-gray-500 text-sm mt-1">{{ $card['label'] }}</p>
    </a>
    @endforeach
</div>

<div class="grid lg:grid-cols-3 gap-6 mb-8">
    {{-- Chart Ranking --}}
    <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Grafik Ranking Jurusan</h2>
                <p class="text-gray-500 text-sm">Nilai preferensi TOPSIS per jurusan</p>
            </div>
            <a href="{{ route('admin.ranking.index') }}" class="text-blue-600 text-sm font-medium hover:text-blue-700">
                Lihat semua →
            </a>
        </div>
        @if(count($chartLabels) > 0)
        <div class="relative h-72">
            <canvas id="rankingChart"></canvas>
        </div>
        @else
        <div class="flex flex-col items-center justify-center h-48 text-center">
            <div class="text-4xl mb-3">📊</div>
            <p class="text-gray-500 text-sm">Belum ada data ranking.</p>
            <a href="{{ route('admin.ranking.index') }}" class="mt-3 text-blue-600 text-sm font-medium hover:underline">
                Hitung Ranking →
            </a>
        </div>
        @endif
    </div>

    {{-- AHP Status Card --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Status AHP</h2>
        @if($latestAhp)
        <div class="space-y-4">
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-gray-500 text-sm">Konsistensi</span>
                @if($latestAhp->is_consistent)
                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">✓ Konsisten</span>
                @else
                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">✗ Tidak Konsisten</span>
                @endif
            </div>
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-gray-500 text-sm">CR (Consistency Ratio)</span>
                <span class="font-bold text-gray-900 text-sm">{{ number_format($latestAhp->cr, 4) }}</span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-gray-500 text-sm">CI</span>
                <span class="font-bold text-gray-900 text-sm">{{ number_format($latestAhp->ci, 4) }}</span>
            </div>
            <div class="flex justify-between items-center py-3">
                <span class="text-gray-500 text-sm">λmax</span>
                <span class="font-bold text-gray-900 text-sm">{{ number_format($latestAhp->lambda_max, 4) }}</span>
            </div>
            <a href="{{ route('admin.ahp.calculate') }}"
               class="block w-full text-center py-2.5 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-colors text-sm mt-2">
                Hitung Ulang AHP
            </a>
        </div>
        @else
        <div class="flex flex-col items-center justify-center h-40 text-center">
            <div class="text-4xl mb-3">⚠️</div>
            <p class="text-gray-500 text-sm mb-4">AHP belum pernah dihitung.</p>
            <a href="{{ route('admin.ahp.matrix') }}"
               class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-colors">
                Input Matriks AHP
            </a>
        </div>
        @endif
    </div>
</div>

{{-- Top Jurusan Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100">
    <div class="flex items-center justify-between p-6 border-b border-gray-100">
        <div>
            <h2 class="text-lg font-bold text-gray-900">🏆 Top Jurusan</h2>
            <p class="text-gray-500 text-sm">Berdasarkan perhitungan TOPSIS terbaru</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.topsis.calculate') }}"
               class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors">
                Hitung TOPSIS
            </a>
            <a href="{{ route('admin.ranking.export-pdf') }}"
               class="px-4 py-2 bg-red-500 text-white text-sm font-semibold rounded-xl hover:bg-red-600 transition-colors">
                Export PDF
            </a>
        </div>
    </div>
    @if($topJurusan->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ranking</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jurusan</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nilai Preferensi</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">D+</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">D-</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Progress</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($topJurusan as $detail)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        @if($detail->ranking <= 3)
                        <span class="text-xl">{{ ['🥇', '🥈', '🥉'][$detail->ranking - 1] }}</span>
                        @else
                        <span class="w-7 h-7 inline-flex items-center justify-center bg-gray-100 text-gray-600 rounded-full text-sm font-bold">{{ $detail->ranking }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-semibold text-gray-900">{{ $detail->alternative->nama ?? '-' }}</div>
                        <div class="text-gray-400 text-xs">{{ $detail->alternative->kode ?? '' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-bold text-blue-600">{{ number_format($detail->nilai_preferensi, 4) }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-600 text-sm">{{ number_format($detail->d_plus, 4) }}</td>
                    <td class="px-6 py-4 text-gray-600 text-sm">{{ number_format($detail->d_minus, 4) }}</td>
                    <td class="px-6 py-4 w-32">
                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-blue-500 to-blue-600 rounded-full transition-all"
                                 style="width: {{ round($detail->nilai_preferensi * 100, 1) }}%"></div>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">{{ round($detail->nilai_preferensi * 100, 1) }}%</p>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="flex flex-col items-center justify-center py-16 text-center">
        <div class="text-5xl mb-4">📊</div>
        <h3 class="text-gray-700 font-semibold mb-2">Belum ada ranking</h3>
        <p class="text-gray-500 text-sm mb-6">Lakukan perhitungan AHP dan TOPSIS terlebih dahulu</p>
        <div class="flex space-x-3">
            <a href="{{ route('admin.ahp.matrix') }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700">
                Mulai AHP
            </a>
        </div>
    </div>
    @endif
</div>

@endsection

@push('scripts')
@if(count($chartLabels) > 0)
<script>
    const ctx = document.getElementById('rankingChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Nilai Preferensi (%)',
                data: @json($chartData),
                backgroundColor: [
                    'rgba(59, 130, 246, 0.85)',
                    'rgba(99, 102, 241, 0.85)',
                    'rgba(139, 92, 246, 0.85)',
                    'rgba(16, 185, 129, 0.85)',
                    'rgba(245, 158, 11, 0.85)',
                    'rgba(239, 68, 68, 0.85)',
                    'rgba(236, 72, 153, 0.85)',
                    'rgba(20, 184, 166, 0.85)',
                    'rgba(251, 146, 60, 0.85)',
                    'rgba(34, 197, 94, 0.85)',
                ],
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.raw}%`
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    grid: { color: 'rgba(0,0,0,0.04)' },
                    ticks: {
                        callback: v => v + '%',
                        font: { size: 11 }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { size: 10 },
                        maxRotation: 30,
                    }
                }
            }
        }
    });
</script>
@endif
@endpush
