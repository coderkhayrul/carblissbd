<?php

namespace App\Services;

use App\Models\Page;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RuntimeException;
use Throwable;

class PageService
{
    public function delete(Page $page): void
    {
        DB::transaction(function () use ($page) {
            if (!$page->delete()) {
                throw new RuntimeException('Unable to delete the page.');
            }

            $directory = realpath(public_path('uploads/pages'));

            foreach (array_unique([$page->image, $page->banner_image]) as $image) {
                $path = $image ? realpath(public_path($image)) : false;

                if ($directory && $path && is_file($path)
                    && str_starts_with(strtolower($path), strtolower($directory . DIRECTORY_SEPARATOR))) {
                    if (!File::delete($path)) {
                        throw new RuntimeException('Unable to delete the page image.');
                    }
                }
            }
        });
    }

    public function update(Page $page, array $data, ?UploadedFile $image = null, ?UploadedFile $bannerImage = null): void
    {
        $uploadedPaths = [];
        $oldImages = [];

        try {
            foreach (['image' => $image, 'banner_image' => $bannerImage] as $field => $file) {
                if (!$file) {
                    continue;
                }

                $directory = public_path('uploads/pages');
                File::ensureDirectoryExists($directory);
                $filename = $file->hashName();
                $file->move($directory, $filename);
                $uploadedPaths[] = $directory . DIRECTORY_SEPARATOR . $filename;
                $oldImages[] = $page->{$field};
                $data[$field] = 'uploads/pages/' . $filename;
            }

            $page->update($data);
        } catch (Throwable $exception) {
            File::delete($uploadedPaths);
            throw $exception;
        }

        foreach ($oldImages as $oldImage) {
            $directory = realpath(public_path('uploads/pages'));
            $path = $oldImage ? realpath(public_path($oldImage)) : false;

            if ($directory && $path && is_file($path)
                && str_starts_with(strtolower($path), strtolower($directory . DIRECTORY_SEPARATOR))
                && !in_array($path, $uploadedPaths, true)) {
                File::delete($path);
            }
        }
    }

    public function create(array $data, ?UploadedFile $image = null, ?UploadedFile $bannerImage = null): Page
    {
        $uploadedPaths = [];

        try {
            foreach (['image' => $image, 'banner_image' => $bannerImage] as $field => $file) {
                if (!$file) {
                    continue;
                }

                $directory = public_path('uploads/pages');
                File::ensureDirectoryExists($directory);
                $filename = $file->hashName();
                $file->move($directory, $filename);
                $uploadedPaths[] = $directory . DIRECTORY_SEPARATOR . $filename;
                $data[$field] = 'uploads/pages/' . $filename;
            }

            return Page::create($data);
        } catch (Throwable $exception) {
            File::delete($uploadedPaths);
            throw $exception;
        }
    }
}

