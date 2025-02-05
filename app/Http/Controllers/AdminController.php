<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard() {
        return view('admin.dashboard');
    }

    public function login() {
        return view('admin.login');
    }

    public function logout(Request $request) {
        // auth()->logout();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    public function post_login(Request $request) {
        // $request->validate([
        //     'email' => 'required|email|exists:users,email',
        //     'password' => 'required'
        // ]);
        // $data = $request->only('email', 'password');
        // // dd($data);
        // if (auth()->attempt($data)) {
        //     return redirect()->route('admin.dashboard')->with('ok', 'Đăng nhập thành công!');
        // }
        // return redirect()->back();

        $credentials = $request->only('login', 'password');
        // dd($request);

        $isEmail = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL);

        if ($isEmail) {
            // Nếu là email, kiểm tra cả bảng users và staff
            $credentials = ['email' => $credentials['login'], 'password' => $credentials['password']];

            if (Auth::guard('web')->attempt($credentials)) {
                return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập thành công với User!');
            }

            if (Auth::guard('staff')->attempt($credentials)) {
                return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập thành công với Staff!');
            }
        } else {
            // Nếu là username, chỉ kiểm tra trong bảng staff
            $credentials = ['username' => $credentials['login'], 'password' => $credentials['password']];

            if (Auth::guard('staff')->attempt($credentials)) {
                return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập thành công với Staff!');
            }
        }

        // Nếu không khớp với bất kỳ tài khoản nào
        return back()->with(['error' => 'Email/Username hoặc mật khẩu không chính xác!']);
    }
}
