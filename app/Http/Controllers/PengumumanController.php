<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use App\Models\Pengumuman;

class PengumumanController extends Controller
{
    // Menampilkan form untuk membuat pengumuman
    public function create()
    {
        // Mengambil semua penyewa untuk dipilih di form
        $penyewa = User::where('role', 'penyewa')->get();

        // Mengarahkan ke view 'CreatePengumuman' dan membawa data penyewa
        return view('CreatePengumuman', compact ('penyewa'));
    }

    // Method store untuk menyimpan pengumuman
    public function store(Request $request)
    {
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
        $pengumuman->creator_id = auth()->user()->id;
        $pengumuman->save();

        // Menyimpan relasi pengumuman dengan penyewa
        $pengumuman->penerima()->sync($request->input('penyewa'));

        // Kirim pengumuman ke Telegram
        $telegram = new TelegramService();
        $message = "<b>Pengumuman Baru:</b>\n<b>{$pengumuman->judul}</b>\n{$pengumuman->deskripsi}";

        foreach ($request->input('penyewa') as $penyewaId) {
            $penyewa = User::find($penyewaId);
            if ($penyewa && $penyewa->telegram_chat_id) {
                $telegram->sendMessage($penyewa->telegram_chat_id, $message);
            }
        }

        return redirect()->back()->with('success', 'Pengumuman berhasil dibuat dan dikirim ke Telegram penyewa.');
    }
}
