<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Login | Gian Cellular',
            'user' => User::all()
        ];

        return view('auth.login', $data);
    }

    public function authenticate(Request $request)
    {
        $credentials =  $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->with('loginError', 'Username atau Password Salah');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/auth');
    }

    public function register()
    {
        $data = [
            'title' => 'User | Gian Cellular',
            'user' => User::all()
        ];

        return view('auth.register');
    }
}
