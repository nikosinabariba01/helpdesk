<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class ForgotPasswordTelegramController extends Controller {
    private function otpCacheKey($email) {
        return 'telegram_password_otp_' . sha1(strtolower($email));
    }

    private function rateLimitKey($email, Request $request) {
        return 'forgot_password_telegram_' . sha1(strtolower($email) . '|' . $request->ip());
    }

    public function showEmailForm() {
        return view('auth.forgot-password-telegram');
    }

    public function sendOtp(Request $request) {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        $email = strtolower($request->email);

        $rateKey = $this->rateLimitKey($email, $request);

        if (RateLimiter::tooManyAttempts($rateKey, 3)) {
            $seconds = RateLimiter::availableIn($rateKey);

            return back()->withErrors([
                'email' => 'Terlalu banyak permintaan OTP. Silakan coba lagi dalam ' . ceil($seconds / 60) . ' menit.',
            ])->withInput();
        }

        RateLimiter::hit($rateKey, 600);

        $user = User::where('email', $email)->first();

        if (! $user) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan.',
            ])->withInput();
        }

        if (! $user->telegram_chat_id) {
            return back()->withErrors([
                'email' => 'Akun ini belum terhubung dengan Telegram. Silakan hubungi admin.',
            ])->withInput();
        }

        $otp = random_int(100000, 999999);

        Cache::put($this->otpCacheKey($email), [
            'email'    => $email,
            'otp_hash' => Hash::make((string) $otp),
        ], now()->addMinutes(10));

        session([
            'telegram_reset_email' => $email,
        ]);

        $message = "<b>Kode OTP Reset Password</b>\n\n";
        $message .= "Kode OTP Anda adalah: <b>{$otp}</b>\n";
        $message .= "Kode ini berlaku selama 10 menit.\n\n";
        $message .= "Jika Anda tidak meminta reset password, abaikan pesan ini.";

        $telegram  = new TelegramService();
        $telegram->sendMessage($user->telegram_chat_id, $message);

        return redirect()->route('telegram.password.verifyForm')
            ->with('success', 'Kode OTP telah dikirim ke Telegram Anda.');
    }

    public function showOtpForm() {
        if (! session('telegram_reset_email')) {
            return redirect()->route('telegram.password.request');
        }

        return view('auth.verify-telegram-otp');
    }

    public function verifyOtp(Request $request) {
        $request->validate([
            'otp' => 'required|digits:6',
        ], [
            'otp.required' => 'OTP wajib diisi.',
            'otp.digits'   => 'OTP harus terdiri dari 6 digit.',
        ]);

        $email = session('telegram_reset_email');

        if (! $email) {
            return redirect()->route('telegram.password.request');
        }

        $cacheKey  = $this->otpCacheKey($email);
        $cachedOtp = Cache::get($cacheKey);

        if (! $cachedOtp) {
            return back()->withErrors([
                'otp' => 'OTP sudah kedaluwarsa atau sudah digunakan. Silakan minta OTP baru.',
            ]);
        }

        if (! Hash::check((string) $request->otp, $cachedOtp['otp_hash'])) {
            return back()->withErrors([
                'otp' => 'OTP yang dimasukkan salah.',
            ]);
        }

        // OTP langsung dihapus supaya hanya bisa digunakan satu kali
        Cache::forget($cacheKey);

        session([
            'telegram_otp_verified'         => true,
            'telegram_reset_verified_until' => now()->addMinutes(10)->timestamp,
        ]);

        return redirect()->route('telegram.password.resetForm')
            ->with('success', 'OTP berhasil diverifikasi. Silakan buat password baru.');
    }

    public function showResetForm() {
        if (! session('telegram_reset_email') || ! session('telegram_otp_verified')) {
            return redirect()->route('telegram.password.request');
        }

        if (! session('telegram_reset_verified_until') || session('telegram_reset_verified_until') < now()->timestamp) {
            session()->forget([
                'telegram_reset_email',
                'telegram_otp_verified',
                'telegram_reset_verified_until',
            ]);

            return redirect()->route('telegram.password.request')
                ->withErrors(['email' => 'Sesi reset password telah berakhir. Silakan minta OTP baru.']);
        }

        return view('auth.reset-password-telegram');
    }

    public function resetPassword(Request $request) {
        if (! session('telegram_reset_email') || ! session('telegram_otp_verified')) {
            return redirect()->route('telegram.password.request');
        }

        if (! session('telegram_reset_verified_until') || session('telegram_reset_verified_until') < now()->timestamp) {
            session()->forget([
                'telegram_reset_email',
                'telegram_otp_verified',
                'telegram_reset_verified_until',
            ]);

            return redirect()->route('telegram.password.request')
                ->withErrors(['email' => 'Sesi reset password telah berakhir. Silakan minta OTP baru.']);
        }

        $request->validate([
            'password' => 'required|min:8|confirmed',
        ], [
            'password.required'  => 'Password baru wajib diisi.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
        ]);

        $email = session('telegram_reset_email');

        $user = User::where('email', $email)->firstOrFail();

        $user->password = Hash::make($request->password);
        $user->setRememberToken(Str::random(60));
        $user->save();

        session()->forget([
            'telegram_reset_email',
            'telegram_otp_verified',
            'telegram_reset_verified_until',
        ]);

        return redirect('/')
            ->with('success', 'Password berhasil diubah. Silakan login dengan password baru.');
    }
}