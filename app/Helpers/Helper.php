<?php

use Illuminate\Support\Facades\File;

if (!function_exists('makeDirectory')) {
    function makeDirectory($location): void
    {
        if (!File::isDirectory(public_path() . $location)) {
            File::makeDirectory(public_path() . $location, 0777, true, true);
        }
    }
}

if (!function_exists('saveImage')) {
    function saveImage($image, $location): string
    {
        makeDirectory($location);
        $imageName = random_int(10000000, 99999999) . '.' . $image->getClientOriginalExtension();
        $image->move(public_path() . $location, $imageName);

        return $location . $imageName;
    }
}

// Delete Image
if (!function_exists('deleteImage')) {
    function deleteImage(string $image): void
    {
        if (!$image) {
            return; // Do nothing if there's no image to delete
        }
        if (File::exists(public_path() . $image)) {
            File::delete(public_path() . $image);
        }
    }
}
