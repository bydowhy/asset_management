<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'document_type_id' => ['required', 'string', 'exists:document_types,id'],
            'description' => ['nullable', 'string'],
            'file' => ['required', 'file', 'mimes:pdf,docx,xlsx,pptx', 'max:20480'],
            'entity_type' => ['nullable', 'in:equipment,asset'],
            'entity_id' => ['nullable', 'string', 'required_with:entity_type'],
        ];
    }

    public function messages(): array
    {
        return [
            'file.mimes' => 'Format file harus PDF, DOCX, XLSX, atau PPTX.',
            'file.max' => 'Ukuran file maksimal 20 MB.',
        ];
    }
}