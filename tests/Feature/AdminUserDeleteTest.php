<?php

use App\Models\User;

test('admin can delete the selected user without deleting other users', function () {
    $admin = User::factory()->create(['role' => 'admin', 'mobile' => '01711111111']);
    $user = User::factory()->create(['role' => 'admin', 'mobile' => '01722222222']);

    $this->actingAs($admin)->delete(route('admin.users.destroy', $user))
        ->assertRedirect(route('admin.users.index'))->assertSessionHas('success');

    $this->assertDatabaseMissing('users', ['id' => $user->id]);
    $this->assertDatabaseHas('users', ['id' => $admin->id]);
});

test('customer cannot delete an admin user', function () {
    $customer = User::factory()->create(['role' => 'customer', 'mobile' => '01711111111']);
    $admin = User::factory()->create(['role' => 'admin', 'mobile' => '01722222222']);

    $this->actingAs($customer)->delete(route('admin.users.destroy', $admin))->assertRedirect('/');
    $this->assertDatabaseHas('users', ['id' => $admin->id]);
});
