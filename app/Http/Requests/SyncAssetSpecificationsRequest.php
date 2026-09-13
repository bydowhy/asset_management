<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SyncAssetSpecificationsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'specifications' => ['nullable', 'array'],
            'specifications.*' => ['nullable', 'string', 'max:65535'],
        ];
    }
}