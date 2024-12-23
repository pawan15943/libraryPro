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


@if(session('successCount'))
<div class="alert alert-success">
    {{ session('successCount') }} records imported successfully.
</div>
@endif

<div class="row justify-content-center mb-4 mt-4">
    <div class="col-lg-4">
        <div class="import-data">
            <form action="{{  route('library.csv.upload')}}" method="POST" enctype="multipart/form-data" id="importForm"> @csrf

                <div class="row g-4">
                    <div class="col-lg-12">
                        <label for="">Select File</label>
                        <input type="file" class="form-control" name="csv_file">
                        <a href="{{ asset('public/sample/sample.csv') }}"><small>Download Sample learnar CSV File</small></a>

                    </div>
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-primary button">Import Data</button>
                    </div>
                </div>
            </form>
            <div id="export-progress-container">
                <progress id="export-progress-bar" value="0" max="100" style="width: 100%;"></progress>
                <span id="export-progress-text">Preparing download: 0%</span>
            </div>
        </div>
    </div>
</div>

{{-- Display Invalid Records --}}
<div class="row">
    <div class="col-lg-12">
        <div id="invalid-records-section">
            @if(session('invalidRecords') && count(session('invalidRecords')) > 0)
            <p class="text-danger">Some records could not be imported:</p>
            <div class="table table-responsive">
                <table class="table text-center data-table">
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
                            {{-- <td class="text-danger">{{ $record['error'] ?? 'No error provided' }}</td> --}}
                            <td class="text-danger">
                                {{ is_array($record['error']) ? implode(', ', $record['error']) : ($record['error'] ?? 'No error provided') }}
                            </td>
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <button class="btn btn-danger btn-sm mt-2" id="clearInvalidRecordsButton">Clear Invalid Records</button>
            </div>
            {{-- Trigger CSV Download Automatically --}}
            @if(session('autoExportCsv'))
            <script type="text/javascript">
                let exportProgressBar = document.getElementById('export-progress-bar');
                let exportProgressText = document.getElementById('export-progress-text');
                let exportrecordurl = "{{ Auth::guard('library')->check() ? route('library.export.invalid.records') : route('web.export.invalid.records') }}";

                // Set an interval to update the progress bar every 100 ms
                let progress = 0;
                let interval = setInterval(function() {
                    progress += 10;
                    exportProgressBar.value = progress;
                    exportProgressText.textContent = `Preparing download: ${progress}%`;

                    if (progress >= 100) {
                        clearInterval(interval);
                        window.location.href = exportrecordurl;
                    }
                }, 100); // 100 ms * 10 = 1 second, change as needed
            </script>
            @endif


            @endif
            <script>
                document.getElementById('clearInvalidRecordsButton').addEventListener('click', function() {
                    // Hide the invalid records section
                    document.getElementById('invalid-records-section').style.display = 'none';

                    // Reset the progress bar
                    let exportProgressBar = document.getElementById('export-progress-bar');
                    let exportProgressText = document.getElementById('export-progress-text');

                    if (exportProgressBar && exportProgressText) {
                        exportProgressBar.value = 0; // Reset progress to 0
                        exportProgressText.textContent = ""; // Clear text percentage
                    }

                    let clearSessionRoute = "{{ Auth::guard('library')->check() ? route('library.clear.session') : route('web.clear.session') }}";

                    // Send AJAX request to clear session
                    fetch(clearSessionRoute, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({})
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                console.log('Session cleared successfully.');
                            } else {
                                console.log('Failed to clear session.');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });
            </script>

        </div>
    </div>
</div>





@endsection