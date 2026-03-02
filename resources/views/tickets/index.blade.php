@extends('layouts.app')

@section('title', 'My Tickets')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>My Tickets</h2>
    <a href="{{ route('tickets.create') }}" class="btn btn-primary">
        Create Ticket
    </a>
</div>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Ticket</th>
            <th>Subject</th>
            <th>Category</th>
            <th>Department</th>
            <th>Status</th>
            <th>Priority</th>
            <th>Created</th>
            <th>View</th>
            <th>View Chat</th>
        </tr>
    </thead>
    

    <tbody>
        @forelse ($tickets as $ticket)
            <tr>
                <td>{{ $ticket->ticket_number }}</td>
                <td>{{ $ticket->subject }}</td>
                <td>{{ $ticket->category->category_name ?? 'N/A' }}
                <td>{{ $ticket->department->name ?? 'N/A' }}</td>
                <td>{{ ($ticket->status ?? 'open') }}</td>
                <td>{{ ($ticket->priority) }}</td>
                <td>{{ $ticket->created_at->format('d M Y') }}</td>
                <td>
                    <a href="{{ route('tickets.show', $ticket->id) }}">
                        {{ $ticket->ticket_number }}
                    </a>
                </td>
                <td>
                    <a href="{{ route('tickets.timeline', $ticket->id) }}">
                        View Timeline
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">
                    No Tickets Found
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection


