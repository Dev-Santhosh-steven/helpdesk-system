@extends('layouts.app')

@section('title', 'Ticket Timeline')

@section('content')

<h3 class="mb-4">Ticket Timeline - {{ $ticket->ticket_number }}</h3>

<div class="card p-4">

    @forelse($ticket->comments->sortBy('created_at') as $activity)

        <div class="mb-4 d-flex">

            <div class="me-3">
                <div class="bg-primary text-white rounded-circle p-2 text-center" style="width:40px;">
                    {{ strtoupper(substr($activity->user->name ?? 'S', 0, 1)) }}
                </div>
            </div>

            <div>
                <strong>
                    {{ $activity->user->name ?? 'System' }}
                </strong>

                <small class="text-muted">
                    • {{ $activity->created_at->format('d M Y - h:i A') }}
                </small>

                <div class="mt-1">
                    {{ $activity->comment }}
                </div>
            </div>

        </div>

        <hr>

    @empty

        <p>No activity found.</p>

    @endforelse

</div>

@endsection