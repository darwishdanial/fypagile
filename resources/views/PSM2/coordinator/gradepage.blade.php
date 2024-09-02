@extends('utility.layout')

@section('navbar')
	@include('utility.navbar')
@endsection('navbar')

@section('content')
<!-- branch AD -->

    <div class="container">
        @if($message = Session::get('success'))
        <div class="alert alert-info">
        {{ $message }}
        </div>
        @endif
      <form method="post" action="{{ route('sendgrade2')}}">
        @csrf
        <input type="hidden" name="id" value="{{ $id }}">
        <div class="row">
          <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Ethics 5%</h3>
                </div>
                  <div class="card-body">
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="similarity">Similarity Percentage</label>
                      <div class="col-md-6 pt-1">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="similarity" id="similarity" value="0" required>
                          <label class="form-check-label" for="similarity">0</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="similarity" id="similarity" value="1" >
                          <label class="form-check-label" for="similarity">1</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="similarity" id="similarity" value="2">
                          <label class="form-check-label" for="similarity">2</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="similarity" id="similarity" value="3">
                          <label class="form-check-label" for="similarity">3</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="similarity" id="similarity" value="4">
                          <label class="form-check-label" for="similarity">4</label>
                        </div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="submission">Submission</label>
                      <div class="col-md-6 pt-1">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="submission" id="submission" value="0" required>
                          <label class="form-check-label" for="submission">0</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="submission" id="submission" value="1" >
                          <label class="form-check-label" for="submission">1</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="submission" id="submission" value="2">
                          <label class="form-check-label" for="submission">2</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="submission" id="submission" value="3">
                          <label class="form-check-label" for="submission">3</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="submission" id="submission" value="4">
                          <label class="form-check-label" for="submission">4</label>
                        </div>
                      </div>
                    </div>
                        </div>
                    </div>
                  </div>
              </div>
              <div class="form-group pt-2 pb-2">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </div>
         
      </form>
    </div> <!-- container -->
    
@endsection('content')
