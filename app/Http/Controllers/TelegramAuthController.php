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
    public function telegramAuthorize(Request $request, $ticket_id)
    {
        $data = $request->all();

        // Validasi autentikasi data Telegram
        if (! $this->isValidTelegramAuth($data)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        if ($user) {
            // Simpan telegram_chat_id ke user
            $user->telegram_chat_id = $data['id'];
            $user->save();

            // Cek role user dan arahkan ke halaman yang sesuai
            if ($user->role == 'pengurus') {
                // Jika pengurus, arahkan ke halaman teknisi
                return redirect(route('viewticketteknisi.index', ['id' => $ticket_id]))
                    ->with('success', 'Akun Telegram berhasil terhubung sebagai pengurus!');
            } elseif ($user->role == 'penyewa') {
                // Jika penyewa, arahkan ke halaman customer
                return redirect(route('viewtickets.index', ['id' => $ticket_id]))
                    ->with('success', 'Akun Telegram berhasil terhubung sebagai penyewa!');
            } else {
                // Jika role selain pengurus atau penyewa, beri pesan error atau ke halaman default
                return redirect()->route('home')->with('error', 'Role tidak dikenali.');
            }
        } else {
            return response()->json(['success' => false, 'message' => 'No authenticated user'], 401);
        }
    }

    /**
     * Validasi data dari Telegram sesuai dokumen resminya.
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
