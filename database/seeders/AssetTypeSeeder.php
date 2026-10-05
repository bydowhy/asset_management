<?php

namespace Database\Seeders;

use App\Models\AssetType;
use App\Models\AssetTypeDefinition;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AssetTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'Pump', 'code' => 'PUMP',
                'definitions' => [
                    ['Flow Rate', 'flow_rate',  'integer', 'm3/h'],
                    ['Head',      'head',       'integer', 'm'],
                    ['Power',     'power',      'integer', 'kW'],
                ],
            ],
            [
                'name' => 'Motor', 'code' => 'MOTOR',
                'definitions' => [
                    ['Voltage',   'voltage',    'integer', 'V'],
                    ['Current',   'current',    'integer', 'A'],
                    ['RPM',       'rpm',        'integer', 'rpm'],
                ],
            ],
            [
                'name' => 'Valve', 'code' => 'VALVE',
                'definitions' => [
                    ['Size',      'size',       'integer', 'inch'],
                    ['Pressure',  'pressure',   'integer', 'bar'],
                    ['Material',  'material',   'text',   null],
                ],
            ],
        ];

        foreach ($types as $typeData) {
            $type = AssetType::updateOrCreate(
                ['code' => $typeData['code']],
                [
                    'id' => (string) Str::uuid(),
                    'name' => $typeData['name'],
                    'description' => "Asset type for {$typeData['name']}",
                ]
            );

            foreach ($typeData['definitions'] as $i => [$name, $code, $dtype, $unit]) {
                AssetTypeDefinition::updateOrCreate(
                    ['asset_type_id' => $type->id, 'code' => $code],
                    [
                        'id' => (string) Str::uuid(),
                        'name' => $name,
                        'data_type' => $dtype,
                        'unit' => $unit,
                        'is_required' => true,
                        'sort_order' => $i,
                    ]
                );
            }
        }
    }
}