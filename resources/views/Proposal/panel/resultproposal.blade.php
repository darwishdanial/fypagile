<!-- branch AD -->
@extends('utility.layout')

@section('navbar')
	@include('utility.navbar')
@endsection('navbar')
@section('content')
<html>

<div class="container mt-5 mb-5">
	<div class="card">
		<div class="card-header">Result Proposal</div>
		<div class="card-body">
            <table class="table table-striped table-bordered table-hover" id="resultPelajar">
                <thead>
                    <tr>
                    <th>No</th>
                    <th>Student</th>
                    <th>Status</th>
                    <th>Comment</th>
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
    console.log('result',result);
    if(result.length == undefined){
        result = [];
    }
    
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
                    "data": "student_name",
                    "targets" : [1],
                },
                {
                    "data": "approval",
                    "targets" : [2],
                    // "defaultContent": '',
                },
                {
                    "data": "notes",
                    "targets" : [3],
                    // "defaultContent": '',
                },
                
            ],
        });

        $("#resultPelajar tbody").on('click', '.left', function () {
            var data = dtresultPelajar.row($(this).parents('tr')).data();
            console.log('student',JSON.stringify(data));
            
            var id = data.id;

            window.location.href = "{{ route('viewresultPSM1', ':student') }}".replace(':student', id);
        })
        
    } );

    
</script>
<script src="{{ asset('assets/js/modal.js') }}"></script>

@endsection('script')

