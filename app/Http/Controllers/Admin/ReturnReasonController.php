<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReturnReason;
use App\Services\ReturnReasonService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReturnReasonController extends Controller
{
    public function index()
    {
        $returnReasons = ReturnReason::orderBy('serial_no')->orderBy('id')->get();
        return view('admin.return-reasons.index', compact('returnReasons'));
    }

    public function create()
    {
        return view('admin.return-reasons.form', ['returnReason' => new ReturnReason(['status' => true, 'serial_no' => 0])]);
    }

    public function edit(ReturnReason $returnReason)
    {
        return view('admin.return-reasons.form', compact('returnReason'));
    }

    private function validatedData(Request $request, ?ReturnReason $returnReason = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255', Rule::unique('return_reasons', 'title')->ignore($returnReason?->id)],
            'description' => ['nullable', 'string', 'max:10000'],
            'serial_no' => ['required', 'integer', 'min:0', 'max:2147483647'],
            'status' => ['required', 'boolean'],
        ]);
    }

    public function store(Request $request, ReturnReasonService $service)
    {
        $data = $this->validatedData($request);
        try {
            $service->save(new ReturnReason, $data);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->with('error', 'Unable to create the return reason. Please try again.');
        }
        return redirect()->route('admin.return-reasons.index')->with('success', 'Return reason created successfully.');
    }

    public function update(Request $request, ReturnReason $returnReason, ReturnReasonService $service)
    {
        $data = $this->validatedData($request, $returnReason);
        try {
            $service->save($returnReason, $data);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->with('error', 'Unable to update the return reason. Please try again.');
        }
        return redirect()->route('admin.return-reasons.index')->with('success', 'Return reason updated successfully.');
    }

    public function updateStatus(Request $request, ReturnReason $returnReason, ReturnReasonService $service)
    {
        $data = $request->validate(['status' => ['required', 'boolean']]);
        try {
            $service->updateStatus($returnReason, (bool) $data['status']);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->with('error', 'Unable to update the return reason status.');
        }
        return back()->with('success', 'Return reason status updated successfully.');
    }

    public function destroy(ReturnReason $returnReason, ReturnReasonService $service)
    {
        try {
            $service->delete($returnReason);
        } catch (\Illuminate\Validation\ValidationException $exception) {
            return back()->with('error', $exception->validator->errors()->first());
        } catch (\Throwable $exception) {
            report($exception);
            return back()->with('error', 'Unable to delete the return reason. Please try again.');
        }
        return redirect()->route('admin.return-reasons.index')->with('success', 'Return reason deleted successfully.');
    }
}
