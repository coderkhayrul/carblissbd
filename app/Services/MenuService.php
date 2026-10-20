<?php

namespace App\Services;

use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class MenuService
{
    public function save(Menu $menu, array $data): void
    {
        $parentId = $data['parent_id'] ?? null;
        $visited = [];
        while ($parentId) {
            if (($menu->exists && (int) $parentId === (int) $menu->id) || isset($visited[$parentId])) {
                throw ValidationException::withMessages(['parent_id' => 'Choose a parent outside this menu and its descendants.']);
            }
            $visited[$parentId] = true;
            $parentId = Menu::findOrFail($parentId)->parent_id;
        }

        $image = $data['image'] ?? null;
        unset($data['image']);
        $oldImage = $menu->image;
        $newImage = null;
        try {
            if ($image) {
                $directory = public_path('uploads/menus');
                File::ensureDirectoryExists($directory);
                $filename = $image->hashName();
                $image->move($directory, $filename);
                $newImage = 'uploads/menus/' . $filename;
                $data['image'] = $newImage;
            }
            $menu->fill($data);
            $menu->saveOrFail();
        } catch (\Throwable $exception) {
            if ($newImage) $this->deleteImage($newImage);
            throw $exception;
        }
        if ($newImage && $oldImage !== $newImage) $this->deleteImage($oldImage);
    }

    public function updateStatus(Menu $menu, bool $active): void
    {
        $menu->status = $active;
        $menu->saveOrFail();
    }

    public function delete(Menu $menu): void
    {
        DB::transaction(function () use ($menu) {
            Menu::whereKey($menu->id)->lockForUpdate()->firstOrFail();

            // Check menu_id references and declared foreign keys, including cascade relations.
            foreach (Schema::getTableListing() as $table) {
                $columns = Schema::hasColumn($table, 'menu_id') ? ['menu_id'] : [];
                foreach (Schema::getForeignKeys($table) as $foreignKey) {
                    if ($foreignKey['foreign_table'] !== $menu->getTable()) continue;
                    foreach ($foreignKey['foreign_columns'] as $index => $foreignColumn) {
                        if ($foreignColumn === $menu->getKeyName()) {
                            $columns[] = $foreignKey['columns'][$index];
                        }
                    }
                }
                foreach (array_unique($columns) as $column) {
                    if (DB::table($table)->where($column, $menu->id)->exists()) {
                        throw ValidationException::withMessages([
                            'menu' => 'This menu is linked to other records and cannot be deleted.',
                        ]);
                    }
                }
            }

            if (!$menu->delete()) throw new \RuntimeException('Unable to delete the menu.');
        });
        $this->deleteImage($menu->image);
    }

    private function deleteImage(?string $image): void
    {
        $directory = realpath(public_path('uploads/menus'));
        $path = $image ? realpath(public_path($image)) : false;
        if ($directory && $path && is_file($path)
            && str_starts_with(strtolower($path), strtolower($directory . DIRECTORY_SEPARATOR))) {
            File::delete($path);
        }
    }
}
