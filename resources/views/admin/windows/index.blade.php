@extends('admin.dashboard')
@section('title', 'Service Windows')
@section('content')
<div class="page-header flex items-center justify-between">
    <div><div class="page-title">🖥 Service Windows</div></div>
    <a href="{{ route('admin.windows.create') }}" class="btn btn-primary">+ New Window</a>
</div>
<div class="card">
    <div class="table-wrap">
        <table>
            <thead><tr><th>Label</th><th>Queue</th><th>Agent</th><th>Active</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($windows as $w)
                <tr>
                    <td><strong>{{ $w->label }}</strong></td>
                    <td>{{ $w->queue->name ?? '-' }}</td>
                    <td>{{ $w->agent->name ?? 'Unassigned' }}</td>
                    <td><span class="badge {{ $w->is_active ? 'badge-success' : 'badge-secondary' }}">{{ $w->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td><div style="display:flex;gap:.4rem">
                        <a href="{{ route('admin.windows.edit', $w) }}" class="btn btn-outline btn-sm">Edit</a>
                        <form method="POST" action="{{ route('admin.windows.destroy', $w) }}">@csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Delete</button>
                        </form>
                    </div></td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-gray" style="padding:2rem">No windows yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $windows->links() }}
@endsection
