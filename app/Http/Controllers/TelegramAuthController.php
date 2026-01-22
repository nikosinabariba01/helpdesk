<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


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
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user) {
            // Simpan telegram_chat_id ke user
            $user->telegram_chat_id = $data['id'];

            // Jika user belum memiliki foto profil dan Telegram memiliki foto, simpan dari Telegram
            if (!$user->profile_photo && isset($data['photo_url'])) {
                $photoUrl = $data['photo_url'];

                // Download foto dari Telegram
                try {
                    $photoContent = file_get_contents($photoUrl);
                    if ($photoContent !== false) {
                        // Buat nama file unik
                        $fileName = 'telegram_' . $user->id . '_' . time() . '.jpg';
                        $filePath = storage_path('app/profile_photos/' . $fileName);

                        // Pastikan direktori ada
                        if (!is_dir(storage_path('app/profile_photos'))) {
                            mkdir(storage_path('app/profile_photos'), 0755, true);
                        }

                        // Simpan foto ke storage
                        file_put_contents($filePath, $photoContent);

                        // Simpan path foto ke database
                        $user->profile_photo = 'profile_photos/' . $fileName;
                    }
                } catch (\Exception $e) {
                    // Jika gagal download foto, tetap lanjut proses
                    Log::error('Failed to download Telegram photo: ' . $e->getMessage());
                }
            }

            $user->save();

            // Redirect ke URL sebelumnya
            $redirectUrl = session('previous_url', route('home'));

            // Cek role user dan arahkan ke halaman yang sesuai
            if (in_array($user->role, ['pengurus', 'pemilik'])) {
                // Jika pengurus, arahkan ke halaman teknisi
                return redirect($redirectUrl)
                    ->with('success', 'Akun Telegram berhasil terhubung sebagai pengurus!');
            } elseif ($user->role == 'penyewa') {
                // Jika penyewa, arahkan ke halaman customer
                return redirect($redirectUrl)
                    ->with('success', 'Akun Telegram berhasil terhubung sebagai penyewa!');
            } else {
                // Jika role selain pengurus atau penyewa, beri pesan error atau ke halaman default
                return redirect($redirectUrl)->with('error', 'Role tidak dikenali.');
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

    /**
     * Handle Telegram logout and remove telegram_chat_id from user.
     */
    public function telegramLogout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $user->telegram_chat_id = null;
            $user->save();

            return redirect()->route('customer.profile')
                ->with('success', 'Telegram berhasil diputuskan!');
        } else {
            return redirect()->route('customer.profile')
                ->with('error', 'User tidak ditemukan!');
        }
    }

    /**
     * Handle Telegram authorization from Profile page (without ticket redirect).
     */
    public function telegramAuthorizeFromProfile(Request $request)
    {
        $data = $request->all();

        // Validasi autentikasi data Telegram
        if (! $this->isValidTelegramAuth($data)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user) {
            // Simpan telegram_chat_id ke user
            $user->telegram_chat_id = $data['id'];

            // Jika user belum memiliki foto profil dan Telegram memiliki foto, simpan dari Telegram
            if (! $user->profile_photo && isset($data['photo_url'])) {
                $photoUrl = $data['photo_url'];

                // Download foto dari Telegram
                try {
                    $photoContent = file_get_contents($photoUrl);
                    if ($photoContent !== false) {
                        // Buat nama file unik
                        $filename = 'telegram_' . $user->id . '_' . time() . '.jpg';

                        // Simpan foto ke storage public menggunakan disk 'public'
                        $path = Storage::disk('public')->put('profile_photos/' . $filename, $photoContent);

                        // Jika berhasil, update path foto profil di database
                        if ($path) {
                            $user->profile_photo = 'profile_photos/' . $filename;
                        }
                    }
                } catch (\Exception $e) {
                    // Jika gagal download foto, tetap lanjut proses
                    Log::error('Failed to download Telegram photo: ' . $e->getMessage());
                }
            }

            $user->save();

            return redirect()->route('customer.profile')
                ->with('success', 'Akun Telegram berhasil terhubung!');
        } else {
            return response()->json(['success' => false, 'message' => 'No authenticated user'], 401);
        }
    }
}
