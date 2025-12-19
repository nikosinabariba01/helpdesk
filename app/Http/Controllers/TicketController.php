<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil komentar terbaru
        $latestComments = $this->getLatestCommentsfromteknisi();

        // Menampilkan view dan mengirimkan komentar terbaru ke view
        return view('ticket', compact('latestComments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function assign(Request $request, $id)
    {
        // Temukan tiket berdasarkan ID
        $ticket = Ticket::findOrFail($id);

        // Cek apakah pengurus yang sedang login sudah ada di dalam asignees
        // Jika tidak ada, tambahkan pengurus yang sedang login
        $ticket->asignees()->syncWithoutDetaching([Auth::id()]);

        $ticket->status = 'on process';  // Ubah status menjadi 'on process'

        // Simpan perubahan ke database
        $ticket->save();

        return redirect(route('teknisi.index'))->with('success', 'Tiket berhasil di-assign.');
    }



    public function cancelAssign($id)
    {
        $ticket = Ticket::findOrFail($id);

        // Menghapus pengurus yang sedang login sebagai asignee
        $ticket->asignees()->detach(Auth::id());  // Menghapus pengurus yang sedang login sebagai asignee

        // Periksa apakah masih ada asignee lainnya
        if ($ticket->asignees->count() === 0) {
            // Jika tidak ada asignee lain, ubah status menjadi 'open'
            $ticket->status = 'open';
        } else {
            // Jika masih ada asignee lain, status tetap 'on process'
            $ticket->status = 'on process';
        }

        // Simpan perubahan ke database
        $ticket->save();

        return redirect()->back()->with('success', 'Assignment canceled successfully!');
    }

    public function requestFollowUp($id)
    {
        $ticket = Ticket::findOrFail($id);

        // Menambahkan pengurus yang sedang login sebagai assignee
        $ticket->asignees()->sync([Auth::id()]);  // Hanya update assignee untuk pengurus yang login

        // Update status tiket menjadi 'escalated'
        $ticket->status = 'escalated';
        $ticket->save();  // Simpan perubahan ke database

        return redirect()->back()->with('success', 'Tiket berhasil di-escalated.');
    }


    public function cancelRequestFollowUp($id)
    {
        $ticket = Ticket::findOrFail($id);

        // Kembalikan status tiket dari 'escalated' ke 'on process'
        $ticket->status = 'on process';
        $ticket->save();  // Simpan perubahan ke database

        return redirect()->back()->with('success', 'Tiket berhasil dikembalikan ke status "on process"');
    }

    public function acceptEscalation($id)
    {
        $ticket = Ticket::findOrFail($id);

        // Cek apakah sudah ada pemilik yang mengassign
        if (!$ticket->asignees->contains(Auth::id())) {
            // Menambahkan pemilik yang sedang login sebagai asignee
            $ticket->asignees()->attach(Auth::id());  // Menambahkan pemilik yang login
        }

        // Ubah status tiket kembali ke "on process"
        $ticket->status = 'on process';
        $ticket->save();  // Simpan perubahan ke database

        return redirect()->back()->with('success', 'Tiket berhasil dikembalikan ke status "on process" dengan pemilik sebagai asignee.');
    }



    public function closeticket(Request $request, $id)
    {
        // Temukan tiket berdasarkan ID
        $ticket = Ticket::findOrFail($id);
        $ticket->status = 'close';
        // Simpan perubahan ke database
        $ticket->save();

        return redirect(route('teknisi.closeticket'))->with('success', 'Data Tiket Berhasil diupdate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        $ticket = Ticket::create([
            'subject' => $request->subject,
            'Jenis_Pengaduan' => $request->Jenis_Pengaduan,
            'Lokasi' => $request->Lokasi,
            'Detail' => $request->Detail,
            'user_id' => auth()->id(),
        ]);

        if ($request->file('gambar')) {
            $this->storeAttachment($request, $ticket);
        }

        return redirect(route('customer.index'))->with('success', 'Data Tiket Berhasil diupdate');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    public function storeAttachment(Request $request, Ticket $ticket)
    {
        // Process and store the attachment (e.g., image upload)
        if ($request->file('gambar')) {
            $gambarPath = $request->file('gambar')->store('img', 'public');
            $ticket->gambar = $gambarPath;
            $ticket->save();
        }
    }

    /**
     * Update the specified resource in storage.
     */

    //customer
    public function update(Request $request, $id)
    {
        // Aturan validasi
        $rules = [
            'subject' => 'required|string|max:255',
            'Jenis_Pengaduan' => 'required',
            'Lokasi' => 'required|string|max:255',
            'Detail' => 'required|string',
            'gambar' => 'sometimes|file|mimes:jpg,jpeg,png,pdf|max:2048', // Validasi file gambar dan pdf dengan ukuran maksimal 2MB
        ];

        // Pesan error custom untuk setiap field
        $messages = [
            'subject.required' => 'Subject wajib diisi.',
            'subject.string' => 'Subject harus berupa teks.',
            'subject.max' => 'Subject tidak boleh lebih dari 255 karakter.',

            'Jenis_Pengaduan.required' => 'Jenis pengaduan wajib dipilih.',

            'Lokasi.required' => 'Lokasi wajib diisi.',
            'Lokasi.string' => 'Lokasi harus berupa teks.',
            'Lokasi.max' => 'Lokasi tidak boleh lebih dari 255 karakter.',

            'Detail.required' => 'Detail wajib diisi.',
            'Detail.string' => 'Detail harus berupa teks.',

            'gambar.sometimes' => 'File gambar bersifat opsional, namun jika diunggah, pastikan format dan ukuran sesuai.',
            'gambar.file' => 'File yang diunggah harus berupa file.',
            'gambar.mimes' => 'Hanya file dengan ekstensi jpg, jpeg, png, dan pdf yang diperbolehkan.',
            'gambar.max' => 'Ukuran file gambar tidak boleh lebih dari 2MB.',
        ];

        // Validasi request dengan pesan error custom
        $request->validate($rules, $messages);

        // Temukan tiket berdasarkan ID dan update
        $ticket = Ticket::findOrFail($id);
        $ticket->subject = $request->subject;
        $ticket->Jenis_Pengaduan = $request->Jenis_Pengaduan;
        $ticket->Lokasi = $request->Lokasi;
        $ticket->Detail = $request->Detail;

        // Jika ada file gambar, simpan
        if ($request->hasFile('gambar')) {
            // Pastikan file gambar yang diupload disimpan di direktori yang benar
            $ticket->gambar = $request->file('gambar')->store('img', 'public');
        }

        // Simpan perubahan tiket
        $ticket->save();

        // Redirect dengan pesan sukses
        return redirect(route('customer.index'))->with('success', 'Data Tiket Berhasil diupdate');
    }



    //Teknisi
    public function updateteknisi(Request $request, $id)
    {
        $rules = [
            'subject' => 'required|string|max:255',
            'Jenis_Pengaduan' => 'required',
            'Lokasi' => 'required|string|max:255',
            'Detail' => 'required|string',
            'gambar' => 'sometimes|file|mimes:jpg,jpeg,png,pdf',
        ];

        $request->validate($rules);

        // Temukan tiket berdasarkan ID dan update
        $ticket = Ticket::findOrFail($id);
        $ticket->update($request->all());

        return redirect(route('teknisi.index'))->with('success', 'Data Tiket Berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    //customer
    public function destroy($id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return dd($ticket);
        }

        $ticket->delete();

        return redirect(route('customer.index'))->with('success', 'Data Tiket Berhasil dihapus');
    }

    //teknisi
    public function destroyteknisi($id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return dd($ticket);
        }

        $ticket->delete();

        return redirect(route('teknisi.index'))->with('success', 'Data Tiket Berhasil dihapus');
    }


    public function downloadImage(Ticket $ticket)
    {
        // Periksa apakah gambar ada
        if (!$ticket->gambar || !Storage::exists('public/' . $ticket->gambar)) {
            return back()->with('error', 'File not found.');
        }

        // Unduh file
        return Storage::download('public/' . $ticket->gambar, basename($ticket->gambar));
    }

    private function getLatestCommentsfromteknisi()
    {
        // Dapatkan ID pengguna yang sedang login (penyewa)
        $userId = Auth::id();

        // Ambil tiket yang dibuat oleh penyewa
        $ticketsByUser = Ticket::where('user_id', $userId)
            ->pluck('id'); // Mendapatkan ID tiket yang dibuat oleh penyewa

        // Ambil tiket yang dibuat oleh penyewa dan waktu pembuatan tiketnya
        $ticketTimestamps = Ticket::whereIn('id', $ticketsByUser)
            ->pluck('created_at', 'id'); // Mendapatkan waktu created_at tiap tiket

        // Ambil komentar terbaru yang diberikan oleh teknisi pada tiket yang dibuat oleh penyewa
        $latestComments = Comment::whereIn('ticket_id', $ticketsByUser)  // Hanya tiket yang dibuat oleh penyewa
            ->whereHas('user', function ($query) {
                // Pastikan komentar berasal dari teknisi (role pemilik atau pengurus)
                $query->whereIn('role', ['pemilik', 'pengurus']);
            })
            ->where(function ($query) use ($ticketTimestamps) {
                foreach ($ticketTimestamps as $ticketId => $ticketCreatedAt) {
                    $query->orWhere(function ($q) use ($ticketId, $ticketCreatedAt) {
                        $q->where('ticket_id', $ticketId)
                            ->where('created_at', '>', $ticketCreatedAt); // Hanya ambil komentar setelah tiket dibuat
                    });
                }
            })
            ->with('ticket.user')  // Memuat relasi untuk menampilkan informasi tiket
            ->latest()  // Mengurutkan berdasarkan waktu terbaru
            ->limit(3)  // Batasi 3 komentar terbaru
            ->get();

        return $latestComments;
    }
}
