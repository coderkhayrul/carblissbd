<?php

namespace Database\Seeders;

use App\Models\Season;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SeasonSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Summer', 'Monsoon', 'Autumn', 'Late Autumn', 'Winter', 'Spring', 'All Season', 'Festive Season'] as $name) {
            Season::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name, 'status' => true]);
        }
    }
}
