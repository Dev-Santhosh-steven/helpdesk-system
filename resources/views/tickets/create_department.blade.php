@extends('layouts.app')

@section('title','My Departments')

@section('content')
<h2>Create Department</h2>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif


<form method="POST" action="{{ route('tickets.departmentStore') }}">
    @csrf

    <div class="mb-3">
        <label>Name</label>
        <input name="name" class="form-control" type="text" placeholder="Enter Department Name">
    </div>

    <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control" rows="4"></textarea>
    </div>

    <button class="btn btn-danger">Submit</button>

    <a href="{{ route('tickets.departments') }}" class="btn btn-secondary ms-2">
        Back to Departments
    </a>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

</form>
@endsection

