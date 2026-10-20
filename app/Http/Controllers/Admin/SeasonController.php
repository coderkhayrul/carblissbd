<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Season;
use App\Services\SeasonService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SeasonController extends Controller
{
    public function index()
    {
        $seasons = Season::orderBy('id')->get();
        return view('admin.seasons.index', compact('seasons'));
    }

    public function create()
    {
        return view('admin.seasons.form', ['season' => new Season(['status' => true])]);
    }

    public function edit(Season $season)
    {
        return view('admin.seasons.form', compact('season'));
    }

    private function validatedData(Request $request, ?Season $season = null): array
    {
        if (!$request->filled('slug')) {
            $request->merge(['slug' => Str::slug((string) $request->input('name'))]);
        }
        return $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('seasons', 'name')->ignore($season?->id)],
            'slug' => ['required', 'string', 'max:255', Rule::unique('seasons', 'slug')->ignore($season?->id)],
            'status' => ['required', 'boolean'],
        ]);
    }

    public function store(Request $request, SeasonService $service)
    {
        $data = $this->validatedData($request);
        try {
            $service->save(new Season, $data);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->with('error', 'Unable to create the season. Please try again.');
        }
        return redirect()->route('admin.seasons.index')->with('success', 'Season created successfully.');
    }

    public function update(Request $request, Season $season, SeasonService $service)
    {
        $data = $this->validatedData($request, $season);
        try {
            $service->save($season, $data);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->with('error', 'Unable to update the season. Please try again.');
        }
        return redirect()->route('admin.seasons.index')->with('success', 'Season updated successfully.');
    }

    public function updateStatus(Request $request, Season $season, SeasonService $service)
    {
        $data = $request->validate(['status' => ['required', 'boolean']]);
        try {
            $service->updateStatus($season, (bool) $data['status']);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->with('error', 'Unable to activate or deactivate the season.');
        }
        return back()->with('success', 'Season updated successfully.');
    }

    public function destroy(Season $season, SeasonService $service)
    {
        try {
            $service->delete($season);
        } catch (\Illuminate\Validation\ValidationException $exception) {
            return back()->with('error', $exception->validator->errors()->first());
        } catch (\Throwable $exception) {
            report($exception);
            return back()->with('error', 'Unable to delete the season. Please try again.');
        }
        return redirect()->route('admin.seasons.index')->with('success', 'Season deleted successfully.');
    }
}
