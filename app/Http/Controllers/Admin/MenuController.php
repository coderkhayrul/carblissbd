<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Services\MenuService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('parent')->orderBy('serial_no')->orderBy('id')->get();
        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.menus.form', [
            'menu' => new Menu(['status' => true, 'serial_no' => 0]),
            'parents' => Menu::orderBy('name')->get(),
        ]);
    }

    public function edit(Menu $menu)
    {
        $excluded = [$menu->id];
        $frontier = [$menu->id];
        while ($frontier) {
            $frontier = Menu::whereIn('parent_id', $frontier)->whereNotIn('id', $excluded)->pluck('id')->all();
            $excluded = array_merge($excluded, $frontier);
        }
        $parents = Menu::whereNotIn('id', $excluded)->orderBy('name')->get();
        return view('admin.menus.form', compact('menu', 'parents'));
    }

    private function validatedData(Request $request, ?Menu $menu = null): array
    {
        if (!$request->filled('slug')) {
            $request->merge(['slug' => Str::slug((string) $request->input('name'))]);
        }
        return $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('menus', 'name')->ignore($menu?->id)],
            'slug' => ['required', 'string', 'max:255', Rule::unique('menus', 'slug')->ignore($menu?->id)],
            'icon' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
            'parent_id' => ['nullable', 'integer', 'exists:menus,id'],
            'serial_no' => ['required', 'integer', 'min:0', 'max:2147483647'],
            'status' => ['required', 'boolean'],
        ]);
    }

    public function store(Request $request, MenuService $service)
    {
        $data = $this->validatedData($request);
        try {
            $service->save(new Menu, $data);
        } catch (\Illuminate\Validation\ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->with('error', 'Unable to create the menu. Please try again.');
        }
        return redirect()->route('admin.menus.index')->with('success', 'Menu created successfully.');
    }

    public function update(Request $request, Menu $menu, MenuService $service)
    {
        $data = $this->validatedData($request, $menu);
        try {
            $service->save($menu, $data);
        } catch (\Illuminate\Validation\ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->with('error', 'Unable to update the menu. Please try again.');
        }
        return redirect()->route('admin.menus.index')->with('success', 'Menu updated successfully.');
    }

    public function updateStatus(Request $request, Menu $menu, MenuService $service)
    {
        $data = $request->validate(['status' => ['required', 'boolean']]);
        try {
            $service->updateStatus($menu, (bool) $data['status']);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->with('error', 'Unable to activate or deactivate the menu.');
        }
        return back()->with('success', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu, MenuService $service)
    {
        try {
            $service->delete($menu);
        } catch (\Illuminate\Validation\ValidationException $exception) {
            return back()->with('error', $exception->validator->errors()->first());
        } catch (\Throwable $exception) {
            report($exception);
            return back()->with('error', 'Unable to delete the menu. Please try again.');
        }
        return redirect()->route('admin.menus.index')->with('success', 'Menu deleted successfully.');
    }
}
