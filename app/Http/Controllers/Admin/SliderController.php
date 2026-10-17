<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Services\SliderService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SliderController extends Controller
{

    public SliderService $sliderService;


    public function __construct(SliderService $sliderService)
    {
        $this->sliderService = $sliderService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $slides = Slider::get();
        return view('admin.sliders.index', compact('slides'));
    }

    /**
     * Update the status of the specified slider.
     */
    public function updateStatus(Request $request, Slider $slider)
    {
        $validated = $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        $slider->status = (bool) $validated['status'];
        $slider->save();

        return response()->json(['status' => (bool) $slider->status]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.sliders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:sliders,slug'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:10000'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
            'button_title' => ['nullable', 'string', 'max:255'],
            'button_icon' => ['nullable', 'string', 'max:255'],
            'button_url' => ['nullable', 'url:http,https', 'max:255'],
            'orderBy' => ['nullable', 'integer', 'min:0', 'max:2147483647'],
            'button_status' => ['required', 'boolean'],
            'status' => ['required', 'boolean'],
        ]);

        unset($validated['image']);
        $validated['orderBy'] = $validated['orderBy'] ?? 0;
        $this->sliderService->create($validated, $request->file('image'));

        return redirect()->route('admin.sliders.index')
            ->with('success', 'Slider created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Slider $slider)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('sliders', 'slug')->ignore($slider)],
            'tagline' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:10000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
            'button_title' => ['nullable', 'string', 'max:255'],
            'button_icon' => ['nullable', 'string', 'max:255'],
            'button_url' => ['nullable', 'url:http,https', 'max:255'],
            'orderBy' => ['nullable', 'integer', 'min:0', 'max:2147483647'],
            'button_status' => ['required', 'boolean'],
            'status' => ['required', 'boolean'],
        ]);

        unset($validated['image']);
        $validated['orderBy'] = $validated['orderBy'] ?? 0;

        try {
            $this->sliderService->update($slider, $validated, $request->file('image'));
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Unable to update the slider. Please try again.');
        }

        return redirect()->route('admin.sliders.index')
            ->with('success', 'Slider updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $slider = Slider::findOrFail($id);

        try {
            $this->sliderService->delete($slider);

            return redirect()->route('admin.sliders.index')
                ->with('success', 'Slider deleted successfully.');
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('admin.sliders.index')
                ->with('error', 'Unable to delete the slider. Please try again.');
        }
    }
}
