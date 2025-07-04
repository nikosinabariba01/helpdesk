<?php
namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller {
    public function handle(Request $request) {
        // Menangani Update (Pesan atau Callback Query)
        $update = $request->all();
        Log::info('Webhook DITERIMA:', $update);

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
    public function handleCallback($callbackQuery) {
        $chatId = $callbackQuery['from']['id']; // Ambil chat_id dari callback query
        $data   = $callbackQuery['data'];       // Ambil data yang dikirimkan saat memilih tiket, seperti "ticket_35"

        Log::info('Callback query diterima dengan data: ' . $data); // Debugging log

        if (strpos($data, 'ticket_') === 0) {
            $ticketId = substr($data, 7); // Mengambil ID tiket dari callback data

            $ticket = Ticket::find($ticketId);
            if ($ticket) {
                // Menyimpan ticket_id yang dipilih ke dalam session
                session(['selected_ticket_id' => $ticket->id]);

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
    public function handleMessage($message) {
        $telegramUserId   = $message['from']['id'];
        $telegramUsername = $message['from']['first_name'] . ' ' . $message['from']['last_name'];
        $telegramChatId   = $message['chat']['id'];
        $commentText      = $message['text']; // Pesan yang dikirim

        Log::info("Pesan diterima dari {$telegramUsername} (ID: {$telegramUserId}): {$commentText}"); // Debugging log

        // Cari user berdasarkan telegram_chat_id
        $user = User::where('telegram_chat_id', $telegramUserId)->first();

        if ($user) {
            // Membuat keyboard dengan pilihan tiket yang dimiliki pengguna
            $keyboard = [
                'inline_keyboard' => [],
            ];

            // Ambil tiket yang dimiliki oleh pengguna dan buat tombol untuk masing-masing tiket
            $tickets = Ticket::where('user_id', $user->id)->get();
            Log::info("Tiket ditemukan untuk pengguna: " . $user->id);
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

            // Cek apakah ada ticket_id yang dipilih di session
            $ticketId = session('selected_ticket_id');
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

    protected function sendTelegramMessage($chatId, $message, $keyboard = null) {
        // Kirim pesan ke Telegram dengan atau tanpa keyboard inline
        $telegram = new TelegramService();
        $telegram->sendMessage($chatId, $message, $keyboard);
    }
}
