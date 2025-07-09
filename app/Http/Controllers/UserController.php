<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Role;
use App\Models\Vertical;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user() || !auth()->user()->isManager()) {
            return redirect()->route('home')->with('error', 'Access denied.');
        }
        $users = User::withTrashed()->with('role', 'verticals')->get();
        $perPage = 'all';
        $roles = Role::whereNotIn('slug', ['admin', 'client'])->get();
        $verticals = Vertical::all();
        return view('users.index', compact('users', 'perPage', 'roles', 'verticals'));
    }

    public function edit(User $user)
    {
        return redirect()->route('users.index');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role_id' => 'nullable|exists:roles,id',
            'password' => 'nullable|string|min:6|confirmed',
            'vertical_ids'  => 'nullable|array',
            'vertical_ids.*' => 'exists:verticals,id',
        ]);

        $data = $request->only('full_name', 'username', 'role_id');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);
        // Sync verticals
        $oldVerticals = $user->verticals()->pluck('id')->toArray();
        $user->verticals()->sync($request->input('vertical_ids', []));
        $newVerticals = $user->verticals()->pluck('id')->toArray();
        // Manual audit log for pivot update
        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->withProperties([
                'user_full_name' => $user->full_name,
                'old_vertical_ids' => $oldVerticals,
                'new_vertical_ids' => $newVerticals,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ])
            ->log('User verticals updated');
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->user()->id) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        // This will now perform a soft delete (not permanent) due to SoftDeletes trait
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username'     => 'required|string|max:50|unique:users',
            'full_name'    => 'required|string|max:100',
            'role_id'      => 'required|exists:roles,id',
            'vertical_ids'  => 'nullable|array',
            'vertical_ids.*' => 'exists:verticals,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('users.index')
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'username'    => $request->username,
            'full_name'   => $request->full_name,
            'password'    => Hash::make('Welcome@123'),
            'role_id'     => $request->role_id,
            'must_change_password' => 1,
        ]);
        // Attach verticals
        $user->verticals()->sync($request->input('vertical_ids', []));
        return redirect()->route('users.index')->with('success', 'User created successfully! Default password is Welcome@123');
    }

    // Restore soft deleted user
    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        return redirect()->route('users.index')->with('success', 'User restored successfully!');
    }
}
