@extends('utility.layout')

@section('navbar')
    @include('utility.navbar')
@endsection

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
        <div class="card-header">Supervisor - Senarai Pelajar PSM1</div>
        <div class="card-body table-responsive">
            <!-- <div class="table-responsive"> -->
                <table class="table table-striped table-bordered table-hover" id="senaraiPelajar">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Matric</th>
                            <th>Course</th>
                            <th>Project Progress</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            <!-- </div> -->
        </div>
    </div>
</div>
@endsection

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
                    "data": null,
                    "targets" : [4],
                    orderable : false,
                    defaultContent: '<div class="btn-group w-100" role="group" aria-label="Basic example">' +
                                    '<button type="button" class="btn btn-outline-primary project-progress">Project Progress</button>' +
                                    '</div>'
                },
                {
                    "data": null,
                    "targets" : [5],
                    orderable : false,
                    defaultContent: '<div class="btn-group w-100" role="group" aria-label="Basic example">' +
                                    '<button type="button" class="btn btn-outline-primary left">Grade</button>' +
                                    '</div>'
                },
            ],
        });
        console.log(dtSenaraiPelajar);

        $("#senaraiPelajar tbody").on('click', '.left', function () {
            var data = dtSenaraiPelajar.row($(this).parents('tr')).data();
            console.log('student', JSON.stringify(data));
            var studentId = data.id;

            window.location.href = "{{ route('gradebaruPSM1', ['id' => ':id']) }}".replace(':id', studentId);
        });

        // $("#senaraiPelajar tbody").on('click', '.project-progress', function () {
        //     var data = dtSenaraiPelajar.row($(this).parents('tr')).data();
        //     console.log('student', JSON.stringify(data));
        //     var studentId = data.id;

        //     
        // });
    });
</script>
<script src="{{ asset('assets/js/modal.js') }}"></script>

@endsection
