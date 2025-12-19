<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();                    // Ambil data user yang sedang login
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
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string|max:500',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar dengan ukuran maksimal 2MB
        ], [
            'profile_photo.image' => 'File yang diunggah harus berupa gambar.',
            'profile_photo.mimes' => 'Hanya gambar dengan ekstensi jpeg, png, jpg, dan gif yang diperbolehkan.',
            'profile_photo.max'   => 'Ukuran gambar tidak boleh lebih dari 2MB.',
        ]);

        // Update data user
        $user->name        = $request->input('name');
        $user->description = $request->input('description');

        // Update foto profil jika ada
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');

            // Hapus foto lama jika ada
            if ($user->profile_photo && Storage::exists($user->profile_photo)) {
                Storage::delete($user->profile_photo);
            }

            // Membuat nama file unik untuk foto profil
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension(); // Nama acak + ekstensi asli

            // Simpan foto baru ke storage public
            $path = $file->storeAs('profile_photos', $filename, 'public');

            // Update path foto profil di database
            $user->profile_photo = $path;
        }

        // Simpan perubahan user
        $user->save();

        // Redirect berdasarkan role pengguna
        if ($user->role == 'pengurus' || $user->role == 'pemilik' || $user->role == 'admin') {
            return redirect()->route('teknisi.profile')->with('success', 'Profile updated successfully.');
        } else {
            return redirect()->route('customer.profile')->with('success', 'Profile updated successfully.');
        }
    }

    public function servePhoto($filename)
    {
        // Path asli file
        $path = storage_path('app/public/profile_photos/' . $filename);

        // Cek file ada atau tidak
        if (! File::exists($path)) {
            abort(404);
        }

        // Bisa tambahkan cek autentikasi/otorisasi di sini jika perlu
        return Response::file($path);
    }

    // Redirect dengan pesan sukses

}
