@extends('admin.dashboard')
@section('title', 'Create Queue')
@section('content')
<div class="page-header"><div class="page-title">➕ Create New Queue</div></div>
<div class="card" style="max-width:520px">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.queues.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Queue Name</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. Customer Service" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    @foreach(['open','closed','paused'] as $s)
                    <option value="{{ $s }}" {{ old('status','open') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Max Capacity</label>
                <input type="number" name="max_capacity" class="form-control" value="{{ old('max_capacity', 50) }}" min="1" max="500">
            </div>
            <div style="display:flex;gap:.75rem">
                <button type="submit" class="btn btn-primary">Create Queue</button>
                <a href="{{ route('admin.queues') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
