<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Services\BrandService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::orderBy('serial')->orderBy('id')->paginate(20);
        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.form', ['brand' => new Brand]);
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.form', compact('brand'));
    }

    private function validatedData(Request $request, ?Brand $brand = null): array
    {
        if (!$request->filled('slug')) {
            $request->merge(['slug' => Str::slug((string) $request->input('name'))]);
        }
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('brands', 'slug')->ignore($brand?->id)],
            'title' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
            'banner_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
            'serial' => ['required', 'integer', 'min:0', 'max:2147483647'],
            'status' => ['required', 'boolean'],
            'is_highlight' => ['required', 'boolean'],
            'is_top_ten' => ['required', 'boolean'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_tags' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:10000'],
        ]);
    }

    public function store(Request $request, BrandService $service)
    {
        $data = $this->validatedData($request);
        try {
            $service->save(new Brand, $data);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->with('error', 'Unable to create the brand. Please try again.');
        }
        return redirect()->route('admin.brands.index')->with('success', 'Brand created successfully.');
    }

    public function update(Request $request, Brand $brand, BrandService $service)
    {
        $data = $this->validatedData($request, $brand);
        try {
            $service->save($brand, $data);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->with('error', 'Unable to update the brand. Please try again.');
        }
        return redirect()->route('admin.brands.index')->with('success', 'Brand updated successfully.');
    }

    public function updateStatus(Request $request, Brand $brand, BrandService $service)
    {
        $data = $request->validate(['status' => ['required', 'boolean']]);
        try {
            $service->updateStatus($brand, (bool) $data['status']);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->with('error', 'Unable to update the brand status.');
        }
        return back()->with('success', 'Brand status updated successfully.');
    }

    public function destroy(Brand $brand, BrandService $service)
    {
        try {
            $service->delete($brand);
        } catch (\Illuminate\Validation\ValidationException $exception) {
            return back()->with('error', $exception->validator->errors()->first());
        } catch (\Throwable $exception) {
            report($exception);
            return back()->with('error', 'Unable to delete the brand. Please try again.');
        }
        return redirect()->route('admin.brands.index')->with('success', 'Brand deleted successfully.');
    }
}
