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
        $latestComments = $this->getLatestComments();

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
        $ticket->subject = $request->subject;
        $ticket->Jenis_Pengaduan = $request->Jenis_Pengaduan;
        $ticket->Lokasi = $request->Lokasi;
        $ticket->Detail = $request->Detail;
        if ($request->hasFile('gambar')) {
            $ticket->gambar = $request->file('gambar')->store('img', 'public');
        }
        $ticket->save();

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

    private function getLatestComments()
    {
        // Dapatkan ID pengguna yang sedang login (penyewa)
        $userId = Auth::id();

        // Ambil tiket yang dibuat oleh penyewa
        $ticketsByUser = Ticket::where('user_id', $userId)
            ->pluck('id'); // Mendapatkan ID tiket yang dibuat oleh penyewa

        // Ambil komentar terbaru yang diberikan oleh teknisi pada tiket yang dibuat oleh penyewa
        $latestComments = Comment::whereIn('ticket_id', $ticketsByUser)  // Hanya tiket yang dibuat oleh penyewa
            ->whereHas('user', function ($query) {
                // Pastikan komentar berasal dari teknisi (role pemilik atau pengurus)
                $query->whereIn('role', ['pemilik', 'pengurus']);
            })
            ->with('ticket.user')  // Memuat relasi untuk menampilkan informasi tiket
            ->latest()
            ->limit(3)  // Batasi 3 komentar terbaru
            ->get();

        return $latestComments;
    }
}
