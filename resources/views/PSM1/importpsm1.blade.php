@extends('utility.layout')

@section('navbar')
	@include('utility.navbar')
@endsection('navbar')

@section('content')     
<div class="container">
    <div class="card mt-3 mb-3">
        <div class="card-header text-center">
            <h4>Import Student PSM1</h4>
        </div>
        <div class="card-body">
            <form id="fileUpload" action="{{ route('studentspsm1.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" class="form-control">
                <span class="text-danger error collapse">No Files!</span>
                <br>
                <button class="btn btn-primary" type="submit">Import Student Data</button>
            </form>
  
            <table class="table table-bordered mt-3">
                <tr>
                    <th colspan="3">
                        List Of Students
                    </th>
                </tr>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
                @foreach($students as $student)
                <tr>
                    <td>{{ $student->id }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->email }}</td>
                </tr>
                @endforeach
            </table>
  
        </div>
    </div>
</div>
    
@endsection('content')

@section('script')
<script>
//     $(document).ready( function () {
//         console.log("no files selected");
// }
//     } );

    $("#fileUpload").on('click', 'button:submit', function (event) {
        event.preventDefault();
        if( $("#fileUpload").find('input:file').get(0).files.length > 0 ){
            $("#fileUpload").find('input:file').siblings('.error').addClass('collapse')
            $("#fileUpload").submit()
        }
        else {
            // popup error
            $("#fileUpload").find('input:file').siblings('.error').removeClass('collapse')
        }
    })
</script>
@endsection('script')



