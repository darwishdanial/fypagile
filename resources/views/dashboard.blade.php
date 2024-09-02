@extends('utility.layout')

@section('navbar')
	@include('utility.navbar')
@endsection('navbar')

@section('content')
	@if($message = Session::get('success'))
		<div class="alert alert-info">
		{{ $message }}
		</div>
	@endif

  @if (Session::get('role_id')==0)
    <div class="container">
          <div class="row justify-content-center">
              <div class="col-md-6">
                  <div class="card">
                      <div class="card-body text-left">
                          <h3>Users</h3>
                          <p>Users : {{$userCounts['totalUsers']-1}} </p>
                          <p>Coordinator : {{$userCounts['coordinators']}}</p>
                          <p>Supervisor : {{$userCounts['supervisors']}}</p>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  @elseif(Session::get('role_id')==1)
  <div class="container">
      <div class="row justify-content-center">
                <div class="col-md-6">
                    <p>Logged in as : {{Session::get('name')}}</p>

                    <div class="card">
                        <div class="card-body text-left">
                            <h3>Coordinator</h3>
                            <p>Student PSM 1: {{$student}} </p>
                            <p>Student PSM 2: {{$student2}} </p>
                        </div>
                    </div>
                </div>
      </div>
  </div>

@elseif(Session::get('role_id')==2) 
<div class="container">
      <div class="row justify-content-center">
                <div class="col-md-6">
                    <p>Logged in as : {{Session::get('name')}}</p>

                    <div class="card">
                        <div class="card-body text-left">
                            <h3>Supervisor</h3>
                            <p>Student PSM 1: {{$student}} </p>
                            <p>Student PSM 2: {{$student2}} </p>

                            
                        </div>
                    </div>
                </div>

                <div class="container mt-5 mb-5">
                    <div class="card">
                        <div class="card-header">List of Students</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="listStudents">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Matric</th>
                                            <th>Course</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $students = [
                                                ['name' => 'Muhammad Zafri Bin Saiful', 'matric' => 'A123456', 'course' => 'Computer Science'],
                                                ['name' => 'Ameer Ikhwan Bin Jazlan', 'matric' => 'B654321', 'course' => 'Information Technology'],
                                                ['name' => 'Ruhaizad Bin Ramli', 'matric' => 'C789012', 'course' => 'Software Engineering'],
                                                ['name' => 'Muhammad Adam Bin Azman', 'matric' => 'D345678', 'course' => 'Cyber Security']
                                            ];
                                        @endphp
                                        @foreach($students as $index => $student)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $student['name'] }}</td>
                                                <td>{{ $student['matric'] }}</td>
                                                <td>{{ $student['course'] }}</td>
                                                <td>
                                                    <div class="btn-group w-100" role="group" aria-label="Basic example">
                                                        <button type="button" class="btn btn-outline-success accept">Accept</button>
                                                        <button type="button" class="btn btn-outline-danger reject">Reject</button>
                                                    </div>
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
  </div>

@endif

@endsection('content')

@section('script')
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            var dtListStudents = $('#listStudents').DataTable();
        });
    </script>
<script src="{{ asset('assets/js/modal.js') }}"></script>
@endsection('script')