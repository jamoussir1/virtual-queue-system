@extends('admin.dashboard')
@section('title', 'Edit Window')
@section('content')
<div class="page-header"><div class="page-title">✏️ Edit Window: {{ $window->label }}</div></div>
<div class="card" style="max-width:520px"><div class="card-body">
    <form method="POST" action="{{ route('admin.windows.update', $window) }}">
        @csrf @method('PUT')
        <div class="form-group"><label class="form-label">Label</label>
            <input type="text" name="label" class="form-control" value="{{ old('label', $window->label) }}" required></div>
        <div class="form-group"><label class="form-label">Queue</label>
            <select name="queue_id" class="form-control" required>
                @foreach($queues as $q)<option value="{{ $q->id }}" {{ old('queue_id',$window->queue_id)==$q->id?'selected':'' }}>{{ $q->name }}</option>@endforeach
            </select></div>
        <div class="form-group"><label class="form-label">Assigned Agent</label>
            <select name="agent_id" class="form-control">
                <option value="">-- No Agent --</option>
                @foreach($agents as $a)<option value="{{ $a->id }}" {{ old('agent_id',$window->agent_id)==$a->id?'selected':'' }}>{{ $a->name }}</option>@endforeach
            </select></div>
        <div class="form-group"><label style="display:flex;align-items:center;gap:.5rem;cursor:pointer">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active',$window->is_active)?'checked':'' }}> Active</label></div>
        <div style="display:flex;gap:.75rem">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.windows') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div></div>
@endsection
