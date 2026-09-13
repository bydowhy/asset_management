<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $assetId = $this->route('asset')?->id;

        return [
            'asset_code' => [
                'required', 'string', 'max:50',
                Rule::unique('assets', 'asset_code')->ignore($assetId),
            ],
            'asset_type_id' => ['required', 'string', 'exists:asset_types,id'],
            'manufacturer' => ['nullable', 'string', 'max:100'],
            'model' => ['nullable', 'string', 'max:100'],
            'serial_number' => ['nullable', 'string', 'max:100'],
            'status' => ['required', Rule::in(['active', 'inactive', 'scrapped'])],
            'description' => ['nullable', 'string'],

            // Spesifikasi dinamis: array dengan key = definition_id
            'specifications' => ['nullable', 'array'],
            'specifications.*' => ['nullable', 'string', 'max:65535'],
        ];
    }
}