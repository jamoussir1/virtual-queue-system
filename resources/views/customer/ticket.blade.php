@extends('layouts.app')
@section('title', 'Ticket #' . $ticket->position)

@section('content')
<div style="max-width:520px;margin:0 auto">
    <a href="{{ route('customer.dashboard') }}" class="btn btn-outline btn-sm mb-3">← Back to Dashboard</a>

    {{-- Ticket hero --}}
    <div class="ticket-hero">
        <div class="label">YOUR QUEUE POSITION</div>
        <div class="position">{{ $ticket->position }}</div>
        <div class="queue">{{ $ticket->queue->name }}</div>
        <div style="margin-top:1rem">
            <span class="badge badge-{{ $ticket->getStatusBadgeClass() }}" style="font-size:.85rem;padding:.3rem .9rem">{{ strtoupper($ticket->status) }}</span>
        </div>
    </div>

    {{-- Details --}}
    <div class="card mb-3">
        <div class="card-header">🎫 Ticket Details</div>
        <div class="card-body">
            <table style="width:100%;font-size:.875rem">
                <tr>
                    <td style="padding:.5rem 0;color:#64748B;width:40%">Ticket Code</td>
                    <td style="padding:.5rem 0;font-weight:600;font-family:monospace">{{ $ticket->qr_code }}</td>
                </tr>
                <tr>
                    <td style="padding:.5rem 0;color:#64748B">Queue</td>
                    <td style="padding:.5rem 0;font-weight:600">{{ $ticket->queue->name }}</td>
                </tr>
                @if($ticket->window)
                <tr>
                    <td style="padding:.5rem 0;color:#64748B">Service Window</td>
                    <td style="padding:.5rem 0;font-weight:600">{{ $ticket->window->label }}</td>
                </tr>
                @endif
                <tr>
                    <td style="padding:.5rem 0;color:#64748B">Joined At</td>
                    <td style="padding:.5rem 0">{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @if($ticket->called_at)
                <tr>
                    <td style="padding:.5rem 0;color:#64748B">Called At</td>
                    <td style="padding:.5rem 0">{{ $ticket->called_at->format('d/m/Y H:i') }}</td>
                </tr>
                @endif
                @if($ticket->status === 'waiting')
                <tr>
                    <td style="padding:.5rem 0;color:#64748B">Est. Wait</td>
                    <td style="padding:.5rem 0;font-weight:600;color:#D97706">~{{ $ticket->estimatedWait() }} minutes</td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    {{-- QR placeholder --}}
    <div class="card mb-3">
        <div class="card-header">📱 QR Code</div>
        <div class="card-body text-center" style="padding:2rem">
            <div style="width:120px;height:120px;background:#F1F5F9;border-radius:12px;margin:0 auto;display:flex;align-items:center;justify-content:center;font-size:3rem">🔲</div>
            <div style="font-family:monospace;font-size:.9rem;margin-top:.75rem;color:#1A2B5E;font-weight:700">{{ $ticket->qr_code }}</div>
            <div style="font-size:.78rem;color:#64748B;margin-top:.25rem">Show this code to the agent</div>
        </div>
    </div>

    @if(in_array($ticket->status, ['waiting','called']))
    <form method="POST" action="{{ route('customer.cancel', $ticket->id) }}">
        @csrf
        <button type="submit" class="btn btn-danger w-full" onclick="return confirm('Cancel this ticket?')">Cancel Ticket</button>
    </form>
    @endif
</div>

@if($ticket->status === 'waiting')
@section('scripts')
<script>
    // Auto-refresh every 30s to update position
    setTimeout(() => location.reload(), 30000);
</script>
@endsection
@endif
@endsection
