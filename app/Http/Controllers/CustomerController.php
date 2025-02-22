<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function loginUser()
    {
        return view('sites.login');
    }

    public function post_login(Request $request){
        
    }

    public function logout(Request $request){
        
    }

    
    public function username()
    {
        $login = request()->input('login');
        return filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    }

    public function register()
    {
        return view('sites.register');
    }

    public function post_register(Request $request){

    }

}
