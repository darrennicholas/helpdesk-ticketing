@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header"><h3>Ticket #{{ $ticket->ticket_no }} ({{ $ticket->user->name }})</h3></div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Subject:</strong> {{ $ticket->subject }}</p>
                <p><strong>Category:</strong> {{ $ticket->category->name }}</p>
                <p><strong>Priority:</strong> <span class="badge bg-{{ $ticket->priority=='High'?'danger':($ticket->priority=='Medium'?'warning':'info') }}">{{ $ticket->priority }}</span></p>
                <p><strong>Status:</strong> <span class="badge bg-{{ $ticket->status=='Open'?'primary':($ticket->status=='On Progress'?'warning':($ticket->status=='Resolved'?'success':'secondary')) }}">{{ $ticket->status }}</span></p>
                <p><strong>Created:</strong> {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Description:</strong><br>{{ nl2br(e($ticket->description)) }}</p>
            </div>
            <div class="col-md-6">
                @if($nextStatus)
                <div class="card mb-3">
                    <div class="card-header">Update Status to {{ $nextStatus }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('support.tickets.update-status', $ticket) }}" enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="{{ $nextStatus }}">
                            <div class="mb-3"><label>Note (optional)</label><textarea name="note" class="form-control" rows="2"></textarea></div>
                            <div class="mb-3"><label>Attachment</label><input type="file" name="attachment" class="form-control"></div>
                            <button type="submit" class="btn btn-primary">Update Status</button>
                        </form>
                    </div>
                </div>
                @endif
                <div class="card">
                    <div class="card-header">Add Note</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('support.tickets.add-note', $ticket) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3"><textarea name="note" class="form-control" rows="2" required placeholder="Write a note..."></textarea></div>
                            <div class="mb-3"><input type="file" name="attachment" class="form-control"></div>
                            <button type="submit" class="btn btn-secondary">Add Note</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <h4>History Log</h4>
        <table class="table table-sm table-bordered">
            <thead><tr><th>Date</th><th>User</th><th>Action</th><th>Details</th></tr></thead>
            <tbody>
                @forelse($ticket->logs as $log)
                <tr>
                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                    <td>{{ $log->user->name ?? 'System' }}</td>
                    <td>{{ ucfirst(str_replace('_',' ',$log->action)) }}</td>
                    <td>
                        @if($log->old_status && $log->new_status) {{ $log->old_status }} → {{ $log->new_status }}<br>@endif
                        @if($log->note) "{{ $log->note }}"<br>@endif
                        @if($log->attachment) <a href="{{ Storage::url($log->attachment) }}" target="_blank">Download</a>@endif
                    </td>
                </tr>
                @empty <tr><td colspan="4">No history</td></tr> @endforelse
            </tbody>
        </table>
        <a href="{{ route('support.tickets.index') }}" class="btn btn-secondary">Back to list</a>
    </div>
</div>
@endsection