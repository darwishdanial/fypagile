<!-- branch AD -->
@extends('utility.layout')

@section('navbar')
	@include('utility.navbar')
@endsection('navbar')
@section('content')
<html>

<div class="container mt-5 mb-5">
	<div class="card">
		<div class="card-header">Senarai Pelajar PSM2</div>
		<div class="card-body">
            <table class="table table-striped table-bordered table-hover" id="senaraiPelajar">
                <thead>
                    <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Matric</th>
                    <th>Course</th>
                    <th>Title</th>
                    <th>Supervisor</th>
                    <th>Panel 1</th>
                    <th>Panel 2</th>
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
                    "data": "course",
                    "targets" : [3],
                },
                {
                    "data": "title",
                    "targets" : [4],
                },
                {
                    "data": "sv_name",
                    "targets" : [5],
                },
                {
                    "data": "panel_name",
                    "targets" : [6],
                },
                {
                    "data": "panel2_name",
                    "targets" : [7],
                },
                {
                    "data": null,
                    "targets" : [8],
                    orderable : false,
                    defaultContent: '<div class="btn-group w-100" role="group" aria-label="Basic example">' +
                                    '<button type="button" class="btn btn-outline-primary left">Edit</button>' +
                                    '<button type="button" class="btn btn-outline-primary mid">Delete</button>' +
                                    // '<button type="button" class="btn btn-outline-primary right">Right</button>' +
                                    '</div>'
                },
            ],
        });
        console.log(dtSenaraiPelajar);

        $("#senaraiPelajar tbody").on('click', '.left', function () {
            var data = dtSenaraiPelajar.row($(this).parents('tr')).data();
            console.log('student',JSON.stringify(data));
            var studentId = data.id

            window.location.href = "{{ route('editstudentPSM1', ['id' => ':id']) }}".replace(':id', studentId);
        })
        
        $("#senaraiPelajar tbody").on('click', '.mid', function () {
            var data = dtsenaraiPelajar.row($(this).parents('tr')).data();
            console.log('mid',data);
        })

        // $("#senaraiPelajar tbody").on('click', '.right', function () {
        //     var data = dtsenaraiPelajar.row($(this).parents('tr')).data();
        //     console.log('right',data);
        // })
    } );

    
</script>
<script src="{{ asset('assets/js/modal.js') }}"></script>

@endsection('script')

