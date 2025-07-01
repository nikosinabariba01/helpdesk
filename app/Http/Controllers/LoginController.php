<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    function index()
    {
        return view('Login');
    }

    function Login(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required',
            ],
            [
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Format email tidak valid',
                'password.required' => 'Password wajib diisi',
            ]
        );

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            if (Auth::attempt($credentials)) {
                if (Auth::user()->role == 'admin') {
                    return redirect('/admin');
                } elseif (Auth::user()->role == 'pengurus' || Auth::user()->role == 'pemilik') {
                    return redirect('/teknisi');  // Ganti dengan URL yang sesuai untuk pengurus dan pemilik
                } elseif (Auth::user()->role == 'penyewa') {
                    return redirect('/customer');
                }
            }
        } else {
            return back()->withErrors(['email' => 'Email atau password salah'])->withInput();
        }
    }


    function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
