<?php
namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Http\Request;

class TicketCommentController extends Controller {
    public function store(Request $request, Ticket $ticket) {
        $request->validate([
            'commentText' => 'required|string',
        ]);

        $comment            = new Comment();
        $comment->comment   = $request->input('commentText');
        $comment->user_id   = auth()->user()->id;
        $comment->ticket_id = $ticket->id;
        $comment->save();

        // Kirim ke Telegram (pemilik tiket & teknisi)
        $telegram = new TelegramService();
        $userName = auth()->user()->name;
        $message = "<b>Ticket #{$ticket->id}</b>\nKomentar oleh <b>{$userName}</b>:\n{$comment->comment}";

        // 1. Kirim ke pemilik tiket (asumsikan $ticket->user relasi ke User)
        if ($ticket->user && $ticket->user->telegram_chat_id) {
            $telegram->sendMessage($ticket->user->telegram_chat_id, $message);
        }

        // 2. Kirim ke semua teknisi yang punya telegram_chat_id
        $teknisiChatIds = User::where('role', 'teknisi')
            ->whereNotNull('telegram_chat_id')
            ->pluck('telegram_chat_id')
            ->toArray();
        if (!empty($teknisiChatIds)) {
            foreach ($teknisiChatIds as $teknisiChatId) {
                $telegram->sendMessage($teknisiChatId, $message);
            }
        }

        return redirect(route('viewtickets.index', ['id' => $ticket->id]))
            ->with('success', 'Komentar berhasil dikirim dan notifikasi Telegram terkirim.');
    }

    public function teknisiComment(Request $request, Ticket $ticket) {
        $request->validate([
            'commentText' => 'required|string',
        ]);

        $comment            = new Comment();
        $comment->comment   = $request->input('commentText');
        $comment->user_id   = auth()->user()->id;
        $comment->ticket_id = $ticket->id;
        $comment->save();

        $telegram = new TelegramService();
        $userName = auth()->user()->name;
        $message = "<b>Ticket #{$ticket->id}</b>\nKomentar teknisi <b>{$userName}</b>:\n{$comment->comment}";

        // 1. Kirim ke pemilik tiket
        if ($ticket->user && $ticket->user->telegram_chat_id) {
            $telegram->sendMessage($ticket->user->telegram_chat_id, $message);
        }

        // 2. Kirim ke semua teknisi
        $teknisiChatIds = User::where('role', 'teknisi')
            ->whereNotNull('telegram_chat_id')
            ->pluck('telegram_chat_id')
            ->toArray();
        if (!empty($teknisiChatIds)) {
            foreach ($teknisiChatIds as $teknisiChatId) {
                $telegram->sendMessage($teknisiChatId, $message);
            }
        }

        return redirect(route('viewticketteknisi.index', ['id' => $ticket->id]))
            ->with('success', 'Komentar teknisi berhasil dikirim dan notifikasi Telegram terkirim.');
    }
}
