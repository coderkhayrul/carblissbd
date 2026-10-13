<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function createUser(array $request)
    {
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'mobile' => $request['mobile'],
            'role' => 'admin',
            'username' => $request['username'],
            'address' => $request['address'],
            'status' => $request['status'] == '1' ? true : false,
        ]);

        // Image Update
        if (isset($request['image']) && $request['image'] instanceof UploadedFile) {
            $image = saveImage($request['image'], '/uploads/users/');
            if ($user->image) {
                deleteImage($user->image);
            }
            $user->update(['image' => $image]);
        }
    }

    public function updateUser(User $user, array $data, Request $request)
    {
        if (empty($data['password'])) {
            unset($data['password']);
        }
        unset($data['image']);
        if ($request->hasFile('image')) {
            $data['image'] = saveImage($request->file('image'), '/uploads/users/');
        }
        if ($user->email !== $data['email']) {
            $user->email_verified_at = null;
        }
        $user->fill($data)->save();
    }
}
