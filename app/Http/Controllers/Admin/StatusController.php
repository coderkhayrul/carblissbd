<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Status;
use App\Services\StatusService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StatusController extends Controller
{
    public function index()
    {
        $statuses = Status::orderBy('id')->get();
        return view('admin.statuses.index', compact('statuses'));
    }

    public function create()
    {
        return view('admin.statuses.form', ['status' => new Status(['status' => true])]);
    }

    public function edit(Status $status)
    {
        return view('admin.statuses.form', compact('status'));
    }

    private function validatedData(Request $request, ?Status $status = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('statuses', 'name')->ignore($status?->id)],
            'status' => ['required', 'boolean'],
        ]);
    }

    public function store(Request $request, StatusService $service)
    {
        $data = $this->validatedData($request);
        try {
            $service->save(new Status, $data);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->with('error', 'Unable to create the status. Please try again.');
        }
        return redirect()->route('admin.statuses.index')->with('success', 'Status created successfully.');
    }

    public function update(Request $request, Status $status, StatusService $service)
    {
        $data = $this->validatedData($request, $status);
        try {
            $service->save($status, $data);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->with('error', 'Unable to update the status. Please try again.');
        }
        return redirect()->route('admin.statuses.index')->with('success', 'Status updated successfully.');
    }

    public function updateStatus(Request $request, Status $status, StatusService $service)
    {
        $data = $request->validate(['status' => ['required', 'boolean']]);
        try {
            $service->updateStatus($status, (bool) $data['status']);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->with('error', 'Unable to activate or deactivate the status.');
        }
        return back()->with('success', 'Status updated successfully.');
    }

    public function destroy(Status $status, StatusService $service)
    {
        try {
            $service->delete($status);
        } catch (\Illuminate\Validation\ValidationException $exception) {
            return back()->with('error', $exception->validator->errors()->first());
        } catch (\Throwable $exception) {
            report($exception);
            return back()->with('error', 'Unable to delete the status. Please try again.');
        }
        return redirect()->route('admin.statuses.index')->with('success', 'Status deleted successfully.');
    }
}
