<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Ticket;


class TelegramAuthController extends Controller
{
    /**
     * Handle Telegram authorization and save telegram_chat_id to user.
     */
    public function telegramAuthorize(Request $request)
    {
        $data = $request->all();
        if (!$this->isValidTelegramAuth($data)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        if ($user) {
            // Menyimpan telegram_chat_id
            $user->telegram_chat_id = $data['id'];
            $user->save();

            // Menyimpan ID tiket ke dalam session jika belum ada
            $ticketId = session('current_ticket_id'); // Ambil dari session atau variabel lain

            // Pastikan ID tiket sudah ada
            if ($ticketId) {
                return redirect(route('viewtickets.index', ['id' => $ticketId]))
                    ->with('success', 'Akun Telegram berhasil terhubung!');
            } else {
                return redirect()->route('home')->with('error', 'Tiket tidak ditemukan.');
            }
        } else {
            return response()->json(['success' => false, 'message' => 'No authenticated user'], 401);
        }
    }


    /**
     * Validate data from Telegram according to official docs.
     */
    protected function isValidTelegramAuth($auth_data)
    {
        $check_hash = $auth_data['hash'];
        unset($auth_data['hash']);
        ksort($auth_data);

        $data_check_arr = [];
        foreach ($auth_data as $key => $value) {
            $data_check_arr[] = $key . '=' . $value;
        }
        $data_check_string = implode("\n", $data_check_arr);

        $secret_key = hash('sha256', config('services.telegram.bot_token'), true);
        $hash       = hash_hmac('sha256', $data_check_string, $secret_key);

        return strcmp($hash, $check_hash) === 0;
    }
}
