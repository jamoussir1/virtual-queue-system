@extends('admin.dashboard')
@section('title', 'Statistics')
@section('content')
<div class="page-header">
    <div class="page-title">📈 Statistics & Analytics</div>
    <div class="page-sub">Queue performance overview.</div>
</div>

{{-- By Queue --}}
<div class="card mb-3">
    <div class="card-header">🏢 Performance by Queue</div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Queue</th><th>Status</th><th>Total Tickets</th><th>Served</th><th>Waiting</th><th>Serve Rate</th></tr></thead>
            <tbody>
                @forelse($byQueue as $q)
                <tr>
                    <td><strong>{{ $q->name }}</strong></td>
                    <td><span class="badge badge-{{ $q->status }}">{{ $q->status }}</span></td>
                    <td>{{ $q->total_tickets }}</td>
                    <td><span style="color:#059669;font-weight:600">{{ $q->served_tickets }}</span></td>
                    <td><span style="color:#D97706;font-weight:600">{{ $q->waiting_tickets }}</span></td>
                    <td>
                        @if($q->total_tickets > 0)
                        <div style="display:flex;align-items:center;gap:.5rem">
                            <div style="flex:1;height:8px;background:#E2E8F0;border-radius:999px;min-width:80px">
                                <div style="height:100%;width:{{ round(($q->served_tickets/$q->total_tickets)*100) }}%;background:#059669;border-radius:999px"></div>
                            </div>
                            <span style="font-size:.8rem;font-weight:600">{{ round(($q->served_tickets/$q->total_tickets)*100) }}%</span>
                        </div>
                        @else <span style="color:#94A3B8">—</span> @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-gray" style="padding:2rem">No data yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Daily --}}
<div class="card">
    <div class="card-header">📅 Daily Activity (Last 14 days)</div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Date</th><th>Tickets Created</th><th>Served</th><th>Completion Rate</th></tr></thead>
            <tbody>
                @forelse($dailyStats as $d)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($d->day)->format('d/m/Y') }}</td>
                    <td>{{ $d->total }}</td>
                    <td>{{ $d->served }}</td>
                    <td>
                        @if($d->total > 0)
                        <div style="display:flex;align-items:center;gap:.5rem">
                            <div style="flex:1;height:6px;background:#E2E8F0;border-radius:999px;min-width:60px">
                                <div style="height:100%;width:{{ round(($d->served/$d->total)*100) }}%;background:#0891B2;border-radius:999px"></div>
                            </div>
                            <span style="font-size:.8rem">{{ round(($d->served/$d->total)*100) }}%</span>
                        </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-gray" style="padding:2rem">No activity yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
