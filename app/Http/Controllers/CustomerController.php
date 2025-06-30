<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $data_ticket = Ticket::with('user')->where('user_id', $userId)->orderBy('created_at', 'desc')->get();
        $totalTickets = $data_ticket->count();


        $OnProcessTickets = Ticket::where('user_id', $userId)
            ->where('status', 'on process')
            ->count();

        $OpenTic = Ticket::where('user_id', $userId)
            ->where('status', 'open')
            ->count();

        $closedtic = Ticket::where('user_id', $userId)
            ->where('status', 'close')
            ->count();

        return view('customer', compact('data_ticket', 'totalTickets', 'OnProcessTickets', 'closedtic', 'OpenTic'));
    }

    public function viewprocess()
    {
        // Dapatkan ID pengguna yang sedang login
        $userId = Auth::id();

        // Ambil tiket yang memiliki assignee_id berdasarkan user_id yang sedang login dan status open atau on process
        $data_ticket = Ticket::with('user')
            ->where('user_id', $userId)
            ->where(function ($query) {
                $query->where('status', 'open')
                    ->orWhere('status', 'on process');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $totalTickets = $data_ticket->count();

        $OnProcessTickets = Ticket::where('user_id', $userId)
            ->where('status', 'on process')
            ->count();

        $OpenTic = Ticket::where('user_id', $userId)
            ->where('status', 'open')
            ->count();

        $closedtic = Ticket::where('user_id', $userId)
            ->where('status', 'close')
            ->count();

        return view('process', compact('data_ticket', 'totalTickets', 'OnProcessTickets', 'closedtic', 'OpenTic'));
    }
}
