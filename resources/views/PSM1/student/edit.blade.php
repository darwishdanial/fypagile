@extends('utility.layout')

@section('navbar')
	@include('utility.navbar')
@endsection('navbar')

@section('content')
<style>
    .container {
      max-width: 450px;
    }
    .push-top {
      margin-top: 50px;
    }
</style>
<div class="container">
  <div class="card push-top">
    <div class="card-header">
      Edit & Update
    </div>
    <div class="card-body">
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
          </ul>
        </div><br />
      @endif
        <form method="post" action="{{ route('updatestudentPSM1', $student->id) }}" enctype="multipart/form-data">
            <div class="form-group">
                @csrf
                @method('PUT')
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" value="{{ $student->name }}"/>
            </div>
            <div class="form-group pb-4">
                <label for="matric">Matric</label>
                <input type="matric" class="form-control" name="matric" value="{{ $student->matric }}"/>
            </div>
            
            <button type="submit" class="btn btn-block btn-primary">Update Student</button>
        </form>
    </div>
  </div>
</div>
@endsection