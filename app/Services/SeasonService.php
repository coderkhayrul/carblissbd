<?php

namespace App\Services;

use App\Models\Season;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class SeasonService
{
    public function save(Season $season, array $data): void
    {
        $season->fill($data);
        $season->saveOrFail();
    }

    public function updateStatus(Season $season, bool $active): void
    {
        $season->status = $active;
        $season->saveOrFail();
    }

    public function delete(Season $season): void
    {
        DB::transaction(function () use ($season) {
            Season::whereKey($season->id)->lockForUpdate()->firstOrFail();

            // Check season_id references and declared foreign keys, including cascade relations.
            foreach (Schema::getTableListing() as $table) {
                $columns = Schema::hasColumn($table, 'season_id') ? ['season_id'] : [];
                foreach (Schema::getForeignKeys($table) as $foreignKey) {
                    if ($foreignKey['foreign_table'] !== $season->getTable()) continue;
                    foreach ($foreignKey['foreign_columns'] as $index => $foreignColumn) {
                        if ($foreignColumn === $season->getKeyName()) {
                            $columns[] = $foreignKey['columns'][$index];
                        }
                    }
                }
                foreach (array_unique($columns) as $column) {
                    if (DB::table($table)->where($column, $season->id)->exists()) {
                        throw ValidationException::withMessages([
                            'season' => 'This season is linked to other records and cannot be deleted.',
                        ]);
                    }
                }
            }

            if (!$season->delete()) throw new \RuntimeException('Unable to delete the season.');
        });
    }
}
