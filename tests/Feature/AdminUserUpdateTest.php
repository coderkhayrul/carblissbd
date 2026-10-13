<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('admin can update a user and keep or replace their password', function () {
    $admin = User::factory()->create(['role' => 'admin', 'mobile' => '01711111111']);
    $user = User::factory()->create(['role' => 'admin', 'mobile' => '01722222222']);
    $originalPassword = $user->password;
    $data = [
        'name' => 'Updated User', 'email' => $user->email,
        'mobile' => $user->mobile, 'username' => 'updated',
        'address' => 'Dhaka', 'status' => '0', 'password' => '',
    ];

    $this->actingAs($admin)->put(route('admin.users.update', $user), $data)
        ->assertSessionHasNoErrors()->assertRedirect(route('admin.users.index'));
    $user->refresh();
    expect($user->name)->toBe('Updated User');
    expect($user->password)->toBe($originalPassword);
    expect((int) $user->status)->toBe(0);

    $data['password'] = 'replacement-password';
    $this->put(route('admin.users.update', $user), $data)->assertSessionHasNoErrors();
    expect(Hash::check('replacement-password', $user->fresh()->password))->toBeTrue();
});

test('duplicate email and mobile are rejected without updating the user', function () {
    $admin = User::factory()->create(['role' => 'admin', 'mobile' => '01711111111']);
    $user = User::factory()->create(['role' => 'admin', 'mobile' => '01722222222']);
    $originalName = $user->name;
    $this->actingAs($admin)->from(route('admin.users.index'))
        ->put(route('admin.users.update', $user), [
            'name' => 'Invalid Update', 'email' => $admin->email,
            'mobile' => $admin->mobile, 'address' => 'Dhaka', 'status' => '1',
        ])->assertSessionHasErrorsIn('editUser', ['email', 'mobile']);
    expect($user->fresh()->name)->toBe($originalName);
});
