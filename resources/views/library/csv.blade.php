@extends('layouts.admin')

@section('content')


<!-- Breadcrumb -->


@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="row justify-content-center mb-4 mt-4">
    <div class="col-lg-4">
        <div class="import-data">
            <form action="{{ route('csv.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-4" >
                    <div class="col-lg-12">
                        <label for="">Select File</label>
                        <input type="file" class="form-control" name="csv_file">
                        <a href="{{ asset('public/sample/sample.csv') }}"><small>Download Sample CSV Fil</small></a>
                    </div>
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-primary button">Import Data</button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- Success Message --}}
@if(session('successCount'))
    <div class="alert alert-success">
        {{ session('successCount') }} records imported successfully.
    </div>
@endif



{{-- Display Invalid Records --}}
@if(session('invalidRecords') && count(session('invalidRecords')) > 0)
    <div class="alert alert-warning">
        <p>Some records could not be imported:</p>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Plan Type</th>
                    <th>Start Date</th>
                    <th>Error Message</th>
                </tr>
            </thead>
            <tbody>
                @foreach (session('invalidRecords') as $record)
                    <tr>
                        <td>{{ $record['name'] ?? 'N/A' }}</td>
                        <td>{{ $record['email'] ?? 'N/A' }}</td>
                        <td>{{ $record['plan_type'] ?? 'N/A' }}</td>
                        <td>{{ $record['start_date'] ?? 'N/A' }}</td>
                        <td>{{ $record['error'] ?? 'No error provided' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Trigger CSV Download Automatically --}}
    @if(session('autoExportCsv'))
        <script type="text/javascript">
            window.onload = function() {
                setTimeout(function() {
                    window.location.href = "{{ route('export.invalid.records') }}"; // Trigger the export CSV route
                }, 1000); // Delay to ensure the page fully loads before triggering
            };
        </script>
    @endif
@endif

@endsection