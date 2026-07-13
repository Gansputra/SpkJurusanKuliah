<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePairwiseMatrixRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'matrix' => 'required|array',
            'matrix.*' => 'required|array',
            'matrix.*.*' => 'required|numeric|min:0.0001|max:9',
        ];
    }

    public function messages(): array
    {
        return [
            'matrix.required' => 'Data matriks wajib diisi.',
            'matrix.*.*.numeric' => 'Nilai matriks harus berupa angka.',
            'matrix.*.*.min' => 'Nilai matriks minimal 0.0001.',
            'matrix.*.*.max' => 'Nilai matriks maksimal 9 (skala Saaty).',
        ];
    }
}
