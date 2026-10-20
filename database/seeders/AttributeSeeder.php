<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            'Color' => ['Black', 'White', 'Red'],
            'Size' => ['Small', 'Medium', 'Large'],
            'Material' => ['Aluminium', 'Steel', 'ABS Plastic'],
            'Vehicle Model' => ['Toyota Corolla', 'Honda Civic', 'Nissan X-Trail'],
            'Model Year' => ['2022', '2023', '2024'],
            'Engine Capacity' => ['1500cc', '1800cc', '2000cc'],
            'Fuel Type' => ['Petrol', 'Diesel', 'Hybrid'],
            'Voltage' => ['12V', '24V', '48V'],
            'Capacity' => ['45Ah', '60Ah', '80Ah'],
            'Fitment Position' => ['Front', 'Rear', 'Left'],
        ];
        $colors = ['Black' => '#000000', 'White' => '#FFFFFF', 'Red' => '#FF0000'];

        foreach ($groups as $typeName => $values) {
            $type = AttributeType::firstOrCreate(['name' => $typeName], ['status' => true]);
            foreach ($values as $name) {
                Attribute::firstOrCreate(['slug' => Str::slug($typeName . '-' . $name)], [
                    'attribute_type_id' => $type->id,
                    'name' => $name,
                    'color_code' => $typeName === 'Color' ? $colors[$name] : null,
                    'description' => $typeName . ': ' . $name,
                    'status' => true,
                ]);
            }
        }
    }
}
