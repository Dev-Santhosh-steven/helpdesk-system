@extends('layouts.app')

@section('title', 'Create Ticket')

@section('content')
<h3>Create Ticket</h3>

<form method="POST" action="{{ route('tickets.store') }}">
    @csrf

    <div class="mb-3">
        <label>Category</label>
        <select name="ticket_category_id" class="form-control" required>
            <option value="" disabled selected>--Select Category--</option>

            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Department</label>
        <select name="department_id" class="form-control" required>
            <option value="" disabled selected>--Select Department--</option>

            @foreach($departments as $department)
                <option value="{{ $department->id }}">{{ $department->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Subject</label>
        <input type="text" name="subject" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control" rows="4" requied></textarea>
    </div>

    <div class="mb-3">
        <label>Priority</label>
        <select name="priority" class="form-control" required>
            <option value="" disabled selected>--Select Priority--</option>
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High</option>
            <option value="urgent">Urgent</option>
        </select>
    </div>

    <button class="btn btn-success">Submit Ticket</button>
</form>
@endsection
