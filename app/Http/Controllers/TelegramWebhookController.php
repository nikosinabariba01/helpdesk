<?php
namespace App\Http\Controllers;

use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
// Pastikan service ini ada dan benar.

class TelegramWebhookController extends Controller {
    public function handle(Request $request) {
        // Menangani pembaruan (update) yang diterima dari Telegram
        $update = $request->all();

        // Log data pembaruan untuk debugging
        Log::info('Webhook Diterima:', $update);

        // Pastikan ada pesan yang diterima
        if (isset($update['message'])) {
            $this->handleMessage($update['message']);
        }

        // Mengembalikan respons JSON ke Telegram (kebutuhan Telegram)
        return response()->json(['ok' => true]);
    }

    // Fungsi untuk menangani pesan dan membalas dengan "Halo"
    public function handleMessage($message) {
        $telegramChatId = $message['chat']['id']; // Ambil chat_id
        $telegramText   = $message['text'];       // Ambil teks pesan yang dikirimkan oleh pengguna

        // Log pesan yang diterima
        Log::info("Pesan diterima: {$telegramText}");

        // Kirim pesan balasan ke Telegram
        $this->sendTelegramMessage($telegramChatId, "Halo");
    }

    // Fungsi untuk mengirim pesan ke Telegram
    protected function sendTelegramMessage($chatId, $message) {
        $telegram = new TelegramService(); // Pastikan service Telegram sudah terimplementasi
        $telegram->sendMessage($chatId, $message);
    }
}
