<?php

namespace App\Services;

use App\Models\Coupon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class CouponService
{
    public function save(Coupon $coupon, array $data): void
    {
        $coupon->fill($data);
        $coupon->saveOrFail();
    }

    public function updateStatus(Coupon $coupon, bool $active): void
    {
        $coupon->status = $active;
        $coupon->saveOrFail();
    }

    public function delete(Coupon $coupon): void
    {
        DB::transaction(function () use ($coupon) {
            Coupon::whereKey($coupon->id)->lockForUpdate()->firstOrFail();

            // Check coupon_id references and declared foreign keys, including cascade relations.
            foreach (Schema::getTableListing() as $table) {
                $columns = Schema::hasColumn($table, 'coupon_id') ? ['coupon_id'] : [];
                foreach (Schema::getForeignKeys($table) as $foreignKey) {
                    if ($foreignKey['foreign_table'] !== $coupon->getTable()) continue;
                    foreach ($foreignKey['foreign_columns'] as $index => $foreignColumn) {
                        if ($foreignColumn === $coupon->getKeyName()) {
                            $columns[] = $foreignKey['columns'][$index];
                        }
                    }
                }
                foreach (array_unique($columns) as $column) {
                    if (DB::table($table)->where($column, $coupon->id)->exists()) {
                        throw ValidationException::withMessages([
                            'coupon' => 'This coupon is linked to other records and cannot be deleted.',
                        ]);
                    }
                }
            }

            if (!$coupon->delete()) throw new \RuntimeException('Unable to delete the coupon.');
        });
    }
}
