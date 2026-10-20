<?php

namespace App\Services;

use App\Models\Attribute;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AttributeService
{
    public function save(Attribute $attribute, array $data): void
    {
        DB::transaction(function () use ($attribute, $data) {
            if ($attribute->exists) {
                $current = Attribute::whereKey($attribute->id)->lockForUpdate()->firstOrFail();
                if ((int) $current->attribute_type_id !== (int) $data['attribute_type_id']
                    && DB::table('product_variation_attributes')->where('attribute_id', $attribute->id)->exists()) {
                    throw ValidationException::withMessages(['attribute_type_id' => 'The type cannot be changed while this attribute is used in product variations.']);
                }
            }
            $attribute->fill($data);
            $attribute->saveOrFail();
        });
    }

    public function updateStatus(Attribute $attribute, bool $status): void
    {
        $attribute->status = $status;
        $attribute->saveOrFail();
    }

    public function delete(Attribute $attribute): void
    {
        DB::transaction(function () use ($attribute) {
            Attribute::whereKey($attribute->id)->lockForUpdate()->firstOrFail();
            if (DB::table('product_variation_attributes')->where('attribute_id', $attribute->id)->exists()) {
                throw ValidationException::withMessages(['attribute' => 'This attribute is used in product variations. Reassign or remove them before deleting it.']);
            }
            if (!$attribute->delete()) throw new \RuntimeException('Unable to delete the attribute.');
        });
    }
}
