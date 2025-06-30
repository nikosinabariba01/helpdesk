<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    function index()
    {
        return view('ticket');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function assign(Request $request, $id)
    {
        // Temukan tiket berdasarkan ID
        $ticket = Ticket::findOrFail($id);

        // Perbarui asignee_id dengan ID pengguna yang sedang login
        $ticket->asignee_id = Auth::id();
        $ticket->status = 'on process';
        // Simpan perubahan ke database
        $ticket->save();

        return redirect(route('teknisi.index'))->with('success', 'Data Tiket Berhasil diupdate');
    }

    public function cancelAssign($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->asignee_id = null;
        $ticket->status = 'open'; // Mengubah status menjadi 'open'
        $ticket->save();

        return redirect()->back()->with('success', 'Assignment canceled successfully!');
    }

    public function closeticket(Request $request, $id)
    {
        // Temukan tiket berdasarkan ID
        $ticket = Ticket::findOrFail($id);

        // Perbarui asignee_id dengan ID pengguna yang sedang login
        $ticket->asignee_id = Auth::id();
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

    
}
