<?php
namespace App\Http\Controllers;

use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
// Pastikan TelegramService sudah ada

class TelegramWebhookController extends Controller {
    public function handle(Request $request) {
        $update = $request->all(); // Ambil semua data dari Telegram

        // Log update yang diterima untuk debugging
        Log::info('Webhook diterima:', $update);

        // Cek jika ada pesan yang diterima dari pengguna
        if (isset($update['message'])) {
            $this->handleMessage($update['message']);
        }

        return response()->json(['ok' => true]);
    }

    // Fungsi untuk menangani pesan
    public function handleMessage($message) {
        // Ambil chat_id untuk balas pesan
        $chatId = $message['chat']['id'];
        $text   = $message['text']; // Teks pesan yang dikirim pengguna

        // Log pesan yang diterima
        Log::info("Pesan diterima: " . $text);

        // Cek apakah pesan yang diterima adalah /pilih
        if ($text === '/pilih') {
            // Tampilkan inline keyboard jika pesan adalah /pilih
            $this->showInlineKeyboard($chatId, "Silahkan pilih");
        } else {
            // Jika bukan /pilih, kirimkan pesan instruksi
            $this->sendTelegramMessage($chatId, "Silahkan ketik /pilih");
        }
    }

    // Fungsi untuk mengirim pesan ke Telegram
    protected function sendTelegramMessage($chatId, $message) {
        // Pastikan TelegramService sudah diimplementasikan
        $telegram = new TelegramService();
        $telegram->sendMessage($chatId, $message);
    }

    // Fungsi untuk menampilkan inline keyboard
    protected function showInlineKeyboard($chatId, $message) {
        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => 'Option 1', 'callback_data' => 'option_1'],
                    ['text' => 'Option 2', 'callback_data' => 'option_2'],
                ],
            ],
        ];

        // Kirim pesan dengan inline keyboard
        $this->sendTelegramMessage($chatId, $message, $keyboard);
    }
}
