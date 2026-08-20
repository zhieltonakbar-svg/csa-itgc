<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Upti;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $users = User::with('upti')->orderBy('created_at', 'asc')->get();
        $uptis = Upti::orderBy('name')->get();
        return view('users.index', compact('users', 'uptis'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:creator,reviewer,approver',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => $request->role,
            'upti_id' => null,
            'is_active' => false,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'upti_id' => 'nullable|exists:uptis,id',
            'is_active' => 'required|boolean',
        ]);

        $user->update([
            'upti_id' => $request->upti_id,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Activate a user.
     */
    public function activate(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $user->update(['is_active' => true]);

        return redirect()->back()->with('success', "User {$user->name} has been successfully activated.");
    }

    /**
     * Deactivate a user.
     */
    public function deactivate(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $user->update(['is_active' => false]);

        return redirect()->back()->with('success', "User {$user->name} has been deactivated.");
    }

    public function destroy(User $user)
    {
        if (!auth()->user()->isAdmin() && auth()->id() !== $user->id) {
            abort(403, 'Unauthorized.');
        }

        $user->delete();

        // If the user deleted their own account, log them out
        if (auth()->id() === $user->id) {
            auth()->logout();
            return redirect('/login')->with('success', 'Your account has been deleted.');
        }

        return redirect()->back()->with('success', 'User has been permanently deleted.');
    }
}
