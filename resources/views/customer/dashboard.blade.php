@extends('layouts.app')
@section('title', 'My Dashboard')

@section('content')
<div class="page-header flex items-center justify-between">
    <div>
        <div class="page-title">Welcome, {{ auth()->user()->name }} 👋</div>
        <div class="page-sub">Join a queue or track your active tickets below.</div>
    </div>
    <a href="{{ route('customer.notifications') }}" class="btn btn-outline btn-sm">
        🔔 Notifications @if($unread > 0) <span style="background:#EF4444;color:#fff;border-radius:999px;padding:0 .4rem;font-size:.7rem">{{ $unread }}</span>@endif
    </a>
</div>

{{-- Active Tickets --}}
@if($myTickets->count())
<div class="card mb-3">
    <div class="card-header">🎫 My Active Tickets</div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:1rem">
            @foreach($myTickets as $ticket)
            <div style="border:1.5px solid #E2E8F0;border-radius:10px;padding:1rem;background:#FAFBFF">
                <div style="display:flex;justify-content:space-between;align-items:flex-start">
                    <div>
                        <div style="font-size:.75rem;color:#64748B;font-weight:600;text-transform:uppercase">{{ $ticket->queue->name }}</div>
                        <div style="font-size:2rem;font-weight:900;color:#1A2B5E;line-height:1.1">#{{ $ticket->position }}</div>
                    </div>
                    <span class="badge badge-{{ $ticket->status === 'waiting' ? 'warning' : 'primary' }}">{{ $ticket->status }}</span>
                </div>
                <div style="font-size:.8rem;color:#64748B;margin-top:.5rem">Est. wait: {{ $ticket->estimatedWait() }} min</div>
                <div style="display:flex;gap:.5rem;margin-top:.75rem">
                    <a href="{{ route('customer.ticket', $ticket->id) }}" class="btn btn-primary btn-sm">View</a>
                    <form method="POST" action="{{ route('customer.cancel', $ticket->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Cancel this ticket?')">Cancel</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- Available Queues --}}
<div class="card">
    <div class="card-header">🏢 Available Queues</div>
    <div class="card-body">
        @if($queues->count())
        <div class="queue-grid">
            @foreach($queues as $queue)
            <div class="queue-card">
                <div>
                    <div class="name">{{ $queue->name }}</div>
                    <div class="meta">
                        <span class="badge badge-open">Open</span>
                        <span>{{ $queue->waiting_count }} waiting</span>
                        <span>~{{ $queue->waiting_count * 5 }} min</span>
                    </div>
                    <div style="margin-top:.5rem">
                        <div style="height:6px;background:#E2E8F0;border-radius:999px;overflow:hidden">
                            <div style="height:100%;width:{{ min(100, ($queue->waiting_count / $queue->max_capacity) * 100) }}%;background:{{ ($queue->waiting_count / $queue->max_capacity) > .8 ? '#EF4444' : '#2563EB' }};border-radius:999px"></div>
                        </div>
                        <div style="font-size:.73rem;color:#64748B;margin-top:.2rem">{{ $queue->waiting_count }}/{{ $queue->max_capacity }} capacity</div>
                    </div>
                </div>
                <div class="actions">
                    <form method="POST" action="{{ route('customer.join') }}">
                        @csrf
                        <input type="hidden" name="queue_id" value="{{ $queue->id }}">
                        <button type="submit" class="btn btn-primary w-full">Join Queue →</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state"><div class="icon">🚫</div><p>No queues are open right now.</p></div>
        @endif
    </div>
</div>
@endsection
