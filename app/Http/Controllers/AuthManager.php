<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthManager extends Controller
{
    

    function adminlogin(){
        if(Auth::check()){
            return redirect()->intended(route('adminhome'));
        }
        return view('admin.login');
    }

    function adminloginPost(Request $request){
        $request->validate([
            'email'=>'required',
            'password'=>'required',
        ]);

        $data = $request->only('email', 'password');
        if(Auth::attempt($data)){
            return redirect()->intended(route('adminhome'));
        }

        return redirect(route('login'))->with('error', 'Email or Password wrong');
    }

    function adminlogout(){
        Session::flush();
        Auth::logout();
        return redirect(route('login'));
    }
/*
    function login(){
        if(Auth::check()){
            return redirect()->intended(route('home'));
        }
        return view('login');
    }
    function register(){
        if(Auth::check()){
            return redirect()->intended(route('home'));
        }
        return view('register');
    }

    function loginPost(Request $request){
        $request->validate([
            'email'=>'required',
            'password'=>'required',
        ]);

        $data = $request->only('email', 'password');
        if(Auth::attempt($data)){
            return redirect()->intended(route('home'));
        }

        return redirect(route('login'))->with('error', 'Email or Password wrong');
    }

    function registerPost(Request $request){
        $request->validate([
            'name'=>'r equired',
            'email'=>'required|email|unique:users',
            'password'=>'required',
        ]);

        $data['name']= $request->name;
        $data['email']= $request->email;
        $data['password']= Hash::make($request->password);
        $user = User::create($data);
        if(!$user){
            return redirect(route('register'))->with('error', 'Name or Email or Password wrong');
        }

        return redirect(route('login'))->with('success', 'Registration Successfull');

    }

    function logout(){
        Session::flush();
        Auth::logout();
        return redirect(route('login'));
    }
*/
}
