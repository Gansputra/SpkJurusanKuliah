@extends('layouts.app')

@section('title', 'Nilai Alternatif')
@section('page-title', 'Nilai Alternatif')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-black text-gray-900">Matriks Nilai Alternatif</h2>
        <p class="text-gray-500 text-sm mt-1">Nilai setiap jurusan untuk setiap kriteria penilaian</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <p class="text-sm text-blue-700 font-medium">
                {{ $alternatives->count() }} jurusan × {{ $criteria->count() }} kriteria
            </p>
            <p class="text-xs text-gray-500">Klik "Edit" untuk memperbarui nilai per jurusan</p>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase sticky left-0 bg-gray-50">Jurusan</th>
                    @foreach($criteria as $c)
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase min-w-24">
                        <div>{{ $c->kode }}</div>
                        <div class="font-normal normal-case text-gray-400">{{ Str::limit($c->nama, 15) }}</div>
                        <div>
                            <span class="{{ $c->tipe === 'benefit' ? 'text-green-500' : 'text-red-500' }} text-xs">
                                {{ $c->tipe === 'benefit' ? '↑' : '↓' }}
                            </span>
                        </div>
                    </th>
                    @endforeach
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($alternatives as $alt)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 sticky left-0 bg-white">
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded text-xs font-bold">{{ $alt->kode }}</span>
                            <span class="font-semibold text-gray-900 text-sm">{{ $alt->nama }}</span>
                        </div>
                    </td>
                    @foreach($criteria as $c)
                    @php
                        $score = $alt->scores->where('criteria_id', $c->id)->first();
                    @endphp
                    <td class="px-4 py-3 text-center">
                        @if($score)
                        <span class="font-mono font-semibold text-gray-900 text-sm">{{ number_format($score->nilai, 2) }}</span>
                        @else
                        <span class="text-gray-300 text-sm">—</span>
                        @endif
                    </td>
                    @endforeach
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('admin.scores.edit', $alt) }}"
                           class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-semibold hover:bg-blue-100 transition-colors">
                            Edit
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
