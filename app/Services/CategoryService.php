<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;

class CategoryService
{
    public function save(Category $category, array $data): void
    {
        $uploaded = [];
        $previous = [];
        try {
            $parentId = $data['parent_id'] ?? null;
            $visited = [];
            while ($parentId) {
                if (($category->exists && (int) $parentId === (int) $category->id) || isset($visited[$parentId])) {
                    throw ValidationException::withMessages(['parent_id' => 'Choose a parent outside this category and its descendants.']);
                }
                $visited[$parentId] = true;
                $parentId = Category::findOrFail($parentId)->parent_id;
            }
            foreach (['image', 'banner_image'] as $field) {
                $file = $data[$field] ?? null;
                unset($data[$field]);
                if (!$file) continue;
                $directory = public_path('uploads/categories');
                File::ensureDirectoryExists($directory);
                $filename = $file->hashName();
                $file->move($directory, $filename);
                $uploaded[] = $directory . DIRECTORY_SEPARATOR . $filename;
                $previous[] = $category->{$field};
                $data[$field] = 'uploads/categories/' . $filename;
            }
            $category->fill($data);
            $category->saveOrFail();
        } catch (\Throwable $exception) {
            File::delete($uploaded);
            throw $exception;
        }
        foreach ($previous as $path) $this->deleteImage($path);
    }

    public function updateStatus(Category $category, bool $status): void
    {
        $category->status = $status;
        $category->saveOrFail();
    }

    public function delete(Category $category): void
    {
        DB::transaction(function () use ($category) {
            Category::whereKey($category->id)->lockForUpdate()->firstOrFail();
            if (Category::where('parent_id', $category->id)->exists()) {
                throw ValidationException::withMessages(['category' => 'This category has subcategories. Reassign or remove them before deleting it.']);
            }
            if (DB::table('products')->where('category_id', $category->id)->exists()) {
                throw ValidationException::withMessages(['category' => 'This category has products. Reassign or remove them before deleting the category.']);
            }
            if (!$category->delete()) throw new \RuntimeException('Unable to delete category.');
        });
        foreach (array_unique([$category->image, $category->banner_image]) as $path) $this->deleteImage($path);
    }

    private function deleteImage(?string $image): void
    {
        $directory = realpath(public_path('uploads/categories'));
        $path = $image ? realpath(public_path($image)) : false;
        if ($directory && $path && is_file($path)
            && str_starts_with(strtolower($path), strtolower($directory . DIRECTORY_SEPARATOR))) {
            File::delete($path);
        }
    }
}
