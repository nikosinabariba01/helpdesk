<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class TelegramService {
    protected $token;

    public function __construct() {
        $this->token = config('services.telegram.bot_token');
    }

    /**
     * Kirim pesan ke satu user/teknisi (atau ke grup/channel, sesuai chat_id)
     */
    public function sendMessage($chatId, $text) {
        $url = "https://api.telegram.org/bot{$this->token}/sendMessage";
        return Http::post($url, [
            'chat_id'    => $chatId,
            'text'       => $text,
            'parse_mode' => 'HTML',
        ]);
    }

    /**
     * Kirim pesan ke banyak user/teknisi sekaligus
     */
    public function sendBulkMessage(array $chatIds, $text) {
        foreach ($chatIds as $chatId) {
            $this->sendMessage($chatId, $text);
        }
    }
}
