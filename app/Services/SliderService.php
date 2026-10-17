<?php

namespace App\Services;

use App\Models\Slider;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

class SliderService
{
    public function update(Slider $slider, array $data, ?UploadedFile $image = null): void
    {
        $newImagePath = null;
        $oldImagePath = $slider->image;

        if ($image) {
            $directory = public_path('uploads/sliders');
            File::ensureDirectoryExists($directory);
            $filename = $image->hashName();
            $image->move($directory, $filename);
            $newImagePath = $directory . DIRECTORY_SEPARATOR . $filename;
            $data['image'] = 'uploads/sliders/' . $filename;
        }

        try {
            DB::transaction(function () use ($slider, $data, $newImagePath, $oldImagePath) {
                $slider->update($data);

                if ($newImagePath) {
                    $this->deleteImage($oldImagePath);
                }
            });
        } catch (Throwable $e) {
            if ($newImagePath) {
                File::delete($newImagePath);
            }
            throw $e;
        }
    }

    private function deleteImage(?string $image): void
    {
        if (!$image) return;

        $imagePath = realpath(public_path($image));
        $uploadDirectory = realpath(public_path('uploads/sliders'));

        if ($imagePath && $uploadDirectory && is_file($imagePath)
            && str_starts_with(strtolower($imagePath), strtolower($uploadDirectory . DIRECTORY_SEPARATOR))) {
            if (!File::delete($imagePath)) {
                throw new RuntimeException('Unable to delete the slider image.');
            }
        }
    }

    public function delete(Slider $slider): void
    {
        DB::transaction(function () use ($slider) {
            $slider->delete();

            if (!$slider->image) {
                return;
            }

            $imagePath = realpath(public_path($slider->image));
            $uploadDirectory = realpath(public_path('uploads/sliders'));

            // Only remove files belonging to the slider upload directory.
            if ($imagePath && $uploadDirectory && is_file($imagePath)
                && str_starts_with(strtolower($imagePath), strtolower($uploadDirectory . DIRECTORY_SEPARATOR))) {
                if (!File::delete($imagePath)) {
                    throw new RuntimeException('Unable to delete the slider image.');
                }
            }
        });
    }

    public function create(array $data, UploadedFile $image): Slider
    {
        $directory = public_path('uploads/sliders');
        File::ensureDirectoryExists($directory);
        $filename = $image->hashName();
        $image->move($directory, $filename);
        $data['image'] = 'uploads/sliders/' . $filename;

        try {
            return Slider::create($data);
        } catch (Throwable $e) {
            File::delete($directory . DIRECTORY_SEPARATOR . $filename);
            throw $e;
        }
    }
}
