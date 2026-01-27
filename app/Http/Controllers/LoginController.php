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
            // Cek apakah user sudah di-soft delete
            $user = Auth::user();

            if ($user->deleted_at !== null) {
                Auth::logout(); // Logout user yang sudah di-soft delete
                return back()->withErrors(['email' => 'Akun Anda telah dinonaktifkan.'])->withInput();
            }

            // Role-based redirect
            if ($user->role == 'admin') {
                return redirect('/admin');
            } elseif ($user->role == 'pengurus' || $user->role == 'pemilik') {
                return redirect('/teknisi');  // Ganti dengan URL yang sesuai untuk pengurus dan pemilik
            } elseif ($user->role == 'penyewa') {
                return redirect('/customer');
            }
        } else {
            return back()->withErrors(['email' => 'Email atau password salah'])->withInput();
        }
    }

    function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
