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
      <form method="post" action="{{ route('markahPSM1')}}">
        @csrf
        <input type="hidden" name="id" value="{{ $id }}">
        <div class="row">
          <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Supervision 10%</h3>
                </div>
                  <div class="card-body">
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="logbook">Logbook</label>
                      <div class="col-md-6 pt-1">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="logbook" id="logbook" value="0" required>
                          <label class="form-check-label" for="logbook">0</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="logbook" id="logbook" value="1" >
                          <label class="form-check-label" for="logbook">1</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="logbook" id="logbook" value="2">
                          <label class="form-check-label" for="logbook">2</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="logbook" id="logbook" value="3">
                          <label class="form-check-label" for="logbook">3</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="logbook" id="logbook" value="4">
                          <label class="form-check-label" for="logbook">4</label>
                        </div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="meetingf">Meeting Frequency</label>
                      <div class="col-md-6 pt-1">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="meetingf" id="meetingf" value="0" required>
                          <label class="form-check-label" for="meetingf">0</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="meetingf" id="meetingf" value="1">
                          <label class="form-check-label" for="meetingf">1</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="meetingf" id="meetingf" value="2">
                          <label class="form-check-label" for="meetingf">2</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="meetingf" id="meetingf" value="3">
                          <label class="form-check-label" for="meetingf">3</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="meetingf" id="meetingf" value="4">
                          <label class="form-check-label" for="meetingf">4</label>
                        </div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="work">Work Ethic</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="work" id="work" value="0" required>
                            <label class="form-check-label" for="work">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="work" id="work" value="1">
                            <label class="form-check-label" for="work">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="work" id="work" value="2">
                            <label class="form-check-label" for="work">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="work" id="work" value="3">
                            <label class="form-check-label" for="work">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="work" id="work" value="4">
                            <label class="form-check-label" for="work">4</label>
                          </div>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="selfreliance">Self-Reliance / Independent</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="selfreliance" id="selfreliance" value="0" required>
                            <label class="form-check-label" for="selfreliance">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="selfreliance" id="selfreliance" value="1">
                            <label class="form-check-label" for="selfreliance">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="selfreliance" id="selfreliance" value="2">
                            <label class="form-check-label" for="selfreliance">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="selfreliance" id="selfreliance" value="3">
                            <label class="form-check-label" for="selfreliance">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="selfreliance" id="selfreliance" value="4">
                            <label class="form-check-label" for="selfreliance">4</label>
                          </div>
                        </div>
                    </div>
                  </div>
              </div>
          </div>

          <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                  <h3 class="card-title">Progress Report 1 10%</h3>
                </div>
                  <div class="card-body">
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="iteration1">Progress of Iteration 1</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="iteration1" id="iteration1" value="0" required>
                            <label class="form-check-label" for="iteration1">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="iteration1" id="iteration1" value="1">
                            <label class="form-check-label" for="iteration1">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="iteration1" id="iteration1" value="2">
                            <label class="form-check-label" for="iteration1">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="iteration1" id="iteration1" value="3">
                            <label class="form-check-label" for="iteration1">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="iteration1" id="iteration1" value="4">
                            <label class="form-check-label" for="iteration1">4</label>
                          </div>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="iteration2">Progress of Iteration 2</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="iteration2" id="iteration2" value="0" required>
                            <label class="form-check-label" for="iteration2">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="iteration2" id="iteration2" value="1">
                            <label class="form-check-label" for="iteration2">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="iteration2" id="iteration2" value="2">
                            <label class="form-check-label" for="iteration2">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="iteration2" id="iteration2" value="3">
                            <label class="form-check-label" for="iteration2">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="iteration2" id="iteration2" value="4">
                            <label class="form-check-label" for="iteration2">4</label>
                          </div>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="writing">Writing Style, Format and Clarity</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="writing" id="writing" value="0" required>
                            <label class="form-check-label" for="writing">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="writing" id="writing" value="1">
                            <label class="form-check-label" for="writing">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="writing" id="writing" value="2">
                            <label class="form-check-label" for="writing">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="writing" id="writing" value="3">
                            <label class="form-check-label" for="writing">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="writing" id="writing" value="4">
                            <label class="form-check-label" for="writing">4</label>
                          </div>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="citation">Citation and References</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="citation" id="citation" value="0" required>
                            <label class="form-check-label" for="citation">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="citation" id="citation" value="1">
                            <label class="form-check-label" for="citation">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="citation" id="citation" value="2">
                            <label class="form-check-label" for="citation">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="citation" id="citation" value="3">
                            <label class="form-check-label" for="citation">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="citation" id="citation" value="4">
                            <label class="form-check-label" for="citation">4</label>
                          </div>
                        </div>
                    </div>
                  </div>
              </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 pt-4 ">
            <div class="card h-100">
                <div class="card-header">
                  <h3 class="card-title">Progress Project 10%</h3>
                  <!-- <h3 class="card-title">Progress Report 2 10%</h3> -->
                </div>
                  <div class="card-body">
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="iteration3">Progress Iteration 3</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="iteration3" id="iteration3" value="0" required>
                            <label class="form-check-label" for="iteration3">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="iteration3" id="iteration3" value="1">
                            <label class="form-check-label" for="iteration3">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="iteration3" id="iteration3" value="2">
                            <label class="form-check-label" for="iteration3">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="iteration3" id="iteration3" value="3">
                            <label class="form-check-label" for="iteration3">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="iteration3" id="iteration3" value="4">
                            <label class="form-check-label" for="iteration3">4</label>
                          </div>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="iteration4">Progress Iteration 4</label>
                        <div class="col-md-6 pt-1">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="iteration4" id="iteration4" value="0" required>
                            <label class="form-check-label" for="iteration4">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="iteration4" id="iteration4" value="1">
                            <label class="form-check-label" for="iteration4">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="iteration4" id="iteration4" value="2">
                            <label class="form-check-label" for="iteration4">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="iteration4" id="iteration4" value="3">
                            <label class="form-check-label" for="iteration4">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="iteration4" id="iteration4" value="4">
                            <label class="form-check-label" for="iteration4">4</label>
                          </div>
                         
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="writing2">Writing Style, Format and Clarity</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="writing2" id="writing2" value="0" required>
                            <label class="form-check-label" for="writing2">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="writing2" id="writing2" value="1">
                            <label class="form-check-label" for="writing2">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="writing2" id="writing2" value="2">
                            <label class="form-check-label" for="writing2">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="writing2" id="writing2" value="3">
                            <label class="form-check-label" for="writing2">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="writing2" id="writing2" value="4">
                            <label class="form-check-label" for="writing2">4</label>
                          </div>
                         
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="citation2">Citation and References</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="citation2" id="citation2" value="0" required>
                            <label class="form-check-label" for="citation2">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="citation2" id="citation2" value="1">
                            <label class="form-check-label" for="citation2">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="citation2" id="citation2" value="2">
                            <label class="form-check-label" for="citation2">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="citation2" id="citation2" value="3">
                            <label class="form-check-label" for="citation2">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="citation2" id="citation2" value="4">
                            <label class="form-check-label" for="citation2">4</label>
                          </div>
                         
                        </div>
                    </div>
                  </div>
              </div>
          </div>
        
          
          <div class="col-md-6 pt-4">
            <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Final Report 30%</h3>
                </div>
                  <div class="card-body">
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="abstract">Abstract</label>
                        <div class="col-md-6 pt-1">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="abstract" id="abstract" value="0" required>
                            <label class="form-check-label" for="abstract">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="abstract" id="abstract" value="1">
                            <label class="form-check-label" for="abstract">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="abstract" id="abstract" value="2">
                            <label class="form-check-label" for="abstract">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="abstract" id="abstract" value="3">
                            <label class="form-check-label" for="abstract">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="abstract" id="abstract" value="4">
                            <label class="form-check-label" for="abstract">4</label>
                          </div>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="completei1">Completeness of iteration 1</label>
                        <div class="col-md-6 pt-1">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="completei1" id="completei1" value="0" required>
                            <label class="form-check-label" for="completei1">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="completei1" id="completei1" value="1">
                            <label class="form-check-label" for="completei1">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="completei1" id="completei1" value="2">
                            <label class="form-check-label" for="completei1">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="completei1" id="completei1" value="3">
                            <label class="form-check-label" for="completei1">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="completei1" id="completei1" value="4">
                            <label class="form-check-label" for="completei1">4</label>
                          </div>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="completei2">Completeness of iteration 2</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="completei2" id="completei2" value="0" required>
                            <label class="form-check-label" for="completei2">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="completei2" id="completei2" value="1">
                            <label class="form-check-label" for="completei2">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="completei2" id="completei2" value="2">
                            <label class="form-check-label" for="completei2">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="completei2" id="completei2" value="3">
                            <label class="form-check-label" for="completei2">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="completei2" id="completei2" value="4">
                            <label class="form-check-label" for="completei2">4</label>
                          </div>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="completei3">Completeness of iteration 3</label>
                        <div class="col-md-6 pt-1">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="completei3" id="completei3" value="0" required>
                            <label class="form-check-label" for="completei3">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="completei3" id="completei3" value="1">
                            <label class="form-check-label" for="completei3">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="completei3" id="completei3" value="2">
                            <label class="form-check-label" for="completei3">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="completei3" id="completei3" value="3">
                            <label class="form-check-label" for="completei3">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="completei3" id="completei3" value="4">
                            <label class="form-check-label" for="completei3">4</label>
                          </div>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="completei4">Completeness of iteration 4</label>
                        <div class="col-md-6 pt-1">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="completei4" id="completei4" value="0" required>
                            <label class="form-check-label" for="completei4">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="completei4" id="completei4" value="1">
                            <label class="form-check-label" for="completei4">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="completei4" id="completei4" value="2">
                            <label class="form-check-label" for="completei4">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="completei4" id="completei4" value="3">
                            <label class="form-check-label" for="completei4">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="completei4" id="completei4" value="4">
                            <label class="form-check-label" for="completei4">4</label>
                          </div>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="writing3">Writing Style, Format and Clarity</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="writing3" id="writing3" value="0" required>
                            <label class="form-check-label" for="writing3">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="writing3" id="writing3" value="1">
                            <label class="form-check-label" for="writing3">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="writing3" id="writing3" value="2">
                            <label class="form-check-label" for="writing3">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="writing3" id="writing3" value="3">
                            <label class="form-check-label" for="writing3">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="writing3" id="writing3" value="4">
                            <label class="form-check-label" for="writing3">4</label>
                          </div>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="citation3">Citation and References</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="citation3" id="citation3" value="0" required>
                            <label class="form-check-label" for="citation3">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="citation3" id="citation3" value="1">
                            <label class="form-check-label" for="citation3">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="citation3" id="citation3" value="2">
                            <label class="form-check-label" for="citation3">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="citation3" id="citation3" value="3">
                            <label class="form-check-label" for="citation3">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="citation3" id="citation3" value="4">
                            <label class="form-check-label" for="citation3">4</label>
                          </div>
                        </div>
                    </div>
                  </div>
              </div>
            </div>
        </div>

        <div class="row">
          <div class="col-md-6 pt-4">
            <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Initial Iteration Progress 25%</h3>
                </div>
                  <div class="card-body">
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="architecture">System Flow and Architecture</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="architecture" id="architecture" value="0" required>
                            <label class="form-check-label" for="architecture">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="architecture" id="architecture" value="1">
                            <label class="form-check-label" for="architecture">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="architecture" id="architecture" value="2">
                            <label class="form-check-label" for="architecture">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="architecture" id="architecture" value="3">
                            <label class="form-check-label" for="architecture">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="architecture" id="architecture" value="4">
                            <label class="form-check-label" for="architecture">4</label>
                          </div>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="requirement">User Requirement</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="requirement" id="requirement" value="0" required>
                            <label class="form-check-label" for="requirement">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="requirement" id="requirement" value="1">
                            <label class="form-check-label" for="requirement">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="requirement" id="requirement" value="2">
                            <label class="form-check-label" for="requirement">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="requirement" id="requirement" value="3">
                            <label class="form-check-label" for="requirement">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="requirement" id="requirement" value="4">
                            <label class="form-check-label" for="requirement">4</label>
                          </div>
                         
                        </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="database">Database Design</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="database" id="database" value="0" required>
                            <label class="form-check-label" for="database">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="database" id="database" value="1">
                            <label class="form-check-label" for="database">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="database" id="database" value="2">
                            <label class="form-check-label" for="database">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="database" id="database" value="3">
                            <label class="form-check-label" for="database">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="database" id="database" value="4">
                            <label class="form-check-label" for="database">4</label>
                          </div>
                          
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="uml">UML Design</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="uml" id="uml" value="0" required>
                            <label class="form-check-label" for="uml">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="uml" id="uml" value="1">
                            <label class="form-check-label" for="uml">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="uml" id="uml" value="2">
                            <label class="form-check-label" for="uml">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="uml" id="uml" value="3">
                            <label class="form-check-label" for="uml">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="uml" id="uml" value="4">
                            <label class="form-check-label" for="uml">4</label>
                          </div>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="gantt">Gantt Chart</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gantt" id="gantt" value="0" required>
                            <label class="form-check-label" for="gantt">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gantt" id="gantt" value="1">
                            <label class="form-check-label" for="gantt">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gantt" id="gantt" value="2">
                            <label class="form-check-label" for="gantt">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gantt" id="gantt" value="3">
                            <label class="form-check-label" for="gantt">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gantt" id="gantt" value="4">
                            <label class="form-check-label" for="gantt">4</label>
                          </div>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="interface">Interface Design</label>
                        <div class="col-md-6 pt-1">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="interface" id="interface" value="0" required>
                            <label class="form-check-label" for="interface">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="interface" id="interface" value="1">
                            <label class="form-check-label" for="interface">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="interface" id="interface" value="2">
                            <label class="form-check-label" for="interface">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="interface" id="interface" value="3">
                            <label class="form-check-label" for="interface">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="interface" id="interface" value="4">
                            <label class="form-check-label" for="interface">4</label>
                          </div>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="element">Course Domain Element</label>
                        <div class="col-md-6 pt-1">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="element" id="element" value="0" required>
                            <label class="form-check-label" for="element">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="element" id="element" value="1">
                            <label class="form-check-label" for="element">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="element" id="element" value="2">
                            <label class="form-check-label" for="element">2</label>
                          </div>  
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="element" id="element" value="3">
                            <label class="form-check-label" for="element">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="element" id="element" value="4">
                            <label class="form-check-label" for="element">4</label>
                          </div>
                        </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="testing">Testing for Iteration 1</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="testing" id="testing" value="0" required>
                            <label class="form-check-label" for="testing">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="testing" id="testing" value="1">
                            <label class="form-check-label" for="testing">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="testing" id="testing" value="2">
                            <label class="form-check-label" for="testing">2</label>
                          </div>  
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="testing" id="testing" value="3">
                            <label class="form-check-label" for="testing">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="testing" id="testing" value="4">
                            <label class="form-check-label" for="testing">4</label>
                          </div>
                        </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="coding1">Coding Programming for Iteration 1</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="coding1" id="coding1" value="0" required>
                            <label class="form-check-label" for="coding1">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="coding1" id="coding1" value="1">
                            <label class="form-check-label" for="coding1">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="coding1" id="coding1" value="2">
                            <label class="form-check-label" for="coding1">2</label>
                          </div>  
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="coding1" id="coding1" value="3">
                            <label class="form-check-label" for="coding1">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="coding1" id="coding1" value="4">
                            <label class="form-check-label" for="coding1">4</label>
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
