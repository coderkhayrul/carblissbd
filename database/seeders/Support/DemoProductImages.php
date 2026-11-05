<?php

namespace Database\Seeders\Support;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DemoProductImages
{
    public static function make(string $name, int $sourceIndex): string
    {
        $path = 'uploads/products/demo-' . Str::slug($name) . '.jpeg';
        if (File::exists(public_path($path))) return $path;

        $number = (($sourceIndex - 1) % 15 + 15) % 15 + 1;
        $source = public_path('frontend/demo/' . $number . '.jpeg');
        if (!File::exists($source)) {
            throw new \RuntimeException('Demo product image is missing: frontend/demo/' . $number . '.jpeg');
        }
        File::ensureDirectoryExists(public_path('uploads/products'));
        if (!File::copy($source, public_path($path))) {
            throw new \RuntimeException('Unable to create demo product image: ' . $path);
        }
        return $path;
    }

    public static function gallery(string $slug, int $sourceIndex): array
    {
        $images = [];
        for ($number = 1; $number <= 3; $number++) {
            $images[] = self::make($slug . '-gallery-' . $number, $sourceIndex + $number - 1);
        }
        return $images;
    }
}
