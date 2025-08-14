<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = User::where('role', 'admin')->orderBy('name')->simplePaginate(10);
        return view('admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admins.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'admin',
        ]);

        return redirect()->route('admins.index')->with('success', 'Admin created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $admin)
    {
        // Verifica se este é um admin
        if ($admin->role !== 'admin') {
            return redirect('/')->with('error', 'User is not an admin.');
        }

        return view('admins.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $admin)
    {
        // Verify this is an admin
        if ($admin->role !== 'admin') {
            return redirect()->route('admins.index')->with('error', 'User is not an admin.');
        }

        return view('admins.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $admin)
    {
        // Verify this is an admin
        if ($admin->role !== 'admin') {
            return redirect()->route('admins.index')->with('error', 'User is not an admin.');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $admin->id,
        ];

        // Only validate password if it's provided
        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8|confirmed';
        }

        $validated = $request->validate($rules);

        // Update user details
        $admin->name = $validated['name'];
        $admin->email = $validated['email'];

        // Only update password if provided
        if ($request->filled('password')) {
            $admin->password = bcrypt($validated['password']);
        }

        $admin->save();

        return redirect()->route('admins.index')->with('success', 'Admin updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $admin)
    {
        // Verify this is an admin
        if ($admin->role !== 'admin') {
            return redirect()->route('admins.index')->with('error', 'User is not an admin.');
        }

        // Prevent self-deletion
        if (Auth::check() && $admin->id === Auth::id()) {
            return redirect()->route('admins.index')->with('error', 'You cannot delete your own admin account.');
        }

        $admin->delete();

        return redirect()->route('admins.index')->with('success', 'Admin deleted successfully.');
    }
}
