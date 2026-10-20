<?php

namespace App\Services;

use App\Models\ReturnReason;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class ReturnReasonService
{
    public function save(ReturnReason $returnReason, array $data): void
    {
        $returnReason->fill($data);
        $returnReason->saveOrFail();
    }

    public function updateStatus(ReturnReason $returnReason, bool $active): void
    {
        $returnReason->status = $active;
        $returnReason->saveOrFail();
    }

    public function delete(ReturnReason $returnReason): void
    {
        DB::transaction(function () use ($returnReason) {
            ReturnReason::whereKey($returnReason->id)->lockForUpdate()->firstOrFail();

            // Check return_reason_id references and declared foreign keys, including cascade relations.
            foreach (Schema::getTableListing() as $table) {
                $columns = Schema::hasColumn($table, 'return_reason_id') ? ['return_reason_id'] : [];
                foreach (Schema::getForeignKeys($table) as $foreignKey) {
                    if ($foreignKey['foreign_table'] !== $returnReason->getTable()) continue;
                    foreach ($foreignKey['foreign_columns'] as $index => $foreignColumn) {
                        if ($foreignColumn === $returnReason->getKeyName()) {
                            $columns[] = $foreignKey['columns'][$index];
                        }
                    }
                }
                foreach (array_unique($columns) as $column) {
                    if (DB::table($table)->where($column, $returnReason->id)->exists()) {
                        throw ValidationException::withMessages([
                            'return_reason' => 'This return reason is linked to other records and cannot be deleted.',
                        ]);
                    }
                }
            }

            if (!$returnReason->delete()) throw new \RuntimeException('Unable to delete the return reason.');
        });
    }
}
