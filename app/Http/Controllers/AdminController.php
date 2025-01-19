<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard() {
        return view('admin.dashboard');
    }

    public function login() {
        return view('admin.login');
    }

    public function logout() {
        auth()->logout();
        return redirect()->route('admin.login');
    }

    public function post_login(Request $request) {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);
        $data = $request->only('email', 'password');
        // dd($data);
        if (auth()->attempt($data)) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->back();
    }
}
