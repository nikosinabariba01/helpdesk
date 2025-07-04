<?php
namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Services\TelegramService;
use Illuminate\Http\Request;

class TelegramWebhookController extends Controller {
    public function handle(Request $request) {
        // Ambil data pesan dari webhook
        $message = $request->input('message');

        // Cek apakah ada pesan dan ID pengirim
        if ($message && isset($message['from']['id'])) {
            $telegramUserId = $message['from']['id']; // ID User Telegram
            $telegramChatId = $message['chat']['id']; // ID Chat Telegram
            $user           = User::where('telegram_chat_id', $telegramUserId)->first();

            if ($user) {
                // Ambil semua tiket yang dimiliki oleh pengguna
                $tickets = Ticket::where('user_id', $user->id)->get();

                if ($tickets->isEmpty()) {
                    $this->sendTelegramMessage($telegramChatId, "Anda belum memiliki tiket yang dapat dipilih.");
                } else {
                    // Membuat Inline Keyboard untuk memilih tiket
                    $keyboard = [];
                    foreach ($tickets as $ticket) {
                        $keyboard[] = [
                            'text'          => "Tiket #{$ticket->id} - {$ticket->subject}",
                            'callback_data' => "ticket_{$ticket->id}", // Data yang akan diterima saat memilih
                        ];
                    }

                    // Kirim pesan dengan pilihan tiket
                    $this->sendTelegramMessage(
                        $telegramChatId,
                        "Silakan pilih tiket yang ingin Anda komentari:",
                        [
                            'inline_keyboard' => [$keyboard],
                        ]
                    );
                }
            }
        }

        // Balas 'ok' ke Telegram
        return response()->json(['ok' => true]);
    }

    public function handleCallback(Request $request) {
        $callbackQuery = $request->input('callback_query');
        $chatId        = $callbackQuery['message']['chat']['id'];
        $data          = $callbackQuery['data']; // data yang dikirimkan saat memilih tiket, seperti "ticket_1234"

        if (strpos($data, 'ticket_') === 0) {
            $ticketId = substr($data, 7); // Mengambil ID tiket dari callback data

            $ticket = Ticket::find($ticketId);
            if ($ticket) {
                // Kirimkan pesan kepada pengguna untuk mengirimkan komentar
                $this->sendTelegramMessage(
                    $chatId,
                    "Silakan kirim komentar Anda untuk tiket #{$ticket->id} - {$ticket->subject}:"
                );
            } else {
                $this->sendTelegramMessage($chatId, "Tiket yang Anda pilih tidak ditemukan.");
            }
        }

        return response()->json(['ok' => true]);
    }

    private function sendTelegramMessage($chatId, $message, $keyboard = null) {
        $telegramService = new TelegramService();
        $telegramService->sendMessage($chatId, $message, $keyboard);
    }
}



