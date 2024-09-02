@extends('utility.layout')

@section('content')

<div class="container">
	@if(Session::has('success'))
            <div class="alert alert-success">
                <div class="flash-message">
                    {!! Session::get('success') !!}
                </div>
            </div>
    @endif
	@if (Session::has('error'))
				<div class="alert alert-danger">
				{!! Session::get('error') !!}
				</div>
	@endif
	<div class="row align-items-center vh-100">
		<div class="row justify-content-center">
			<div class="col-md-4">
				<div class="card">
				
					<div class="card-header">Login</div>
					<div class="card-body">
						<form action="{{ route('auth.validate_login') }}" method="post">
							@csrf
							{{-- <div class="form-group mb-3">
								<input type="text" name="email" class="form-control" placeholder="Email" />
								@if($errors->has('email'))
									<span class="text-danger">{{ $errors->first('email') }}</span>
								@endif
							</div> --}}
							<div class="form-group mb-3">
								<input type="text" name="username" class="form-control" placeholder="Username" />
								@if($errors->has('username'))
									<span class="text-danger">{{ $errors->first('username') }}</span>
								@endif
							</div>
							<div class="form-group mb-3">
								<input type="password" name="password" class="form-control" placeholder="Password" />
								@if($errors->has('password'))
									<span class="text-danger">{{ $errors->first('password') }}</span>
								@endif
							</div>
							<div class="d-grid mx-auto">
								<button type="subit" class="btn btn-dark btn-block">Login</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



@endsection('content')