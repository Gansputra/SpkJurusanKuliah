@extends('layouts.app')

@section('title', 'Edit Nilai ' . $alternative->nama)
@section('page-title', 'Edit Nilai Alternatif')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.scores.index') }}" class="inline-flex items-center space-x-2 text-gray-500 hover:text-blue-600 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            <span>Kembali ke Nilai Alternatif</span>
        </a>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="flex items-center space-x-3">
                <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-lg font-bold text-sm">{{ $alternative->kode }}</span>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">{{ $alternative->nama }}</h2>
                    <p class="text-gray-500 text-sm">Isi nilai untuk setiap kriteria (skala 0-10)</p>
                </div>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.scores.update', $alternative) }}" class="p-6 space-y-4">
            @csrf @method('PUT')
            @foreach($criteria as $c)
            @php
                $score = $scores->get($c->id);
            @endphp
            <div class="p-4 bg-gray-50 rounded-xl">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded font-bold text-xs">{{ $c->kode }}</span>
                            <span class="font-semibold text-gray-900 text-sm">{{ $c->nama }}</span>
                        </div>
                        <span class="text-xs {{ $c->tipe === 'benefit' ? 'text-green-600' : 'text-red-600' }} mt-1 block">
                            {{ $c->tipe === 'benefit' ? '↑ Benefit: nilai lebih besar lebih baik' : '↓ Cost: nilai lebih kecil lebih baik' }}
                        </span>
                    </div>
                    <span id="val-{{ $c->id }}" class="text-2xl font-black text-blue-600">
                        {{ $score ? number_format($score->nilai, 1) : '0.0' }}
                    </span>
                </div>
                <input type="range" name="scores[{{ $c->id }}]"
                       min="0" max="10" step="0.1"
                       value="{{ $score ? $score->nilai : 0 }}"
                       class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-600"
                       oninput="document.getElementById('val-{{ $c->id }}').textContent = parseFloat(this.value).toFixed(1); document.getElementById('num-{{ $c->id }}').value = parseFloat(this.value).toFixed(1);"/>
                <div class="flex items-center justify-between mt-2">
                    <span class="text-xs text-gray-400">0</span>
                    <input type="number" id="num-{{ $c->id }}" min="0" max="10" step="0.1"
                           value="{{ $score ? number_format($score->nilai, 1) : '0.0' }}"
                           class="w-16 text-center text-sm border border-gray-200 rounded-lg py-1 focus:ring-2 focus:ring-blue-500"
                           oninput="let r = document.querySelector('[name=\'scores[{{ $c->id }}]\']'); r.value = this.value; document.getElementById('val-{{ $c->id }}').textContent = parseFloat(this.value || 0).toFixed(1);"/>
                    <span class="text-xs text-gray-400">10</span>
                </div>
            </div>
            @endforeach
            <div class="pt-2 flex items-center space-x-3">
                <button type="submit" class="flex-1 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-colors">
                    Simpan Nilai
                </button>
                <a href="{{ route('admin.scores.index') }}" class="flex-1 py-3 text-center bg-gray-100 text-gray-600 font-semibold rounded-xl hover:bg-gray-200 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
