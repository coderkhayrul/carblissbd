<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Services\SliderService;
use Illuminate\Http\Request;

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
        return view('admin.slides.index', compact('slides'));
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
        return view('admin.slides.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
