@extends('layouts.app')

@section('title', 'Ticket Timeline')

@section('content')

<h3 class="mb-4">
    Ticket Timeline - {{ $ticket->ticket_number }}
</h3>

<div class="card p-4">

    <div class="timeline-item mb-4">
        <strong>Ticket Created</strong>
        <small class="text-muted d-block">
            {{ $ticket->created_at->format('d M Y - h:i A') }}
        </small>
        <div>
            Ticket was created by {{ $ticket->user->name ?? 'User' }}.
        </div>
    </div>

    <hr>

    @foreach($ticket->comments->sortBy('created_at') as $activity)

        <div class="timeline-item mb-4">

            <strong>
                {{ $activity->user->name ?? 'System' }}
            </strong>

            <small class="text-muted d-block">
                {{ $activity->created_at->format('d M Y - h:i A') }}
            </small>

            <div class="mt-1">
                {{ $activity->comment }}
            </div>

        </div>

        <hr>

    @endforeach

    @if($ticket->status === 'close')
        <div class="timeline-item mb-4">
            <strong>Ticket Closed</strong>
            <small class="text-muted d-block">
                {{ $ticket->closed_at ? $ticket->closed_at->format('d M Y - h:i A') : '' }}
            </small>
            <div>
                Ticket closed by {{ $ticket->closedBy->name ?? 'User' }}.
            </div>
        </div>
    @endif

</div>

@endsection
