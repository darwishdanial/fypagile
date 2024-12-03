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
        <div class="card-header">Coordinator - Data for Panel Assignment</div>
        <div class="card-body table-responsive">
            <p>
                Panel data: 
                <a href="http://web.fc.utm.my/~wmf12apps2/cgi-bin/webman/psm2/index_json-v2.cgi?entity=examiner" target="_blank">
                    http://web.fc.utm.my/~wmf12apps2/cgi-bin/webman/psm2/index_json-v2.cgi?entity=examiner
                </a>
            </p>
            <p>
                Student data: 
                <a href="http://web.fc.utm.my/~wmf12apps2/cgi-bin/webman/psm2/index_json-v2.cgi?entity=project" target="_blank">
                    http://web.fc.utm.my/~wmf12apps2/cgi-bin/webman/psm2/index_json-v2.cgi?entity=project
                </a>
            </p>
            
            <table class="table table-striped table-bordered table-hover" id="samplesTable">
            <thead>
                    <tr>
                        <th>No</th>
                        <!-- <th>Project Area (Numeric)</th> -->
                        <th>Project Area (Name)</th>
                        <!-- <th>Project Type (Numeric)</th> -->
                        <th>Project Type (Name)</th>
                        <!-- <th>Lecturer (Numeric)</th> -->
                        <th>Lecturer (Name)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($samples as $index => $sample)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <!-- <td>{{ $sample['project_area'][0] }}</td> -->
                        <td> [{{ $sample['project_area'][0] }}] {{ $sample['project_area'][1] }}</td>
                        <!-- <td>{{ $sample['project_type'][0] }}</td> -->
                        <td> [{{ $sample['project_type'][0] }}] {{ $sample['project_type'][1] }}</td>
                        <!-- <td>{{ $labels[$index][0] }}</td> -->
                        <td> [{{ $labels[$index][0] }}] {{ $labels[$index][1] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function () {
        // Initialize DataTable for Samples table
        $('#samplesTable').DataTable();

        // Initialize DataTable for Labels table
        $('#labelsTable').DataTable();
    });
</script>
<script src="{{ asset('assets/js/modal.js') }}"></script>
@endsection
</html>
