<?php

use App\Models\User;

test('admin can unsuspend a selected user without changing other information', function () {
    $admin = User::factory()->create(['role' => 'admin', 'mobile' => '01711111111']);
    $user = User::factory()->create(['role' => 'admin', 'mobile' => '01722222222', 'status' => false]);
    $original = $user->only(['name', 'email', 'password', 'mobile', 'role']);

    $this->actingAs($admin)->patch(route('admin.users.unsuspend', $user->id))
        ->assertRedirect(route('admin.users.index'))->assertSessionHas('success');
    $user->refresh();
    expect((int) $user->status)->toBe(1);
    expect($user->only(array_keys($original)))->toBe($original);
});

test('customer cannot unsuspend an admin', function () {
    $customer = User::factory()->create(['role' => 'customer', 'mobile' => '01711111111']);
    $admin = User::factory()->create(['role' => 'admin', 'mobile' => '01722222222', 'status' => false]);

    $this->actingAs($customer)->patch(route('admin.users.unsuspend', $admin->id))->assertRedirect('/');
    expect((int) $admin->fresh()->status)->toBe(0);
});
