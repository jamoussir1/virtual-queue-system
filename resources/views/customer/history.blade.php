@extends('layouts.app')
@section('title', 'My Queue History')

@section('content')
<div class="page-header">
    <div class="page-title">📋 Queue History</div>
    <div class="page-sub">All your past and active queue tickets.</div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Ticket Code</th>
                    <th>Queue</th>
                    <th>Position</th>
                    <th>Status</th>
                    <th>Joined At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                <tr>
                    <td><code>{{ $ticket->qr_code }}</code></td>
                    <td>{{ $ticket->queue->name }}</td>
                    <td>#{{ $ticket->position }}</td>
                    <td><span class="badge badge-{{ $ticket->getStatusBadgeClass() }}">{{ $ticket->status }}</span></td>
                    <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @if(in_array($ticket->status, ['waiting','called']))
                            <a href="{{ route('customer.ticket', $ticket->id) }}" class="btn btn-primary btn-sm">View</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-gray" style="padding:2rem">No tickets yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $tickets->links() }}
@endsection
