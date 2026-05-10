@extends('admin.dashboard')
@section('title', 'Edit Queue')
@section('content')
<div class="page-header"><div class="page-title">✏️ Edit Queue: {{ $queue->name }}</div></div>
<div class="card" style="max-width:520px">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.queues.update', $queue) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Queue Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $queue->name) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    @foreach(['open','closed','paused'] as $s)
                    <option value="{{ $s }}" {{ old('status', $queue->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Max Capacity</label>
                <input type="number" name="max_capacity" class="form-control" value="{{ old('max_capacity', $queue->max_capacity) }}" min="1" max="500">
            </div>
            <div style="display:flex;gap:.75rem">
                <button type="submit" class="btn btn-primary">Update Queue</button>
                <a href="{{ route('admin.queues') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
