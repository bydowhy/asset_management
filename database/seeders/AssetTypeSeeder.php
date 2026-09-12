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
                    ['Flow Rate', 'flow_rate',  'number', 'm3/h'],
                    ['Head',      'head',       'number', 'm'],
                    ['Power',     'power',      'number', 'kW'],
                ],
            ],
            [
                'name' => 'Motor', 'code' => 'MOTOR',
                'definitions' => [
                    ['Voltage',   'voltage',    'number', 'V'],
                    ['Current',   'current',    'number', 'A'],
                    ['RPM',       'rpm',        'number', 'rpm'],
                ],
            ],
            [
                'name' => 'Valve', 'code' => 'VALVE',
                'definitions' => [
                    ['Size',      'size',       'number', 'inch'],
                    ['Pressure',  'pressure',   'number', 'bar'],
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