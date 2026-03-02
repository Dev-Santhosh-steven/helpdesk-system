@extends('layouts.app')

@section ('title', 'My Tickets')

@section('content')
<div>
    <h2>Categories</h2>
    <a href="{{ route('tickets.create_category') }}" class="btn btn-primary"> Create Category</a>
</div>

<table class="table table-bordred">
    <thead> 
        <tr>
            <th>Name</th>
            <th>Description</th>
        </tr>
    </thead>

    <tbody>
        @forelse($categories as $category)
        <tr>
            <td>{{$category->category_name}}</td>
            <td>{{$category->category_description}}</td>
        </tr>
        @empty
        <tr>
            <th colspan="5">No Categories Found</th>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection



