<?php

namespace App\Services;

use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class TagService
{
    public function save(Tag $tag, array $data): void
    {
        $tag->fill($data);
        $tag->saveOrFail();
    }

    public function updateStatus(Tag $tag, bool $active): void
    {
        $tag->status = $active;
        $tag->saveOrFail();
    }

    public function delete(Tag $tag): void
    {
        DB::transaction(function () use ($tag) {
            Tag::whereKey($tag->id)->lockForUpdate()->firstOrFail();

            // Check tag_id references and declared foreign keys, including cascade relations.
            foreach (Schema::getTableListing() as $table) {
                $columns = Schema::hasColumn($table, 'tag_id') ? ['tag_id'] : [];
                foreach (Schema::getForeignKeys($table) as $foreignKey) {
                    if ($foreignKey['foreign_table'] !== $tag->getTable()) continue;
                    foreach ($foreignKey['foreign_columns'] as $index => $foreignColumn) {
                        if ($foreignColumn === $tag->getKeyName()) {
                            $columns[] = $foreignKey['columns'][$index];
                        }
                    }
                }
                foreach (array_unique($columns) as $column) {
                    if (DB::table($table)->where($column, $tag->id)->exists()) {
                        throw ValidationException::withMessages([
                            'tag' => 'This tag is linked to other records and cannot be deleted.',
                        ]);
                    }
                }
            }

            if (!$tag->delete()) throw new \RuntimeException('Unable to delete the tag.');
        });
    }
}
