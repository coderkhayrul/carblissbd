<?php

namespace Database\Seeders;

use App\Models\Product;
use Database\Seeders\Support\DemoProductImages;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductVariationSeeder extends Seeder
{
    public function run(): void
    {
        // Variation rows and attribute combinations are created by ProductSeeder.
        // This seeder can also be rerun independently to fill or restore demo image files.
        DB::transaction(function () {
            Product::where('sku', 'like', 'CB-DEMO-%')->with(['variations' => fn ($query) => $query->orderBy('id')])
                ->orderBy('id')->chunkById(100, function ($products) {
                    foreach ($products as $product) {
                        preg_match('/^CB-DEMO-(\d+)/', $product->sku, $match);
                        $sourceIndex = (int) ($match[1] ?? $product->id);
                        foreach ($product->variations as $index => $variation) {
                            if ($variation->image && !str_starts_with($variation->image, 'uploads/products/demo-variation-')) continue;
                            $path = DemoProductImages::make('variation-' . $variation->sku, $sourceIndex + $index + 3);
                            if ($variation->image !== $path) {
                                $variation->image = $path;
                                $variation->saveOrFail();
                            }
                        }
                    }
                });
        });
    }
}
