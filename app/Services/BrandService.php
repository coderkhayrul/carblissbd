<?php

namespace App\Services;

use App\Models\Brand;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;

class BrandService
{
    public function save(Brand $brand, array $data): void
    {
        $uploaded = [];
        $previous = [];
        try {
            foreach (['logo', 'banner_image'] as $field) {
                $file = $data[$field] ?? null;
                unset($data[$field]);
                if (!$file) continue;
                $directory = public_path('uploads/brands');
                File::ensureDirectoryExists($directory);
                $filename = $file->hashName();
                $file->move($directory, $filename);
                $uploaded[] = $directory . DIRECTORY_SEPARATOR . $filename;
                $previous[] = $brand->{$field};
                $data[$field] = 'uploads/brands/' . $filename;
            }
            $brand->fill($data);
            $brand->saveOrFail();
        } catch (\Throwable $exception) {
            File::delete($uploaded);
            throw $exception;
        }
        foreach ($previous as $path) $this->deleteImage($path);
    }

    public function updateStatus(Brand $brand, bool $status): void
    {
        $brand->status = $status;
        $brand->saveOrFail();
    }

    public function delete(Brand $brand): void
    {
        DB::transaction(function () use ($brand) {
            Brand::whereKey($brand->id)->lockForUpdate()->firstOrFail();
            if (DB::table('products')->where('brand_id', $brand->id)->exists()) {
                throw ValidationException::withMessages(['brand' => 'This brand has products. Reassign or remove them before deleting the brand.']);
            }
            if (!$brand->delete()) throw new \RuntimeException('Unable to delete brand.');
        });
        foreach (array_unique([$brand->logo, $brand->banner_image]) as $path) $this->deleteImage($path);
    }

    private function deleteImage(?string $image): void
    {
        $directory = realpath(public_path('uploads/brands'));
        $path = $image ? realpath(public_path($image)) : false;
        if ($directory && $path && is_file($path)
            && str_starts_with(strtolower($path), strtolower($directory . DIRECTORY_SEPARATOR))) {
            File::delete($path);
        }
    }
}
