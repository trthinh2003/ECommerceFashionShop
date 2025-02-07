<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function login()
    {
        return view('admin.login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    public function username()
    {
        $login = request()->input('login');
        return filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    }

    public function post_login(Request $request)
    {
        $request->validate([
            'login' => [
                'required',
                function ($attribute, $value, $fail) {
                    $exists = DB::table('users')
                        ->where('username', $value)
                        ->orWhere('email', $value)
                        ->exists();

                    if (!$exists) {
                        $fail("Username hoặc Email không tồn tại.");
                    }
                }
            ],
            'password' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    // Lấy user theo login
                    $user = DB::table('users')
                        ->where('username', $request->login)
                        ->orWhere('email', $request->login)
                        ->first();

                    if (!$user || !Hash::check($value, $user->password)) {
                        $fail("Mật khẩu không chính xác.");
                    }
                }
            ]
        ]);
        $credentials = [
            $this->username() => $request->input('login'),
            'password' => $request->input('password'),
        ];
        if (auth()->attempt($credentials)) {
            return redirect()->route('admin.dashboard')->with('ok', 'Đăng nhập thành công!');
        }
        return redirect()->back();
    }
}
