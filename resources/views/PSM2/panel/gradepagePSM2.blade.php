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
      <form method="post" action="{{ route('markahPSM2Panel')}}">
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
                      <label class="col-md-6 col-form-label" for="conclusion">Conclusion (discussion and analysis)</label>
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
                      <label class="col-md-6 col-form-label" for="writing2">Writing style, format and clarity</label>
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

                  </div><!-- card body -->
              </div>
          </div><!-- col-md-6 -->

          <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                  <h3 class="card-title">System 30%</h3>
                </div>
                  <div class="card-body ">
                    <div class="form-group row">
                      <label class="col-md-6 col-form-label" for="scope">Fullfil aims, objectives and scopes</label>
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
                      <label class="col-md-6 col-form-label" for="coding">Coding/Programming</label>
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
                      <label class="col-md-6 col-form-label" for="enhanced">Enhanced service and/or commercialization</label>
                        <div class="col-md-6 pt-1">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="enhanced" id="enhanced" value="0" required>
                            <label class="form-check-label" for="enhanced">0</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="enhanced" id="enhanced" value="1">
                            <label class="form-check-label" for="enhanced">1</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="enhanced" id="enhanced" value="2">
                            <label class="form-check-label" for="enhanced">2</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="enhanced" id="enhanced" value="3">
                            <label class="form-check-label" for="enhanced">3</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="enhanced" id="enhanced" value="4">
                            <label class="form-check-label" for="enhanced">4</label>
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
        <!-- Form content goes here -->
      </div><!-- card-body -->
    </div><!-- card -->
  </div><!-- col -->
  
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
                      <label class="col-md-6 col-form-label" for="technical">Technical Soundness</label>
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
                      <label class="col-md-6 col-form-label" for="clarity">Clarity of Presentation</label>
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
    </div><!-- card -->
  </div><!-- col -->

  <div class="form-group pt-2">
    <button type="submit" class="btn btn-primary">Submit</button>
  </div>  
</div><!-- row -->
 

        
            
      </form>
    </div> <!-- container -->
    
@endsection('content')
