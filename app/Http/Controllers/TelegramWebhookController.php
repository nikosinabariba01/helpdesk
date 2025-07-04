<?php
namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller {
    public function handle(Request $request) {
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

    // Menangani callback query (setelah pengguna memilih tiket)
    public function handleMessage($message) {
        $telegramUserId   = $message['from']['id'];
        $telegramUsername = $message['from']['first_name'] . ' ' . $message['from']['last_name'];
        $telegramChatId   = $message['chat']['id'];
        $commentText      = $message['text']; // Pesan yang dikirim
    
        Log::info("Pesan diterima dari {$telegramUsername} (ID: {$telegramUserId}): {$commentText}"); // Debugging log
    
        // Cari user berdasarkan telegram_chat_id
        $user = User::where('telegram_chat_id', $telegramUserId)->first();
    
        if ($user) {
            // Cek apakah pengguna sudah memilih tiket sebelumnya
            $ticketId = Cache::get("user_ticket_{$telegramChatId}");
    
            if ($ticketId) {
                // Jika tiket sudah dipilih, simpan komentar
                $this->saveComment($user, $commentText);
            } else {
                // Jika tiket belum dipilih, buatkan inline keyboard dengan tiket yang dimiliki pengguna
                $keyboard = [
                    'inline_keyboard' => [],
                ];
    
                // Ambil tiket yang dimiliki oleh pengguna dan buat tombol untuk masing-masing tiket
                $tickets = Ticket::where('user_id', $user->id)->get();
                foreach ($tickets as $ticket) {
                    $keyboard['inline_keyboard'][] = [
                        [
                            'text'          => "Tiket #{$ticket->id} - {$ticket->subject}",
                            'callback_data' => "ticket_{$ticket->id}", // Data yang dikirim saat memilih tiket
                        ],
                    ];
                }
    
                // Kirim pesan dengan inline keyboard berisi pilihan tiket
                $this->sendTelegramMessage($telegramChatId, "Silakan pilih tiket yang ingin Anda komentari:", $keyboard);
            }
        }
    
        return response()->json(['ok' => true]);
    }
    
    // Menangani callback query (setelah pengguna memilih tiket)
    public function handleCallback($callbackQuery) {
        $chatId = $callbackQuery['message']['chat']['id']; // Ambil chat_id dari callback query
        $data   = $callbackQuery['data'];                  // Ambil data yang dikirimkan saat memilih tiket, seperti "ticket_35"
    
        Log::info('Callback query diterima dengan data: ' . $data); // Debugging log
    
        if (strpos($data, 'ticket_') === 0) {
            $ticketId = substr($data, 7); // Mengambil ID tiket dari callback data
    
            $ticket = Ticket::find($ticketId);
            if ($ticket) {
                // Simpan ticket_id yang dipilih ke dalam cache
                Cache::put("user_ticket_{$chatId}", $ticketId, 3600); // Simpan selama 1 jam
    
                Log::info("Tiket ditemukan: {$ticket->id} - {$ticket->subject}");
    
                // Setelah memilih tiket, menunggu komentar dari pengguna
                $this->sendTelegramMessage($chatId, "Silakan kirim komentar Anda untuk tiket #{$ticket->id} - {$ticket->subject}:");
    
            } else {
                Log::error("Tiket dengan ID {$ticketId} tidak ditemukan.");
                $this->sendTelegramMessage($chatId, "Tiket yang Anda pilih tidak ditemukan.");
            }
        }
    }

    protected function sendTelegramMessage($chatId, $message, $keyboard = null) {
        // Kirim pesan ke Telegram dengan atau tanpa keyboard inline
        $telegram = new TelegramService(); // Pastikan TelegramService sudah diimplementasikan
        $telegram->sendMessage($chatId, $message, $keyboard);
    }
    
    
    // Menyimpan komentar berdasarkan ticket_id yang dipilih
    protected function saveComment($user, $commentText) {
        // Ambil ticket_id yang dipilih dari cache atau sesi
        $ticketId = Cache::get("user_ticket_{$user->telegram_chat_id}");
    
        if ($ticketId) {
            // Simpan komentar ke database untuk tiket yang sesuai
            $comment = new Comment();
            $comment->comment = $commentText;
            $comment->user_id = $user->id;
            $comment->ticket_id = $ticketId; // ID tiket yang valid
            $comment->save();
    
            // Mengirimkan konfirmasi ke pengguna
            $this->sendTelegramMessage($user->telegram_chat_id, "Komentar Anda telah berhasil disimpan untuk tiket #{$ticketId}.");
            return true;
        }
    
        return false;
    }
    
}
