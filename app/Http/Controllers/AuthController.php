<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ===============================
    // LOGIN FORM
    // ===============================
    public function loginForm()
    {
        return view('auth.login');
    }

    // ===============================
    // LOGIN LOGIC (ADMIN + USER)
    // ===============================
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|regex:/^[^@\s]+@[^@\s]+\.[^@\s]+$/',
            'password' => 'required|min:3'
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 3 characters'
        ]);

        /* ===============================
           STATIC ADMIN LOGIN (FIXED)
        =============================== */
        if ($request->email === 'admin@gmail.com' && $request->password === 'admin123') {

            // 🔴 CLEAR OLD SESSION COMPLETELY
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $admin = User::firstOrCreate(
                ['email' => 'admin@gmail.com'],
                [
                    'name'     => 'Admin',
                    'password' => Hash::make('admin123'),
                    'role'     => 'admin'
                ]
            );

            // ✅ SAFE LOGIN
            Auth::loginUsingId($admin->id);
            $request->session()->regenerate();

            return redirect('/admin/dashboard');
        }

        /* ===============================
           NORMAL USER / ADMIN LOGIN
        =============================== */
        if (Auth::attempt($request->only('email', 'password'))) {

            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect('/admin/dashboard');
            }

            return redirect('/home');
        }

        return back()->with('error', 'Invalid Email or Password');
    }

    // ===============================
    // REGISTER FORM
    // ===============================
    public function registerForm()
    {
        return view('auth.register');
    }

    // ===============================
    // REGISTER USER
    // ===============================
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:50',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ], [
            'name.required' => 'Name is required',
            'name.max' => 'Name cannot exceed 50 characters',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already registered',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters',
            'password.confirmed' => 'Passwords do not match'
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user'
        ]);

        return redirect('/login')->with('success', 'Registration successful! Please login.');
    }

    // ===============================
    // LOGOUT
    // ===============================
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
