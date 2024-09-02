<!-- branch AD -->
@extends('utility.layout')

@section('navbar')
	@include('utility.navbar')
@endsection('navbar')
@section('content')
<html>

<div class="container mt-5 mb-5">
    
    @if(Session::has('success'))
            <div class="alert alert-success">
                <div class="flash-message">
                    {!! Session::get('success') !!}
                </div>
            </div>
    @endif
    
    
	<div class="card">
		<div class="card-header">Evaluation Rubrics PSM1</div>
		<div class="card-body">
        <a href="#" class="btn btn-primary">+ Add New Rubric</a>
		</div>
	</div>
</div>
@endsection('content')
</html>