<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\ActivityLog;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->paginate(15);
        $roles = Role::all();
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:users,email',
            'role_id' => 'required|exists:roles,id',
            'password' => 'required|string|min:8',
            'is_active' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['avatar'] = 'assets/images/sellers/' . rand(1, 10) . '.png';

        $user = User::create($validated);
        ActivityLog::log('user.create', "Created administrative user {$user->name} ({$user->email})", $user);

        return back()->with('success', "User '{$user->name}' created successfully.");
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => "required|email|max:255|unique:users,email,{$user->id}",
            'role_id' => 'required|exists:roles,id',
            'password' => 'nullable|string|min:8',
            'is_active' => 'boolean',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->boolean('is_active');

        $user->update($validated);
        ActivityLog::log('user.update', "Updated admin user {$user->name}", $user);

        return back()->with('success', "User '{$user->name}' updated successfully.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        ActivityLog::log('user.delete', "Deleted admin user {$user->name}", $user);

        return back()->with('success', 'User account removed.');
    }
}
