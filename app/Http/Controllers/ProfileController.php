<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Ambil data user yang sedang login
        return view('profile', compact('user')); // Tampilkan halaman profil
    }


    function teknisiprofile()
    {
        $user = Auth::user();
        return view('teknisiprofile', compact('user'));
    }


    public function updatecustomer(Request $request)
    {
        $user = Auth::user(); // Ambil user yang sedang login

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update data user
        $user->name = $request->input('name');
        $user->description = $request->input('description');

        // Update foto profil jika ada
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');

            // Hapus foto lama jika ada
            if ($user->profile_photo && Storage::exists($user->profile_photo)) {
                Storage::delete($user->profile_photo);
            }

            // Simpan foto baru
            $path = $file->store('profile_photos', 'public');
            $user->profile_photo = $path;
        }

        $user->save(); // Simpan perubahan

        // Redirect berdasarkan role pengguna
        if ($user->role == 'pengurus' || $user->role == 'pemilik' || $user->role == 'admin') {
            return redirect()->route('teknisi.profile')->with('success', 'Profile updated successfully.');
        } else {
            return redirect()->route('customer.profile')->with('success', 'Profile updated successfully.');
        }
    }



    // Redirect dengan pesan sukses


}
