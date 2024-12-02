@extends('utility.layout')

@section('navbar')
	@include('utility.navbar')
@endsection('navbar')
@section('content')
<html>

<div class="container mb-5">
    <h2>Assign Supervisor</h2>
	<div class="card">
		<div class="card-header">List of Supervisor</div>
		<div class="card-body  overflow-x-auto">
            <table class="table table-striped table-bordered table-hover" id="listSupervisor">
                <thead>
                    <tr>
                    <th>No</th>
                    <th>Name</th>
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
@section('modal')
    @include('modal-students')
@endsection('modal')
</html>

@section('script')
<script>
    var supervisors = @json($supervisors);
    console.log(supervisors);
    $(document).ready( function () {
        var dtListSupervisor = $('#listSupervisor').DataTable({
            "data" : supervisors,
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
                    "data": null,
                    "targets" : [2],
                    orderable : false,
                    defaultContent: '<div class="btn-group w-100" role="group" aria-label="Basic example">' +
                                    '<button type="button" class="btn btn-outline-primary left">Assign Students</button>' +
                                    // '<button type="button" class="btn btn-outline-primary mid">Middle</button>' +
                                    // '<button type="button" class="btn btn-outline-primary right">Right</button>' +
                                    '</div>'
                },
            ],
        });
        console.log(dtListSupervisor);

        $("#listSupervisor tbody").on('click', '.left', function () {
            var data = dtListSupervisor.row($(this).parents('tr')).data();
            console.log('supervisor',JSON.stringify(data));
            var svId = data.id
            $("#studentModal").attr("svid", svId)

            getStudents(svId)
        })

        // $("#listSupervisor tbody").on('click', '.mid', function () {
        //     var data = dtListSupervisor.row($(this).parents('tr')).data();
        //     console.log('mid',data);
        // })

        // $("#listSupervisor tbody").on('click', '.right', function () {
        //     var data = dtListSupervisor.row($(this).parents('tr')).data();
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

