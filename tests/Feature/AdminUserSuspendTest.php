<?php

use App\Models\User;

test('admin can suspend a selected user without changing other information', function () {
    $admin = User::factory()->create(['role' => 'admin', 'mobile' => '01711111111']);
    $user = User::factory()->create(['role' => 'admin', 'mobile' => '01722222222', 'status' => true]);
    $original = $user->only(['name', 'email', 'password', 'mobile', 'role']);

    $this->actingAs($admin)->patch(route('admin.users.suspend', $user->id))
        ->assertRedirect(route('admin.users.index'))->assertSessionHas('success');
    $user->refresh();
    expect((int) $user->status)->toBe(0);
    expect($user->only(array_keys($original)))->toBe($original);
    expect((int) $admin->fresh()->status)->toBe(1);
});

test('customer cannot suspend an admin', function () {
    $customer = User::factory()->create(['role' => 'customer', 'mobile' => '01711111111']);
    $admin = User::factory()->create(['role' => 'admin', 'mobile' => '01722222222', 'status' => true]);

    $this->actingAs($customer)->patch(route('admin.users.suspend', $admin->id))->assertRedirect('/');
    expect((int) $admin->fresh()->status)->toBe(1);
});
