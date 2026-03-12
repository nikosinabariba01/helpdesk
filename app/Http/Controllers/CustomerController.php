<?php
namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Pengumuman;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CustomerController extends Controller {
    private function getLatestCommentsfromteknisi() {
        $userId = Auth::id();

        $ticketsByUser = Ticket::where('user_id', $userId)->pluck('id');

        $ticketTimestamps = Ticket::whereIn('id', $ticketsByUser)
            ->pluck('created_at', 'id');

        $latestComments = Comment::whereIn('ticket_id', $ticketsByUser)
            ->whereHas('user', function ($query) {
                $query->whereIn('role', ['pemilik', 'pengurus']);
            })
            ->where(function ($query) use ($ticketTimestamps) {
                foreach ($ticketTimestamps as $ticketId => $ticketCreatedAt) {
                    $query->orWhere(function ($q) use ($ticketId, $ticketCreatedAt) {
                        $q->where('ticket_id', $ticketId)
                            ->where('created_at', '>', $ticketCreatedAt);
                    });
                }
            })
            ->with('ticket.user')
            ->latest()
            ->limit(3)
            ->get();

        return $latestComments;
    }

    private function getCustomerCounts(int $userId): array {
        return [
            'totalEscalation'  => Ticket::where('user_id', $userId)->where('status', 'escalated')->count(),
            'OnProcessTickets' => Ticket::where('user_id', $userId)->where('status', 'on process')->count(),
            'OpenTic'          => Ticket::where('user_id', $userId)->where('status', 'open')->count(),
            'closedtic'        => Ticket::where('user_id', $userId)->where('status', 'close')->count(),
        ];
    }

    private function getCustomerAnnouncements(int $userId) {
        return Pengumuman::with('creator')
            ->whereHas('penerima', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    private function customerTicketBaseQuery(string $mode, int $userId): Builder {
        $query = Ticket::query()
            ->select([
                'id',
                'subject',
                'status',
                'Jenis_Pengaduan',
                'Lokasi',
                'Detail',
                'user_id',
                'created_at',
            ])
            ->where('user_id', $userId);

        if ($mode === 'customer_active') {
            $query->whereIn('status', ['open', 'on process', 'escalated']);
        }

        return $query;
    }

    private function applyCustomerFilters(Builder $query, Request $request): void {
        $filterStatus = trim((string) $request->input('filter_status', ''));
        $filterJenis  = trim((string) $request->input('filter_jenis_pengaduan', ''));

        if (in_array($filterStatus, ['open', 'on process', 'escalated', 'close'], true)) {
            $query->where('status', $filterStatus);
        }

        if (in_array($filterJenis, ['perbaikan', 'permintaan'], true)) {
            $query->where('Jenis_Pengaduan', $filterJenis);
        }
    }

    private function applyCustomerSearch(Builder $query, Request $request): void {
        $searchValue = trim((string) data_get($request->input('search', []), 'value', ''));

        if ($searchValue === '') {
            return;
        }

        $query->where(function ($q) use ($searchValue) {
            $q->where('subject', 'like', '%' . $searchValue . '%')
                ->orWhere('Detail', 'like', '%' . $searchValue . '%')
                ->orWhere('Jenis_Pengaduan', 'like', '%' . $searchValue . '%')
                ->orWhere('status', 'like', '%' . $searchValue . '%');
        });
    }

    private function applyCustomerOrder(Builder $query, Request $request): void {
        $orderColumn   = (int) $request->input('order.0.column', -1);
        $orderDir      = strtolower((string) $request->input('order.0.dir', 'asc')) === 'desc' ? 'desc' : 'asc';
        $sortCreatedAt = strtolower((string) $request->input('sort_created_at', 'desc')) === 'asc' ? 'asc' : 'desc';

        $allowedOrder = [
            0 => 'subject',
            1 => 'status',
        ];

        if (isset($allowedOrder[$orderColumn])) {
            $query->orderBy($allowedOrder[$orderColumn], $orderDir)
                ->orderBy('created_at', $sortCreatedAt);
        } else {
            $query->orderBy('created_at', $sortCreatedAt);
        }
    }

    private function makeTicketCode(Ticket $ticket): string {
        return 'sp-' .
        substr(preg_replace('/[^0-9]/', '', (string) $ticket->id), -3) .
        Carbon::parse($ticket->created_at)->format('dmy') .
            ($ticket->Jenis_Pengaduan == 0 ? '0' : '1');
    }

    private function transformCustomerTicketRow(Ticket $ticket): array {
        return [
            'id'                   => $ticket->id,
            'subject'              => $ticket->subject,
            'status'               => $ticket->status,
            'Jenis_Pengaduan'      => $ticket->Jenis_Pengaduan,
            'Lokasi'               => $ticket->Lokasi,
            'Detail'               => $ticket->Detail,
            'detail_short'         => Str::limit($ticket->Detail, 40, '...'),
            'ticket_code'          => $this->makeTicketCode($ticket),
            'created_at_formatted' => $ticket->formattedTanggalPengaduan ?? Carbon::parse($ticket->created_at)->format('d/m/Y H:i'),
            'view_url'             => route('viewtickets.index', ['id' => $ticket->id]),
            'update_url'           => url('/tickets/' . $ticket->id),
            'delete_url'           => url('/tickets/' . $ticket->id),
            'can_delete'           => $ticket->status === 'open',
        ];
    }

    public function ticketDatatableJson(Request $request, string $mode) {
        $userId = Auth::id();

        abort_unless($userId, 403);
        abort_unless(in_array($mode, ['customer_all', 'customer_active'], true), 404);

        $baseQuery    = $this->customerTicketBaseQuery($mode, $userId);
        $totalRecords = (clone $baseQuery)->count();

        $filteredQuery = clone $baseQuery;

        $this->applyCustomerFilters($filteredQuery, $request);
        $this->applyCustomerSearch($filteredQuery, $request);

        $recordsFiltered = (clone $filteredQuery)->count();

        $this->applyCustomerOrder($filteredQuery, $request);

        $start  = max((int) $request->input('start', 0), 0);
        $length = (int) $request->input('length', 10);

        if ($length < 1 || $length > 100) {
            $length = 10;
        }

        $tickets = $filteredQuery
            ->skip($start)
            ->take($length)
            ->get();

        $data = $tickets->map(function (Ticket $ticket) {
            return $this->transformCustomerTicketRow($ticket);
        })->values();

        return response()->json([
            'draw'            => (int) $request->input('draw', 1),
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
        ]);
    }

    public function index() {
        $userId = Auth::id();

        $counts         = $this->getCustomerCounts($userId);
        $latestComments = $this->getLatestCommentsfromteknisi();
        $pengumuman     = $this->getCustomerAnnouncements($userId);
        $tableMode      = 'customer_all';

        return view('customer', array_merge($counts, compact(
            'latestComments',
            'pengumuman',
            'tableMode'
        )));
    }

    public function viewprocess() {
        $userId = Auth::id();

        $counts         = $this->getCustomerCounts($userId);
        $latestComments = $this->getLatestCommentsfromteknisi();
        $pengumuman     = $this->getCustomerAnnouncements($userId);
        $tableMode      = 'customer_active';

        return view('process', array_merge($counts, compact(
            'latestComments',
            'pengumuman',
            'tableMode'
        )));
    }
}