@extends('layouts.app')

@section('title', 'Dashboard User')
@section('page-title', 'Dashboard')

@section('content')

{{-- Welcome Banner --}}
<div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-6 mb-6 text-white">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-black">Selamat datang, {{ $user->name }}! 👋</h2>
            <p class="text-blue-100 text-sm mt-1">
                @if($user->school)
                    {{ $user->school }} —
                @endif
                Temukan jurusan kuliah terbaikmu
            </p>
        </div>
        <div class="hidden sm:block text-5xl">🎓</div>
    </div>
    @if(!$latestRecommendation)
    <div class="mt-4 flex flex-col sm:flex-row gap-3">
        <a href="{{ route('user.scores.index') }}"
           class="px-5 py-2.5 bg-white text-blue-700 font-bold rounded-xl hover:bg-blue-50 transition-colors text-sm text-center">
            Mulai Input Nilai →
        </a>
    </div>
    @endif
</div>

{{-- Stats --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="text-2xl mb-2">📊</div>
        <p class="text-2xl font-black text-gray-900">{{ $totalRecommendations }}</p>
        <p class="text-gray-500 text-sm">Total Rekomendasi</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="text-2xl mb-2">📋</div>
        <p class="text-2xl font-black text-gray-900">{{ $criteria->count() }}</p>
        <p class="text-gray-500 text-sm">Kriteria Penilaian</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="text-2xl mb-2">🥇</div>
        <p class="text-base font-black text-gray-900 truncate">
            {{ $latestRecommendation?->details->first()?->alternative?->nama ?? '-' }}
        </p>
        <p class="text-gray-500 text-sm">Rekomendasi Terakhir</p>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    {{-- Rekomendasi Terbaru --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between p-5 border-b border-gray-100">
            <h3 class="font-bold text-gray-900">Rekomendasi Terbaru</h3>
            @if($latestRecommendation)
            <a href="{{ route('user.history.index') }}" class="text-blue-600 text-xs hover:underline">Lihat semua</a>
            @endif
        </div>
        @if($latestRecommendation && $latestRecommendation->details->count() > 0)
        <div class="p-5 space-y-3">
            @foreach($latestRecommendation->details as $detail)
            <div class="flex items-center space-x-3">
                <div class="w-7 h-7 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0
                    {{ $detail->ranking === 1 ? 'bg-yellow-400 text-yellow-900' : ($detail->ranking === 2 ? 'bg-gray-300 text-gray-700' : 'bg-orange-300 text-orange-900') }}">
                    {{ $detail->ranking }}
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-gray-900 text-sm">{{ $detail->alternative->nama }}</span>
                        <span class="text-blue-600 text-xs font-bold">{{ round($detail->nilai_preferensi * 100, 1) }}%</span>
                    </div>
                    <div class="h-1.5 bg-gray-100 rounded-full mt-1 overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full"
                             style="width: {{ round($detail->nilai_preferensi * 100, 1) }}%"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="p-8 text-center">
            <div class="text-4xl mb-3">🎯</div>
            <p class="text-gray-500 text-sm mb-4">Anda belum memiliki rekomendasi.</p>
            <a href="{{ route('user.scores.index') }}"
               class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 text-sm transition-colors">
                Mulai Sekarang
            </a>
        </div>
        @endif
    </div>

    {{-- Chart --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between p-5 border-b border-gray-100">
            <h3 class="font-bold text-gray-900">Grafik Preferensi</h3>
            @if($latestRecommendation)
            <a href="{{ route('user.recommendation.index') }}" class="text-blue-600 text-xs hover:underline">Lihat detail →</a>
            @endif
        </div>
        <div class="p-5">
            @if(count($chartLabels) > 0)
            <div class="relative h-56">
                <canvas id="userChart"></canvas>
            </div>
            @else
            <div class="flex flex-col items-center justify-center h-48 text-center">
                <div class="text-4xl mb-3">📈</div>
                <p class="text-gray-400 text-sm">Belum ada data untuk divisualisasikan.</p>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="mt-6 grid grid-cols-2 sm:grid-cols-4 gap-4">
    <a href="{{ route('user.scores.index') }}" class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 text-center hover:shadow-md transition-all hover:-translate-y-0.5">
        <div class="text-3xl mb-2">✏️</div>
        <p class="font-semibold text-gray-900 text-sm">Input Nilai</p>
        <p class="text-gray-400 text-xs">Isi nilai kriteria Anda</p>
    </a>
    <a href="{{ route('user.recommendation.index') }}" class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 text-center hover:shadow-md transition-all hover:-translate-y-0.5">
        <div class="text-3xl mb-2">🎯</div>
        <p class="font-semibold text-gray-900 text-sm">Rekomendasi</p>
        <p class="text-gray-400 text-xs">Lihat ranking jurusan</p>
    </a>
    <a href="{{ route('user.history.index') }}" class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 text-center hover:shadow-md transition-all hover:-translate-y-0.5">
        <div class="text-3xl mb-2">📜</div>
        <p class="font-semibold text-gray-900 text-sm">Riwayat</p>
        <p class="text-gray-400 text-xs">Semua rekomendasi</p>
    </a>
    <a href="{{ route('profile.edit') }}" class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 text-center hover:shadow-md transition-all hover:-translate-y-0.5">
        <div class="text-3xl mb-2">👤</div>
        <p class="font-semibold text-gray-900 text-sm">Profil</p>
        <p class="text-gray-400 text-xs">Edit data diri</p>
    </a>
</div>
@endsection

@push('scripts')
@if(count($chartLabels) > 0)
<script>
new Chart(document.getElementById('userChart').getContext('2d'), {
    type: 'horizontalBar',
    type: 'bar',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'Nilai Preferensi (%)',
            data: @json($chartData),
            backgroundColor: ['rgba(234,179,8,0.8)','rgba(107,114,128,0.8)','rgba(180,83,9,0.8)'],
            borderRadius: 8,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false, indexAxis: 'y',
        plugins: { legend: { display: false }},
        scales: {
            x: { beginAtZero: true, max: 100, ticks: { callback: v => v+'%' }},
            y: { grid: { display: false }}
        }
    }
});
</script>
@endif
@endpush
