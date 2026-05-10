@extends('admin.dashboard')
@section('title', 'Manage Users')
@section('content')
<div class="page-header flex items-center justify-between">
    <div><div class="page-title">👥 Manage Users</div></div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ New User</a>
</div>
<div class="card">
    <div class="table-wrap">
        <table>
            <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Joined</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($users as $u)
                <tr>
                    <td><strong>{{ $u->name }}</strong></td>
                    <td>{{ $u->email }}</td>
                    <td><span class="badge {{ $u->role==='admin'?'badge-danger':($u->role==='agent'?'badge-primary':'badge-success') }}">{{ $u->role }}</span></td>
                    <td style="font-size:.8rem;color:#64748B">{{ $u->created_at->format('d/m/Y') }}</td>
                    <td><div style="display:flex;gap:.4rem">
                        <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-outline btn-sm">Edit</a>
                        <form method="POST" action="{{ route('admin.users.destroy', $u) }}">@csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Delete user?')">Delete</button>
                        </form>
                    </div></td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-gray" style="padding:2rem">No users.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $users->links() }}
@endsection
