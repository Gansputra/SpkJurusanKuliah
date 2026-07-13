@extends('layouts.app')

@section('title', 'Edit Jurusan')
@section('page-title', 'Edit Jurusan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.alternatives.index') }}" class="inline-flex items-center space-x-2 text-gray-500 hover:text-blue-600 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            <span>Kembali ke Jurusan</span>
        </a>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-amber-50 to-yellow-50">
            <h2 class="text-lg font-bold text-gray-900">Edit Jurusan: <span class="text-blue-600">{{ $alternative->nama }}</span></h2>
        </div>
        <form method="POST" action="{{ route('admin.alternatives.update', $alternative) }}" class="p-6 space-y-5">
            @csrf @method('PUT')
            <div>
                <label for="kode" class="block text-sm font-semibold text-gray-700 mb-2">Kode <span class="text-red-500">*</span></label>
                <input type="text" id="kode" name="kode" value="{{ old('kode', $alternative->kode) }}"
                       class="w-full px-4 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500 {{ $errors->has('kode') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}"/>
                @error('kode') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">Nama <span class="text-red-500">*</span></label>
                <input type="text" id="nama" name="nama" value="{{ old('nama', $alternative->nama) }}"
                       class="w-full px-4 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500 {{ $errors->has('nama') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}"/>
                @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="3"
                          class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 resize-none">{{ old('deskripsi', $alternative->deskripsi) }}</textarea>
            </div>
            <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-xl">
                <input type="checkbox" id="active" name="active" value="1" {{ old('active', $alternative->active) ? 'checked' : '' }}
                       class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500"/>
                <label for="active" class="text-sm font-medium text-gray-700">Jurusan aktif</label>
            </div>
            <div class="pt-2 flex items-center space-x-3">
                <button type="submit" class="flex-1 py-3 bg-amber-500 text-white font-bold rounded-xl hover:bg-amber-600 transition-colors">
                    Update Jurusan
                </button>
                <a href="{{ route('admin.alternatives.index') }}" class="flex-1 py-3 text-center bg-gray-100 text-gray-600 font-semibold rounded-xl hover:bg-gray-200 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
