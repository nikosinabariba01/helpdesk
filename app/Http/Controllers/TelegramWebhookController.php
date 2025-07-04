<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Menangani Update (Pesan atau Callback Query)
        $update = $request->all();
        Log::info('Webhook DITERIMA:', $update);

        // Cek apakah ini adalah callback query
        if (isset($update['callback_query'])) {
            // Ini adalah callback query
            $this->handleCallback($update['callback_query']);
        } elseif (isset($update['message'])) {
            // Ini adalah pesan biasa
            $this->handleMessage($update['message']);
        }

        return response()->json(['ok' => true]);
    }

    // Menangani callback query
    public function handleCallback($callbackQuery)
    {
        $chatId = $callbackQuery['from']['id']; // Gunakan callback_query['from']['id'] untuk mendapatkan chat_id
        $data = $callbackQuery['data']; // Data yang dikirimkan saat memilih tiket, seperti "ticket_35"

        Log::info('Callback query diterima dengan data: ' . $data); // Debugging log

        if (strpos($data, 'ticket_') === 0) {
            $ticketId = substr($data, 7); // Mengambil ID tiket dari callback data

            $ticket = Ticket::find($ticketId);
            if ($ticket) {
                // Kirimkan pesan kepada pengguna untuk mengirimkan komentar
                Log::info("Tiket ditemukan: {$ticket->id} - {$ticket->subject}");
                $this->sendTelegramMessage($chatId, "Silakan kirim komentar Anda untuk tiket #{$ticket->id} - {$ticket->subject}:");
            } else {
                Log::error("Tiket dengan ID {$ticketId} tidak ditemukan.");
                $this->sendTelegramMessage($chatId, "Tiket yang Anda pilih tidak ditemukan.");
            }
        } else {
            Log::error("Data callback query tidak valid: {$data}");
        }
    }


    // Menangani pesan biasa
    public function handleMessage($message)
    {
        $telegramUserId   = $message['from']['id'];
        $telegramUsername = $message['from']['first_name'] . ' ' . $message['from']['last_name'];
        $telegramChatId   = $message['chat']['id'];
        $commentText      = $message['text']; // pesan yang dikirim

        Log::info("Pesan diterima dari {$telegramUsername} (ID: {$telegramUserId}): {$commentText}"); // Debugging log

        // Cari user berdasarkan telegram_chat_id
        $user = User::where('telegram_chat_id', $telegramUserId)->first();

        if ($user) {
            // Cari tiket berdasarkan ID pengguna dan kirimkan komentar
            $ticketId = $this->getTicketIdForUser($user);
            if ($ticketId) {
                // Simpan komentar ke database untuk tiket yang sesuai
                $comment            = new Comment();
                $comment->comment   = $commentText;
                $comment->user_id   = $user->id;
                $comment->ticket_id = $ticketId; // ID tiket yang valid
                $comment->save();
            }
        }

        return response()->json(['ok' => true]);
    }

    protected function sendTelegramMessage($chatId, $message)
    {
        // Kirim pesan ke Telegram
        $telegram = new TelegramService();
        $telegram->sendMessage($chatId, $message);
    }

    protected function getTicketIdForUser($user)
    {
        // Ambil tiket yang valid berdasarkan user
        // Misalnya ambil tiket pertama yang valid
        $ticket = Ticket::where('user_id', $user->id)->first();
        return $ticket ? $ticket->id : null;
    }
}
