<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserScoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'scores' => 'required|array',
            'scores.*' => 'required|numeric|min:0|max:10',
        ];
    }

    public function messages(): array
    {
        return [
            'scores.required' => 'Data nilai wajib diisi.',
            'scores.*.required' => 'Semua nilai kriteria harus diisi.',
            'scores.*.numeric' => 'Nilai harus berupa angka.',
            'scores.*.min' => 'Nilai minimal 0.',
            'scores.*.max' => 'Nilai maksimal 10.',
        ];
    }
}
