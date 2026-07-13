<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAlternativeScoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'scores' => 'required|array',
            'scores.*' => 'required|array',
            'scores.*.*' => 'required|numeric|min:0|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'scores.required' => 'Data nilai wajib diisi.',
            'scores.*.*.numeric' => 'Nilai harus berupa angka.',
            'scores.*.*.min' => 'Nilai tidak boleh kurang dari 0.',
            'scores.*.*.max' => 'Nilai tidak boleh lebih dari 100.',
        ];
    }
}
