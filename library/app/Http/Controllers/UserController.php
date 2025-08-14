<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Requisition;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('role', 'citizen')->orderBy('name')->paginate(10);
        return view('users.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $requisitions = Requisition::where('user_id', $user->id)->orderByRaw('actual_return_date IS NULL DESC') // Ativas primeiro
            ->orderBy('actual_return_date', 'asc')->paginate(10);
        return view('users.show', compact('user', 'requisitions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
