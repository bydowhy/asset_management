<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRelationshipTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $typeId = $this->route('relationship_type')?->id;

        return [
            'name' => ['required', 'string', 'max:100'],
            'code' => [
                'required', 'string', 'max:50',
                Rule::unique('relationship_types', 'code')->ignore($typeId),
            ],
            'description' => ['nullable', 'string'],
        ];
    }
}