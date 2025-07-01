<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller {
    public function handle(Request $request) {
        // Tulis ke log semua data yg dikirim Telegram
        Log::info('Webhook DITERIMA:', $request->all());

        // Atau, untuk debug langsung:
        // return response()->json($request->all());

        // WAJIB balas ok ke Telegram!
        return response()->json(['ok' => true]);
    }
}
