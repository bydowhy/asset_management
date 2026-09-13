<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFailureRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'failure_type' => ['required', 'string', 'max:100'],
            'symptom' => ['nullable', 'string'],
            'root_cause' => ['nullable', 'string'],
            'action_taken' => ['nullable', 'string'],
            'downtime_hours' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ];
    }
}