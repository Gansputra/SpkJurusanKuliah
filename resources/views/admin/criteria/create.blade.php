@extends('layouts.app')

@section('title', 'Tambah Kriteria')
@section('page-title', 'Tambah Kriteria')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.criteria.index') }}" class="inline-flex items-center space-x-2 text-gray-500 hover:text-blue-600 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            <span>Kembali ke Kriteria</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
            <h2 class="text-lg font-bold text-gray-900">Tambah Kriteria Baru</h2>
            <p class="text-gray-500 text-sm mt-1">Isi data kriteria penilaian</p>
        </div>
        <form method="POST" action="{{ route('admin.criteria.store') }}" class="p-6 space-y-5">
            @csrf
            <div>
                <label for="kode" class="block text-sm font-semibold text-gray-700 mb-2">Kode Kriteria <span class="text-red-500">*</span></label>
                <input type="text" id="kode" name="kode" value="{{ old('kode') }}"
                       placeholder="Contoh: C1, C2, ..."
                       class="w-full px-4 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('kode') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}"/>
                @error('kode') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">Nama Kriteria <span class="text-red-500">*</span></label>
                <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
                       placeholder="Contoh: Nilai Akademik"
                       class="w-full px-4 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('nama') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}"/>
                @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="tipe" class="block text-sm font-semibold text-gray-700 mb-2">Tipe Kriteria <span class="text-red-500">*</span></label>
                <select id="tipe" name="tipe"
                        class="w-full px-4 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('tipe') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}">
                    <option value="">-- Pilih Tipe --</option>
                    <option value="benefit" {{ old('tipe') === 'benefit' ? 'selected' : '' }}>Benefit (↑ Semakin besar semakin baik)</option>
                    <option value="cost" {{ old('tipe') === 'cost' ? 'selected' : '' }}>Cost (↓ Semakin kecil semakin baik)</option>
                </select>
                @error('tipe') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="urutan" class="block text-sm font-semibold text-gray-700 mb-2">Urutan <span class="text-red-500">*</span></label>
                <input type="number" id="urutan" name="urutan" value="{{ old('urutan', $nextUrutan) }}"
                       min="1"
                       class="w-full px-4 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('urutan') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}"/>
                @error('urutan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="pt-2 flex items-center space-x-3">
                <button type="submit" class="flex-1 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-colors">
                    Simpan Kriteria
                </button>
                <a href="{{ route('admin.criteria.index') }}" class="flex-1 py-3 text-center bg-gray-100 text-gray-600 font-semibold rounded-xl hover:bg-gray-200 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
