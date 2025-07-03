<?php
namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
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

        // Ambil message dari request
        $message = $request->input('message');

        if ($message && isset($message['from']['id'])) {
            $telegramUserId   = $message['from']['id']; // telegram user ID
            $telegramUsername = $message['from']['first_name'] . ' ' . $message['from']['last_name'];
            $telegramChatId   = $message['chat']['id'];
            $commentText      = $message['text']; // pesan yang dikirim

            // Cari user di sistem berdasarkan telegram_chat_id
            $user = User::where('telegram_chat_id', $telegramUserId)->first();

            if ($user) {
                // Dapatkan tiket yang sesuai dengan user
                // Misalnya, bisa menggunakan ID tiket yang terkait dengan pengguna atau sesuatu yang relevan
                $ticket = Ticket::where('user_id', $user->id)->first(); // Anda bisa menyesuaikan logika pencarian tiket sesuai kebutuhan

                if ($ticket) {
                    // Jika tiket ditemukan, buat komentar baru untuk tiket terkait
                    $comment            = new Comment();
                    $comment->comment   = $commentText;
                    $comment->user_id   = $user->id;              // ID user dari database
                    $comment->ticket_id = $ticket->id;             // Menggunakan ID tiket yang valid
                    $comment->save();
                } else {
                    // Log jika tiket tidak ditemukan
                    Log::error('Tiket tidak ditemukan untuk user Telegram ID: ' . $telegramUserId);
                }
            }

            // Balas dengan pesan 'ok' ke Telegram
            return response()->json(['ok' => true]);
        }

        return response()->json(['ok' => false]);
    }
}


