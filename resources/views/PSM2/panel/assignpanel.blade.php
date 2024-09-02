@extends('utility.layout')

@section('navbar')
	@include('utility.navbar')
@endsection('navbar')
@section('content')
<html>

<div class="container mt-5 mb-5">
    <h2>Assign Panel PSM2</h2>
    
	<div class="card">
		<div class="card-header">List of Panel</div>
		<div class="card-body">
            <table class="table table-striped table-bordered table-hover" id="listPanel">
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
    @include('PSM2.panel.modal-students')
@endsection('modal')
</html>

@section('script')
<script>
    var panel = @json($panels);
    console.log(panel);
    $(document).ready( function () {
        var dtListPanel = $('#listPanel').DataTable({
            "data" : panel,
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
                                    '<button type="button" class="btn btn-outline-primary left">Assign Student Panel 1</button>' +
                                    '<button type="button" class="btn btn-outline-primary mid">Assign Student Panel 2</button>' +
                                    '</div>'
                },
            ],
        });
        console.log(dtListPanel);

        $("#listPanel tbody").on('click', '.left', function () {
            var data = dtListPanel.row($(this).parents('tr')).data();
            console.log('panel',JSON.stringify(data));
            var panelId = data.id
            var panelType = 'panel1'
            $("#studentModal").attr("panelid", panelId)
            $("#studentModal").attr("paneltype", panelType)

            getStudents(panelId, panelType)
        })

        $("#listPanel tbody").on('click', '.mid', function () {
            var data = dtListPanel.row($(this).parents('tr')).data();
            console.log('panel',JSON.stringify(data));
            var panelId = data.id
            var panelType = 'panel2'
            $("#studentModal").attr("panelid", panelId)
            $("#studentModal").attr("paneltype", panelType)

            getStudents(panelId, panelType)
        })
    } );

    function getStudents(panelId, panelType) {
        var metadata = {
            panelId : panelId,
            panelType : panelType
        }
        $.ajax({
                type:'GET',
                url:"{{ route('psm2.get.panelstudents') }}",
                data: metadata,
                success: function(res){
                    console.log('res',res);
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

