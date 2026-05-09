@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header"><h3>Ticket #{{ $ticket->ticket_no }}</h3></div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Subject:</strong> {{ $ticket->subject }}</p>
                <p><strong>Category:</strong> {{ $ticket->category->name ?? 'N/A' }}</p>
                <p><strong>Priority:</strong> <span class="badge bg-{{ $ticket->priority=='High'?'danger':($ticket->priority=='Medium'?'warning':'info') }}">{{ $ticket->priority }}</span></p>
                <p><strong>Status:</strong> <span class="badge bg-{{ $ticket->status=='Open'?'primary':($ticket->status=='On Progress'?'warning':($ticket->status=='Resolved'?'success':'secondary')) }}">{{ $ticket->status }}</span></p>
                <p><strong>Created:</strong> {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Description:</strong><br>{{ nl2br(e($ticket->description)) }}</p>
            </div>
        </div>
        <hr>
        <h4>History</h4>
        <table class="table table-sm table-bordered">
            <thead><tr><th>Date</th><th>User</th><th>Action</th><th>Details</th></tr></thead>
            <tbody>
                @forelse($ticket->logs as $log)
                <tr>
                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                    <td>{{ $log->user->name ?? 'System' }}</td>
                    <td>{{ ucfirst(str_replace('_',' ',$log->action)) }}</td>
                    <td>
                        @if($log->old_status && $log->new_status) Status: {{ $log->old_status }} → {{ $log->new_status }}<br>@endif
                        @if($log->note) <em>"{{ $log->note }}"</em><br>@endif
                        @if($log->attachment) <a href="{{ Storage::url($log->attachment) }}" target="_blank">Download</a>@endif
                    </td>
                </tr>
                @empty <tr><td colspan="4">No history</td></tr> @endforelse
            </tbody>
        </table>
        <a href="{{ route('employee.tickets.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>
@endsection