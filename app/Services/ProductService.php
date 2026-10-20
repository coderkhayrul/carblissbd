<?php

namespace App\Services;

use App\Models\{Product, ProductVariation, Attribute};
use Illuminate\Support\Facades\{DB, File, Schema};
use Illuminate\Validation\ValidationException;

class ProductService
{
    public function save(Product $product, array $data): void
    {
        $uploaded = [];
        $oldImages = [];
        try {
            DB::transaction(function () use ($product, $data, &$uploaded, &$oldImages) {
                if ($product->exists) Product::whereKey($product->id)->lockForUpdate()->firstOrFail();
                $rows = !empty($data['is_variation']) ? ($data['variations'] ?? []) : [];
                $tags = $data['tags'] ?? [];
                unset($data['variations'], $data['tags']);
                $this->validateVariations($product, $rows);

                foreach (['thumbnail_image', 'size_chart_image'] as $field) {
                    $file = $data[$field] ?? null;
                    unset($data[$field]);
                    if (!$file) continue;
                    $directory = public_path('uploads/products');
                    File::ensureDirectoryExists($directory);
                    $filename = $file->hashName();
                    $file->move($directory, $filename);
                    $path = 'uploads/products/' . $filename;
                    $uploaded[] = $path;
                    $oldImages[] = $product->{$field};
                    $data[$field] = $path;
                }

                $product->fill($data);
                $product->saveOrFail();
                $product->tags()->sync($tags);

                $keep = array_filter(array_column($rows, 'id'));
                foreach ($product->variations()->whereNotIn('id', $keep)->get() as $removed) {
                    if ($this->hasReferences('product_variations', 'product_variation_id', $removed->id, ['product_variation_attributes'])) {
                        throw ValidationException::withMessages(['variations' => 'A removed variation is used by other records and cannot be deleted.']);
                    }
                    $removed->variationAttributes()->delete();
                    $removed->delete();
                }

                foreach ($rows as $row) {
                    $variation = !empty($row['id'])
                        ? $product->variations()->findOrFail($row['id'])
                        : new ProductVariation(['product_id' => $product->id]);
                    $attributes = $row['attributes'];
                    unset($row['id'], $row['attributes']);
                    $variation->fill(array_intersect_key($row, array_flip([
                        'name', 'sku', 'purchase_price', 'sale_price', 'bangla_sale_price', 'mrp_price', 'status',
                    ])));
                    $variation->saveOrFail();
                    $variation->variationAttributes()->delete();
                    foreach ($attributes as $pair) {
                        $variation->variationAttributes()->create([
                            'product_id' => $product->id,
                            'attribute_type_id' => $pair['attribute_type_id'],
                            'attribute_id' => $pair['attribute_id'],
                        ]);
                    }
                }
            });
        } catch (\Throwable $exception) {
            foreach ($uploaded as $path) $this->deleteImage($path);
            throw $exception;
        }
        foreach ($oldImages as $path) {
            if (!in_array($path, $uploaded, true)) $this->deleteImage($path);
        }
    }

    private function validateVariations(Product $product, array $rows): void
    {
        $combinations = [];
        foreach ($rows as $index => $row) {
            $id = $row['id'] ?? null;
            if ($id && (!$product->exists || !$product->variations()->whereKey($id)->exists())) {
                throw ValidationException::withMessages(["variations.$index.id" => 'This variation does not belong to this product.']);
            }
            if (ProductVariation::where('sku', $row['sku'])->when($id, fn ($query) => $query->where('id', '!=', $id))->exists()) {
                throw ValidationException::withMessages(["variations.$index.sku" => 'This variation SKU is already in use.']);
            }
            $types = [];
            $combination = [];
            foreach ($row['attributes'] as $pairIndex => $pair) {
                $type = (int) $pair['attribute_type_id'];
                if (isset($types[$type])) {
                    throw ValidationException::withMessages(["variations.$index.attributes" => 'Select each attribute type only once per variation.']);
                }
                $types[$type] = true;
                if (!Attribute::whereKey($pair['attribute_id'])->where('attribute_type_id', $type)->exists()) {
                    throw ValidationException::withMessages(["variations.$index.attributes.$pairIndex.attribute_id" => 'The selected attribute does not belong to this attribute type.']);
                }
                $combination[$type] = (int) $pair['attribute_id'];
            }
            ksort($combination);
            $key = json_encode($combination);
            if (isset($combinations[$key])) {
                throw ValidationException::withMessages(["variations.$index.attributes" => 'This attribute combination is already used by another variation.']);
            }
            $combinations[$key] = true;
        }
    }

    public function updateStatus(Product $product, bool $status): void
    {
        $product->status = $status;
        $product->saveOrFail();
    }

    public function delete(Product $product): void
    {
        DB::transaction(function () use ($product) {
            Product::whereKey($product->id)->lockForUpdate()->firstOrFail();
            if ($this->hasReferences('products', 'product_id', $product->id)) {
                throw ValidationException::withMessages(['product' => 'This product is linked to other records (such as variations, tags or orders) and cannot be deleted.']);
            }
            if (!$product->delete()) throw new \RuntimeException('Unable to delete product.');
        });
        foreach ([$product->thumbnail_image, $product->size_chart_image] as $image) $this->deleteImage($image);
    }

    private function hasReferences(string $target, string $conventionalColumn, int $id, array $ignore = []): bool
    {
        foreach (Schema::getTableListing() as $table) {
            if (in_array($table, $ignore, true)) continue;
            $columns = Schema::hasColumn($table, $conventionalColumn) ? [$conventionalColumn] : [];
            foreach (Schema::getForeignKeys($table) as $key) {
                if ($key['foreign_table'] !== $target) continue;
                foreach ($key['foreign_columns'] as $index => $column) {
                    if ($column === 'id') $columns[] = $key['columns'][$index];
                }
            }
            foreach (array_unique($columns) as $column) {
                if (DB::table($table)->where($column, $id)->exists()) return true;
            }
        }
        return false;
    }

    private function deleteImage(?string $image): void
    {
        $directory = realpath(public_path('uploads/products'));
        $path = $image ? realpath(public_path($image)) : false;
        if ($directory && $path && is_file($path)
            && str_starts_with(strtolower($path), strtolower($directory . DIRECTORY_SEPARATOR))) File::delete($path);
    }
}
