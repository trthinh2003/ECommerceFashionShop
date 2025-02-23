<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CustomerController extends Controller
{
    public function login()
    {
        return view('sites.login');
    }

    public function post_login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password_login' => 'required|min:6',
        ], [
            'login.required' => 'Vui lòng nhập email hoặc username.',
            'password_login.required' => 'Vui lòng nhập mật khẩu.',
            'password_login.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
        ]);

        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $loginField => $request->login,
            'password'  => $request->password_login
        ];

        if (Auth::guard('customer')->attempt($credentials)) {
            return redirect()->route('sites.home')->with('success', 'Đăng nhập thành công!');
        }

        return back()->withErrors(['login' => 'Email, Username hoặc mật khẩu không đúng.'])->withInput();
    }

    public function logout(Request $request){
        Auth::guard('customer')->logout();
        $request->session()->invalidate(); // Xóa toàn bộ session
        $request->session()->regenerateToken();

        return redirect()->route('user.login');
    }

    public function register()
    {
        return redirect()->route('user.login');
    }

    public function post_register(Request $request) {
        $request->validate([
            'name' => 'required|min:3|max:200',
            'email' => 'required|email|unique:customers,email',
            'password' => 'required|min:6|max:200',
            're_password' => 'required|same:password',
        ], [
            'name.required' => 'Họ và tên không được để trống.',
            'email.required' => 'Vui lòng nhập email của bạn.',
            'email.email' => 'Vui lòng nhập email hợp lệ.',
            'email.unique' => 'Email này đã tồn tại.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            're_password.required' => 'Vui lòng nhập lại mật khẩu.',
            're_password.same' => 'Mật khẩu xác nhận không khớp.',
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return view('sites.success.register', compact('customer'));
    }

    public function profile(Request $request) {
        $customer = Auth::guard('customer')->user();
        return view('sites.profile', compact('customer'));
    }
}
