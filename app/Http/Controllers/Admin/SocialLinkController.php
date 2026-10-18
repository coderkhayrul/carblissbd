<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebSocialLink;
use App\Services\SocialLinkService;
use Illuminate\Http\Request;

class SocialLinkController extends Controller
{
    public function updateStatus(Request $request, string $id, SocialLinkService $socialLinkService)
    {
        $validated = $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        $socialLink = WebSocialLink::findOrFail($id);
        $socialLinkService->updateStatus($socialLink, (bool) $validated['status']);

        return response()->json(['status' => (bool) $socialLink->status]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $socials = WebSocialLink::get();
        return view('admin.social-links.index', compact('socials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.social-links.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, SocialLinkService $socialLinkService)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'url' => ['required', 'url:http,https', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'background_color' => ['nullable', 'string', 'max:255'],
            'foreground_color' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'boolean'],
        ]);

        try {
            $socialLinkService->create($validated);
        } catch (\Throwable $exception) {
            report($exception);

            return back()->withInput()
                ->with('error', 'Unable to create the social link. Please try again.');
        }

        return redirect()->route('admin.social-links.index')
            ->with('success', 'Social link created successfully.');
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
    public function edit(WebSocialLink $social_link)
    {
        return view('admin.social-links.edit', compact('social_link'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WebSocialLink $social_link, SocialLinkService $socialLinkService)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'url' => ['required', 'url:http,https', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'background_color' => ['nullable', 'string', 'max:255'],
            'foreground_color' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'boolean'],
        ]);

        try {
            $socialLinkService->update($social_link, $validated);
        } catch (\Throwable $exception) {
            report($exception);

            return back()->withInput()
                ->with('error', 'Unable to update the social link. Please try again.');
        }

        return redirect()->route('admin.social-links.index')
            ->with('success', 'Social link updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, SocialLinkService $socialLinkService)
    {
        $socialLink = WebSocialLink::findOrFail($id);

        try {
            $socialLinkService->delete($socialLink);
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()->route('admin.social-links.index')
                ->with('error', 'Unable to delete the social link. Please try again.');
        }

        return redirect()->route('admin.social-links.index')
            ->with('success', 'Social link deleted successfully.');
    }
}
