@extends('utility.layout')

@section('navbar')
	@include('utility.navbar')
@endsection('navbar')

@section('content')
<!DOCTYPE html>
<html>
<head>
    <title>List Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <style>
         #message.container {
        max-width: 650px;
        }
    </style>
    <div class="container">
        <div class="container" id="message" >
        @if(Session::has('success'))
                    <div class="alert alert-success">
                        <div class="flash-message">
                            {!! Session::get('success') !!}
                        </div>
                    </div>
                @endif
        </div>
            <div class="card mt-3 mb-3">
                            <div class="card-header text-center">
                                <h4>List of Users</h4>
                            </div>
                            <div class="card-body">
                            <div class="card-body">
                <table class="table table-bordered mt-3">
                    <tr>
                        <th>#</th> <!-- New column for counting -->
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                    @php
                    $count = 1; // Initialize count variable
                    @endphp
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $count++ }}</td> <!-- Increment count for each row -->
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>@if($user->role == 1)
                            Coordinator
                        @else
                            Supervisor
                        @endif</td>
                        <td class="text-center">
                            <a href="{{ route('users.edit', $user->id)}}" class="btn btn-primary btn-sm">Edit</a>
                            <form action="{{ route('users.delete', $user->id)}}" method="post" style="display: inline-block">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </table>

            </div>

    </div>
</div>
     
</body>
</html>
@endsection('content')