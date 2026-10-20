<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeType;
use App\Services\AttributeService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AttributeController extends Controller
{
    public function index()
    {
        $attributes = Attribute::with('attributeType')->orderBy('id')->get();
        return view('admin.attributes.index', compact('attributes'));
    }

    public function create()
    {
        return view('admin.attributes.form', ['attribute' => new Attribute(['status' => true]), 'attributeTypes' => AttributeType::orderBy('name')->get()]);
    }

    public function edit(Attribute $attribute)
    {
        $attributeTypes = AttributeType::orderBy('name')->get();
        return view('admin.attributes.form', compact('attribute', 'attributeTypes'));
    }

    private function validatedData(Request $request, ?Attribute $attribute = null): array
    {
        if (!$request->filled('slug')) {
            $request->merge(['slug' => Str::slug((string) $request->input('name'))]);
        }
        return $request->validate([
            'attribute_type_id' => ['required', 'integer', 'exists:attribute_types,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('attributes', 'slug')->ignore($attribute?->id)],
            'color_code' => ['nullable', 'string', 'regex:/^#(?:[0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'description' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'boolean'],
        ]);
    }

    public function store(Request $request, AttributeService $service)
    {
        $data = $this->validatedData($request);
        try {
            $service->save(new Attribute, $data);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->with('error', 'Unable to create the attribute. Please try again.');
        }
        return redirect()->route('admin.attributes.index')->with('success', 'Attribute created successfully.');
    }

    public function update(Request $request, Attribute $attribute, AttributeService $service)
    {
        $data = $this->validatedData($request, $attribute);
        try {
            $service->save($attribute, $data);
        } catch (\Illuminate\Validation\ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->with('error', 'Unable to update the attribute. Please try again.');
        }
        return redirect()->route('admin.attributes.index')->with('success', 'Attribute updated successfully.');
    }

    public function updateStatus(Request $request, Attribute $attribute, AttributeService $service)
    {
        $data = $request->validate(['status' => ['required', 'boolean']]);
        try {
            $service->updateStatus($attribute, (bool) $data['status']);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->with('error', 'Unable to update the attribute status.');
        }
        return back()->with('success', 'Attribute status updated successfully.');
    }

    public function destroy(Attribute $attribute, AttributeService $service)
    {
        try {
            $service->delete($attribute);
        } catch (\Illuminate\Validation\ValidationException $exception) {
            return back()->with('error', $exception->validator->errors()->first());
        } catch (\Throwable $exception) {
            report($exception);
            return back()->with('error', 'Unable to delete the attribute. Please try again.');
        }
        return redirect()->route('admin.attributes.index')->with('success', 'Attribute deleted successfully.');
    }
}
