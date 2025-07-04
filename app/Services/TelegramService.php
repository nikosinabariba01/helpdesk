<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService {
    private $apiUrl;
    private $botToken;

    public function __construct() {
        $this->botToken = config('services.telegram.bot_token'); // Ambil bot token dari konfigurasi
        $this->apiUrl   = "https://api.telegram.org/bot{$this->botToken}/";
    }

    public function sendMessage($chatId, $message, $keyboard = null) {
        $url  = $this->apiUrl . 'sendMessage';
        $data = [
            'chat_id'    => $chatId,
            'text'       => $message,
            'parse_mode' => 'HTML',
        ];
    
        if ($keyboard) {
            $data['reply_markup'] = json_encode($keyboard);
        }
    
        // Log data yang akan dikirim ke Telegram
        Log::info("URL: " . $url);
        Log::info("Data yang dikirim: " . json_encode($data));
    
        // Kirim permintaan HTTP ke API Telegram
        $response = Http::post($url, $data);
    
        // Cek dan log respons dari Telegram
        if ($response->successful()) {
            Log::info("Pesan berhasil dikirim ke Telegram.");
        } else {
            Log::error("Terjadi kesalahan saat mengirim pesan ke Telegram. Respons: " . $response->body());
        }
    }
    
}

