@extends('admin.dashboard')
@section('title', 'Edit User')
@section('content')
<div class="page-header"><div class="page-title">✏️ Edit User: {{ $user->name }}</div></div>
<div class="card" style="max-width:520px"><div class="card-body">
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf @method('PUT')
        <div class="form-group"><label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required></div>
        <div class="form-group"><label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required></div>
        <div class="form-group"><label class="form-label">Role</label>
            <select name="role" class="form-control">
                @foreach(['customer','agent','admin'] as $r)<option value="{{ $r }}" {{ old('role',$user->role)==$r?'selected':'' }}>{{ ucfirst($r) }}</option>@endforeach
            </select></div>
        <div class="form-group"><label class="form-label">New Password <small style="color:#94A3B8">(leave blank to keep)</small></label>
            <input type="password" name="password" class="form-control"></div>
        <div style="display:flex;gap:.75rem">
            <button type="submit" class="btn btn-primary">Update User</button>
            <a href="{{ route('admin.users') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div></div>
@endsection
