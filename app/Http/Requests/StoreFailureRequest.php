<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFailureRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'asset_id' => ['required', 'string', 'exists:assets,id'],
            'failure_date' => ['required', 'date'],
            'failure_type' => ['required', 'string', 'max:100'],
            'symptom' => ['nullable', 'string'],
            'root_cause' => ['nullable', 'string'],
            'action_taken' => ['nullable', 'string'],
            'downtime_hours' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ];
    }
}