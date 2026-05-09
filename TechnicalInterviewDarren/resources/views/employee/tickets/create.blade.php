@extends('layouts.app')
@section('content')
<div class="row justify-content-center"><div class="col-md-8">
<div class="card"><div class="card-header">New Ticket</div>
<div class="card-body">
<form method="POST" action="{{ route('employee.tickets.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="mb-3"><label>Category *</label><select name="category_id" class="form-control" required>
        <option value="">Select</option>@foreach($categories as $cat)<option value="{{ $cat->id }}">{{ $cat->name }}</option>@endforeach
    </select></div>
    <div class="mb-3"><label>Subject *</label><input type="text" name="subject" class="form-control" required></div>
    <div class="mb-3"><label>Description *</label><textarea name="description" rows="5" class="form-control" required></textarea></div>
    <div class="mb-3"><label>Priority *</label><select name="priority" class="form-control"><option>Low</option><option>Medium</option><option>High</option></select></div>
    <div class="mb-3"><label>Attachment (max 5MB)</label><input type="file" name="attachment" class="form-control"></div>
    <button type="submit" class="btn btn-primary">Submit Ticket</button>
    <a href="{{ route('employee.tickets.index') }}" class="btn btn-secondary">Cancel</a>
</form>
</div></div></div></div>
@endsection