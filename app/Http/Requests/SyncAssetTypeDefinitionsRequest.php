<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SyncAssetTypeDefinitionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'definitions' => ['present', 'array'],
            'definitions.*.id' => ['nullable', 'string'],
            'definitions.*.name' => ['required', 'string', 'max:100'],
            'definitions.*.code' => ['required', 'string', 'max:50', 'regex:/^[a-z][a-z0-9_]*$/'],
            'definitions.*.data_type' => ['required', 'in:decimal,varchar,integer,boolean,text'],
            'definitions.*.unit' => ['nullable', 'string', 'max:50'],
            'definitions.*.is_required' => ['required', 'boolean'],
            'definitions.*.sort_order' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'definitions.*.code.regex' => 'Code harus lowercase, dimulai dengan huruf, hanya boleh huruf/angka/underscore.',
        ];
    }
}