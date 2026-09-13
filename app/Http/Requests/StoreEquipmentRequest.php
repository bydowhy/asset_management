<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEquipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $equipmentId = $this->route('equipment')?->id;

        return [
            'location_id' => ['required', 'string', 'exists:locations,id'],
            'tag' => [
                'required', 'string', 'max:50',
                Rule::unique('equipment', 'tag')->ignore($equipmentId),
            ],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'equipment_type' => ['nullable', 'string', 'max:50'],
        ];
    }
}