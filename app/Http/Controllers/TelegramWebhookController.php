<?php
namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class TelegramWebhookController extends Controller {
    public function handle(Request $request) {
        // Log semua request yang masuk (debug)
        Log::info('Telegram Webhook Hit', [
            'body'    => $request->all(),
            'headers' => $request->headers->all(),
        ]);

        $message = $request->input('message');
        if (! $message || ! isset($message['from']['id'])) {
            Log::warning('Telegram Webhook: No valid message or from ID!');
            return response()->json(['ok' => true]);
        }

        // Temukan user berdasarkan telegram_chat_id
        $telegramUserId = $message['from']['id'];
        $user           = User::where('telegram_chat_id', $telegramUserId)->first();

        $ticketId = 1; // contoh default

        if ($user) {
            $comment            = new Comment();
            $comment->comment   = $message['text'];
            $comment->user_id   = $user->id;
            $comment->ticket_id = $ticketId;
            $comment->save();

            Log::info('Comment saved from Telegram user', ['user_id' => $user->id, 'comment' => $message['text']]);
        } else {
            Log::warning('Telegram Webhook: User not found!', ['telegram_id' => $telegramUserId]);
        }

        return response()->json(['ok' => true]);
    }
}