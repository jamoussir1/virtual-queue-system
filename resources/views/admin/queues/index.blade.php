@extends('admin.dashboard')
@section('title', 'Manage Queues')
@section('content')
<div class="page-header flex items-center justify-between">
    <div>
        <div class="page-title">🏢 Manage Queues</div>
        <div class="page-sub">Create, edit and delete service queues.</div>
    </div>
    <a href="{{ route('admin.queues.create') }}" class="btn btn-primary">+ New Queue</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead><tr><th>Name</th><th>Status</th><th>Tickets</th><th>Capacity</th><th>Created By</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($queues as $q)
                <tr>
                    <td><strong>{{ $q->name }}</strong></td>
                    <td><span class="badge badge-{{ $q->status }}">{{ $q->status }}</span></td>
                    <td>{{ $q->tickets_count }}</td>
                    <td>{{ $q->max_capacity }}</td>
                    <td>{{ $q->creator->name ?? '-' }}</td>
                    <td>
                        <div style="display:flex;gap:.4rem">
                            <a href="{{ route('admin.queues.edit', $q) }}" class="btn btn-outline btn-sm">Edit</a>
                            <form method="POST" action="{{ route('admin.queues.destroy', $q) }}">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Delete queue?')">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-gray" style="padding:2rem">No queues yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $queues->links() }}
@endsection
