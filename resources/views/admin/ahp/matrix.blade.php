@extends('layouts.app')

@section('title', 'Input Matriks AHP')
@section('page-title', 'Matriks AHP')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-black text-gray-900">Input Matriks Perbandingan Berpasangan</h2>
        <p class="text-gray-500 text-sm mt-1">Isi nilai perbandingan menggunakan skala Saaty (1-9)</p>
    </div>
    <a href="{{ route('admin.ahp.calculate') }}"
       class="flex items-center space-x-2 px-4 py-2.5 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M12 7h.01M9 7h.01m6 0h.01"/>
        </svg>
        <span>Hitung AHP</span>
    </a>
</div>

{{-- Saaty Scale Reference --}}
<div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 mb-6">
    <h3 class="text-blue-800 font-semibold text-sm mb-2">📏 Panduan Skala Saaty</h3>
    <div class="grid grid-cols-3 sm:grid-cols-5 lg:grid-cols-9 gap-2">
        @php
            $saaty = [
                '1' => 'Sama penting', '2' => 'Antara', '3' => 'Agak lebih penting',
                '4' => 'Antara', '5' => 'Lebih penting', '6' => 'Antara',
                '7' => 'Sangat lebih penting', '8' => 'Antara', '9' => 'Mutlak lebih penting'
            ];
        @endphp
        @foreach($saaty as $val => $label)
        <div class="bg-white border border-blue-200 rounded-lg p-2 text-center">
            <div class="text-blue-700 font-black text-lg">{{ $val }}</div>
            <div class="text-blue-500 text-xs leading-tight">{{ $label }}</div>
        </div>
        @endforeach
    </div>
</div>

@if($n < 2)
<div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6 text-center">
    <div class="text-4xl mb-3">⚠️</div>
    <p class="text-yellow-800 font-semibold">Minimal 2 kriteria diperlukan.</p>
    <a href="{{ route('admin.criteria.create') }}" class="mt-3 inline-block text-blue-600 hover:underline text-sm">Tambah Kriteria →</a>
</div>
@else
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
        <p class="text-sm text-blue-700 font-medium">
            Matriks {{ $n }}×{{ $n }} — Isi bagian <strong>atas diagonal</strong> (bawah diagonal otomatis diisi dengan nilai resiprokal)
        </p>
    </div>
    <form method="POST" action="{{ route('admin.ahp.matrix.store') }}" class="p-6" id="ahpForm">
        @csrf
        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead>
                    <tr>
                        <th class="w-32 p-2"></th>
                        @foreach($criteria as $c)
                        <th class="p-2 text-center">
                            <div class="px-3 py-1.5 bg-blue-100 text-blue-800 rounded-lg text-xs font-bold">{{ $c->kode }}</div>
                            <div class="text-gray-400 text-xs mt-1">{{ Str::limit($c->nama, 12) }}</div>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($criteria as $i => $c1)
                    <tr class="{{ $i % 2 == 0 ? 'bg-gray-50' : 'bg-white' }}">
                        <td class="p-2">
                            <div class="px-3 py-1.5 bg-indigo-100 text-indigo-800 rounded-lg text-xs font-bold text-center">{{ $c1->kode }}</div>
                            <div class="text-gray-400 text-xs text-center mt-1">{{ Str::limit($c1->nama, 12) }}</div>
                        </td>
                        @foreach($criteria as $j => $c2)
                        <td class="p-2 text-center">
                            @if($c1->id === $c2->id)
                            {{-- Diagonal: always 1 --}}
                            <div class="w-20 h-10 mx-auto bg-blue-100 border-2 border-blue-300 rounded-xl flex items-center justify-center font-black text-blue-700 text-lg">1</div>
                            @elseif($c1->id < $c2->id)
                            {{-- Upper triangle: input --}}
                            <select name="matrix[{{ $c1->id }}][{{ $c2->id }}]"
                                    id="cell_{{ $c1->id }}_{{ $c2->id }}"
                                    class="w-20 h-10 mx-auto border-2 border-gray-200 rounded-xl text-sm font-semibold text-center focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white cursor-pointer"
                                    onchange="updateReciprocal({{ $c1->id }}, {{ $c2->id }}, this.value)">
                                @php
                                    $currentVal = $matrix[$c1->id][$c2->id] ?? 1;
                                    $options = [1/9, 1/8, 1/7, 1/6, 1/5, 1/4, 1/3, 1/2, 1, 2, 3, 4, 5, 6, 7, 8, 9];
                                    $labels = ['1/9','1/8','1/7','1/6','1/5','1/4','1/3','1/2','1','2','3','4','5','6','7','8','9'];
                                @endphp
                                @foreach($options as $idx => $opt)
                                <option value="{{ $opt }}" {{ abs($currentVal - $opt) < 0.01 ? 'selected' : '' }}>
                                    {{ $labels[$idx] }}
                                </option>
                                @endforeach
                            </select>
                            @else
                            {{-- Lower triangle: reciprocal (readonly display) --}}
                            @php
                                $recipVal = $matrix[$c1->id][$c2->id] ?? 1;
                                $displayLabels = ['1/9'=>1/9,'1/8'=>1/8,'1/7'=>1/7,'1/6'=>1/6,'1/5'=>1/5,'1/4'=>1/4,'1/3'=>1/3,'1/2'=>1/2,'1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5,'6'=>6,'7'=>7,'8'=>8,'9'=>9];
                                $label = '1';
                                foreach($displayLabels as $lbl => $v) {
                                    if(abs($recipVal - $v) < 0.02) { $label = $lbl; break; }
                                }
                            @endphp
                            <div class="w-20 h-10 mx-auto bg-gray-100 border-2 border-gray-200 rounded-xl flex items-center justify-center text-sm text-gray-500 font-medium"
                                 id="recip_{{ $c1->id }}_{{ $c2->id }}">
                                {{ $label }}
                            </div>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6 flex items-center space-x-3">
            <button type="submit" class="flex-1 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-colors">
                Simpan Matriks & Hitung AHP
            </button>
            <button type="button" onclick="resetMatrix()"
                    class="px-6 py-3 bg-gray-100 text-gray-600 font-semibold rounded-xl hover:bg-gray-200 transition-colors">
                Reset ke 1
            </button>
        </div>
    </form>
</div>
@endif
@endsection

@push('scripts')
<script>
// Mapping nilai ke label tampilan
const valueToLabel = {
    0.1111: '1/9', 0.1250: '1/8', 0.1429: '1/7', 0.1667: '1/6',
    0.2: '1/5', 0.25: '1/4', 0.3333: '1/3', 0.5: '1/2',
    1: '1', 2: '2', 3: '3', 4: '4', 5: '5', 6: '6', 7: '7', 8: '8', 9: '9'
};

const reciprocalMap = {
    0.1111: 9, 0.1250: 8, 0.1429: 7, 0.1667: 6, 0.2: 5, 0.25: 4,
    0.3333: 3, 0.5: 2, 1: 1, 2: 0.5, 3: 0.3333, 4: 0.25, 5: 0.2,
    6: 0.1667, 7: 0.1429, 8: 0.1250, 9: 0.1111
};

const labelMap = {
    0.1111: '1/9', 0.1250: '1/8', 0.1429: '1/7', 0.1667: '1/6', 0.2: '1/5',
    0.25: '1/4', 0.3333: '1/3', 0.5: '1/2', 1: '1', 2: '2', 3: '3',
    4: '4', 5: '5', 6: '6', 7: '7', 8: '8', 9: '9'
};

function updateReciprocal(id1, id2, value) {
    const recipValue = reciprocalMap[parseFloat(value)];
    const label = labelMap[recipValue] || recipValue;
    const recipCell = document.getElementById(`recip_${id2}_${id1}`);
    if (recipCell) recipCell.textContent = label;
}

function resetMatrix() {
    document.querySelectorAll('[name^="matrix"]').forEach(sel => {
        sel.value = 1;
    });
    document.querySelectorAll('[id^="recip_"]').forEach(div => {
        div.textContent = '1';
    });
}
</script>
@endpush
