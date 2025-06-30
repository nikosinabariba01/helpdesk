<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display dashboard for admin.
     */
    public function indexadmin()
    {
        // Dapatkan ID pengguna yang sedang login
        $userId = Auth::id();

        // Ambil tiket yang memiliki asignee_id null dan status open
        $teknisi_data_ticket = Ticket::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung statistik tiket berdasarkan status
        $totalOnProcessTickets = Ticket::where('asignee_id', $userId)
            ->where('status', 'on process')
            ->count();

        $totalClosedTickets = Ticket::where('asignee_id', $userId)
            ->where('status', 'close')
            ->count();

        $totalAllTickets = Ticket::whereNotNull('id')->count();

        $totalTickets = $teknisi_data_ticket->count();

        return view('administrator', compact('teknisi_data_ticket', 'totalTickets', 'totalOnProcessTickets', 'totalClosedTickets', 'totalAllTickets'));
    }

    /**
     * Display user management page.
     */
    public function ViewManageUser()
    {
        $users = User::all(); // Mengambil daftar pengguna
        return view('ManageUser', compact('users')); // Kirim variabel 'users' ke view
    }

    public function editUsers($id)
    {
        $user = User::findOrFail($id); // Mengambil pengguna berdasarkan ID
        return view('EditUser', compact('user')); // Kirim variabel 'user' ke view
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('CreateUser'); // View for user creation
    }

    /**
     * Store a newly created user.
     */
    public function storeUser(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role' => 'required|in:admin,pengurus,penyewa,pemilik', // Validasi role yang diperbarui
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal harus 8 karakter.',
            'role.required' => 'Peran wajib dipilih.',
            'role.in' => 'Peran yang dipilih tidak valid.', // Memastikan hanya role baru yang bisa dipilih
        ]);
    
        // Membuat user baru dengan data yang divalidasi
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Enkripsi password
            'role' => $request->role, // Role yang dipilih
        ]);
    
        // Redirect ke halaman manage user dengan pesan sukses
        return redirect()->route('admin.manageuser')->with('success', 'User berhasil dibuat.');
    }
    


    /**
     * Show the form for editing a user.
     */
    // public function editUser(User $user) {
    //     return view('admin.user.edit', compact('user'));
    // }

    /**
     * Update the specified user.
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'role' => 'required|in:admin,pengurus,penyewa,pemilik',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.manageuser')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Delete a user.
     */
    public function destroyUser(User $user)
    {
        $user->delete();

        return redirect()->route('admin.manageuser')->with('success', 'User berhasil dihapus.');
    }
}
