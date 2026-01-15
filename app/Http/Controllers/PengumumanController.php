<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Comment;
use App\Models\Ticket;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use App\Models\Pengumuman;
use Illuminate\Support\Facades\Auth;

class PengumumanController extends Controller
{
    private function getLatestComments()
    {
        // Dapatkan ID pengguna yang sedang login
        $userId = Auth::id();

        // Mengambil tiket yang ditugaskan ke teknisi yang sedang login
        $assignedTickets = Ticket::whereHas('asignees', function ($query) use ($userId) {
            $query->where('user_id', $userId); // Menyaring tiket yang ditugaskan ke teknisi
        })->pluck('id'); // Mengambil ID tiket yang ditugaskan ke teknisi

        // Mengambil tiket yang ditugaskan dan waktu penugasannya
        $assignedTicketTimestamps = Ticket::whereIn('id', $assignedTickets)
            ->whereHas('asignees', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->pluck('created_at', 'id'); // Mengambil created_at tiap tiket yang ditugaskan ke teknisi

        // Ambil komentar-komentar setelah tiket ditugaskan (created_at tiket < created_at komentar)
        $latestComments = Comment::whereIn('ticket_id', $assignedTickets)
            ->where('user_id', '!=', $userId) // Menambahkan kondisi untuk menghindari komentar dari teknisi yang sedang login
            ->where(function ($query) use ($assignedTicketTimestamps) {
                foreach ($assignedTicketTimestamps as $ticketId => $assignedAt) {
                    $query->orWhere(function ($q) use ($ticketId, $assignedAt) {
                        $q->where('ticket_id', $ticketId)
                            ->where('created_at', '>', $assignedAt); // Hanya ambil komentar setelah tiket ditugaskan
                    });
                }
            })
            ->with('ticket.user') // Mengambil informasi tiket dan pengguna yang mengomentari
            ->latest() // Mengurutkan berdasarkan waktu terbaru
            ->limit(3) // Batasi hanya 3 komentar terbaru
            ->get();

        return $latestComments;
    }

    // Menampilkan form untuk membuat pengumuman
    public function create()
    {
        // Mengambil semua penyewa untuk dipilih di form
        $penyewa = User::where('role', 'penyewa')->get();

        // Dapatkan komentar terbaru
        $latestComments = $this->getLatestComments();

        // Mengarahkan ke view 'CreatePengumuman' dan membawa data penyewa
        return view('CreatePengumuman', compact ('penyewa', 'latestComments'));
    }

    // Method store untuk menyimpan pengumuman
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'penyewa' => 'required|array', // Penyewa yang dipilih
            'penyewa.*' => 'exists:users,id', // Pastikan ID pengguna valid
        ]);

        // Simpan pengumuman
        $pengumuman = new Pengumuman();
        $pengumuman->judul = $request->input('judul');
        $pengumuman->deskripsi = $request->input('deskripsi');
        $pengumuman->creator_id = auth()->user()->id;
        $pengumuman->save();

        // Menyimpan relasi pengumuman dengan penyewa
        $pengumuman->penerima()->sync($request->input('penyewa'));

        // Kirim pengumuman ke Telegram
        $telegram = new TelegramService();
        $message = "<b>Pengumuman Baru:</b>\n<b>{$pengumuman->judul}</b>\n{$pengumuman->deskripsi}";

        foreach ($request->input('penyewa') as $penyewaId) {
            $penyewa = User::find($penyewaId);
            if ($penyewa && $penyewa->telegram_chat_id) {
                $telegram->sendMessage($penyewa->telegram_chat_id, $message);
            }
        }

        return redirect()->back()->with('success', 'Pengumuman berhasil dibuat dan dikirim ke Telegram penyewa.');
    }
}
