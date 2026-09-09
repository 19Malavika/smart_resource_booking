<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('department');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(15)->withQueryString();
        $departments = Department::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'departments'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        $managers = User::where('role', 'manager')->orderBy('name')->get();
        return view('admin.users.create', compact('departments', 'managers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'employee_id' => 'nullable|string|max:50|unique:users,employee_id',
            'role' => 'required|in:admin,manager,employee',
            'department_id' => 'nullable|exists:departments,id',
            'designation' => 'nullable|string|max:255',
            'manager_id' => 'nullable|exists:users,id',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'created_user',
            'auditable_type' => User::class,
            'auditable_id' => $user->id,
            'new_values' => $user->only(['name', 'email', 'role', 'status']),
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load('department', 'manager', 'bookings.resource');
        $recentBookings = $user->bookings()->with('resource')->latest()->limit(10)->get();
        return view('admin.users.show', compact('user', 'recentBookings'));
    }

    public function edit(User $user)
    {
        $departments = Department::orderBy('name')->get();
        $managers = User::where('role', 'manager')->where('id', '!=', $user->id)->orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'departments', 'managers'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'employee_id' => ['nullable', 'string', 'max:50', Rule::unique('users', 'employee_id')->ignore($user->id)],
            'role' => 'required|in:admin,manager,employee',
            'department_id' => 'nullable|exists:departments,id',
            'designation' => 'nullable|string|max:255',
            'manager_id' => 'nullable|exists:users,id',
            'status' => 'required|in:active,inactive',
        ]);

        $oldValues = $user->only(['name', 'email', 'role', 'status']);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated_user',
            'auditable_type' => User::class,
            'auditable_id' => $user->id,
            'old_values' => $oldValues,
            'new_values' => $user->only(['name', 'email', 'role', 'status']),
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted_user',
            'auditable_type' => User::class,
            'auditable_id' => $user->id,
            'old_values' => $user->only(['name', 'email', 'role', 'status']),
            'ip_address' => request()->ip(),
        ]);

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
