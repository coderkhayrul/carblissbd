<?php

namespace App\Services;

use App\Models\PromoAds;
use Illuminate\Support\Facades\File;

class PromoAdsService
{
    public function save(PromoAds $promoAd, array $data): void
    {
        $image = $data['image'] ?? null;
        unset($data['image']);
        $oldImage = $promoAd->image;
        $newImage = null;

        try {
            if ($image) {
                $directory = public_path('uploads/promo-ads');
                File::ensureDirectoryExists($directory);
                $filename = $image->hashName();
                $image->move($directory, $filename);
                $newImage = 'uploads/promo-ads/' . $filename;
                $data['image'] = $newImage;
            }

            $promoAd->fill($data);
            $promoAd->saveOrFail();
        } catch (\Throwable $exception) {
            if ($newImage) $this->deleteImage($newImage);
            throw $exception;
        }

        if ($newImage && $oldImage !== $newImage) $this->deleteImage($oldImage);
    }

    public function updateStatus(PromoAds $promoAd, bool $status): void
    {
        $promoAd->status = $status;
        $promoAd->saveOrFail();
    }

    public function delete(PromoAds $promoAd): void
    {
        if (!$promoAd->deleteOrFail()) throw new \RuntimeException('Unable to delete the promo ad.');
        $this->deleteImage($promoAd->image);
    }

    private function deleteImage(?string $image): void
    {
        $directory = realpath(public_path('uploads/promo-ads'));
        $path = $image ? realpath(public_path($image)) : false;
        if ($directory && $path && is_file($path)
            && str_starts_with(strtolower($path), strtolower($directory . DIRECTORY_SEPARATOR))) {
            File::delete($path);
        }
    }
}
