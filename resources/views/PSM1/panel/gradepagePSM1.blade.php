@extends('utility.layout')

@section('navbar')
	@include('utility.navbar')
@endsection('navbar')

@section('content')


    <div class="container">
      @if(Session::has('success'))
              <div class="alert alert-success">
                  <div class="flash-message">
                      {!! Session::get('success') !!}
                  </div>
              </div>
          @endif
      
      <form method="post" action="{{ route('markahPSM1Panel')}}">
        @csrf
        <input type="hidden" name="id" value="{{ $id }}">
        <div class="row">
          <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                  <h3 class="card-title">Final Report System Development 30%</h3>
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
                      <label class="col-md-6 col-form-label" for="completei1">Completeness of Chapter 1</label>
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
                      <label class="col-md-6 col-form-label" for="completei2">Completeness of Chapter 2</label>
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
                      <label class="col-md-6 col-form-label" for="completei3">Completeness of Chapter 3</label>
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
                      <label class="col-md-6 col-form-label" for="completei4">Completeness of Chapter 4 (Iteration 1)</label>
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
                          <input class="form-check-input" type="radio" name="writing3" id="writing3" value="1" >
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
                  </div><!-- card body -->
              </div>
          </div><!-- col-md-6 -->

          <div class="col-md-6">
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
                      <label class="col-md-6 col-form-label" for="uml">UML Design for Iteration 1</label>
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
                      <label class="col-md-6 col-form-label" for="gantt">Burndown Chart for Iteration 1</label>
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
                      <label class="col-md-6 col-form-label" for="interface">Interface Design for Iteration 1</label>
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

        </div> <!-- row -->

        <div class="row">
          <div class="col-md-6 pt-4">
            <div class="card h-100">
                <div class="card-header">
                  <h3 class="card-title">Presentation 10%</h3>
                </div><!-- card-header -->
                  <div class="card-body">
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="appearance">Appearance and attitude</label>
                        <div class="col-md-6 pt-1">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="appearance" id="appearance" value="0" required>
                                <label class="form-check-label" for="appearance">0</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="appearance" id="appearance" value="1">
                                <label class="form-check-label" for="appearance">1</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="appearance" id="appearance" value="2">
                                <label class="form-check-label" for="appearance">2</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="appearance" id="appearance" value="3">
                                <label class="form-check-label" for="appearance">3</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="appearance" id="appearance" value="4">
                                <label class="form-check-label" for="appearance">4</label>
                            </div>
                            
                            
                        </div>
                    </div>
                    
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="understanding">Project Understanding</label>
                        <div class="col-md-6 pt-1">
                        <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="understanding" id="understanding" value="0" required>
                                <label class="form-check-label" for="understanding">0</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="understanding" id="understanding" value="1">
                                <label class="form-check-label" for="understanding">1</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="understanding" id="understanding" value="2">
                                <label class="form-check-label" for="understanding">2</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="understanding" id="understanding" value="3">
                                <label class="form-check-label" for="understanding">3</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="understanding" id="understanding" value="4">
                                <label class="form-check-label" for="understanding">4</label>
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
                      <label class="col-md-6 col-form-label" for="question">Question and answer session</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="question" id="question" value="0" required>
                                <label class="form-check-label" for="question">0</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="question" id="question" value="1">
                                <label class="form-check-label" for="question">1</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="question" id="question" value="2">
                                <label class="form-check-label" for="question">2</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="question" id="question" value="3">
                                <label class="form-check-label" for="question">3</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="question" id="question" value="4">
                                <label class="form-check-label" for="question">4</label>
                            </div>
                            </div>
                        </div>
                    </div>

                    
                    
              </div><!-- card-body -->
            </div><!-- card -->   
          </div><!-- col -->   
                <div class="form-group pt-2">
                              <button type="submit" class="btn btn-primary">Submit</button>
                </div>  
        </div><!-- row -->    

            
      </form>
    </div> <!-- container -->
    
@endsection('content')
