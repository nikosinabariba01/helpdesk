<?php
namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
// Pastikan TelegramService sudah ada

class TelegramWebhookController extends Controller {
    public function handle(Request $request) {
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
    public function handleMessage($message) {
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
                $this->sendTelegramMessage($chatId, "Silahkan ketik /pilih untuk memilih tiket.");
            }
        } else {
            $this->sendTelegramMessage($chatId, "Pengguna tidak terdaftar.");
        }
    }

    // Fungsi untuk mengirim pesan ke Telegram
    protected function sendTelegramMessage($chatId, $message, $keyboard = null) {
        // Pastikan TelegramService sudah diimplementasikan
        $telegram = new TelegramService();
        $telegram->sendMessage($chatId, $message, $keyboard);
    }

    // Fungsi untuk menampilkan inline keyboard dengan daftar tiket milik pengguna
    protected function showTicketInlineKeyboard($chatId, $message) {
        // Ambil tiket yang dimiliki oleh pengguna berdasarkan user_id
        $user = User::where('telegram_chat_id', $chatId)->first();

        if (! $user) {
            $this->sendTelegramMessage($chatId, "Pengguna tidak terdaftar.");
            return;
        }

                                                       // Ambil tiket yang dimiliki oleh pengguna dengan status selain 'close'
        $tickets = Ticket::where('user_id', $user->id) // Mengambil tiket berdasarkan user_id
            ->where('status', '!=', 'close')               // Hanya tiket yang belum ditutup
            ->get();

        if ($tickets->isEmpty()) {
            $this->sendTelegramMessage($chatId, "Anda tidak memiliki tiket aktif.");
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
    public function handleCallbackQuery($callbackQuery) {
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
                // Kirim balasan dengan informasi tiket
                $this->sendTelegramMessage($chatId, $this->formatTicketResponse($ticket));
            } else {
                $this->sendTelegramMessage($chatId, "Tiket tidak ditemukan.");
            }
        }
    }

    // Format balasan detail tiket
    protected function formatTicketResponse($ticket) {
        return "Detail Tiket:\n" .
        "Nomor Tiket: #sp-" . substr(preg_replace('/[^0-9]/', '', $ticket->id), -3) . \Carbon\Carbon::parse($ticket->created_at)->format('dmy') . ($ticket->Jenis_Pengaduan == 0 ? '0' : '1') . "\n" .
            "Subjek: {$ticket->subject}\n" .
            "Status: {$ticket->status}\n" .
            "Deskripsi: {$ticket->Detail}";
    }
}
