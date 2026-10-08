<?php

namespace App\Http\Requests;

use App\Models\AssetRelationship;
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
        $sourceAssetId = $this->route('asset')->id;

        return [
            'target_asset_id' => [
                'required', 'string', 'exists:assets,id',
                Rule::notIn([$sourceAssetId]),
            ],
            'relationship_type_id' => ['required', 'string', 'exists:relationship_types,id'],
            'valid_from' => [
                'required', 'date',
                // Custom rule: cek kombinasi unik di asset_relationships
                function ($attribute, $value, $fail) use ($sourceAssetId) {
                    $exists = AssetRelationship::where('source_asset_id', $sourceAssetId)
                        ->where('target_asset_id', $this->target_asset_id)
                        ->where('relationship_type_id', $this->relationship_type_id)
                        ->where('valid_from', $value)
                        ->exists();

                    if ($exists) {
                        $fail('Relationship dengan kombinasi ini pada tanggal tersebut sudah ada. Silakan gunakan tanggal berbeda atau akhiri relationship yang lama terlebih dahulu.');
                    }
                },
            ],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}