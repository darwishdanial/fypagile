@extends('utility.layout')

@section('navbar')
	@include('utility.navbar')
@endsection('navbar')

@section('content')
	<style>
		#message.container {
		max-width: 650px;
		}
	</style>
	<div class="container" id="message" >
    @if(Session::has('success'))
		<div class="alert alert-success">
			<div class="flash-message">
				{!! Session::get('success') !!}
			</div>
		</div>
	@endif
    </div>
<div class="row justify-content-center">
	<div class="col-md-4">
		<div class="card">
		<div class="card-header">Registration</div>
		<div class="card-body">
			<form action="{{ route('auth.validate_registration') }}" method="POST">
				@csrf
				<div class="form-group mb-3">
					<input type="text" name="name" class="form-control" placeholder="Name" />
					@if($errors->has('name'))
						<span class="text-danger">{{ $errors->first('name') }}</span>
					@endif
				</div>
				<div class="form-group mb-3">
					<input type="text" name="username" class="form-control" placeholder="Username" />
					@if($errors->has('username'))
						<span class="text-danger">{{ $errors->first('username') }}</span>
					@endif
				</div>
				<div class="form-group mb-3">
					<input type="text" name="email" class="form-control" placeholder="Email Address" />
					@if($errors->has('email'))
						<span class="text-danger">{{ $errors->first('email') }}</span>
					@endif
				</div>
				
				<div class="form-group mb-3">
					<p>Role</p>
					<input type="radio" id="coordinator" name="role" value="1">
					<label for="coordinator">Coordinator</label><br>
					<input type="radio" id="supervisor" name="role" value="2">
					<label for="supervisor">Supervisor</label><br>
				</div>

				<div class="form-group mb-3">
					<p>Panel</p>
					<input type="radio" id="panel" name="panel" value="1">Yes</input>
					<input type="radio" id="panel" name="panel" value="0">No</input>
					
				</div>

			
				<div class="d-grid mx-auto">
					<button type="submit" class="btn btn-dark btn-block">Register</button>
				</div>
			</form>
		</div>
	</div>
</div>

@endsection('content')
