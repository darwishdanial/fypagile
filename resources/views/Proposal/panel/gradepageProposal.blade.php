@extends('utility.layout')

@section('navbar')
	@include('utility.navbar')
@endsection('navbar')

@section('content')


<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          @if($message = Session::get('success'))
            <div class="alert alert-info">
              {{ $message }}
            </div>
          @endif

          <form method="post" action="{{ route('markahproposal')}}">
            @csrf
            <input type="hidden" name="id" value="{{ $id }}">

            <div class="form-group">
              <label for="approval">Approval:</label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="approval" id="full-approval" value="full_approval" required>
                <label class="form-check-label" for="full-approval">
                  Full Approval
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="approval" id="conditional-minor" value="conditional_minor">
                <label class="form-check-label" for="conditional-minor">
                  Conditional Approval (Minor)
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="approval" id="conditional-major" value="conditional_major">
                <label class="form-check-label" for="conditional-major">
                  Conditional Approval (Major)
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="approval" id="fail" value="fail">
                <label class="form-check-label" for="fail">
                  Fail
                </label>
              </div>
            </div>

            <div class="form-group">
              <label for="notes">Notes:</label>
              <textarea class="form-control" name="notes" id="notes" rows="3"></textarea>
            </div>

            <div class="form-group pt-2">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>


    
@endsection('content')
