<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCriteriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'kode' => 'required|string|max:10|unique:criteria,kode,' . $this->route('criteria'),
            'nama' => 'required|string|max:255',
            'tipe' => 'required|in:benefit,cost',
            'urutan' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode kriteria wajib diisi.',
            'kode.unique' => 'Kode kriteria sudah digunakan.',
            'nama.required' => 'Nama kriteria wajib diisi.',
            'tipe.required' => 'Tipe kriteria wajib dipilih.',
            'tipe.in' => 'Tipe kriteria harus benefit atau cost.',
            'urutan.required' => 'Urutan kriteria wajib diisi.',
        ];
    }
}
