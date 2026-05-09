@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header"><h3>My Tickets</h3></div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead><tr><th>Ticket No</th><th>Subject</th><th>Category</th><th>Status</th><th>Created</th><th>Action</th></tr></thead>
            <tbody>
                @forelse($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->ticket_no }}</td>
                    <td>{{ $ticket->subject }}</td>
                    <td>{{ $ticket->category->name }}</td>
                    <td><span class="badge bg-{{ $ticket->status=='Open'?'primary':($ticket->status=='On Progress'?'warning':($ticket->status=='Resolved'?'success':'secondary')) }}">{{ $ticket->status }}</span></td>
                    <td>{{ $ticket->created_at->format('d/m/Y') }}</td>
                    <td><a href="{{ route('employee.tickets.show', $ticket) }}" class="btn btn-sm btn-info">View</a></td>
                </tr>
                @empty
                <tr><td colspan="6">No tickets</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $tickets->links() }}
    </div>
</div>
@endsection