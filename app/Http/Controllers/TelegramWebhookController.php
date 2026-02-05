<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $update = $request->all(); // Ambil semua data dari Telegram

        // Log update yang diterima untuk debugging
        Log::info('Webhook diterima:', $update);

        // Cek jika ada pesan yang diterima dari pengguna
        if (isset($update['message'])) {
            $this->handleMessage($update['message']);
        }

        // Cek jika ada callback query (tombol ditekan)
        if (isset($update['callback_query'])) {
            $this->handleCallbackQuery($update['callback_query']);
        }

        return response()->json(['ok' => true]);
    }

    // Fungsi untuk menangani pesan
    public function handleMessage($message)
    {
        // Ambil chat_id untuk balas pesan
        $chatId = $message['chat']['id'];
        $text   = $message['text']; // Teks pesan yang dikirim pengguna

        // Log pesan yang diterima
        Log::info("Pesan diterima: " . $text);

        // Cari user berdasarkan telegram_chat_id
        $user = User::where('telegram_chat_id', $chatId)->first();

        if ($user) {
            // Cek apakah pesan yang diterima adalah /pilih
            if ($text === '/pilih') {
                // Ambil tiket yang dimiliki pengguna dan tampilkan inline keyboard
                $this->showTicketInlineKeyboard($chatId, "Silahkan pilih tiket:");
            } else {
                // Jika bukan /pilih, kirimkan pesan instruksi
                // Cek apakah pengguna sudah memilih tiket sebelumnya
                $ticketId = Cache::get("user_ticket_{$chatId}");

                if ($ticketId) {
                    // Menyimpan komentar pengguna
                    $this->saveComment($user, $text);
                } else {
                    // Kirimkan pesan instruksi jika tiket belum dipilih
                    $this->sendTelegramMessage($chatId, "❌ Anda belum memilih tiket.\n\nSilakan ketik <code>/ticket</code> atau <code>/pilih</code> untuk memilih tiket terlebih dahulu.");
                }
            }
        } else {
            $this->sendTelegramMessage($chatId, "Pengguna tidak terdaftar.");
        }
    }

    // Fungsi untuk mengirim pesan ke Telegram
    protected function sendTelegramMessage($chatId, $message, $keyboard = null)
    {
        // Pastikan TelegramService sudah diimplementasikan
        $telegram = new TelegramService();
        $telegram->sendMessage($chatId, $message, $keyboard);
    }

    // Fungsi untuk menampilkan inline keyboard dengan daftar tiket
    protected function showTicketInlineKeyboard($chatId, $message)
    {
        // Ambil tiket yang dimiliki oleh pengguna dan statusnya bukan 'close'
        $user = User::where('telegram_chat_id', $chatId)->first();

        if (! $user) {
            $this->sendTelegramMessage($chatId, "Pengguna tidak terdaftar.");
            return;
        }

        // Logika berdasarkan role pengguna
        if ($user->role == 'penyewa') {
            // Jika role adalah penghuni, ambil tiket yang dimiliki oleh pengguna
            $tickets = Ticket::where('user_id', $user->id)
                ->where('status', '!=', 'close')
                ->get();
        } elseif ($user->role == 'pemilik' || $user->role == 'pengurus') {
            // Jika role adalah teknisi atau pengelola, ambil tiket yang di-assign ke mereka
            $tickets = Ticket::whereHas('asignees', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
                ->where('status', '!=', 'close')
                ->get();
        } else {
            // Role tidak dikenali, kirim pesan kesalahan
            $this->sendTelegramMessage($chatId, "Role pengguna tidak dikenali.");
            return;
        }

        // Membuat inline keyboard
        $keyboard = [
            'inline_keyboard' => [],
        ];

        // Menambahkan tombol inline untuk setiap tiket yang ditemukan
        foreach ($tickets as $ticket) {
            $ticketText = "Tiket #sp-" . substr(preg_replace('/[^0-9]/', '', $ticket->id), -3) . \Carbon\Carbon::parse($ticket->created_at)->format('dmy') . ($ticket->Jenis_Pengaduan == 0 ? '0' : '1') . " - {$ticket->subject}";

            $keyboard['inline_keyboard'][] = [
                [
                    'text'          => $ticketText,            // Nama tombol sesuai dengan tiket
                    'callback_data' => "ticket_{$ticket->id}", // Callback data yang akan diproses saat tombol dipilih
                ],
            ];
        }

        // Kirimkan pesan dengan inline keyboard
        $this->sendTelegramMessage($chatId, $message, $keyboard);
    }

    // Fungsi untuk menangani callback query (setelah pengguna memilih tiket)
    public function handleCallbackQuery($callbackQuery)
    {
        $chatId = $callbackQuery['message']['chat']['id']; // Ambil chat_id dari callback query
        $data   = $callbackQuery['data'];                  // Ambil data yang dikirimkan saat memilih tombol, seperti "ticket_35"

        // Log callback query yang diterima
        Log::info('Callback query diterima dengan data: ' . $data);

        // Memeriksa apakah data adalah ID tiket
        if (strpos($data, 'ticket_') === 0) {
            $ticketId = substr($data, 7); // Mengambil ID tiket dari callback data

            // Ambil tiket berdasarkan ID
            $ticket = Ticket::find($ticketId);
            if ($ticket) {
                // Simpan ticket_id yang dipilih ke dalam cache menggunakan chat_id sebagai key
                Cache::put("user_ticket_{$chatId}", $ticketId, 3600); // Simpan selama 1 jam

                // Kirim balasan dengan pesan instruksi untuk komentar
                $this->sendTelegramMessage($chatId, "Silahkan kirim komentar Anda untuk tiket {$ticket->subject}.");
            } else {
                $this->sendTelegramMessage($chatId, "Tiket tidak ditemukan.");
            }
        }
    }

    // Menyimpan komentar berdasarkan ticket_id yang dipilih
    protected function saveComment($user, $commentText)
    {
        // Ambil ticket_id yang dipilih dari cache menggunakan telegram_chat_id sebagai key
        $ticketId = Cache::get("user_ticket_{$user->telegram_chat_id}");

        Log::info("Mencoba menyimpan comment. User ID: {$user->id}, Telegram Chat ID: {$user->telegram_chat_id}");
        Log::info("Ticket ID dari cache: " . ($ticketId ?? 'NOT FOUND'));

        if ($ticketId) {
            Log::info("Tiket ditemukan, menyimpan comment untuk ticket_id: {$ticketId}");

            // Ambil data tiket untuk menampilkan format yang benar
            $ticket = Ticket::find($ticketId);

            // Simpan komentar ke database untuk tiket yang sesuai
            $comment            = new Comment();
            $comment->comment   = $commentText;
            $comment->user_id   = $user->id;
            $comment->ticket_id = $ticketId; // ID tiket yang valid
            $comment->save();

            Log::info("Comment berhasil disimpan. Comment ID: {$comment->id}");

            // Format nomor tiket dengan format yang sama seperti di inline keyboard
            $ticketNumber = "sp-" . substr(preg_replace('/[^0-9]/', '', $ticketId), -3) . \Carbon\Carbon::parse($ticket->created_at)->format('dmy') . ($ticket->Jenis_Pengaduan == 0 ? '0' : '1');

            // Mengirimkan konfirmasi ke pengguna dengan format tiket yang benar
            $this->sendTelegramMessage($user->telegram_chat_id, "✅ Komentar Anda telah berhasil disimpan untuk tiket <b>#" . $ticketNumber . "</b>.");

            // Clear cache setelah komentar disimpan
            Cache::forget("user_ticket_{$user->telegram_chat_id}");

            return true;
        } else {
            Log::warning("Ticket ID tidak ditemukan di cache untuk user {$user->id}");
            $this->sendTelegramMessage($user->telegram_chat_id, "❌ Silakan pilih tiket terlebih dahulu sebelum mengirim komentar.");
        }

        return false;
    }
}
