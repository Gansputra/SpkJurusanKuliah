@extends('layouts.app')

@section('title', 'Input Nilai')
@section('page-title', 'Input Nilai')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-black text-gray-900">Input Nilai Diri Anda</h2>
        <p class="text-gray-500 text-sm mt-1">Isi nilai jujur berdasarkan kondisi Anda saat ini (skala 0-10)</p>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 mb-6">
        <div class="flex items-start space-x-3">
            <span class="text-2xl">💡</span>
            <div>
                <p class="text-blue-800 font-semibold text-sm">Panduan Pengisian</p>
                <ul class="text-blue-600 text-xs mt-1 space-y-1 list-disc list-inside">
                    <li>Nilai 1-3: Rendah / Kurang</li>
                    <li>Nilai 4-6: Sedang / Cukup</li>
                    <li>Nilai 7-8: Baik</li>
                    <li>Nilai 9-10: Sangat Baik / Excellent</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
            <h3 class="font-bold text-gray-900">Form Penilaian Diri</h3>
        </div>
        <form method="POST" action="{{ route('user.scores.store') }}" class="p-6 space-y-6" id="scoreForm">
            @csrf
            @foreach($criteria as $c)
            @php $savedVal = $sessionScores[$c->id] ?? 5; @endphp
            <div class="p-5 bg-gray-50 rounded-2xl">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <div class="flex items-center space-x-2 mb-1">
                            <span class="px-2.5 py-1 bg-blue-100 text-blue-700 rounded-lg font-bold text-xs">{{ $c->kode }}</span>
                            <span class="font-bold text-gray-900">{{ $c->nama }}</span>
                        </div>
                        <p class="text-xs {{ $c->tipe === 'benefit' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $c->tipe === 'benefit' ? '↑ Benefit: semakin tinggi semakin baik' : '↓ Cost: semakin rendah semakin baik' }}
                        </p>
                        @if($c->kode === 'C1')
                        <p class="text-gray-400 text-xs mt-1">Nilai rata-rata akademik / rapor Anda</p>
                        @elseif($c->kode === 'C2')
                        <p class="text-gray-400 text-xs mt-1">Seberapa besar minat Anda pada jurusan ini</p>
                        @elseif($c->kode === 'C3')
                        <p class="text-gray-400 text-xs mt-1">Seberapa besar bakat Anda di bidang ini</p>
                        @elseif($c->kode === 'C4')
                        <p class="text-gray-400 text-xs mt-1">Perkiraan peluang kerja di bidang ini</p>
                        @elseif($c->kode === 'C5')
                        <p class="text-gray-400 text-xs mt-1">Kemampuan biaya kuliah (semakin rendah = semakin terjangkau)</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <span id="display-{{ $c->id }}" class="text-3xl font-black text-blue-600">{{ number_format($savedVal, 1) }}</span>
                        <div class="text-xs text-gray-400">/ 10</div>
                    </div>
                </div>
                <input type="range" name="scores[{{ $c->id }}]"
                       id="range-{{ $c->id }}"
                       min="0" max="10" step="0.1"
                       value="{{ $savedVal }}"
                       class="w-full h-3 rounded-lg appearance-none cursor-pointer accent-blue-600"
                       oninput="updateDisplay('{{ $c->id }}', this.value)"/>
                <div class="flex justify-between text-xs text-gray-400 mt-2">
                    <span>0 (Rendah)</span>
                    <span>5 (Sedang)</span>
                    <span>10 (Tinggi)</span>
                </div>
                @error('scores.' . $c->id)
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endforeach

            <div class="flex items-center space-x-3 pt-2">
                <button type="submit" class="flex-1 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-colors">
                    Simpan & Lihat Rekomendasi
                </button>
                <a href="{{ route('user.dashboard') }}" class="px-6 py-3 bg-gray-100 text-gray-600 font-semibold rounded-xl hover:bg-gray-200 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateDisplay(id, value) {
    const el = document.getElementById('display-' + id);
    if (el) el.textContent = parseFloat(value).toFixed(1);
}
</script>
@endpush
