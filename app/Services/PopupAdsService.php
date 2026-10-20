<?php

namespace App\Services;

use App\Models\PopupAds;
use Illuminate\Support\Facades\File;

class PopupAdsService
{
    public function save(PopupAds $popup, array $data): void
    {
        $image = $data['image'] ?? null;
        unset($data['image']);
        $oldImage = $popup->image;
        $newImage = null;

        try {
            if ($image) {
                $directory = public_path('uploads/popup-ads');
                File::ensureDirectoryExists($directory);
                $filename = $image->hashName();
                $image->move($directory, $filename);
                $newImage = 'uploads/popup-ads/' . $filename;
                $data['image'] = $newImage;
            }

            $popup->fill($data);
            $popup->saveOrFail();
        } catch (\Throwable $exception) {
            if ($newImage) $this->deleteImage($newImage);
            throw $exception;
        }

        if ($newImage && $oldImage !== $newImage) $this->deleteImage($oldImage);
    }

    public function updateStatus(PopupAds $popup, bool $status): void
    {
        $popup->status = $status;
        $popup->saveOrFail();
    }

    private function deleteImage(?string $image): void
    {
        $directory = realpath(public_path('uploads/popup-ads'));
        $path = $image ? realpath(public_path($image)) : false;
        if ($directory && $path && is_file($path)
            && str_starts_with(strtolower($path), strtolower($directory . DIRECTORY_SEPARATOR))) {
            File::delete($path);
        }
    }
}
