<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
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
