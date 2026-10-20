<?php

namespace App\Services;

use App\Models\AttributeType;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AttributeTypeService
{
    public function save(AttributeType $attributeType, array $data): void
    {
        $attributeType->fill($data);
        $attributeType->saveOrFail();
    }

    public function updateStatus(AttributeType $attributeType, bool $status): void
    {
        $attributeType->status = $status;
        $attributeType->saveOrFail();
    }

    public function delete(AttributeType $attributeType): void
    {
        DB::transaction(function () use ($attributeType) {
            AttributeType::whereKey($attributeType->id)->lockForUpdate()->firstOrFail();
            if (DB::table('attributes')->where('attribute_type_id', $attributeType->id)->exists()
                || DB::table('product_variation_attributes')->where('attribute_type_id', $attributeType->id)->exists()) {
                throw ValidationException::withMessages([
                    'attribute_type' => 'This attribute type is in use. Reassign or remove its attributes before deleting it.',
                ]);
            }
            if (!$attributeType->delete()) {
                throw new \RuntimeException('Unable to delete the attribute type.');
            }
        });
    }
}
