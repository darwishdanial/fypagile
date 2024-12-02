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
		<div class="card-header">Supervisor - Senarai Pelajar PSM2</div>
		<div class="card-body table-responsive">
            <table class="table table-striped table-bordered table-hover" id="senaraiPelajar">
                <thead>
                    <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Matric</th>
                    <th>Course</th>
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
     var data = @json($students);
    var students = data;
    if(students.length == undefined){
        students = [];
        Object.values(data).forEach(student => {
            students.push(student);
        });
    }
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
                    "data": null,
                    "targets" : [4],
                    orderable : false,
                    defaultContent: '<div class="btn-group w-100" role="group" aria-label="Basic example">' +
                                    '<button type="button" class="btn btn-outline-primary left">Grade</button>' +
                                    // '<button type="button" class="btn btn-outline-primary mid">Middle</button>' +
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

            window.location.href = "{{ route('gradebaruPSM2', ['id' => ':id']) }}".replace(':id', studentId);
        })
        
        // $("#senaraiPelajar tbody").on('click', '.mid', function () {
        //     var data = dtsenaraiPelajar.row($(this).parents('tr')).data();
        //     console.log('mid',data);
        // })

        // $("#senaraiPelajar tbody").on('click', '.right', function () {
        //     var data = dtsenaraiPelajar.row($(this).parents('tr')).data();
        //     console.log('right',data);
        // })
    } );

    
</script>
<script src="{{ asset('assets/js/modal.js') }}"></script>

@endsection('script')

