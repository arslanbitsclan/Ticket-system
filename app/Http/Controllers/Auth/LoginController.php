<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function index(){
        return view('auth/login');
    }
    
    public function login(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
    
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard')->withSuccess('Signed in');
            return redirect()->route('dashboard')->with('success', 'Signed in successfully');
        }
        return redirect()->route('login')->with('warning', 'Login details are not valid');
    }
    
    public function logout() {
        Session::flush();
        Auth::logout();
        return Redirect('login');
    }
}
