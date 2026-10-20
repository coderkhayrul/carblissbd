<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent')->orderBy('menu_serial')->orderBy('id')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.form', ['category' => new Category, 'parents' => Category::orderBy('name')->get()]);
    }

    public function edit(Category $category)
    {
        $excluded = [$category->id];
        $frontier = [$category->id];
        while ($frontier) {
            $frontier = Category::whereIn('parent_id', $frontier)->whereNotIn('id', $excluded)->pluck('id')->all();
            $excluded = array_merge($excluded, $frontier);
        }
        $parents = Category::whereNotIn('id', $excluded)->orderBy('name')->get();
        return view('admin.categories.form', compact('category', 'parents'));
    }

    private function validatedData(Request $request, ?Category $category = null): array
    {
        if (!$request->filled('slug')) {
            $request->merge(['slug' => Str::slug((string) $request->input('name'))]);
        }
        return $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($category?->id)],
            'slug' => ['required', 'string', 'max:255', Rule::unique('categories', 'slug')->ignore($category?->id)],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
            'icon' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:10000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
            'banner_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
            'menu_serial' => ['required', 'integer', 'min:0', 'max:2147483647'],
            'tab_serial' => ['required', 'integer', 'min:0', 'max:2147483647'],
            'show_on_menu' => ['required', 'boolean'],
            'show_on_side_menu' => ['required', 'boolean'],
            'is_tab' => ['required', 'boolean'],
            'status' => ['required', 'boolean'],
            'is_highlight' => ['required', 'boolean'],
            'is_top_ten' => ['required', 'boolean'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_tags' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:10000'],
        ]);
    }

    public function store(Request $request, CategoryService $service)
    {
        $data = $this->validatedData($request);
        try {
            $service->save(new Category, $data);
        } catch (\Illuminate\Validation\ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->with('error', 'Unable to create the category. Please try again.');
        }
        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function update(Request $request, Category $category, CategoryService $service)
    {
        $data = $this->validatedData($request, $category);
        try {
            $service->save($category, $data);
        } catch (\Illuminate\Validation\ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->with('error', 'Unable to update the category. Please try again.');
        }
        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function updateStatus(Request $request, Category $category, CategoryService $service)
    {
        $data = $request->validate(['status' => ['required', 'boolean']]);
        try {
            $service->updateStatus($category, (bool) $data['status']);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->with('error', 'Unable to update the category status.');
        }
        return back()->with('success', 'Category status updated successfully.');
    }

    public function destroy(Category $category, CategoryService $service)
    {
        try {
            $service->delete($category);
        } catch (\Illuminate\Validation\ValidationException $exception) {
            return back()->with('error', $exception->validator->errors()->first());
        } catch (\Throwable $exception) {
            report($exception);
            return back()->with('error', 'Unable to delete the category. Please try again.');
        }
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
