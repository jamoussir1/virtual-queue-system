@extends('admin.dashboard')
@section('title', 'Create User')
@section('content')
<div class="page-header"><div class="page-title">➕ Create User</div></div>
<div class="card" style="max-width:520px"><div class="card-body">
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <div class="form-group"><label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="form-group"><label class="form-label">Email</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="form-group"><label class="form-label">Role</label>
            <select name="role" class="form-control">
                @foreach(['customer','agent','admin'] as $r)<option value="{{ $r }}" {{ old('role')==$r?'selected':'' }}>{{ ucfirst($r) }}</option>@endforeach
            </select></div>
        <div class="form-group"><label class="form-label">Password</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div style="display:flex;gap:.75rem">
            <button type="submit" class="btn btn-primary">Create User</button>
            <a href="{{ route('admin.users') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div></div>
@endsection
