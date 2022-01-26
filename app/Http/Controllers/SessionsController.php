<?php

namespace App\Http\Controllers;

class SessionsController extends Controller
{
    public function create()
    {
        return view('sessions.create');
    }

    public function store()
    {
        $attributes = request()->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        // Attempt to authenticate and log in the user
        // based on the provided credentials

        if (auth()->attempt($attributes)) {
            session()->regenerate();
            // redirect with a success flash message
            return redirect('/')->with('success', 'Welcome Back!');
        }

        // auth failed
        return back()
            ->withInput()
            ->withErrors(['email'=>'Your provided credentials could not be verified.']);
    }

    public function destroy()
    {
        auth()->logout();

        return redirect('/')->with('success', 'Goodbye!');
    }
}
