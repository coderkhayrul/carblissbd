<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttributeType;
use App\Services\AttributeTypeService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AttributeTypeController extends Controller
{
    public function index()
    {
        $attributeTypes = AttributeType::orderBy('id')->get();
        return view('admin.attribute-types.index', compact('attributeTypes'));
    }

    public function create()
    {
        return view('admin.attribute-types.form', ['attributeType' => new AttributeType(['status' => true])]);
    }

    public function edit(AttributeType $attributeType)
    {
        return view('admin.attribute-types.form', compact('attributeType'));
    }

    private function validatedData(Request $request, ?AttributeType $attributeType = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('attribute_types', 'name')->ignore($attributeType?->id)],
            'description' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'boolean'],
        ]);
    }

    public function store(Request $request, AttributeTypeService $service)
    {
        $data = $this->validatedData($request);
        try {
            $service->save(new AttributeType, $data);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->with('error', 'Unable to create the attribute type. Please try again.');
        }
        return redirect()->route('admin.attribute-types.index')->with('success', 'Attribute type created successfully.');
    }

    public function update(Request $request, AttributeType $attributeType, AttributeTypeService $service)
    {
        $data = $this->validatedData($request, $attributeType);
        try {
            $service->save($attributeType, $data);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->with('error', 'Unable to update the attribute type. Please try again.');
        }
        return redirect()->route('admin.attribute-types.index')->with('success', 'Attribute type updated successfully.');
    }

    public function updateStatus(Request $request, AttributeType $attributeType, AttributeTypeService $service)
    {
        $data = $request->validate(['status' => ['required', 'boolean']]);
        try {
            $service->updateStatus($attributeType, (bool) $data['status']);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->with('error', 'Unable to update the attribute type status.');
        }
        return back()->with('success', 'Attribute type status updated successfully.');
    }

    public function destroy(AttributeType $attributeType, AttributeTypeService $service)
    {
        try {
            $service->delete($attributeType);
        } catch (\Illuminate\Validation\ValidationException $exception) {
            return back()->with('error', $exception->validator->errors()->first());
        } catch (\Throwable $exception) {
            report($exception);
            return back()->with('error', 'Unable to delete the attribute type. Please try again.');
        }
        return redirect()->route('admin.attribute-types.index')->with('success', 'Attribute type deleted successfully.');
    }
}
