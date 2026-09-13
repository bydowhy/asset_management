<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReplaceAssetRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'asset_id' => ['required', 'string', 'exists:assets,id'],
            'replaced_at' => ['required', 'date'],
            'close_relationships' => ['boolean'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}