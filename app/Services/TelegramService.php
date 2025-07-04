<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

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

        Http::post($url, $data);
    }
}

