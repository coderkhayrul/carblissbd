<?php

namespace App\Services;

use App\Models\{Product, ProductVariation, ShippingChargeSetting};
use Illuminate\Validation\ValidationException;

class CheckoutService
{
    public function summary(array $items, ?int $shippingId = null): array
    {
        $lines = [];
        $subtotal = 0;
        $products = Product::whereIn('id', collect($items)->pluck('id')->filter(fn ($id) => $id > 0))
            ->where('status', true)->where('publish', true)->get()->keyBy('id');
        $variations = ProductVariation::with('product')->whereIn('id', collect($items)->pluck('id')->filter(fn ($id) => $id < 0)->map(fn ($id) => abs($id)))
            ->where('status', true)->get()->keyBy('id');

        foreach ($items as $item) {
            $id = (int) $item['id'];
            $variation = $id < 0 ? $variations->get(abs($id)) : null;
            $product = $id < 0 ? $variation?->product : $products->get($id);
            if (!$product || !$product->status || !$product->publish
                || ($id < 0 && !$product->is_variation) || ($id > 0 && $product->is_variation)) {
                throw ValidationException::withMessages(['items' => 'A cart product or variation is no longer available. Please remove it and choose an available product.']);
            }
            $unit = (int) round((float) ($variation?->sale_price ?? $product->sale_price) * 100);
            $qty = (int) $item['qty'];
            $subtotal += $unit * $qty;
            $lines[] = [
                'id' => $id, 'product_id' => $product->id, 'variation_id' => $variation?->id,
                'product_name' => $product->name,
                'name' => $variation ? $product->name . ' — ' . $variation->name : $product->name,
                'sku' => $variation?->sku ?? $product->sku, 'qty' => $qty,
                'price' => $unit / 100, 'line_total' => ($unit * $qty) / 100,
                'img' => asset($product->thumbnail_image ?: 'backend/assets/extra/preview-image.png'),
                'url' => route('product.detail', ['product' => $product->slug]),
            ];
        }

        $shippingOptions = ShippingChargeSetting::where('status', true)->whereNotNull('delivery_charge')
            ->where('delivery_charge', '>=', 0)->orderBy('id')->get();
        $shipping = $shippingId ? $shippingOptions->firstWhere('id', $shippingId) : $shippingOptions->first();
        if ($shippingId && !$shipping) {
            throw ValidationException::withMessages(['shipping_id' => 'This delivery area is no longer available. Please choose another area.']);
        }
        $charge = $lines && $shipping ? (int) round((float) $shipping->delivery_charge * 100) : 0;

        return [
            'items' => $lines, 'subtotal' => $subtotal / 100, 'shipping_charge' => $charge / 100,
            'total' => ($subtotal + $charge) / 100, 'shipping' => $shipping, 'shippingOptions' => $shippingOptions,
        ];
    }
}
