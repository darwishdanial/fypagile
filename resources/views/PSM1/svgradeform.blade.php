@extends('utility.layout')

@section('navbar')
	@include('utility.navbar')
@endsection('navbar')

@section('content')

<!-- tak pakai -->
    <div class="container">
      <form method="post" action="{{ route('gradePSM1', $student->id) }}">
      @csrf
        <div class="row">
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Supervision</h3>
              </div>
                  <div class="card-body">
                    <div class="form-group" style="width: 50%">
                        <label for="logbook">Log Book</label>
                        <input type="number" class="form-control" id="logbook" name="logbook" min="0" max="4" required>
                        <!-- <label><input type="radio" name="logbook" value="1"> 1</label>

                        <label><input type="radio" name="logbook" value="2"> 2</label>

                        <label><input type="radio" name="logbook" value="3"> 3</label>

                        <label><input type="radio" name="logbook" value="4">4</label> -->
                    </div>
                    <div class="form-group" style="width: 50%">
                        <label for="meeting">Meeting Frequency</label>
                        <input type="number" class="form-control" id="meeting" name="meeting" min="0" max="4" required>
                    </div>
                    <div class="form-group" style="width: 50%">
                        <label for="ethic">Work Ethic</label>
                        <input type="number" class="form-control" id="ethic" name="ethic" min="0" max="4" required>
                    </div>
                    <div class="form-group" style="width: 50%">
                        <label for="independent">Self Reliance</label>
                        <input type="number" class="form-control" id="independent" name="independent" min="0" max="4" required>
                    </div>
                  </div>
              </div>
          </div>

          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Progress Report 1</h3>
              </div>
              <div class="card-body">
                <div class="form-group" style="width: 50%">
                    <label for="chapter1">Progress of Chapter 1</label>
                    <input type="number" class="form-control" id="chapter1" name="chapter1" min="0" max="4" required>
                </div>
                <div class="form-group" style="width: 50%">
                    <label for="chapter2">Progress of Chapter 2</label>
                    <input type="number" class="form-control" id="chapter2" name="chapter2" min="0" max="4" required>
                </div>
                <div class="form-group" style="width: 50%">
                    <label for="format">Writing style, Format and Clarity</label>
                    <input type="number" class="form-control" id="format" name="format" min="0" max="4" required>
                </div>
                <div class="form-group" style="width: 50%">
                    <label for="citation">Citation and References</label>
                    <input type="number" class="form-control" id="citation" name="citation" min="0" max="4" required>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 pt-3">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Progress Report 2</h3>
              </div>
              <div class="card-body">
                <div class="form-group" style="width: 50%">
                    <label for="chapter3">Progress of Chapter 3</label>
                    <input type="number" class="form-control" id="chapter3" name="chapter3" min="0" max="4" required>
                </div>
                <div class="form-group" style="width: 50%">
                    <label for="chapter4">Progress of Chapter 4</label>
                    <input type="number" class="form-control" id="chapter4" name="chapter4" min="0" max="4" required>
                </div>
                <div class="form-group" style="width: 50%">
                    <label for="format2">Writing style, Format and Clarity</label>
                    <input type="number" class="form-control" id="format2" name="format2" min="0" max="4" required>
                </div>
                <div class="form-group" style="width: 50%">
                    <label for="citation2">Citation and References</label>
                    <input type="number" class="form-control" id="citation2" name="citation2" min="0" max="4" required>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6 pt-3">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Final Report</h3>
              </div>
              <div class="card-body">
                <div class="form-group" style="width: 50%">
                    <label for="abstract">Abstract</label>
                    <input type="number" class="form-control" id="abstract" name="abstract" min="0" max="4" required>
                </div>
                <div class="form-group" style="width: 50%">
                    <label for="complete1">Completeness of Chapter 1</label>
                    <input type="number" class="form-control" id="complete1" name="complete1" min="0" max="4" required>
                </div>
                <div class="form-group" style="width: 50%">
                    <label for="complete2">Completeness of Chapter 2</label>
                    <input type="number" class="form-control" id="complete2" name="complete2" min="0" max="4" required>
                </div>
                <div class="form-group" style="width: 50%">
                    <label for="complete3">Completeness of Chapter 3</label>
                    <input type="number" class="form-control" id="complete3" name="complete3" min="0" max="4" required>
                </div>
                <div class="form-group" style="width: 50%">
                    <label for="complete4">Completeness of Chapter 4</label>
                    <input type="number" class="form-control" id="complete4" name="complete4" min="0" max="4" required>
                </div>
                <div class="form-group" style="width: 50%">
                    <label for="format3">Writing style, Format and Clarity</label>
                    <input type="number" class="form-control" id="format3" name="format3" min="0" max="4" required>
                </div>
                <div class="form-group" style="width: 50%">
                    <label for="citation3">Citation and References</label>
                    <input type="number" class="form-control" id="citation3" name="citation3" min="0" max="4" required>
                </div>
              </div>
            </div>
          </div>
            <div class="form-group pt-12">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>

      </form>
    </div>


@endsection('content')
