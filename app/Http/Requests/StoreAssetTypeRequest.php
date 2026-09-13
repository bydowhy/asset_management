<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAssetTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $assetTypeId = $this->route('asset_type')?->id;

        return [
            'name' => ['required', 'string', 'max:100'],
            'code' => [
                'required', 'string', 'max:50',
                Rule::unique('asset_types', 'code')->ignore($assetTypeId),
            ],
            'description' => ['nullable', 'string'],
        ];
    }
}