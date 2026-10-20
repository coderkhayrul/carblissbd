<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Services\TagService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::orderBy('id')->get();
        return view('admin.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('admin.tags.form', ['tag' => new Tag(['status' => true])]);
    }

    public function edit(Tag $tag)
    {
        return view('admin.tags.form', compact('tag'));
    }

    private function validatedData(Request $request, ?Tag $tag = null): array
    {
        if (!$request->filled('slug')) {
            $request->merge(['slug' => Str::slug((string) $request->input('name'))]);
        }
        return $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('tags', 'name')->ignore($tag?->id)],
            'slug' => ['required', 'string', 'max:255', Rule::unique('tags', 'slug')->ignore($tag?->id)],
            'status' => ['required', 'boolean'],
        ]);
    }

    public function store(Request $request, TagService $service)
    {
        $data = $this->validatedData($request);
        try {
            $service->save(new Tag, $data);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->with('error', 'Unable to create the tag. Please try again.');
        }
        return redirect()->route('admin.tags.index')->with('success', 'Tag created successfully.');
    }

    public function update(Request $request, Tag $tag, TagService $service)
    {
        $data = $this->validatedData($request, $tag);
        try {
            $service->save($tag, $data);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->with('error', 'Unable to update the tag. Please try again.');
        }
        return redirect()->route('admin.tags.index')->with('success', 'Tag updated successfully.');
    }

    public function updateStatus(Request $request, Tag $tag, TagService $service)
    {
        $data = $request->validate(['status' => ['required', 'boolean']]);
        try {
            $service->updateStatus($tag, (bool) $data['status']);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->with('error', 'Unable to activate or deactivate the tag.');
        }
        return back()->with('success', 'Tag updated successfully.');
    }

    public function destroy(Tag $tag, TagService $service)
    {
        try {
            $service->delete($tag);
        } catch (\Illuminate\Validation\ValidationException $exception) {
            return back()->with('error', $exception->validator->errors()->first());
        } catch (\Throwable $exception) {
            report($exception);
            return back()->with('error', 'Unable to delete the tag. Please try again.');
        }
        return redirect()->route('admin.tags.index')->with('success', 'Tag deleted successfully.');
    }
}
