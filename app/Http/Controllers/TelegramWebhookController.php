<?php
namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;

class TelegramWebhookController extends Controller {
    public function handle(Request $request) {
        $message = $request->input('message');
        if (! $message || ! isset($message['from']['id'])) {
            return response()->json(['ok' => true]);
        }

        // Temukan user berdasarkan telegram_chat_id
        $telegramUserId = $message['from']['id'];
        $user           = User::where('telegram_chat_id', $telegramUserId)->first();

                       // Anda bisa mapping ke ticket tertentu, atau parsing ticket_id dari text (custom)
        $ticketId = 1; // contoh default, sesuaikan kebutuhan

        if ($user) {
            $comment            = new Comment();
            $comment->comment   = $message['text'];
            $comment->user_id   = $user->id;
            $comment->ticket_id = $ticketId;
            $comment->save();
        }

        return response()->json(['ok' => true]);
    }
}
