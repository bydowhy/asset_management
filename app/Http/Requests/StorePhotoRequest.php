<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePhotoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'caption' => ['nullable', 'string', 'max:255'],
            'taken_at' => ['nullable', 'date'],
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:10240'],
            'entity_type' => ['nullable', 'in:equipment,asset'],
            'entity_id' => ['nullable', 'string', 'required_with:entity_type'],
        ];
    }

    public function messages(): array
    {
        return [
            'file.mimes' => 'Format foto harus JPG, JPEG, atau PNG.',
            'file.max' => 'Ukuran foto maksimal 10 MB.',
        ];
    }
}