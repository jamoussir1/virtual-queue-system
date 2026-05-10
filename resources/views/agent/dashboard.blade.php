@extends('layouts.app')
@section('title', 'Agent Dashboard')

@section('content')
<div class="page-header">
    <div class="page-title">🖥 Agent Dashboard</div>
    <div class="page-sub">Manage your service window and call customers.</div>
</div>

@if(!$activeWindow)
<div class="card">
    <div class="card-body empty-state">
        <div class="icon">🚫</div>
        <p>No active service window assigned to you.</p>
        <p class="text-sm text-gray mt-2">Contact your administrator.</p>
    </div>
</div>
@else

{{-- Window Info --}}
<div class="stats-grid">
    <div class="stat-card blue">
        <div class="label">Your Window</div>
        <div class="value">{{ $activeWindow->label }}</div>
        <div class="sub">Queue: {{ $activeWindow->queue->name }}</div>
    </div>
    <div class="stat-card teal">
        <div class="label">Waiting Now</div>
        <div class="value">{{ $waiting->count() }}</div>
        <div class="sub">customers in queue</div>
    </div>
    <div class="stat-card green">
        <div class="label">Est. Total Wait</div>
        <div class="value">{{ $waiting->count() * 5 }}m</div>
        <div class="sub">~5 min per customer</div>
    </div>
</div>

{{-- Call Next --}}
<div class="card mb-3">
    <div class="card-header">📢 Call Next Customer</div>
    <div class="card-body">
        @if($waiting->count() > 0)
            <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap">
                <div>
                    <div style="font-size:.8rem;color:#64748B">Next in line:</div>
                    <div style="font-size:1.25rem;font-weight:700;color:#1A2B5E">#{{ $waiting->first()->position }} — {{ $waiting->first()->customer->name }}</div>
                </div>
                <form method="POST" action="{{ route('agent.callNext') }}" style="margin-left:auto">
                    @csrf
                    <input type="hidden" name="window_id" value="{{ $activeWindow->id }}">
                    <button type="submit" class="btn btn-primary" style="font-size:1rem;padding:.65rem 1.5rem">📢 Call Next</button>
                </form>
            </div>
        @else
            <div class="empty-state" style="padding:1.5rem"><div class="icon">✅</div><p>Queue is empty!</p></div>
        @endif
    </div>
</div>

{{-- Waiting List --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
    <div class="card">
        <div class="card-header">⏳ Waiting List ({{ $waiting->count() }})</div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>#</th><th>Customer</th><th>Wait</th></tr></thead>
                <tbody>
                    @forelse($waiting as $ticket)
                    <tr>
                        <td><strong>#{{ $ticket->position }}</strong></td>
                        <td>{{ $ticket->customer->name }}</td>
                        <td>{{ $ticket->estimatedWait() }}m</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center text-gray" style="padding:1rem">No one waiting.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">📋 Recently Called</div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>#</th><th>Customer</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($recentlyCalled as $ticket)
                    <tr>
                        <td><strong>#{{ $ticket->position }}</strong></td>
                        <td>{{ $ticket->customer->name }}</td>
                        <td>
                            <div style="display:flex;gap:.3rem">
                                <form method="POST" action="{{ route('agent.markServed') }}">
                                    @csrf
                                    <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                                    <button class="btn btn-success btn-sm">✓ Served</button>
                                </form>
                                <form method="POST" action="{{ route('agent.markAbsent') }}">
                                    @csrf
                                    <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                                    <button class="btn btn-danger btn-sm">✗ Absent</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center text-gray" style="padding:1rem">No recent calls.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
    // Auto-refresh every 20 seconds
    setTimeout(() => location.reload(), 20000);
</script>
@endsection
