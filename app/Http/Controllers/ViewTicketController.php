<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class ViewTicketController extends Controller
{

    //customer
    public function index($id)
    {
        // Mengambil tiket dengan relasi 'asignee', 'user', dan 'comments' menggunakan eager loading
        $ticket = Ticket::with(['asignee', 'user', 'comments.user'])->findOrFail($id);

        // Periksa apakah pengguna yang terautentikasi memiliki akses ke tiket
        $authUserId = Auth::id();
        if ($authUserId !== $ticket->user_id) {
            abort(404, 'Ticket not found or unauthorized access.');
        }

        // Mengurutkan komentar dari yang terbaru ke yang terlama
        $ticket->setRelation('comments', $ticket->comments()->orderBy('created_at', 'desc')->get());

        // Mendapatkan nama pengguna yang membuat tiket
        $userName = $ticket->user->name;

        // Mengembalikan view dengan data tiket, nama pengguna, dan komentar
        return view('viewticket', compact('ticket', 'userName'));
    }

    //teknisi
    public function viewticketteknisi($id)
    {
        // Mengambil tiket dari database berdasarkan ID
        $ticket = Ticket::with(['asignee', 'user', 'comments.user'])->findOrFail($id);
        $userName = $ticket->user->name;
        $ticket->setRelation('comments', $ticket->comments()->orderBy('created_at', 'desc')->get());



        return view('teknisiviewticket', compact('ticket', 'userName'));
    }

    public function downloadImage(Ticket $ticket)
    {
        // Periksa apakah gambar ada
        if (!$ticket->gambar || !Storage::exists('public/' . $ticket->gambar)) {
            return back()->with('error', 'File not found.');
        }

        // Unduh file
        return Storage::download('public/' . $ticket->gambar, basename($ticket->gambar));
    }
}
