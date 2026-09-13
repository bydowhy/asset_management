<?php

namespace App\Http\Requests;

use App\Models\Location;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $locationId = $this->route('location')?->id;

        return [
            'parent_id' => [
                'nullable',
                'string',
                'exists:locations,id',
                function ($attribute, $value, $fail) use ($locationId) {
                    // Cegah circular: parent tidak boleh dirinya sendiri
                    if ($value && $value === $locationId) {
                        $fail('Location tidak boleh menjadi parent dirinya sendiri.');
                    }

                    // Cegah circular: parent tidak boleh descendant dari location ini
                    if ($value && $locationId && $this->isDescendantOf($value, $locationId)) {
                        $fail('Location yang dipilih adalah turunan dari location ini.');
                    }
                },
            ],
            'name' => ['required', 'string', 'max:100'],
            'code' => [
                'required', 'string', 'max:50',
                Rule::unique('locations', 'code')->ignore($locationId),
            ],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Cek apakah $candidateId adalah descendant dari $ancestorId.
     */
    private function isDescendantOf(string $candidateId, string $ancestorId): bool
    {
        $current = Location::find($candidateId);

        while ($current) {
            if ($current->parent_id === $ancestorId) {
                return true;
            }
            $current = $current->parent;
        }

        return false;
    }
}