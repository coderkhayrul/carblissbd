<?php

namespace Database\Seeders;

use App\Models\{Product, Brand, Category, AttributeType, Attribute};
use App\Services\ProductService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $category = Category::firstOrCreate(['slug' => 'car-accessories'], ['name' => 'Car Accessories', 'status' => true]);
            $brand = Brand::firstOrCreate(['slug' => 'carbliss'], ['name' => 'CarBliss', 'status' => true]);
            $type = AttributeType::firstOrCreate(['name' => 'Color'], ['status' => true]);
            $colors = [];
            foreach (['Black' => '#000000', 'Red' => '#FF0000'] as $name => $code) {
                $colors[] = Attribute::firstOrCreate(['slug' => 'color-' . Str::slug($name)], [
                    'attribute_type_id' => $type->id, 'name' => $name, 'color_code' => $code, 'status' => true,
                ]);
            }

            $service = app(ProductService::class);
            foreach (['Steering Wheel Cover', 'Car Seat Cushion', 'Car Floor Mat', 'Seat Belt Pad',
                'Microfiber Cleaning Cloth', 'Car Phone Holder', 'Car Air Freshener', 'Car Sun Shade'] as $index => $name) {
                $slug = Str::slug($name);
                if (Product::where('slug', $slug)->exists()) continue;
                $sku = 'CB-DEMO-' . str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT);
                $price = 500 + $index * 150;
                $data = [
                    'name' => $name, 'slug' => $slug, 'sku' => $sku,
                    'category_id' => $category->id, 'brand_id' => $brand->id,
                    'purchase_price' => $price - 100, 'sale_price' => $price,
                    'bangla_sale_price' => $price, 'mrp_price' => $price + 100,
                    'status' => true, 'publish' => true, 'is_variation' => $index < 4,
                    'is_refundable' => true, 'is_new_arrival' => true,
                    'short_description' => 'Sample ' . strtolower($name) . ' for catalog setup.',
                    'description' => 'Sample product data. Update the specifications and prices before selling.',
                    'meta_title' => $name, 'tags' => [], 'variations' => [],
                ];
                if ($index < 4) {
                    foreach ($colors as $colorIndex => $color) {
                        $data['variations'][] = [
                            'name' => $name . ' - ' . $color->name, 'sku' => $sku . '-' . strtoupper($color->name),
                            'purchase_price' => $price - 100, 'sale_price' => $price + $colorIndex * 50,
                            'bangla_sale_price' => $price + $colorIndex * 50, 'mrp_price' => $price + 100,
                            'status' => true,
                            'attributes' => [['attribute_type_id' => $color->attribute_type_id, 'attribute_id' => $color->id]],
                        ];
                    }
                }
                $service->save(new Product, $data);
            }
        });
    }
}
