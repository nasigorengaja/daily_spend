<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        //hash::make / enkripsi
        // if (Auth::attempt([
        //     'email' => $validatedData['email'],
        //     'password' => $validatedData['password']
        // ])) {
        //     return redirect()->route('spend.index')->with('success', 'Login Success');
        // } else {
        //     $message = "Email or password wrong";
        //     return redirect()->route('login')->with('error', $message);
        // };

        //tanpa hash:make / enkripsi
        $user = User::where('email', $validatedData['email'])->first();

        if ($user && $user->password === $validatedData['password']) 
        {
            Auth::login($user);
            return redirect()->route('spend.index')->with('success', 'Login Success');
        } else {
            $message = "Email or password wrong";
            return redirect()->route('login')->with('error', $message);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
        ]);
        return redirect()->route('spend.index')->with('success', 'Register Success');
    }

    public function logOut(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Logout Success');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
