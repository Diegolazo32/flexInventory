<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        //Si el request tiene un campo de busqueda
        if ($request->has('search')) {
            $users = User::where('nombre', 'like', "%{$request->search}%")
                ->orWhere('DUI', 'like', "%{$request->search}%")
                ->paginate(10);
        } else {
            $users = User::paginate(10);
        }

        $users = User::paginate(10);

        return view('Users.index')->with([
            'users' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'DUI' => 'required',
            'telefono' => 'required',
            'direccion' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
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
