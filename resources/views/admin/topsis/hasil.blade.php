@extends('layouts.app')

@section('title', 'Hasil Perhitungan TOPSIS')
@section('page-title', 'Perhitungan TOPSIS')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-black text-gray-900">Hasil Perhitungan TOPSIS</h2>
        <p class="text-gray-500 text-sm mt-1">Technique for Order of Preference by Similarity to Ideal Solution</p>
    </div>
    <div class="flex space-x-2">
        <a href="{{ route('admin.ahp.calculate') }}" class="px-4 py-2.5 bg-gray-100 text-gray-600 font-semibold rounded-xl hover:bg-gray-200 text-sm">
            Lihat AHP
        </a>
        <a href="{{ route('admin.ranking.index') }}" class="px-4 py-2.5 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 text-sm">
            Lihat Ranking →
        </a>
    </div>
</div>

{{-- Bobot AHP yang digunakan --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6 p-5">
    <h3 class="font-bold text-gray-900 mb-3">Bobot AHP yang Digunakan</h3>
    <div class="flex flex-wrap gap-3">
        @foreach($result['criteria'] as $j => $crit)
        @php
            $w = $result['weights'][$crit['id']] ?? 0;
        @endphp
        <div class="flex items-center space-x-2 px-3 py-2 bg-blue-50 border border-blue-200 rounded-xl">
            <span class="text-blue-700 font-bold text-sm">{{ $crit['kode'] }}</span>
            <span class="text-blue-900 text-sm">{{ $crit['nama'] }}</span>
            <span class="px-2 py-0.5 bg-blue-600 text-white rounded-full text-xs font-bold">{{ number_format($w * 100, 2) }}%</span>
            <span class="text-xs {{ $crit['tipe'] === 'benefit' ? 'text-green-600' : 'text-red-600' }}">
                {{ $crit['tipe'] === 'benefit' ? '↑' : '↓' }}
            </span>
        </div>
        @endforeach
    </div>
</div>

{{-- Step 1: Matriks Keputusan --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-blue-50">
        <h3 class="font-bold text-blue-900">Langkah 1 — Matriks Keputusan</h3>
    </div>
    <div class="p-4 overflow-x-auto">
        @include('admin.topsis._matrix_table', ['matrix' => $result['decision_matrix'], 'criteria' => $result['criteria'], 'alternatives' => $result['alternatives'], 'format' => 4])
    </div>
</div>

{{-- Step 2: Matriks Normalisasi --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-indigo-50">
        <h3 class="font-bold text-indigo-900">Langkah 2 — Normalisasi Matriks (r_ij = x_ij / √Σx²_ij)</h3>
        <div class="flex flex-wrap gap-2 mt-2">
            @foreach($result['col_norms'] as $j => $norm)
            <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full">
                √Σ{{ $result['criteria'][$j]['kode'] }}² = {{ number_format($norm, 4) }}
            </span>
            @endforeach
        </div>
    </div>
    <div class="p-4 overflow-x-auto">
        @include('admin.topsis._matrix_table', ['matrix' => $result['normalized_matrix'], 'criteria' => $result['criteria'], 'alternatives' => $result['alternatives'], 'format' => 6])
    </div>
</div>

{{-- Step 3: Normalisasi Terbobot --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-purple-50">
        <h3 class="font-bold text-purple-900">Langkah 3 — Normalisasi Terbobot (v_ij = w_j × r_ij)</h3>
    </div>
    <div class="p-4 overflow-x-auto">
        @include('admin.topsis._matrix_table', ['matrix' => $result['weighted_matrix'], 'criteria' => $result['criteria'], 'alternatives' => $result['alternatives'], 'format' => 6])
    </div>
</div>

{{-- Step 4 & 5: Ideal Positif & Negatif --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-green-50">
        <h3 class="font-bold text-green-900">Langkah 4 & 5 — Solusi Ideal Positif (A⁺) dan Negatif (A⁻)</h3>
    </div>
    <div class="p-4 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-2 text-left text-gray-500 font-semibold">Solusi</th>
                    @foreach($result['criteria'] as $c)
                    <th class="px-4 py-2 text-center text-gray-700 font-bold">
                        {{ $c['kode'] }}
                        <span class="text-xs {{ $c['tipe'] === 'benefit' ? 'text-green-500' : 'text-red-500' }}">
                            {{ $c['tipe'] === 'benefit' ? '↑' : '↓' }}
                        </span>
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <tr class="bg-green-50 border-t border-gray-100">
                    <td class="px-4 py-3 font-bold text-green-700">A⁺ (Ideal Positif)</td>
                    @foreach($result['ideal_positive'] as $val)
                    <td class="px-4 py-3 text-center font-mono font-bold text-green-700">{{ number_format($val, 6) }}</td>
                    @endforeach
                </tr>
                <tr class="bg-red-50 border-t border-gray-100">
                    <td class="px-4 py-3 font-bold text-red-700">A⁻ (Ideal Negatif)</td>
                    @foreach($result['ideal_negative'] as $val)
                    <td class="px-4 py-3 text-center font-mono font-bold text-red-700">{{ number_format($val, 6) }}</td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
</div>

{{-- Step 6-8: D+, D-, Preferensi, Ranking --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-amber-50">
        <h3 class="font-bold text-amber-900">Langkah 6-9 — Jarak, Preferensi & Ranking</h3>
        <p class="text-amber-600 text-xs mt-1">D⁺ = √Σ(v_ij - A⁺_j)² &nbsp;|&nbsp; D⁻ = √Σ(v_ij - A⁻_j)² &nbsp;|&nbsp; Vi = D⁻ / (D⁺ + D⁻)</p>
    </div>
    <div class="p-4 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-2 text-left text-gray-500 font-semibold">No</th>
                    <th class="px-4 py-2 text-left text-gray-500 font-semibold">Jurusan</th>
                    <th class="px-4 py-2 text-center text-gray-700 font-bold">D⁺</th>
                    <th class="px-4 py-2 text-center text-gray-700 font-bold">D⁻</th>
                    <th class="px-4 py-2 text-center text-gray-700 font-bold">Vi (Preferensi)</th>
                    <th class="px-4 py-2 text-center text-gray-700 font-bold">Progress</th>
                    <th class="px-4 py-2 text-center text-gray-700 font-bold">Ranking</th>
                </tr>
            </thead>
            <tbody>
                @foreach($result['results'] as $r)
                <tr class="border-t border-gray-50 hover:bg-gray-50 transition-colors {{ $r['ranking'] === 1 ? 'bg-yellow-50' : '' }}">
                    <td class="px-4 py-3 text-gray-500">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3">
                        <div class="font-semibold text-gray-900">{{ $r['alternative']['nama'] }}</div>
                        <div class="text-gray-400 text-xs">{{ $r['alternative']['kode'] }}</div>
                    </td>
                    <td class="px-4 py-3 text-center font-mono text-red-600">{{ number_format($r['d_plus'], 6) }}</td>
                    <td class="px-4 py-3 text-center font-mono text-green-600">{{ number_format($r['d_minus'], 6) }}</td>
                    <td class="px-4 py-3 text-center font-mono font-black text-blue-700 text-base">{{ number_format($r['nilai_preferensi'], 6) }}</td>
                    <td class="px-4 py-3 w-28">
                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-blue-500 to-blue-600 rounded-full"
                                 style="width: {{ round($r['nilai_preferensi'] * 100, 1) }}%"></div>
                        </div>
                        <p class="text-xs text-center text-gray-400 mt-0.5">{{ round($r['nilai_preferensi'] * 100, 1) }}%</p>
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($r['ranking'] <= 3)
                        <span class="text-2xl">{{ ['🥇','🥈','🥉'][$r['ranking']-1] }}</span>
                        @else
                        <span class="w-8 h-8 inline-flex items-center justify-center bg-gray-100 text-gray-600 rounded-full font-bold text-sm">{{ $r['ranking'] }}</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
