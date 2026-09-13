<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDocumentTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $typeId = $this->route('document_type')?->id;

        return [
            'name' => ['required', 'string', 'max:100'],
            'code' => [
                'required', 'string', 'max:50',
                Rule::unique('document_types', 'code')->ignore($typeId),
            ],
            'description' => ['nullable', 'string'],
        ];
    }
}