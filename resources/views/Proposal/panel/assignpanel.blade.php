@extends('utility.layout')

@section('navbar')
	@include('utility.navbar')
@endsection('navbar')
@section('content')
<html>

<div class="container mt-5 mb-5">
    <h2>Assign Panel for Proposal</h2>
    
    
	<div class="card">
		<div class="card-header">List of Panel</div>
        
		<div class="card-body">
            <table class="table table-striped table-bordered table-hover" id="listPanel">
                <button type="button" class="btn btn-outline-primary mb-3" id="autoAssignPanelBtn">Auto Assign Panel</button>
                <br>
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
    @include('Proposal.panel.modal-students')
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
                                    '<button type="button" class="btn btn-outline-primary left">Assign Students</button>' +
                                    // '<button type="button" class="btn btn-outline-primary mid">Middle</button>' +
                                    // '<button type="button" class="btn btn-outline-primary right">Right</button>' +
                                    '</div>'
                },
            ],
        });
        console.log(dtListPanel);

        $("#listPanel tbody").on('click', '.left', function () {
            var data = dtListPanel.row($(this).parents('tr')).data();
            console.log('panel',JSON.stringify(data));
            var panelId = data.id
            $("#studentModal").attr("panelid", panelId)

            getStudents(panelId)
        })

        // $("#listPanel tbody").on('click', '.mid', function () {
        //     var data = dtListPanel.row($(this).parents('tr')).data();
        //     console.log('mid',data);
        // })

        // $("#listPanel tbody").on('click', '.right', function () {
        //     var data = dtListPanel.row($(this).parents('tr')).data();
        //     console.log('right',data);
        // })
    } );

    function getStudents(panelId) {
        var metadata = {
            panelId : panelId
        }
        $.ajax({
                type:'GET',
                url:"{{ route('proposal.get.panelstudents') }}",
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

