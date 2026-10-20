<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Product, Category, Brand, Tag, AttributeType, Attribute};
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'brand'])->withCount('variations')->latest('id')->get();
        return view('admin.products.index', compact('products'));
    }

    private function form(Product $product)
    {
        $product->load(['tags', 'variations.variationAttributes']);
        return view('admin.products.form', [
            'product' => $product,
            'categories' => Category::orderBy('name')->get(),
            'brands' => Brand::orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
            'attributeTypes' => AttributeType::orderBy('name')->get(['id', 'name', 'status']),
            'attributes' => Attribute::orderBy('name')->get(['id', 'name', 'attribute_type_id', 'status']),
        ]);
    }

    public function create()
    {
        return $this->form(new Product([
            'status' => true, 'publish' => true, 'is_variation' => false,
            'is_refundable' => false, 'is_new_arrival' => false,
        ]));
    }

    public function edit(Product $product) { return $this->form($product); }

    private function validatedData(Request $request, ?Product $product = null): array
    {
        $request->merge(['tags' => $request->input('tags', [])]);
        if (!$request->filled('slug') && is_string($request->input('name'))) {
            $request->merge(['slug' => Str::slug($request->input('name'))]);
        }
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($product?->id)],
            'sku' => ['required', 'string', 'max:255', Rule::unique('products', 'sku')->ignore($product?->id)],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'brand_id' => ['required', 'integer', 'exists:brands,id'],
            'video_link' => ['nullable', 'url:http,https', 'max:255'],
            'landing_youtube_link' => ['nullable', 'url:http,https', 'max:10000'],
            'tags' => ['nullable', 'array', 'max:100'],
            'tags.*' => ['integer', 'distinct', 'exists:tags,id'],
        ];
        foreach (['bangla_name', 'barcode', 'landing_page_title', 'landing_page_feature', 'landing_short_description',
            'landing_long_description', 'meta_title', 'meta_description', 'meta_keywords'] as $field) {
            $rules[$field] = ['nullable', 'string', 'max:255'];
        }
        foreach (['features', 'bangla_features', 'short_description', 'bangla_short_description', 'description', 'bangla_description'] as $field) {
            $rules[$field] = ['nullable', 'string', 'max:10000'];
        }
        foreach (['purchase_price', 'sale_price', 'bangla_sale_price', 'mrp_price'] as $field) {
            $rules[$field] = ['required', 'numeric', 'decimal:0,2', 'min:0', 'max:99999999.99'];
        }
        foreach (['is_variation', 'is_refundable', 'is_new_arrival', 'status', 'publish'] as $field) {
            $rules[$field] = ['required', 'boolean'];
        }
        foreach (['thumbnail_image', 'size_chart_image'] as $field) {
            $rules[$field] = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'];
        }
        if ($request->boolean('is_variation')) {
            $rules['variations'] = ['required', 'array', 'min:1', 'max:100'];
            $rules['variations.*.id'] = ['nullable', 'integer', 'distinct'];
            $rules['variations.*.name'] = ['required', 'string', 'max:255'];
            $rules['variations.*.sku'] = ['required', 'string', 'max:255', 'distinct:ignore_case'];
            $rules['variations.*.status'] = ['required', 'boolean'];
            foreach (['purchase_price', 'sale_price', 'bangla_sale_price', 'mrp_price'] as $field) {
                $rules['variations.*.' . $field] = ['required', 'numeric', 'decimal:0,2', 'min:0', 'max:99999999.99'];
            }
            $rules['variations.*.attributes'] = ['required', 'array', 'min:1', 'max:20'];
            $rules['variations.*.attributes.*.attribute_type_id'] = ['required', 'integer', 'exists:attribute_types,id'];
            $rules['variations.*.attributes.*.attribute_id'] = ['required', 'integer', 'exists:attributes,id'];
        }
        return $request->validate($rules);
    }

    public function store(Request $request, ProductService $service)
    {
        return $this->save($request, new Product, $service);
    }

    public function update(Request $request, Product $product, ProductService $service)
    {
        return $this->save($request, $product, $service);
    }

    private function save(Request $request, Product $product, ProductService $service)
    {
        $data = $this->validatedData($request, $product->exists ? $product : null);
        try {
            $service->save($product, $data);
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->with('error', 'Unable to save the product. Please try again.');
        }
        return redirect()->route('admin.products.edit', $product)->with('success', 'Product saved successfully.');
    }

    public function updateStatus(Request $request, Product $product, ProductService $service)
    {
        $data = $request->validate(['status' => ['required', 'boolean']]);
        try {
            $service->updateStatus($product, (bool) $data['status']);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->with('error', 'Unable to update product status.');
        }
        return back()->with('success', 'Product status updated successfully.');
    }

    public function destroy(Product $product, ProductService $service)
    {
        try {
            $service->delete($product);
        } catch (ValidationException $exception) {
            return back()->with('error', $exception->validator->errors()->first());
        } catch (\Throwable $exception) {
            report($exception);
            return back()->with('error', 'Unable to delete the product.');
        }
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
