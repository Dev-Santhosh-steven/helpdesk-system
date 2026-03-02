@extends('layouts.app')

@section('title', 'Ticket Details')

@section('content')

@php
    $status = strtolower(trim($ticket->status));
    $user = auth()->user();
    $role = strtolower($user->role ?? '');

    $canResolve = in_array($role, ['admin','staff']) && in_array($status, ['in_progress','reopened']);
    $canClose = $role === 'user'
                && auth()->id() === $ticket->user_id
                && $status === 'resolved';
@endphp

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card p-3 mb-3">

    <h3>Ticket #{{ $ticket->ticket_number }}</h3>

    <p><strong>Subject:</strong> {{ $ticket->subject }}</p>
    <p><strong>Department:</strong> {{ $ticket->department->name ?? 'N/A' }}</p>
    <p><strong>Category:</strong> {{ $ticket->category->category_name ?? 'N/A' }}</p>

    <p>
        <strong>Status:</strong>
        <span class="badge 
            @if($status === 'open') bg-secondary
            @elseif($status === 'in_progress') bg-warning
            @elseif($status === 'resolved') bg-info
            @elseif($status === 'close') bg-dark
            @elseif($status === 'reopened') bg-danger
            @endif">
            {{ ucfirst(str_replace('_',' ', $status)) }}
        </span>
    </p>

    <p><strong>Description:</strong></p>
    <div class="border rounded p-2">
        {{ $ticket->description }}
    </div>

    @if($canResolve)
        <form method="POST" action="{{ route('tickets.status', $ticket->id) }}" class="mt-3">
            @csrf
            @method('PATCH')

            <input type="hidden" name="status" value="resolved">

            <button class="btn btn-success btn-sm">
                Mark as Resolved
            </button>
        </form>
    @endif

    @if(in_array($role, ['admin','staff']) 
        && $status === 'resolved' 
        && !$ticket->closure_requested_at)

        <form method="POST" action="{{ route('tickets.closure.request', $ticket->id) }}" class="mt-2">
            @csrf
            @method('PATCH')

            <button class="btn btn-primary btn-sm">
                Send Closing Request
            </button>
        </form>
    @endif

    @php
    $canClose = $role === 'user'
                && auth()->id() === $ticket->user_id
                && $status === 'resolved'
                && !empty($ticket->closure_requested_at);
    @endphp

    @if(
        $status === 'close' &&
        $ticket->reopen_count < 1 &&
        (
            auth()->id() === $ticket->user_id ||
            in_array($role, ['admin','staff'])
        )
    )
        <div class="mt-3">
            <form method="POST" action="{{ route('tickets.status', $ticket->id) }}">
                @csrf
                @method('PATCH')

                <input type="hidden" name="status" value="reopened">

                <button class="btn btn-warning btn-sm">
                    🔁 Reopen Ticket
                </button>
            </form>
        </div>
    @endif

    @if($canClose)
        <div class="text-end mt-3">
            <form method="POST" action="{{ route('tickets.status', $ticket->id) }}">
                @csrf
                @method('PATCH')

                <input type="hidden" name="status" value="close">

                <button class="btn btn-danger">
                    Confirm & Close Ticket
                </button>
            </form>
        </div>
    @endif

</div>

<div class="card p-3 mb-3">

    <h5>Conversation</h5>

    @forelse($ticket->comments as $comment)
        <div class="mb-3">
            <strong>
                {{ $comment->user->name ?? 'User' }}

                @if(in_array(strtolower($comment->user->role ?? ''), ['admin','staff']))
                    <span class="badge bg-info text-dark ms-1">
                        Support
                    </span>
                @endif
            </strong>

            <small class="text-muted">
                · {{ $comment->created_at->diffForHumans() }}
            </small>

            <div class="border rounded p-2 mt-1">
                {{ $comment->comment }}
            </div>
        </div>
    @empty
        <p class="text-muted">No comments yet.</p>
    @endforelse

</div>

@if($status !== 'close')

    <div class="text-end mb-2">
        <button class="btn btn-primary" onclick="openChat()">
            Add Comment
        </button>
    </div>

    <div id="chatBox" class="card p-3 d-none">
        <form method="POST" action="{{ route('tickets.comments', $ticket->id) }}">
            @csrf

            <div class="mb-3">
                <textarea
                    id="commentInput"
                    name="comment"
                    class="form-control"
                    rows="3"
                    placeholder="Type your message..."
                    required
                ></textarea>
            </div>

            <div class="text-end">
                <button class="btn btn-success">
                    Send
                </button>
            </div>

        </form>
    </div>

@else

    <div class="alert alert-secondary text-center">
        This ticket has been closed. No further replies are allowed.
    </div>

@endif

@endsection


@push('scripts')
<script>
    function openChat() {
        const chatBox = document.getElementById('chatBox');
        const input = document.getElementById('commentInput');

        chatBox.classList.remove('d-none');
        input.focus();
    }
</script>
@endpush
