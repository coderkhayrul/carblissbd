<?php

use App\Models\PopupAds;
use App\Models\User;

test('active popup content appears on storefront pages and is escaped', function () {
    PopupAds::create([
        'title' => 'Weekend offer', 'description' => '<script>alert(1)</script>',
        'url' => 'https://example.com/offer', 'status' => true, 'is_video' => false,
    ]);

    foreach (['/', '/category'] as $page) {
        $this->get($page)->assertOk()
            ->assertSee('id="popup-ad-modal"', false)
            ->assertSee('modal-dialog-centered', false)
            ->assertSee('Weekend offer')
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)
            ->assertDontSee('<script>alert(1)</script>', false);
    }
});

test('missing inactive and empty ads do not render a modal', function () {
    $this->get('/')->assertOk()->assertDontSee('id="popup-ad-modal"', false);
    $ad = PopupAds::create(['title' => 'Hidden offer', 'status' => false]);
    $this->get('/')->assertOk()->assertDontSee('id="popup-ad-modal"', false);
    $ad->update(['title' => null, 'status' => true]);
    $this->get('/')->assertOk()->assertDontSee('id="popup-ad-modal"', false);
});

test('admin updates and status changes control the public popup', function () {
    $admin = User::factory()->create(['role' => 'admin', 'mobile' => '01711111111']);
    $this->actingAs($admin)->put(route('admin.popup-ads.update'), [
        'title' => 'New promotion', 'description' => 'Offer details',
        'url' => 'https://youtu.be/dQw4w9WgXcQ', 'is_video' => '1', 'status' => '1',
    ])->assertSessionHasNoErrors()->assertRedirect(route('admin.popup-ads.edit'));
    $this->get('/')->assertOk()->assertSee('New promotion')
        ->assertSee('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ', false);
    $this->patch(route('admin.popup-ads.status.update'), ['status' => '0'])
        ->assertSessionHasNoErrors();
    $this->get('/')->assertOk()->assertDontSee('id="popup-ad-modal"', false);
});
