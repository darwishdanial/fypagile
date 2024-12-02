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
		<div class="card-header">Coordinator - Senarai Panel PSM1</div>
		<div class="card-body table-responsive">
            <table class="table table-striped table-bordered table-hover" id="listPanels">
                <thead>
                    <tr>
                    <th>No</th>
                    <th>Name</th>
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
    var data = @json($panels);
    var panels = data;
    if(panels.length == undefined){
        students = [];
        Object.values(data).forEach(student => {
            panels.push(student);
        });
    }
    console.log(panels);
    console.log('testing');
    $(document).ready( function () {
        var dtlistPanels = $('#listPanels').DataTable({
            "data" : panels,
            "columnDefs" : [
                {
                    "data": null,
                    "targets" : [0],
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    "data": "panel_name",
                    "targets" : [1],
                },
            ],
        });
        console.log(dtlistPanels);
        
    } );

    
</script>
<script src="{{ asset('assets/js/modal.js') }}"></script>

@endsection('script')

