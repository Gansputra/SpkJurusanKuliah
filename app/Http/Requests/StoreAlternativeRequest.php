<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAlternativeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'kode' => 'required|string|max:10|unique:alternatives,kode',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode jurusan wajib diisi.',
            'kode.unique' => 'Kode jurusan sudah digunakan.',
            'nama.required' => 'Nama jurusan wajib diisi.',
        ];
    }
}
