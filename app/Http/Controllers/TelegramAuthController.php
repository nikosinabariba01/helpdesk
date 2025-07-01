<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TelegramAuthController extends Controller {
    /**
     * Handle Telegram authorization and save telegram_chat_id to user.
     */
    public function telegramAuthorize(Request $request) {
        $data = $request->all();
        if (! $this->isValidTelegramAuth($data)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        if ($user) {
            $user->telegram_chat_id = $data['id']; // Telegram user ID
            $user->save();

            return redirect(route('customer.profile'))->with('success', 'Akun Telegram berhasil terhubung!');
        } else {
            return response()->json(['success' => false, 'message' => 'No authenticated user'], 401);
        }
    }

}
