<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InstallAssetRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'asset_id' => ['required', 'string', 'exists:assets,id'],
            'relationship_role' => ['required', 'string', 'max:50'],
            'installed_at' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}