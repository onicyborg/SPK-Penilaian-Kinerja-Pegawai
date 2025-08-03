<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function profile()
    {
        return view('profile');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->save();
        return response()->json(['message' => 'Profile berhasil diupdate']);
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Password lama anda salah'], 422);
        }
        $user->password = Hash::make($request->new_password);
        $user->save();
        return response()->json(['message' => 'Password berhasil diupdate']);
    }

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

    public function logout()
    {
        Auth::logout();

        return redirect('/login')->with('success', 'You have been logged out successfully.');
    }
}
