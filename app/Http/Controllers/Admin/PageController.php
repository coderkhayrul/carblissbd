<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Services\PageService;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function updateStatus(Request $request, Page $page)
    {
        $validated = $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        $page->status = (bool) $validated['status'];
        $page->save();

        return response()->json(['status' => (bool) $page->status]);
    }

    public function updateQuickLink(Request $request, Page $page)
    {
        $validated = $request->validate([
            'show_in_quick_links' => ['required', 'boolean'],
        ]);

        $page->show_in_quick_links = (bool) $validated['show_in_quick_links'];
        $page->save();

        return response()->json(['show_in_quick_links' => (bool) $page->show_in_quick_links]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Page::all();
        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, PageService $pageService)
    {
        $validated = $this->validatePage($request);

        unset($validated['image'], $validated['banner_image']);
        $pageService->create($validated, $request->file('image'), $request->file('banner_image'));

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page created successfully.');
    }

    private function validatePage(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'group_name' => ['nullable', 'string', 'max:255'],
            'bangla_name' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
            'banner_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
            'content' => ['nullable', 'string'],
            'orderBy' => ['nullable', 'integer', 'min:0', 'max:2147483647'],
            'status' => ['required', 'boolean'],
            'show_in_quick_links' => ['required', 'boolean'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:65535'],
            'meta_tags' => ['nullable', 'string', 'max:255'],
        ]);

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
    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page, PageService $pageService)
    {
        $validated = $this->validatePage($request);
        unset($validated['image'], $validated['banner_image']);
        $pageService->update($page, $validated, $request->file('image'), $request->file('banner_image'));

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page, PageService $pageService)
    {
        try {
            $pageService->delete($page);

            return redirect()->route('admin.pages.index')
                ->with('success', 'Page deleted successfully.');
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()->route('admin.pages.index')
                ->with('error', 'Unable to delete the page. Please try again.');
        }
    }
}
