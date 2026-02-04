<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Http\Request;

class TicketCommentController extends Controller
{

    // Fungsi untuk komentar dari penyewa
    public function store(Request $request, Ticket $ticket)
    {
        $request->validate([
            'commentText' => 'required|string',
        ]);

        // Simpan komentar
        $comment            = new Comment();
        $comment->comment   = $request->input('commentText');
        $comment->user_id   = auth()->user()->id;
        $comment->ticket_id = $ticket->id;
        $comment->save();

        // Mengirim pesan ke Telegram
        $telegram = new TelegramService();
        $userName = auth()->user()->name;
        $ticketNumber = "sp-" . substr(preg_replace('/[^0-9]/', '', $ticket->id), -3) . \Carbon\Carbon::parse($ticket->created_at)->format('dmy') . ($ticket->Jenis_Pengaduan == 0 ? '0' : '1');
        $message = "<b>Ticket {$ticketNumber}</b>\nKomentar oleh <b>{$userName} (anda)</b>:\n{$comment->comment}";

        // 1. Kirim ke pemilik tiket
        if ($ticket->user && $ticket->user->telegram_chat_id) {
            $telegram->sendMessage($ticket->user->telegram_chat_id, $message);
        }

        // 2. Kirim ke semua teknisi yang memiliki telegram_chat_id
        $teknisiChatIds = User::where('role', 'teknisi')
            ->whereNotNull('telegram_chat_id')
            ->pluck('telegram_chat_id')
            ->toArray();
        foreach ($teknisiChatIds as $teknisiChatId) {
            $telegram->sendMessage($teknisiChatId, $message);
        }

        return redirect(route('viewtickets.index', ['id' => $ticket->id]))
            ->with('success', 'Komentar berhasil dikirim dan notifikasi Telegram terkirim.');
    }

    // Fungsi untuk komentar dari teknisi
    public function teknisiComment(Request $request, Ticket $ticket)
    {
        $request->validate([
            'commentText' => 'required|string',
        ]);

        // Simpan komentar
        $comment            = new Comment();
        $comment->comment   = $request->input('commentText');
        $comment->user_id   = auth()->user()->id;
        $comment->ticket_id = $ticket->id;
        $comment->save();

        // Mengirim pesan ke Telegram
        $telegram = new TelegramService();
        $userName = auth()->user()->name;
        $ticketNumber = "sp-" . substr(preg_replace('/[^0-9]/', '', $ticket->id), -3) . \Carbon\Carbon::parse($ticket->created_at)->format('dmy') . ($ticket->Jenis_Pengaduan == 0 ? '0' : '1');
        $userRole = auth()->user()->role;
        $message  = "<b>Ticket #{$ticketNumber}</b>\nKomentar teknisi <b>{$userName} ({$userRole})</b>:\n{$comment->comment}";

        // 1. Kirim ke pemilik tiket
        if ($ticket->user && $ticket->user->telegram_chat_id) {
            $telegram->sendMessage($ticket->user->telegram_chat_id, $message);
        }

        // 2. Kirim ke semua teknisi
        $teknisiChatIds = User::where('role', 'teknisi')
            ->whereNotNull('telegram_chat_id')
            ->pluck('telegram_chat_id')
            ->toArray();
        foreach ($teknisiChatIds as $teknisiChatId) {
            $telegram->sendMessage($teknisiChatId, $message);
        }

        return redirect(route('viewticketteknisi.index', ['id' => $ticket->id]))
            ->with('success', 'Komentar teknisi berhasil dikirim dan notifikasi Telegram terkirim.');
    }
}
