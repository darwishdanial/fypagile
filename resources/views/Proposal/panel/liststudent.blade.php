
@extends('utility.layout')

@section('navbar')
	@include('utility.navbar')
@endsection('navbar')
@section('content')
<html>

<div class="container mt-5 mb-5">
	<div class="card">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
		<div class="card-header">Senarai Proposal</div>
		<div class="card-body">
            <table class="table table-striped table-bordered table-hover" id="senaraiPelajar">
                <thead>
                    <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Matric</th>
                    <th>Actions</th>
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
    var students = @json($students);
    console.log(students);
    console.log('testing');
    $(document).ready( function () {
        var dtSenaraiPelajar = $('#senaraiPelajar').DataTable({
            "data" : students,
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
                    "data": "matric",
                    "targets" : [2],
                },
                {
                    "data": null,
                    "targets" : [3],
                    orderable : false,
                    defaultContent: '<div class="btn-group w-100" role="group" aria-label="Basic example">' +
                                    '<button type="button" class="btn btn-outline-primary left">Grade</button>' +
                                    // '<button type="button" class="btn btn-outline-danger mid">Delete</button>' +
                                     // '<button type="button" class="btn btn-outline-primary right">Right</button>' +
                                    '</div>'
                },
            ],
        });
        console.log(dtSenaraiPelajar);

        $("#senaraiPelajar tbody").on('click', '.left', function () {
            var data = dtSenaraiPelajar.row($(this).parents('tr')).data();
            console.log('dah tekan');
            console.log('student',JSON.stringify(data));
            
            var id = data.id;

            window.location.href = "{{ route('gradeproposal', ['id' => ':id']) }}".replace(':id', id);
        })

        // $("#senaraiPelajar tbody").on('click', '.right', function () {
        //     var data = dtsenaraiPelajar.row($(this).parents('tr')).data();
        //     console.log('right',data);
        // })
    } );

    function getStudents(svId) {
        var metadata = {
            svId : svId
        }

        $.ajax({
                type:'GET',
                url:"{{ route('get.students') }}",
                data: metadata,
                success: function(res){
                    console.log(res);
                    // var unassignStudents = $.map(res.all, function (element) {
                    //     element.assigned = false; return element;
                    // })

                    // var assignStudents = $.map(res.assigned, function (element) { 
                    //     element.assigned = true; return element; 
                    // });

                    // var students = [...assignStudents, ...unassignStudents]
                    var students = res
                    var dtListStudent = $('#listStudent').DataTable()
                    dtListStudent.clear().rows.add(students).draw()
                    $("#studentModal").modal('toggle')
                },
                error: function(res){
                    console.log('Error: ' ,  res.statusText);
                    console.log('Status: ' , res.status);
                },
            });
    }
</script>
<script src="{{ asset('assets/js/modal.js') }}"></script>

@endsection('script')

