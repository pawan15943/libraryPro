@extends('layouts.admin')

@section('content')
<style>
    .import-data {
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        padding: 20px;
        transition: all 0.3s ease;
        
    }

    .import-data:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    }

    .import-data label {
        font-weight: 600;
        color: #333;
        font-size: 16px;
    }

    .import-data  input[type="file"] {
        border: 2px dashed #c4c4c4;
        padding: 10px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .import-data  input[type="file"]:hover {
        border-color: #6c63ff;
        background-color: #f1f0ff;
    }

    .import-data  a {
        display: block;
        margin-top: 8px;
        color: #6c63ff;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .import-data  a:hover {
        color: #4b47d4;
    }

    .import-data  button {
        background-color: #3a3a8f;
        color: #fff;
        font-weight: 600;
        border: none;
        border-radius: 4px;
        padding: 10px 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
    }

    .import-data  button:hover {
        background-color: #2e2e7e;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .import-data  button:active {
        transform: scale(0.98);
    }

    #export-progress-container {
        margin-top: 20px;
        text-align: center;
    }

    #export-progress-bar {
        width: 100%;
        height: 10px;
        border-radius: 4px;
        background-color: #e0e0e0;
        appearance: none;
        transition: width 0.3s ease;
    }

    #export-progress-bar::-webkit-progress-bar {
        background-color: #e0e0e0;
        border-radius: 4px;
    }

    #export-progress-bar::-webkit-progress-value {
        background-color: #3a3a8f;
        border-radius: 4px;
        animation: progress-animation 2s ease-in-out infinite;
    }

    #export-progress-text {
        font-size: 14px;
        color: #555;
        margin-top: 8px;
        display: block;
        animation: fade-in 1.5s ease;
    }

    @keyframes fade-in {
        0% {
            opacity: 0;
        }

        100% {
            opacity: 1;
        }
    }

    @keyframes progress-animation {
        0% {
            width: 0%;
        }

        100% {
            width: 100%;
        }
    }

    .alert {
        padding: 15px 20px;
        border-radius: 5px;
        margin: 20px 0;
        font-size: 16px;
        display: flex;
        align-items: center;
        transition: all 0.4s ease;
        opacity: 0;
        transform: translateY(-10px);
        animation: slide-in 0.6s forwards;
    }

    .alert ul {
        margin: 0;
        padding: 0;
        list-style-type: none;
    }

    .alert.alert-danger {
        background-color: #ffdddd;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }

    .alert.alert-danger li:before {
        content: "⚠️ ";
        margin-right: 5px;
    }

    .alert.alert-success {
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }

    .alert.alert-success:before {
        content: "✅ ";
        font-weight: bold;
        margin-right: 10px;
        font-size: 20px;
    }

    @keyframes slide-in {
        0% {
            opacity: 0;
            transform: translateY(-10px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

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
                            <td class="text-danger">{{ $record['error'] ?? 'No error provided' }}</td>
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