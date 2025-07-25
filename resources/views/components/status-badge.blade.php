<!-- resources/views/components/status-badge.blade.php -->
@php
    $userRole = Auth::user()->role;
@endphp

<!-- Cek kondisi role pengguna -->
@if($userRole == 'pengurus')
    <!-- Jika role pengguna adalah pengurus -->
    @if($status == 'open')
        <span class="badge badge-sm bg-gradient-success">{{ $status }}</span>
    @elseif($status == 'on process')
        <span class="badge badge-sm bg-gradient-warning">{{ $status }}</span>
    @elseif($status == 'close')
        <span class="badge badge-sm bg-gradient-danger">{{ $status }}</span>
    @elseif($status == 'escalated')
        @if($ticket->asignees->where('role', 'pemilik')->isEmpty())
            <!-- Jika tiket escalated tapi belum ada pemilik yang mengassign -->
            <span class="badge badge-sm bg-gradient-info">Escalated</span>
        @else
            <!-- Jika tiket escalated dan sudah di-assign oleh pemilik -->
            <span class="badge badge-sm bg-gradient-primary">Accepted - In Progress</span>
        @endif
    @else
        <!-- Handle case when status is not recognized -->
    @endif
@elseif(in_array($userRole, ['pemilik', 'admin', 'penyewa']))
    <!-- Jika role pengguna adalah pemilik, admin, atau penyewa -->
    @if($status == 'open')
        <span class="badge badge-sm bg-gradient-success">{{ $status }}</span>
    @elseif($status == 'on process')
        <span class="badge badge-sm bg-gradient-warning">{{ $status }}</span>
    @elseif($status == 'close')
        <span class="badge badge-sm bg-gradient-danger">{{ $status }}</span>
    @elseif($status == 'escalated')
        <span class="badge badge-sm bg-gradient-info">{{ $status }}</span> <!-- Badge untuk status escalated -->
    @else
        <!-- Handle case when status is not recognized -->
    @endif
@endif
