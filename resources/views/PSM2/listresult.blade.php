<!-- branch AD -->
@extends('utility.layout')

@section('navbar')
	@include('utility.navbar')
@endsection('navbar')
@section('content')
<html>

<div class="container mt-5 mb-5">
    @if(Session::has('success'))
            <div class="alert alert-success">
                <div class="flash-message">
                    {!! Session::get('success') !!}
                </div>
            </div>
    @endif
	<div class="card">
		<div class="card-header">Result PSM2 </div>
		<div class="card-body">
            <table class="table table-striped table-bordered table-hover" id="resultPelajar">
                <thead>
                    <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Supervisor</th>
                    <th>Panel 1</th>
                    <th>Panel 2</th>
                    <th>Coordinator</th>
                    <th>Total</th>
                    <th></th>
                    </tr>
                </thead>
                    <tbody>
                    </tbody>
            </table>
		</div>
	</div>
</div>
@endsection('content')
</html>

@section('script')
<script>
    var data = @json($totalResult);
    var result = data;
    if(result.length == undefined){
        result = [];
        Object.values(data).forEach(student => {
            result.push(student);
        });
    }
    console.log(result);
    console.log('testing');
    $(document).ready( function () {
        var dtresultPelajar = $('#resultPelajar').DataTable({
            "data" : result,
            "columnDefs" : [
                {
                    "data": null,
                    "targets" : [0],
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    "data": "name",
                    "targets" : [1],
                },
                {
                    "data": "sv",
                    "targets" : [2],
                },
                {
                    "data": "panel1",
                    "targets" : [3],
                },
                {
                    "data": "panel2",
                    "targets" : [4],
                },
                {
                    "data": "coordinator",
                    "targets" : [5],
                    // "defaultContent": '',
                },
                {
                    "data": "totalmarks",
                    "targets" : [6],
                },
                {
                    "data": null,
                    "targets" : [7],
                    orderable : false,
                    defaultContent: '<div class="btn-group w-100" role="group" aria-label="Basic example">' +
                                    '<button type="button" class="btn btn-outline-primary left">View</button>' +
                                    // '<button type="button" class="btn btn-outline-danger mid">Delete</button>' +
                                     // '<button type="button" class="btn btn-outline-primary right">Right</button>' +
                                    '</div>'
                },
                
            ],
        });
        console.log(dtresultPelajar);

        $("#resultPelajar tbody").on('click', '.left', function () {
            var data = dtresultPelajar.row($(this).parents('tr')).data();
            console.log('student',JSON.stringify(data));
            
            var id = data.id;

            window.location.href = "{{ route('viewresultPSM2', ':student') }}".replace(':student', id);
        })
        
    } );

    
</script>
<script src="{{ asset('assets/js/modal.js') }}"></script>

@endsection('script')

