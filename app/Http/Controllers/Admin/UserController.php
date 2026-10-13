<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    public $userService;


    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::whereRole('admin')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        try {
            $this->userService->createUser($request->all());

            return redirect()->back()->with('success', 'User Create Successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while Created: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::whereRole('admin')->findOrFail($id);
        $data = $request->validateWithBag('editUser', [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'mobile' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'username' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'status' => ['required', 'boolean'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        try {
            $this->userService->updateUser($user, $data, $request);

            return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function suspend(string $id)
    {
        $user = User::whereRole('admin')->findOrFail($id);
        $user->update(['status' => false]);

        return redirect()->route('admin.users.index')->with('success', 'User suspended successfully.');
    }

    public function unsuspend(string $id)
    {
        $user = User::whereRole('admin')->findOrFail($id);
        $user->update(['status' => true]);

        return redirect()->route('admin.users.index')->with('success', 'User unsuspended successfully.');
    }

    public function destroy(string $id)
    {
        $user = User::whereRole('admin')->findOrFail($id);
        $user->delete();
        if ($user->image) {
            deleteImage($user->image);
        }

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
