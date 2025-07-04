<?php
namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class TelegramWebhookController extends Controller
{
    // Menangani callback query
    public function handleCallback($callbackQuery)
    {
        $chatId = $callbackQuery['from']['id']; // Ambil chat_id dari callback query
        $data = $callbackQuery['data']; // Ambil data yang dikirimkan saat memilih tiket, seperti "ticket_35"
    
        Log::info('Callback query diterima dengan data: ' . $data); // Debugging log
    
        if (strpos($data, 'ticket_') === 0) {
            $ticketId = substr($data, 7); // Mengambil ID tiket dari callback data
    
            $ticket = Ticket::find($ticketId);
            if ($ticket) {
                // Menyimpan ticket_id yang dipilih ke dalam cache
                Cache::put('selected_ticket_id_' . $chatId, $ticket->id, now()->addMinutes(30));

                // Kirimkan pesan kepada pengguna untuk mengirimkan komentar
                Log::info("Tiket ditemukan: {$ticket->id} - {$ticket->subject}");
                $this->sendTelegramMessage($chatId, "Silakan kirim komentar Anda untuk tiket #{$ticket->id} - {$ticket->subject}:");
            } else {
                Log::error("Tiket dengan ID {$ticketId} tidak ditemukan.");
                $this->sendTelegramMessage($chatId, "Tiket yang Anda pilih tidak ditemukan.");
            }
        }
    }

    // Menangani pesan biasa
    public function handleMessage($message)
    {
        $telegramUserId   = $message['from']['id'];
        $telegramUsername = $message['from']['first_name'] . ' ' . $message['from']['last_name'];
        $telegramChatId   = $message['chat']['id'];
        $commentText      = $message['text']; // Pesan yang dikirim
    
        Log::info("Pesan diterima dari {$telegramUsername} (ID: {$telegramUserId}): {$commentText}"); // Debugging log
    
        // Cari user berdasarkan telegram_chat_id
        $user = User::where('telegram_chat_id', $telegramUserId)->first();
    
        if ($user) {
            // Mengambil ticket_id yang dipilih dari cache
            $ticketId = Cache::get('selected_ticket_id_' . $telegramChatId);
            if ($ticketId) {
                // Simpan komentar ke database untuk tiket yang dipilih
                $comment            = new Comment();
                $comment->comment   = $commentText;
                $comment->user_id   = $user->id;
                $comment->ticket_id = $ticketId; // Gunakan ticket_id yang dipilih
                $comment->save();

                Log::info("Komentar berhasil disimpan untuk tiket {$ticketId}: {$commentText}");
            } else {
                Log::error("Tidak ada tiket yang dipilih untuk user dengan ID {$telegramUserId}. Komentar tidak dapat disimpan.");
            }
        }

        return response()->json(['ok' => true]);
    }

    protected function sendTelegramMessage($chatId, $message, $keyboard = null)
    {
        // Kirim pesan ke Telegram dengan atau tanpa keyboard inline
        $telegram = new TelegramService();
        $telegram->sendMessage($chatId, $message, $keyboard);
    }
}
