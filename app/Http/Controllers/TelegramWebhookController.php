<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller {
    public function handle(Request $request) {
        // Tulis ke laravel.log
        Log::info('Webhook DITERIMA:', $request->all());

        // Tulis juga ke telegram_webhook.txt
        file_put_contents(
            storage_path('logs/telegram_webhook.txt'),
            "[" . now() . "] " . json_encode($request->all()) . "\n",
            FILE_APPEND
        );

        // WAJIB balas ok ke Telegram!
        return response()->json(['ok' => true]);
    }
}
