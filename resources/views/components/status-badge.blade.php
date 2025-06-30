<!-- resources/views/components/status-badge.blade.php -->
    @if($status == 'open')
        <span class="badge badge-sm bg-gradient-success">{{ $status }}</span>
    @elseif($status == 'on process')
        <span class="badge badge-sm bg-gradient-warning">{{ $status }}</span>
    @elseif($status == 'close')
        <span class="badge badge-sm bg-gradient-danger">{{ $status }}</span>
    @else
        <!-- Handle case when status is not recognized -->
    @endif
