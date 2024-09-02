@extends('utility.layout')

@section('navbar')
	@include('utility.navbar')
@endsection('navbar')

@section('content')

    <div class="container">
        @if($message = Session::get('success'))
        <div class="alert alert-info">
        {{ $message }}
        </div>
        @endif
      <form method="post" action="{{ route('markahPSM2')}}">
        @csrf
        <input type="hidden" name="id" value="{{ $id }}">
        <div class="row">
          <div class="col-md-6">
            <div class="card h-100">
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
                          <input class="form-check-input" type="radio" name="logbook" id="logbook" value="1">
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
                      <label class="col-md-6 col-form-label" for="meeting">Meeting Frequency</label>
                      <div class="col-md-6 pt-1">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="meeting" id="meeting" value="0" required>
                          <label class="form-check-label" for="meeting">0</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="meeting" id="meeting" value="1">
                          <label class="form-check-label" for="meeting">1</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="meeting" id="meeting" value="2">
                          <label class="form-check-label" for="meeting">2</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="meeting" id="meeting" value="3">
                          <label class="form-check-label" for="meeting">3</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="meeting" id="meeting" value="4">
                          <label class="form-check-label" for="meeting">4</label>
                        </div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="ethic">ethic Ethic</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="ethic" id="ethic" value="0" required>
                            <label class="form-check-label" for="ethic">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="ethic" id="ethic" value="1">
                            <label class="form-check-label" for="ethic">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="ethic" id="ethic" value="2">
                            <label class="form-check-label" for="ethic">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="ethic" id="ethic" value="3">
                            <label class="form-check-label" for="ethic">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="ethic" id="ethic" value="4">
                            <label class="form-check-label" for="ethic">4</label>
                          </div>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="independent">Self-Reliance / Independent</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="independent" id="independent" value="0" required>
                            <label class="form-check-label" for="independent">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="independent" id="independent" value="1">
                            <label class="form-check-label" for="independent">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="independent" id="independent" value="2">
                            <label class="form-check-label" for="independent">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="independent" id="independent" value="3">
                            <label class="form-check-label" for="independent">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="independent" id="independent" value="4">
                            <label class="form-check-label" for="independent">4</label>
                          </div>
                        </div>
                    </div>
                  </div>
              </div>
          </div>

          <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                  <h3 class="card-title">Progress Report 5%</h3>
                </div>
                  <div class="card-body">
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="improvement">Improvement of PSM1 Report (Chapter 1 to 3)</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="improvement" id="improvement" value="0" required>
                            <label class="form-check-label" for="improvement">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="improvement" id="improvement" value="1">
                            <label class="form-check-label" for="improvement">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="improvement" id="improvement" value="2">
                            <label class="form-check-label" for="improvement">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="improvement" id="improvement" value="3">
                            <label class="form-check-label" for="improvement">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="improvement" id="improvement" value="4">
                            <label class="form-check-label" for="improvement">4</label>
                          </div>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="revisedc4">Revised of Chapter 4 (Iteration 2-3)</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="revisedc4" id="revisedc4" value="0" required>
                            <label class="form-check-label" for="revisedc4">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="revisedc4" id="revisedc4" value="1">
                            <label class="form-check-label" for="revisedc4">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="revisedc4" id="revisedc4" value="2">
                            <label class="form-check-label" for="revisedc4">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="revisedc4" id="revisedc4" value="3">
                            <label class="form-check-label" for="revisedc4">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="revisedc4" id="revisedc4" value="4">
                            <label class="form-check-label" for="revisedc4">4</label>
                          </div>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="progressc5">Progres of Chapter 5 (Iteration 4-6)</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="progressc5" id="progressc5" value="0" required>
                            <label class="form-check-label" for="progressc5">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="progressc5" id="progressc5" value="1">
                            <label class="form-check-label" for="progressc5">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="progressc5" id="progressc5" value="2">
                            <label class="form-check-label" for="progressc5">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="progressc5" id="progressc5" value="3">
                            <label class="form-check-label" for="progressc5">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="progressc5" id="progressc5" value="4">
                            <label class="form-check-label" for="progressc5">4</label>
                          </div>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="writing">Writing style, format and clarity</label>
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
          <!-- project progress 1 -->
          <div class="col-md-6 pt-4 ">
            <div class="card h-100">
                <div class="card-header">
                  <h3 class="card-title">Project Progress 1 (5%)</h3>
                </div>
                  <div class="card-body">
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="iteration3">Milestone achieved until iteration 3</label>
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
                      <label class="col-md-6 col-form-label" for="execution">Project execution follows planning</label>
                        <div class="col-md-6 pt-1">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="execution" id="execution" value="0" required>
                            <label class="form-check-label" for="execution">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="execution" id="execution" value="1">
                            <label class="form-check-label" for="execution">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="execution" id="execution" value="2">
                            <label class="form-check-label" for="execution">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="execution" id="execution" value="3">
                            <label class="form-check-label" for="execution">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="execution" id="execution" value="4">
                            <label class="form-check-label" for="execution">4</label>
                          </div>
                         
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="knowledge">Knowledge and skill improvement</label>
                        <div class="col-md-6 pt-1">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="knowledge" id="knowledge" value="0" required>
                            <label class="form-check-label" for="knowledge">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="knowledge" id="knowledge" value="1">
                            <label class="form-check-label" for="knowledge">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="knowledge" id="knowledge" value="2">
                            <label class="form-check-label" for="knowledge">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="knowledge" id="knowledge" value="3">
                            <label class="form-check-label" for="knowledge">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="knowledge" id="knowledge" value="4">
                            <label class="form-check-label" for="knowledge">4</label>
                          </div>
                         
                        </div>
                    </div>
                    
                    
                  </div>
              </div>
          </div>
          <!-- project progress 2 -->
          <div class="col-md-6 pt-4 ">
            <div class="card h-100">
                <div class="card-header">
                  <h3 class="card-title">Project Progress 2 (5%)</h3>
                </div>
                  <div class="card-body">
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="iteration6">Milestone achieved until iteration 6</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="iteration6" id="iteration6" value="0" required>
                            <label class="form-check-label" for="iteration6">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="iteration6" id="iteration6" value="1">
                            <label class="form-check-label" for="iteration6">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="iteration6" id="iteration6" value="2">
                            <label class="form-check-label" for="iteration6">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="iteration6" id="iteration6" value="3">
                            <label class="form-check-label" for="iteration6">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="iteration6" id="iteration6" value="4">
                            <label class="form-check-label" for="iteration6">4</label>
                          </div>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="execution2">Project execution follows planning</label>
                        <div class="col-md-6 pt-1">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="execution2" id="execution2" value="0" required>
                            <label class="form-check-label" for="execution2">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="execution2" id="execution2" value="1">
                            <label class="form-check-label" for="execution2">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="execution2" id="execution2" value="2">
                            <label class="form-check-label" for="execution2">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="execution2" id="execution2" value="3">
                            <label class="form-check-label" for="execution2">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="execution2" id="execution2" value="4">
                            <label class="form-check-label" for="execution2">4</label>
                          </div>
                         
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="knowledge2">Knowledge and skill improvement</label>
                        <div class="col-md-6 pt-1">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="knowledge2" id="knowledge2" value="0" required>
                            <label class="form-check-label" for="knowledge2">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="knowledge2" id="knowledge2" value="1">
                            <label class="form-check-label" for="knowledge2">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="knowledge2" id="knowledge2" value="2">
                            <label class="form-check-label" for="knowledge2">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="knowledge2" id="knowledge2" value="3">
                            <label class="form-check-label" for="knowledge2">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="knowledge2" id="knowledge2" value="4">
                            <label class="form-check-label" for="knowledge2">4</label>
                          </div>
                         
                        </div>
                    </div>
                  </div>
              </div>
          </div>
          
        

        <div class="row">
        <!-- Final Report System Development 25% -->
          <div class="col-md-6 pt-4">
            <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Final Report System Development 25%</h3>
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
                      <label class="col-md-6 col-form-label" for="introduction">Introduction</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="introduction" id="introduction" value="0" required>
                            <label class="form-check-label" for="introduction">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="introduction" id="introduction" value="1">
                            <label class="form-check-label" for="introduction">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="introduction" id="introduction" value="2">
                            <label class="form-check-label" for="introduction">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="introduction" id="introduction" value="3">
                            <label class="form-check-label" for="introduction">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="introduction" id="introduction" value="4">
                            <label class="form-check-label" for="introduction">4</label>
                          </div>
                         
                        </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="literature">Literature Review</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="literature" id="literature" value="0" required>
                            <label class="form-check-label" for="literature">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="literature" id="literature" value="1">
                            <label class="form-check-label" for="literature">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="literature" id="literature" value="2">
                            <label class="form-check-label" for="literature">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="literature" id="literature" value="3">
                            <label class="form-check-label" for="literature">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="literature" id="literature" value="4">
                            <label class="form-check-label" for="literature">4</label>
                          </div>
                          
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="methodology">Methodology</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="methodology" id="methodology" value="0" required>
                            <label class="form-check-label" for="methodology">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="methodology" id="methodology" value="1">
                            <label class="form-check-label" for="methodology">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="methodology" id="methodology" value="2">
                            <label class="form-check-label" for="methodology">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="methodology" id="methodology" value="3">
                            <label class="form-check-label" for="methodology">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="methodology" id="methodology" value="4">
                            <label class="form-check-label" for="methodology">4</label>
                          </div>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="revised">Revised System Design</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="revised" id="revised" value="0" required>
                            <label class="form-check-label" for="revised">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="revised" id="revised" value="1">
                            <label class="form-check-label" for="revised">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="revised" id="revised" value="2">
                            <label class="form-check-label" for="revised">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="revised" id="revised" value="3">
                            <label class="form-check-label" for="revised">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="revised" id="revised" value="4">
                            <label class="form-check-label" for="revised">4</label>
                          </div>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="implementation">Revised System Implementation</label>
                        <div class="col-md-6 pt-1">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="implementation" id="implementation" value="0" required>
                            <label class="form-check-label" for="implementation">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="implementation" id="implementation" value="1">
                            <label class="form-check-label" for="implementation">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="implementation" id="implementation" value="2">
                            <label class="form-check-label" for="implementation">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="implementation" id="implementation" value="3">
                            <label class="form-check-label" for="implementation">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="implementation" id="implementation" value="4">
                            <label class="form-check-label" for="implementation">4</label>
                          </div>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="revisedt">Revised Testing</label>
                        <div class="col-md-6 pt-1">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="revisedt" id="revisedt" value="0" required>
                            <label class="form-check-label" for="revisedt">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="revisedt" id="revisedt" value="1">
                            <label class="form-check-label" for="revisedt">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="revisedt" id="revisedt" value="2">
                            <label class="form-check-label" for="revisedt">2</label>
                          </div>  
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="revisedt" id="revisedt" value="3">
                            <label class="form-check-label" for="revisedt">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="revisedt" id="revisedt" value="4">
                            <label class="form-check-label" for="revisedt">4</label>
                          </div>
                        </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="conclusion">Conclusion (Discussion and Analysis)</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="conclusion" id="conclusion" value="0" required>
                            <label class="form-check-label" for="conclusion">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="conclusion" id="conclusion" value="1">
                            <label class="form-check-label" for="conclusion">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="conclusion" id="conclusion" value="2">
                            <label class="form-check-label" for="conclusion">2</label>
                          </div>  
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="conclusion" id="conclusion" value="3">
                            <label class="form-check-label" for="conclusion">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="conclusion" id="conclusion" value="4">
                            <label class="form-check-label" for="conclusion">4</label>
                          </div>
                        </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="writing2">Writing style, format and clarity </label>
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
                      <label class="col-md-6 col-form-label" for="citation">Citation and references</label>
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
          
          <!-- Short Paper 5% -->
          <div class="col-md-6 pt-4">
            <div class="card h-100">
                <div class="card-header">
                  <h3 class="card-title">Short Paper 5%</h3>
                </div>
                  <div class="card-body">
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="originality">Originality of the paper</label>
                        <div class="col-md-6 pt-1">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="originality" id="originality" value="0" required>
                            <label class="form-check-label" for="originality">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="originality" id="originality" value="1">
                            <label class="form-check-label" for="originality">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="originality" id="originality" value="2">
                            <label class="form-check-label" for="originality">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="originality" id="originality" value="3">
                            <label class="form-check-label" for="originality">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="originality" id="originality" value="4">
                            <label class="form-check-label" for="originality">4</label>
                          </div>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="technical">Technical soundness</label>
                        <div class="col-md-6 pt-1">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="technical" id="technical" value="0" required>
                            <label class="form-check-label" for="technical">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="technical" id="technical" value="1">
                            <label class="form-check-label" for="technical">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="technical" id="technical" value="2">
                            <label class="form-check-label" for="technical">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="technical" id="technical" value="3">
                            <label class="form-check-label" for="technical">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="technical" id="technical" value="4">
                            <label class="form-check-label" for="technical">4</label>
                          </div>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="clarity">Clarity of presentation</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="clarity" id="clarity" value="0" required>
                            <label class="form-check-label" for="clarity">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="clarity" id="clarity" value="1">
                            <label class="form-check-label" for="clarity">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="clarity" id="clarity" value="2">
                            <label class="form-check-label" for="clarity">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="clarity" id="clarity" value="3">
                            <label class="form-check-label" for="clarity">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="clarity" id="clarity" value="4">
                            <label class="form-check-label" for="clarity">4</label>
                          </div>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="format">Format, citation and references</label>
                        <div class="col-md-6 pt-1">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="format" id="format" value="0" required>
                            <label class="form-check-label" for="format">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="format" id="format" value="1">
                            <label class="form-check-label" for="format">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="format" id="format" value="2">
                            <label class="form-check-label" for="format">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="format" id="format" value="3">
                            <label class="form-check-label" for="format">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="format" id="format" value="4">
                            <label class="form-check-label" for="format">4</label>
                          </div>
                        </div>
                    </div>
                  </div>
              </div>
            </div> 
          </div>
           
        </div>

        <div class="row">
          <!-- System 30% -->
          <div class="col-md-6 pt-4">
            <div class="card">
                <div class="card-header">
                  <h3 class="card-title">System 30%</h3>
                </div>
                  <div class="card-body">
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="scope">Full aims, objectives and scopes</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="scope" id="scope" value="0" required>
                            <label class="form-check-label" for="scope">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="scope" id="scope" value="1">
                            <label class="form-check-label" for="scope">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="scope" id="scope" value="2">
                            <label class="form-check-label" for="scope">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="scope" id="scope" value="3">
                            <label class="form-check-label" for="scope">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="scope" id="scope" value="4">
                            <label class="form-check-label" for="scope">4</label>
                          </div>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="coding">Coding/programming</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="coding" id="coding" value="0" required>
                            <label class="form-check-label" for="coding">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="coding" id="coding" value="1">
                            <label class="form-check-label" for="coding">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="coding" id="coding" value="2">
                            <label class="form-check-label" for="coding">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="coding" id="coding" value="3">
                            <label class="form-check-label" for="coding">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="coding" id="coding" value="4">
                            <label class="form-check-label" for="coding">4</label>
                          </div>
                         
                        </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="completeness">Completeness</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="completeness" id="completeness" value="0" required>
                            <label class="form-check-label" for="completeness">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="completeness" id="completeness" value="1">
                            <label class="form-check-label" for="completeness">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="completeness" id="completeness" value="2">
                            <label class="form-check-label" for="completeness">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="completeness" id="completeness" value="3">
                            <label class="form-check-label" for="completeness">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="completeness" id="completeness" value="4">
                            <label class="form-check-label" for="completeness">4</label>
                          </div>
                          
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="service">Enhanced service and/or commercialization</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="service" id="service" value="0" required>
                            <label class="form-check-label" for="service">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="service" id="service" value="1">
                            <label class="form-check-label" for="service">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="service" id="service" value="2">
                            <label class="form-check-label" for="service">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="service" id="service" value="3">
                            <label class="form-check-label" for="service">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="service" id="service" value="4">
                            <label class="form-check-label" for="service">4</label>
                          </div>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="elements">Course Domain Elements</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="elements" id="elements" value="0" required>
                            <label class="form-check-label" for="elements">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="elements" id="elements" value="1">
                            <label class="form-check-label" for="elements">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="elements" id="elements" value="2">
                            <label class="form-check-label" for="elements">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="elements" id="elements" value="3">
                            <label class="form-check-label" for="elements">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="elements" id="elements" value="4">
                            <label class="form-check-label" for="elements">4</label>
                          </div>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="interface">Creativity/interface and user experience</label>
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
                  </div>
              </div>
          </div>
                   
            <div class="form-group pt-2">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>

      </form>
    </div> <!-- container -->
    
@endsection('content')
