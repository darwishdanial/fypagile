
@extends('utility.layout')

@section('navbar')
	@include('utility.navbar')
@endsection('navbar')
@section('content')
<html>



<div class="container mt-5 mb-5" style="max-width:1500px">
    @if(Session::has('success'))
            <div class="alert alert-success">
                <div class="flash-message">
                    {!! Session::get('success') !!}
                </div>
            </div>
    @endif
    
	<div class="card">
		<div class="card-header">Senarai Pelajar PSM1 </div>
		<div class="card-body table-responsive">
            <table class="table table-striped table-bordered table-hover" id="senaraiPelajar">
                <thead>
                    <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Matric</th>
                    <th>Course</th>
                    <th>Title</th>
                    <th>Supervisor</th>
                    <th>Panel</th>
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
                    "data": "panel2_name",
                    "targets" : [6],
                },
                {
                    "data": "panel_name",
                    "targets" : [7],
                },
                {
                    "data": null,
                    "targets" : [8],
                    orderable : false,
                    defaultContent: '<div class="btn-group w-100" role="group" aria-label="Basic example">' +
                                    '<button type="button" class="btn btn-outline-primary left">Edit</button>' +
                                    '<button type="button" class="btn btn-outline-danger mid" onclick="confirmDelete(event)">Delete</button>' +
                                     // '<button type="button" class="btn btn-outline-primary right">Right</button>' +
                                    '</div>'
                },
            ],
        });
        console.log(dtSenaraiPelajar);

        $("#senaraiPelajar tbody").on('click', '.left', function () {
            var data = dtSenaraiPelajar.row($(this).parents('tr')).data();
            console.log('student',JSON.stringify(data));
            
            var id = data.id;

            window.location.href = "{{ route('editstudentPSM1', ':student') }}".replace(':student', id);
        })

        $("#senaraiPelajar tbody").on('click', '.mid', function() {
            var data = dtSenaraiPelajar.row($(this).parents('tr')).data();
            var id = data.id; 
            console.log('delete', data);

            window.location.href = "{{ route('studentdestroy', ':student') }}".replace(':student', id);
        });


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

    function confirmDelete(event) {
        if (!confirm('Are you sure you want to delete this item?')) {
            event.preventDefault(); // Prevent the default behavior of the button
        }
    }
</script>
<script src="{{ asset('assets/js/modal.js') }}"></script>

@endsection('script')

