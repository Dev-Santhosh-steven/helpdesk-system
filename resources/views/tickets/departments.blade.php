@extends('layouts.app')

@section ('title', 'My Departments')

@section('content')
<div>
    <h2>Departments</h2>
    <a href="{{ route('tickets.create_department') }}" class="btn btn-primary"> Create Department</a>
</div>

<table class="table table-bordred">
    <thead> 
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
        @forelse($departments as $department)
        <tr>
            <td>{{$department->name}}</td>
            <td>{{$department->description ?? '-'}}</td>
            <td>
                <span class="badge bg-{{ $department->is_active ? 'success' : 'secondary' }}">
                    {{ $department->is_active ? 'Active' : 'Inactive' }}
                </span>
            </td>
        </tr>
        @empty
        <tr>
            <th colspan="5">No Departments Found</th>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection



