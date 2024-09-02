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
        <form method="post" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
            <div class="form-group">
                @csrf
                @method('PUT')
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" value="{{ $user->name }}"/>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" name="username" value="{{ $user->username }}"/>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" value="{{ $user->email }}"/>
            </div>
            <div class="form-group">
              <label for="panel">Panel</label><br>
              <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn">
                  <input type="radio" name="isPanel" id="true" value="1" autocomplete="off" {{ $user->isPanel == 1 ? 'checked' : '' }}> Yes
                </label>
                <label class="btn">
                  <input type="radio" name="isPanel" id="false" value="0" autocomplete="off" {{ $user->isPanel == 0 ? 'checked' : '' }}> No
                </label>
              </div>    
            </div>
            <button type="submit" class="btn btn-block btn-danger">Update User</button>
        </form>
    </div>
  </div>
</div>
@endsection