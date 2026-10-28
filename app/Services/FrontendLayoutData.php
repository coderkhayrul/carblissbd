<?php

namespace App\Services;

use App\Models\{Category, CompanySetting, Page, PopupAds, WebSocialLink};
use Illuminate\Support\Facades\Cache;

class FrontendLayoutData
{
    private ?array $data = null;

    public function get(): array
    {
        if ($this->data !== null) {
            return $this->data;
        }

        // Store plain arrays: the cache configuration disallows serialized PHP objects.
        // The array wrapper also caches missing settings and an absent popup correctly.
        $payload = Cache::store(config('cache.frontend_store', 'file'))->remember(
            'frontend.layout.v1',
            now()->addMinutes(10),
            function () {
                $pages = Page::where('status', true)->where('show_in_quick_links', true)
                    ->whereIn('group_name', ['Customer Care', 'Information'])
                    ->orderBy('orderBy')->orderBy('id')
                    ->get(['id', 'name', 'slug', 'group_name'])->toArray();

                return [
                    'pages' => $pages,
                    'social_links' => WebSocialLink::where('status', true)->orderBy('id')->get()->toArray(),
                    'company' => CompanySetting::orderBy('id')->first()?->toArray(),
                    'categories' => Category::where('status', true)->where('show_on_menu', true)
                        ->with(['children' => fn ($query) => $query->where('status', true)->where('show_on_menu', true)
                            ->orderBy('menu_serial')->orderBy('id')])
                        ->orderBy('menu_serial')->orderBy('id')->get()->toArray(),
                    'popup' => PopupAds::orderBy('id')->first()?->toArray(),
                ];
            }
        );

        // Hydration creates view models in memory without querying the database.
        $pages = Page::hydrate($payload['pages']);
        $categories = Category::hydrate(array_map(function (array $category) {
            unset($category['children']);
            return $category;
        }, $payload['categories']));
        foreach ($categories as $index => $category) {
            $category->setRelation('children', Category::hydrate($payload['categories'][$index]['children'] ?? []));
        }
        $popup = $payload['popup'] ? (new PopupAds)->newFromBuilder($payload['popup']) : null;

        return $this->data = [
            'footerSocialLinks' => WebSocialLink::hydrate($payload['social_links']),
            'footerCompany' => $payload['company'] ? (new CompanySetting)->newFromBuilder($payload['company']) : null,
            'informationPages' => $pages->where('group_name', 'Information'),
            'customerCarePages' => $pages->where('group_name', 'Customer Care'),
            'menuCategories' => $categories,
            'popupAd' => $popup?->status ? $popup : null,
        ];
    }
}
