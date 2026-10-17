<?php

use App\Models\Slider;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

beforeEach(function () {
    $this->sliderTestPublicPath = storage_path('framework/testing/slider-' . Str::uuid());
    $this->app->usePublicPath($this->sliderTestPublicPath);
    File::ensureDirectoryExists(public_path('uploads/sliders'));
    File::put(public_path('uploads/sliders/old.png'), 'old image');
    $this->actingAs(\App\Models\User::factory()->create(['role' => 'admin', 'mobile' => '01711111111']));
});

afterEach(function () {
    File::deleteDirectory($this->sliderTestPublicPath);
});

it('updates slider information while retaining the image and its own slug', function () {
    $slider = Slider::create(['name' => 'Original', 'slug' => 'original', 'image' => 'uploads/sliders/old.png']);

    $this->put(route('admin.sliders.update', $slider), [
        'name' => 'Updated', 'slug' => 'original', 'status' => '1', 'button_status' => '0',
    ])->assertSessionHasNoErrors()->assertSessionHas('success')->assertRedirect(route('admin.sliders.index'));

    expect($slider->fresh()->name)->toBe('Updated');
    expect($slider->fresh()->image)->toBe('uploads/sliders/old.png');
    expect(File::exists(public_path('uploads/sliders/old.png')))->toBeTrue();
});

it('replaces the slider image and removes the old uploaded image', function () {
    $slider = Slider::create(['name' => 'Original', 'image' => 'uploads/sliders/old.png']);

    $this->put(route('admin.sliders.update', $slider), [
        'name' => 'Updated', 'status' => '1', 'button_status' => '1',
        'image' => UploadedFile::fake()->image('replacement.png'),
    ])->assertSessionHasNoErrors()->assertSessionHas('success');

    expect($slider->fresh()->image)->not->toBe('uploads/sliders/old.png');
    expect(File::exists(public_path($slider->fresh()->image)))->toBeTrue();
    expect(File::exists(public_path('uploads/sliders/old.png')))->toBeFalse();
});
