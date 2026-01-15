<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function createAnnouncement(Request $request)
    {
        // Validasi input pengumuman
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'penyewa' => 'required|array', // Penyewa yang dipilih
            'penyewa.*' => 'exists:users,id', // Pastikan ID pengguna valid
        ]);

        // Simpan pengumuman
        $pengumuman = new Pengumuman();
        $pengumuman->judul = $request->input('judul');
        $pengumuman->deskripsi = $request->input('deskripsi');
        $pengumuman->creator_id = auth()->user()->id;  // ID pengguna yang membuat pengumuman
        $pengumuman->save();

        // Menyimpan relasi pengumuman dengan penyewa
        $pengumuman->penerima()->sync($request->input('penyewa')); // `penyewa` adalah array dari ID penyewa yang dipilih

        // Mengirim pengumuman ke Telegram
        $telegram = new TelegramService();
        $message = "<b>Pengumuman Baru:</b>\n<b>{$pengumuman->judul}</b>\n{$pengumuman->deskripsi}";

        // Mengirim pesan ke setiap penyewa yang dipilih
        foreach ($request->input('penyewa') as $penyewaId) {
            $penyewa = User::find($penyewaId);
            if ($penyewa && $penyewa->telegram_chat_id) {
                $telegram->sendMessage($penyewa->telegram_chat_id, $message);
            }
        }

        return redirect()->back()->with('success', 'Pengumuman berhasil dibuat dan dikirim ke Telegram penyewa.');
    }
}
