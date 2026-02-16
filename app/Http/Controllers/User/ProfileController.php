<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Hash;

class ProfileController extends Controller
{
    public function show()
    {
        return view('user.profile');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_no' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'password' => 'nullable|string|min:6',
        ], [
            'name.required' => 'Name is required',
            'name.max' => 'Name cannot exceed 255 characters',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already in use',
            'phone_no.required' => 'Phone number is required',
            'address.required' => 'Address is required',
            'password.min' => 'Password must be at least 6 characters'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_no = $request->phone_no;
        $user->address = $request->address;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Fixed success message
        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
