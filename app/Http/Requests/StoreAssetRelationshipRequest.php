<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAssetRelationshipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'target_asset_id' => [
                'required', 'string', 'exists:assets,id',
                Rule::notIn([$this->route('asset')->id]),
            ],
            'relationship_type_id' => ['required', 'string', 'exists:relationship_types,id'],
            'valid_from' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}