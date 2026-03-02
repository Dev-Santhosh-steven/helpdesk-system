@extends('layouts.app')

@section('title','My Tickets')

@section('content')
<h2>Create Category</h2>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif


<form method="POST" action="{{ route('tickets.categoryStore') }}">
    @csrf

    <div class="mb-3">
        <label>Name</label>
        <input name="category_name" class="form-control" type="text" placeholder="Enter Category Name">
    </div>

    <div class="mb-3">
        <label>Description</label>
        <textarea name="category_description" class="form-control" rows="4"></textarea>
    </div>

    <button class="btn btn-danger">Submit</button>

    <a href="{{ route('tickets.categories') }}" class="btn btn-secondary ms-2">
        Back to Categories
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

