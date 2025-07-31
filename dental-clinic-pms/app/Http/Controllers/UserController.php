<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        // Only administrators can manage users
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'Only administrators can manage users.');
        }

        $query = User::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by role
        if ($request->has('role') && !empty($request->role)) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->has('status') && $request->status === 'active') {
            $query->where('is_active', true);
        }

        $users = $query->orderBy('name')->paginate(20);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        // Only administrators can create users
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'Only administrators can create users.');
        }

        return view('users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        // Only administrators can create users
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'Only administrators can create users.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:administrator,dentist,receptionist',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'specialization' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['email_verified_at'] = now(); // Auto-verify for admin-created users

        $user = User::create($validated);

        return redirect()->route('users.show', $user)
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        // Only administrators can view user details
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'Only administrators can view user details.');
        }

        $user->load(['appointments', 'treatmentRecords']);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // Only administrators can edit users
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'Only administrators can edit users.');
        }

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        // Only administrators can edit users
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'Only administrators can edit users.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:administrator,dentist,receptionist',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'specialization' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        // Handle password update if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('users.show', $user)
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Only administrators can delete users
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'Only administrators can delete users.');
        }

        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return redirect()->route('users.show', $user)
                ->with('error', 'You cannot delete your own account.');
        }

        // Check if user has associated records
        if ($user->appointments()->count() > 0 || $user->treatmentRecords()->count() > 0) {
            return redirect()->route('users.show', $user)
                ->with('error', 'Cannot delete user with associated records. Consider deactivating instead.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Toggle user active status.
     */
    public function toggleStatus(User $user)
    {
        // Only administrators can toggle user status
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'Only administrators can toggle user status.');
        }

        // Prevent self-deactivation
        if ($user->id === auth()->id()) {
            return redirect()->route('users.show', $user)
                ->with('error', 'You cannot deactivate your own account.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';

        return redirect()->route('users.show', $user)
            ->with('success', "User {$status} successfully.");
    }

    /**
     * Reset user password.
     */
    public function resetPassword(Request $request, User $user)
    {
        // Only administrators can reset passwords
        if (!auth()->user()->isAdministrator()) {
            abort(403, 'Only administrators can reset passwords.');
        }

        $validated = $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update(['password' => Hash::make($validated['password'])]);

        return redirect()->route('users.show', $user)
            ->with('success', 'Password reset successfully.');
    }
}