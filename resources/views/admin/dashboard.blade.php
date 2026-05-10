@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('sidebar')
<div class="sidebar-section">Overview</div>
<a href="{{ route('admin.dashboard') }}"   class="sidebar-link {{ request()->routeIs('admin.dashboard')  ? 'active' : '' }}"><span class="sidebar-icon">📊</span> Dashboard</a>
<a href="{{ route('admin.statistics') }}"  class="sidebar-link {{ request()->routeIs('admin.statistics') ? 'active' : '' }}"><span class="sidebar-icon">📈</span> Statistics</a>

<div class="sidebar-section">Management</div>
<a href="{{ route('admin.queues') }}"  class="sidebar-link {{ request()->routeIs('admin.queues*')   ? 'active' : '' }}"><span class="sidebar-icon">🏢</span> Queues</a>
<a href="{{ route('admin.windows') }}" class="sidebar-link {{ request()->routeIs('admin.windows*')  ? 'active' : '' }}"><span class="sidebar-icon">🖥</span> Windows</a>
<a href="{{ route('admin.users') }}"   class="sidebar-link {{ request()->routeIs('admin.users*')    ? 'active' : '' }}"><span class="sidebar-icon">👥</span> Users</a>

<div style="margin-top:auto;padding-top:1rem;border-top:1px solid #E2E8F0;margin-top:2rem">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="sidebar-link w-full" style="background:none;border:none;cursor:pointer;text-align:left;width:100%"><span class="sidebar-icon">🚪</span> Logout</button>
    </form>
</div>
@endsection

@section('content')
@if(isset($stats) && isset($queues) && isset($recent))
<div class="page-header">
    <div class="page-title">Admin Dashboard</div>
    <div class="page-sub">Overview of the Virtual Queue Management System.</div>
</div>

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card blue">
        <div class="label">Total Users</div>
        <div class="value">{{ $stats['total_users'] }}</div>
        <div class="sub">customers, agents, admins</div>
    </div>
    <div class="stat-card teal">
        <div class="label">Total Queues</div>
        <div class="value">{{ $stats['total_queues'] }}</div>
        <div class="sub">configured queues</div>
    </div>
    <div class="stat-card orange">
        <div class="label">Waiting Now</div>
        <div class="value">{{ $stats['waiting_now'] }}</div>
        <div class="sub">active tickets</div>
    </div>
    <div class="stat-card green">
        <div class="label">Served Today</div>
        <div class="value">{{ $stats['served_today'] }}</div>
        <div class="sub">customers served</div>
    </div>
</div>

{{-- Queue status --}}
<div class="card mb-3">
    <div class="card-header">
        🏢 Queue Status
        <a href="{{ route('admin.queues') }}" class="btn btn-outline btn-sm">Manage →</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Queue Name</th><th>Status</th><th>Waiting</th><th>Capacity</th><th>Fill Rate</th></tr></thead>
            <tbody>
                @foreach($queues as $q)
                <tr>
                    <td><strong>{{ $q->name }}</strong></td>
                    <td><span class="badge badge-{{ $q->status }}">{{ $q->status }}</span></td>
                    <td>{{ $q->waiting }}</td>
                    <td>{{ $q->max_capacity }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:.5rem">
                            <div style="flex:1;height:6px;background:#E2E8F0;border-radius:999px">
                                <div style="height:100%;width:{{ $q->max_capacity ? min(100,($q->waiting/$q->max_capacity)*100) : 0 }}%;background:{{ ($q->max_capacity && ($q->waiting/$q->max_capacity) > .8) ? '#EF4444' : '#2563EB' }};border-radius:999px"></div>
                            </div>
                            <span style="font-size:.8rem;color:#64748B">{{ $q->max_capacity ? round(($q->waiting/$q->max_capacity)*100) : 0 }}%</span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Recent tickets --}}
<div class="card">
    <div class="card-header">🎫 Recent Tickets</div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Code</th><th>Customer</th><th>Queue</th><th>Position</th><th>Status</th><th>Time</th></tr></thead>
            <tbody>
                @foreach($recent as $t)
                <tr>
                    <td><code style="font-size:.8rem">{{ $t->qr_code }}</code></td>
                    <td>{{ $t->customer->name }}</td>
                    <td>{{ $t->queue->name }}</td>
                    <td>#{{ $t->position }}</td>
                    <td><span class="badge badge-{{ $t->getStatusBadgeClass() }}">{{ $t->status }}</span></td>
                    <td style="font-size:.8rem;color:#64748B">{{ $t->created_at->diffForHumans() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
