<?php

namespace App\Services;

use App\Models\Status;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class StatusService
{
    public function save(Status $status, array $data): void
    {
        $status->fill($data);
        $status->saveOrFail();
    }

    public function updateStatus(Status $status, bool $active): void
    {
        $status->status = $active;
        $status->saveOrFail();
    }

    public function delete(Status $status): void
    {
        DB::transaction(function () use ($status) {
            Status::whereKey($status->id)->lockForUpdate()->firstOrFail();

            // Check status_id references and declared foreign keys, including cascade relations.
            foreach (Schema::getTableListing() as $table) {
                $columns = Schema::hasColumn($table, 'status_id') ? ['status_id'] : [];
                foreach (Schema::getForeignKeys($table) as $foreignKey) {
                    if ($foreignKey['foreign_table'] !== $status->getTable()) continue;
                    foreach ($foreignKey['foreign_columns'] as $index => $foreignColumn) {
                        if ($foreignColumn === $status->getKeyName()) {
                            $columns[] = $foreignKey['columns'][$index];
                        }
                    }
                }
                foreach (array_unique($columns) as $column) {
                    if (DB::table($table)->where($column, $status->id)->exists()) {
                        throw ValidationException::withMessages([
                            'status' => 'This status is linked to other records and cannot be deleted.',
                        ]);
                    }
                }
            }

            if (!$status->delete()) throw new \RuntimeException('Unable to delete the status.');
        });
    }
}
