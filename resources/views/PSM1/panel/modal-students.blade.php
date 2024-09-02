<!-- Modal -->
<div class="modal modal-lg fade" id="studentModal" tabindex="-1" role="dialog" aria-labelledby="studentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="studentModalLabel">PSM 1</h5>
        <button type="button" class="btn modalClose">
            &times;
        </button>
      </div>
      <div class="modal-body">
      <div class="container mt-5 mb-5">
        <div class="card">
          <div class="card-header">List of Students</div>
            <div class="card-body">
                    <table class="table table-striped table-bordered table-hover w-100" id="listStudent">
                        <thead>
                            <tr>
                            <th>No</th>
                            <th>Name</th>
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
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary modalClose">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready( function () {
        var dtListStudent = $('#listStudent').DataTable({
            // "data" : students,
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
                    "data": "course",
                    "targets" : [2],
                },
                {
                    "data": "assigned",
                    "targets" : [3],
                    orderable : false,
                    render: function (data, type, row, meta) {
                      var dtData = data;
                      var dtButton =  ' <div class="btn-group w-100">' +
                                      '   <button type="button" class="btn btn-primary assign">Assign</button>' +
                                      ' </div>'
                      if (data){
                        dtButton =    ' <div class="btn-group w-100">' +
                                      '   <button type="button" class="btn btn-danger unassign">Unassign</button>' +
                                      ' </div>'
                      }
                      return dtButton;
                    }
                },
            ],
        });

        $("#listStudent tbody").on('click', '.assign', function () {
            var data = dtListStudent.row($(this).parents('tr')).data();
            $(this).removeClass('btn-primary assign').addClass('btn-danger unassign')
              .text('Unassign')

            var panelId = $(this).closest(".modal").attr("panelid")
            var panelType = $(this).closest(".modal").attr("paneltype")
            var metadata = {
              id : data.id,
              panelId : panelId,
              panelType : panelType
            }


            console.log(metadata);
            $.ajax({
                type:'GET',
                url:"{{ route('assign.panelstudent') }}",
                data: metadata,
                success: function(res){
                  console.log(res);
                },
                error: function(res){
                  console.log('Error: ' ,  res.statusText);
                  console.log('Status: ' , res.status);
                },
            });
        })

        $("#listStudent tbody").on('click', '.unassign', function () {
            var data = dtListStudent.row($(this).parents('tr')).data();
            $(this).addClass('btn-primary assign').removeClass('btn-danger unassign').text('Assign')
            
            var panelType = $(this).closest(".modal").attr("panelType")
            var metadata = {
              id : data.id,
              panelType : panelType,
            }

            console.log(metadata);
            $.ajax({
                type:'GET',
                url:"{{ route('unassign.panelstudent') }}",
                data: metadata,
                success: function(res){
                  console.log(res);
                },
                error: function(res){
                  console.log('Error: ' ,  res.statusText);
                  console.log('Status: ' , res.status);
                },
            });
        })
    } );
</script>
