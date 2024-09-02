@extends('utility.layout')

@section('navbar')
	@include('utility.navbar')
@endsection('navbar')

@section('content')
<style>
    .container {
        max-width: 1000px;
    }
    
    .push-top {
      margin-top: 50px;
    }

    table {
            border-collapse: collapse;
        }
    th, td {
        border: 1px solid black;
        padding: 8px;
        width: 100px;

    }
</style>
<div class="container">
  <div class="card push-top">
    <div class="card-header">
      Result
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
        
    
        <table>
            <thead>
            <tr>
                <th>Rubric</th>
                <th>Supervision</th>
                <th>Progress Report 1</th>
                <th>Progress Report 2</th>
                <th>Final Report</th>
                <th>Initial Iteration Progress</th>
                <th>Presentation</th>
                <th>Ethics</th>
               
            </tr>
            </thead>
            @foreach($results as $count => $result)
                
                <tbody>
                    <tr>
                        <td>{{$result->type}} </td> <!--Row 1, Cell 1  -->
                        <td>@if($result->supervision != null){{{$result->supervision}}} @else N/A @endif </td> <!--Row 1, Cell 2  -->
                        <td>@if($result->progressreport1 != null){{{$result->progressreport1}}} @else N/A @endif</td> <!--Row 1, Cell 3  -->
                        <td>@if($result->progressreport2 != null){{{$result->progressreport2}}} @else N/A @endif</td> <!--Row 1, Cell 4  -->
                        <td>@if($result->finalreport != null){{{$result->finalreport}}} @else N/A @endif</td> <!--Row 1, Cell 5  -->
                        <td>@if($result->design != null){{{$result->design}}} @else N/A @endif</td> <!--Row 1, Cell 6  -->
                        <td>@if($result->presentation != null){{{$result->presentation}}} @else N/A @endif</td> <!--Row 1, Cell 7  -->
                        <td>@if($result->ethics != null){{{$result->ethics}}} @else N/A @endif</td> <!--Row 1, Cell 8  -->
                        
                    </tr>
                

                </tbody>
            @endforeach
        </table>
   
        
    
    </div>
  </div>
</div>
@endsection