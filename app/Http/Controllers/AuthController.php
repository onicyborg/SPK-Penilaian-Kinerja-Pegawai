<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index()
    {
        // Redirect to dashboard if already authenticated
        if (Auth::check()) {
            return redirect('/');
        }

        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'emailorusername' => 'required|string',
            'password' => 'required|string',
        ],[
            'emailorusername.required' => 'Email or Username is required',
            'password.required' => 'Password is required',
        ]);

        $loginField = $request->input('emailorusername');
        $password = $request->input('password');

        // Determine if the input is email or username
        $fieldType = filter_var($loginField, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Attempt authentication
        $credentials = [
            $fieldType => $loginField,
            'password' => $password
        ];

        // Add remember me functionality
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended('/')->with('success', 'Login successful!');
        }

        return back()->withInput($request->except('password'))->withErrors([
            'emailorusername' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return redirect('/login')->with('success', 'You have been logged out successfully.');
    }
}
