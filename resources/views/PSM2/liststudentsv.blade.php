@extends('utility.layout')

@section('navbar')
	@include('utility.navbar')
@endsection('navbar')

@section('content')     
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">List of PSM2 Students Under Supervision</div>

                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Course</th>
                                    <th>Action</th>
                                    <!-- <th>Supervisor</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @php($i = 0)
                                @foreach($students as $index => $student)
                                @php($i = $i + 1)
                                    <tr>
                                        <td>{{ $i}}</td>
                                        <td>{{ $student->name }}</td>
                                        <td>{{ $student->matric }}</td>
                                        <td>{{ $student->course }}</td>
                                        <td>
                                        <a href="{{ route('svgradePSM2', $student->id)}}" class="btn btn-primary btn-sm">Grade</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection('content')

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
@endsection('script')