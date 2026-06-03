<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


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
            // Cek apakah telegram_chat_id sudah digunakan oleh user lain
            $existingUser = \App\Models\User::where('telegram_chat_id', $data['id'])
                ->where('id', '!=', $user->id)
                ->first();

            if ($existingUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor Telegram ini sudah terdaftar. Silakan gunakan nomor Telegram yang berbeda.'
                ], 400);
            }

            // Simpan telegram_chat_id ke user
            $user->telegram_chat_id = $data['id'];

            // Jika user belum memiliki foto profil dan Telegram memiliki foto, simpan dari Telegram
            if (! $user->profile_photo && isset($data['photo_url'])) {
                $photoUrl = $data['photo_url'];

                // Download foto dari Telegram
                try {
                    $photoContent = file_get_contents($photoUrl);
                    if ($photoContent !== false) {
                        // Buat nama file unik menggunakan Str::random
                        $filename = Str::random(40) . '.' . pathinfo($photoUrl, PATHINFO_EXTENSION);

                        // Tentukan path penyimpanan foto di storage publik
                        $path = storage_path('app/public/profile_photos/' . $filename);

                        // Pastikan direktori ada
                        if (! is_dir(storage_path('app/public/profile_photos'))) {
                            mkdir(storage_path('app/public/profile_photos'), 0755, true);
                        }

                        // Simpan foto ke storage publik
                        file_put_contents($path, $photoContent);

                        // Update path foto di database dengan path publik
                        $user->profile_photo = 'profile_photos/' . $filename;
                    }
                } catch (\Exception $e) {
                    // Jika gagal download foto, tetap lanjutkan proses dan log error
                    Log::error('Failed to download Telegram photo: ' . $e->getMessage());
                }
            }

            $user->save();

            // Cek role user dan arahkan ke halaman yang sesuai
            $redirectUrl = '';
            if (in_array($user->role, ['pengurus', 'pemilik'])) {
                // Jika pengurus, arahkan ke halaman teknisi
                $redirectUrl = route('viewticketteknisi.index', ['id' => $ticket_id]);
            } elseif ($user->role == 'penyewa') {
                // Jika penyewa, arahkan ke halaman customer
                $redirectUrl = route('viewtickets.index', ['id' => $ticket_id]);
            } else {
                // Jika role selain pengurus atau penyewa, beri pesan error atau ke halaman default
                $redirectUrl = route('home');
            }

            return response()->json([
                'success' => true,
                'message' => 'Akun Telegram berhasil terhubung!',
                'url' => $redirectUrl
            ], 200);
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
        /** @var \App\Models\User $user */
        $user = Auth::user(); // Ambil user yang sedang login
        if ($user) {
            // Set telegram_chat_id to null to disconnect Telegram
            $user->telegram_chat_id = null;
            $user->save();

            // Redirect ke halaman berdasarkan role user
            if (in_array($user->role, ['pengurus', 'pemilik', 'admin'])) {
                return redirect()->route('teknisi.profile')
                    ->with('success', 'Telegram berhasil diputuskan!');
            } elseif ($user->role == 'penyewa') {
                return redirect()->route('customer.profile')
                    ->with('success', 'Telegram berhasil diputuskan!');
            } else {
                return redirect()->route('home')->with('error', 'Role tidak dikenali.');
            }
        } else {
            return response()->json(['success' => false, 'message' => 'No authenticated user'], 401);
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
            // Cek apakah telegram_chat_id sudah digunakan oleh user lain
            $existingUser = \App\Models\User::where('telegram_chat_id', $data['id'])
                ->where('id', '!=', $user->id)
                ->first();

            if ($existingUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor Telegram ini sudah terdaftar. Silakan gunakan nomor Telegram yang berbeda.'
                ], 400);
            }

            // Simpan telegram_chat_id ke user
            $user->telegram_chat_id = $data['id'];

            // Jika user belum memiliki foto profil dan Telegram memiliki foto, simpan dari Telegram
            if (! $user->profile_photo && isset($data['photo_url'])) {
                $photoUrl = $data['photo_url'];

                // Download foto dari Telegram
                try {
                    $photoContent = file_get_contents($photoUrl);
                    if ($photoContent !== false) {
                        // Buat nama file unik menggunakan Str::random
                        $filename = Str::random(40) . '.' . pathinfo($photoUrl, PATHINFO_EXTENSION);

                        // Tentukan path penyimpanan foto di storage publik
                        $path = storage_path('app/public/profile_photos/' . $filename);

                        // Pastikan direktori ada
                        if (! is_dir(storage_path('app/public/profile_photos'))) {
                            mkdir(storage_path('app/public/profile_photos'), 0755, true);
                        }

                        // Simpan foto ke storage publik
                        file_put_contents($path, $photoContent);

                        // Update path foto di database dengan path publik
                        $user->profile_photo = 'profile_photos/' . $filename;
                    }
                } catch (\Exception $e) {
                    // Jika gagal download foto, tetap lanjutkan proses dan log error
                    Log::error('Failed to download Telegram photo: ' . $e->getMessage());
                }
            }

            // Simpan perubahan ke database
            $user->save();

        // Cek role user dan arahkan ke halaman yang sesuai
            if (in_array($user->role, ['pengurus', 'pemilik', 'admin'])) {
                // Jika pengurus, arahkan ke halaman teknisi
                return redirect()->route('teknisi.profile')
                    ->with('success', 'Akun Telegram berhasil terhubung sebagai pengurus!');
            } elseif ($user->role == 'penyewa') {
                // Jika penyewa, arahkan ke halaman customer
                return redirect()->route('customer.profile')
                    ->with('success', 'Akun Telegram berhasil terhubung sebagai penyewa!');
            } else {
                // Jika role selain pengurus atau penyewa, beri pesan error atau ke halaman default
                return redirect()->route('home')->with('error', 'Role tidak dikenali.');
            }
        } else {
            return response()->json(['success' => false, 'message' => 'No authenticated user'], 401);
        }
    }
}
