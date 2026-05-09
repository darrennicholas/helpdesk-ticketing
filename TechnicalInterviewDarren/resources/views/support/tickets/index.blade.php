@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header"><h3>All Tickets</h3></div>
    <div class="card-body">
        <form method="GET" class="row mb-3">
            <div class="col-md-2"><label>Status</label><select name="status" class="form-control"><option value="">All</option>@foreach($statuses as $s)<option {{ request('status')==$s?'selected':'' }}>{{ $s }}</option>@endforeach</select></div>
            <div class="col-md-2"><label>Category</label><select name="category_id" class="form-control"><option value="">All</option>@foreach($categories as $c)<option value="{{ $c->id }}" {{ request('category_id')==$c->id?'selected':'' }}>{{ $c->name }}</option>@endforeach</select></div>
            <div class="col-md-2"><label>Priority</label><select name="priority" class="form-control"><option value="">All</option>@foreach($priorities as $p)<option {{ request('priority')==$p?'selected':'' }}>{{ $p }}</option>@endforeach</select></div>
            <div class="col-md-2"><label>Date From</label><input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}"></div>
            <div class="col-md-2"><label>Date To</label><input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}"></div>
            <div class="col-md-2"><label>&nbsp;</label><button type="submit" class="btn btn-primary form-control">Filter</button></div>
        </form>
        <table class="table table-bordered">
            <thead><tr><th>Ticket No</th><th>Employee</th><th>Subject</th><th>Category</th><th>Priority</th><th>Status</th><th>Created</th><th>Action</th></tr></thead>
            <tbody>
                @forelse($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->ticket_no }}</td>
                    <td>{{ $ticket->user->name }}</td>
                    <td>{{ $ticket->subject }}</td>
                    <td>{{ $ticket->category->name }}</td>
                    <td><span class="badge bg-{{ $ticket->priority=='High'?'danger':($ticket->priority=='Medium'?'warning':'info') }}">{{ $ticket->priority }}</span></td>
                    <td><span class="badge bg-{{ $ticket->status=='Open'?'primary':($ticket->status=='On Progress'?'warning':($ticket->status=='Resolved'?'success':'secondary')) }}">{{ $ticket->status }}</span></td>
                    <td>{{ $ticket->created_at->format('d/m/Y') }}</td>
                    <td><a href="{{ route('support.tickets.show', $ticket) }}" class="btn btn-sm btn-info">Process</a></td>
                </tr>
                @empty <tr><td colspan="8">No tickets</td></tr> @endforelse
            </tbody>
        </table>
        {{ $tickets->appends(request()->query())->links() }}
    </div>
</div>
@endsection