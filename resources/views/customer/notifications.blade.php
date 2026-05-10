@extends('layouts.app')
@section('title', 'My Notifications')

@section('content')
<div class="page-header">
    <div class="page-title">🔔 Notifications</div>
    <div class="page-sub">All your queue alerts and updates.</div>
</div>

<div class="card">
    <div class="card-body">
        @forelse($notifs as $notif)
        <div style="display:flex;gap:.75rem;padding:.85rem 0;border-bottom:1px solid #F1F5F9;align-items:flex-start">
            <div style="font-size:1.4rem;line-height:1">{{ $notif->is_read ? '📭' : '📬' }}</div>
            <div style="flex:1">
                <div style="font-size:.9rem;color:#0F172A{{ $notif->is_read ? '' : ';font-weight:600' }}">{{ $notif->message }}</div>
                <div style="font-size:.75rem;color:#94A3B8;margin-top:.2rem">{{ $notif->sent_at->diffForHumans() }}</div>
            </div>
        </div>
        @empty
        <div class="empty-state"><div class="icon">📭</div><p>No notifications yet.</p></div>
        @endforelse
    </div>
</div>
{{ $notifs->links() }}
@endsection
