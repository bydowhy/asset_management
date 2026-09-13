<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EndAssetRelationshipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'valid_to' => ['required', 'date', 'after_or_equal:relationship.valid_from'],
        ];
    }
}