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

        // Cek jika ada callback query (tombol ditekan)
        if (isset($update['callback_query'])) {
            $this->handleCallbackQuery($update['callback_query']);
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
            $this->showInlineKeyboard($chatId, "Silahkan pilih:");
        } else {
            // Jika bukan /pilih, kirimkan pesan instruksi
            $this->sendTelegramMessage($chatId, "Silahkan ketik /pilih");
        }
    }

    // Fungsi untuk mengirim pesan ke Telegram
    protected function sendTelegramMessage($chatId, $message, $keyboard = null) {
        // Pastikan TelegramService sudah diimplementasikan
        $telegram = new TelegramService();
        $telegram->sendMessage($chatId, $message, $keyboard);
    }

    // Fungsi untuk menampilkan inline keyboard
    protected function showInlineKeyboard($chatId, $message) {
        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => 'Halo Bro', 'callback_data' => 'halo_bro'],
                    ['text' => 'Halo Mas', 'callback_data' => 'halo_mas'],
                ],
            ],
        ];

        // Kirim pesan dengan inline keyboard
        $this->sendTelegramMessage($chatId, $message, $keyboard);
    }

    // Fungsi untuk menangani callback query (setelah pengguna memilih opsi di inline keyboard)
    public function handleCallbackQuery($callbackQuery) {
        $chatId = $callbackQuery['message']['chat']['id']; // Ambil chat_id dari callback query
        $data   = $callbackQuery['data'];                  // Ambil data yang dikirimkan saat memilih tombol, seperti "halo_bro" atau "halo_mas"

        // Log callback query yang diterima
        Log::info('Callback query diterima dengan data: ' . $data);

        // Mengirimkan pesan balasan berdasarkan pilihan pengguna
        if ($data === 'halo_bro') {
            $this->sendTelegramMessage($chatId, "Pesan dari bot: Halo Bro!");
        } elseif ($data === 'halo_mas') {
            $this->sendTelegramMessage($chatId, "Pesan dari bot: Halo Mas!");
        }
    }
}
