<?php

namespace Database\Seeders;

use App\Models\{Product, Brand, Category, AttributeType, Attribute};
use App\Services\ProductService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{DB, File};
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        File::ensureDirectoryExists(public_path('uploads/products'));
        $chart = 'uploads/products/demo-size-chart.png';
        if (!File::exists(public_path($chart))) {
            File::copy(public_path('frontend/demo/size-chart.png'), public_path($chart));
        }

        DB::transaction(function () use ($chart) {
            $brand = Brand::firstOrCreate(['slug' => 'carbliss'], ['name' => 'CarBliss', 'status' => true]);
            $attributes = [];
            foreach (['Color' => ['Black' => '#000000', 'White' => '#FFFFFF', 'Red' => '#FF0000'],
                'Size' => ['Small' => null, 'Medium' => null, 'Large' => null]] as $typeName => $values) {
                $type = AttributeType::firstOrCreate(['name' => $typeName], ['status' => true]);
                foreach ($values as $name => $code) {
                    $attributes[$typeName][] = Attribute::firstOrCreate(['slug' => Str::slug($typeName . '-' . $name)], [
                        'attribute_type_id' => $type->id, 'name' => $name, 'color_code' => $code, 'status' => true,
                    ]);
                }
            }

            $products = [
                ['Steering Wheel Cover', 'Interior Accessories'],
                ['Car Seat Cushion', 'Interior Accessories'],
                ['Car Floor Mat', 'Seat Covers & Floor Mats'],
                ['Seat Belt Pad', 'Interior Accessories'],
                ['Microfiber Cleaning Cloth', 'Car Care Products'],
                ['Car Phone Holder', 'Interior Accessories'],
                ['Car Air Freshener', 'Car Perfumes & Air Fresheners'],
                ['Car Sun Shade', 'Interior Accessories'],
                ['Leather & Tyre Wax', 'Car Care Products'],
                ['Dashboard Polish', 'Car Care Products'],
                ['Car Wash Shampoo', 'Car Care Products'],
                ['Glass Cleaner Spray', 'Car Care Products'],
                ['Alloy Wheel Cleaner', 'Car Care Products'],
                ['Car Body Cover', 'Exterior Accessories'],
                ['Universal Seat Cover', 'Seat Covers & Floor Mats'],
                ['LED Headlight Kit', 'Car Lighting'],
                ['Fog Light Assembly', 'Car Lighting'],
                ['Silicone Wiper Blade', 'Wipers & Washers'],
                ['Engine Air Filter', 'Air Filters'],
                ['Cabin Air Filter', 'Air Filters'],
                ['Synthetic Engine Oil', 'Oils & Lubricants'],
                ['Ceramic Brake Pads', 'Brake Parts'],
                ['Tyre Pressure Gauge', 'Car Tools & Equipment'],
                ['Portable Tyre Inflator', 'Car Tools & Equipment'],
                ['Car Vacuum Cleaner', 'Car Care Products'],
                ['Bluetooth Audio Receiver', 'Car Audio & Multimedia'],
                ['Reverse Parking Camera', 'Car Security Systems'],
                ['Car Door Edge Guard', 'Exterior Accessories'],
                ['Car Trunk Organizer', 'Interior Accessories'],
                ['Car Wash Sponge', 'Car Care Products'],
            ];
            // A balanced mix, randomly assigned on the first seed only.
            $modes = array_merge(array_fill(0, 10, 'Color'), array_fill(0, 10, 'Size'), array_fill(0, 10, null));
            shuffle($modes);
            $service = app(ProductService::class);

            foreach ($products as $index => [$name, $categoryName]) {
                $slug = Str::slug($name);
                $image = 'uploads/products/demo-' . $slug . '.jpeg';
                if (!File::exists(public_path($image))) {
                    File::copy(public_path('frontend/demo/' . (($index % 15) + 1) . '.jpeg'), public_path($image));
                }
                $product = Product::where('slug', $slug)->first();
                if ($product) {
                    $missing = [];
                    if (!$product->thumbnail_image) $missing['thumbnail_image'] = $image;
                    if (!$product->size_chart_image) $missing['size_chart_image'] = $chart;
                    if ($missing) $product->update($missing);
                    continue;
                }

                $category = Category::firstOrCreate(['slug' => Str::slug($categoryName)], ['name' => $categoryName, 'status' => true]);
                $sku = 'CB-DEMO-' . str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT);
                $price = random_int(5, 60) * 100;
                $mode = $modes[$index];
                $data = [
                    'name' => $name, 'slug' => $slug, 'sku' => $sku,
                    'category_id' => $category->id, 'brand_id' => $brand->id,
                    'purchase_price' => $price - 200, 'sale_price' => $price,
                    'bangla_sale_price' => $price, 'mrp_price' => $price + 300,
                    'status' => true, 'publish' => true, 'is_variation' => $mode !== null,
                    'is_refundable' => true, 'is_new_arrival' => true,
                    'short_description' => 'Demo ' . strtolower($name) . ' for your car.',
                    'description' => 'Demo catalog product with sample images and prices. Replace with actual specifications before selling.',
                    'meta_title' => $name, 'tags' => [], 'variations' => [],
                ];
                if ($mode !== null) {
                    $options = collect($attributes[$mode])->shuffle()->take(random_int(2, 3));
                    foreach ($options->values() as $optionIndex => $option) {
                        $optionPrice = $price + $optionIndex * 100;
                        $data['variations'][] = [
                            'name' => $name . ' - ' . $option->name, 'sku' => $sku . '-' . strtoupper($option->name),
                            'purchase_price' => $price - 200, 'sale_price' => $optionPrice,
                            'bangla_sale_price' => $optionPrice, 'mrp_price' => $price + 300,
                            'status' => true,
                            'attributes' => [['attribute_type_id' => $option->attribute_type_id, 'attribute_id' => $option->id]],
                        ];
                    }
                }
                $product = new Product;
                $service->save($product, $data);
                $product->update(['thumbnail_image' => $image, 'size_chart_image' => $chart]);
            }
        });
    }
}