<!-- resources/views/components/status-badge.blade.php -->
@if($status == 'open')
    <span class="badge badge-sm bg-gradient-success">{{ $status }}</span>
@elseif($status == 'on process')
    <span class="badge badge-sm bg-gradient-warning">{{ $status }}</span>
@elseif($status == 'close')
    <span class="badge badge-sm bg-gradient-danger">{{ $status }}</span>
@elseif($status == 'escalated')
    <span class="badge badge-sm bg-gradient-info">{{ $status }}</span> <!-- Badge untuk status escalated -->
@else
    <span class="badge badge-sm bg-gradient-secondary">Unknown Status</span>
@endif