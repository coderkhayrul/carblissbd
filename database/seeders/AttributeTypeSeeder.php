<?php

namespace Database\Seeders;

use App\Models\AttributeType;
use Illuminate\Database\Seeder;

class AttributeTypeSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            'Color' => 'Product color options.',
            'Size' => 'Product size options.',
            'Material' => 'Product construction material.',
            'Vehicle Model' => 'Compatible vehicle models.',
            'Model Year' => 'Compatible vehicle model years.',
            'Engine Capacity' => 'Engine displacement options.',
            'Fuel Type' => 'Compatible fuel types.',
            'Voltage' => 'Operating voltage options.',
            'Capacity' => 'Product capacity options.',
            'Fitment Position' => 'Installation position on the vehicle.',
        ] as $name => $description) {
            AttributeType::firstOrCreate(['name' => $name], ['description' => $description, 'status' => true]);
        }
    }
}
