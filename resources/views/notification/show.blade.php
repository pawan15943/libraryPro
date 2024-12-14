@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')


<div class="row justify-content-center">
    <div class="col-lg-8">
        <ul class="notification-list">
            @foreach($notifications as $index => $notification)
            @php
            $data = json_decode($notification->data, true);
            @endphp
            <li>
                <h4>{{ $data['title'] ?? 'N/A' }} <span class="text-success">(Active)</span></h4>
                <p>{{ $data['description'] ?? 'N/A' }}</p>
                <div class="d-flex">
                    <div class="validity">
                        <span>Valid Till : </span>
                        <p class="m-0">From {{ $notification->start_date }} to {{ $notification->end_date }}</p>
                    </div>
                    @if(!empty($data['link']))
                    <a href="{{ $data['link'] }}" class="btn btn-primary view-info" target="_blank">View</a>
                    @else
                    <a href="{{ $data['link'] }}" class="btn btn-primary view-info" target="_blank">View Detials</a>
                    @endif
                </div>
            </li>
            @endforeach
        </ul>

        <div class="table-responsive">
            <table class="table text-center datatable border-bottom">
                <thead>
                    <tr>
                        <th>S.No.</th>

                        <th>Title</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>URL</th>
                        <th>Start Date</th>
                        <th>Expiry Date</th>
                        <th>Status</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($notifications as $index => $notification)
                    @php
                    $data = json_decode($notification->data, true);
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>

                        <td>{{ $data['title'] ?? 'N/A' }}</td>
                        <td>{{ $data['description'] ?? 'N/A' }}</td>
                        <td>
                            @if(!empty($data['image']))
                            <img src="{{ $data['image'] }}" alt="Notification Image" style="width: 50px; height: auto;">
                            @else
                            N/A
                            @endif
                        </td>
                        <td>
                            @if(!empty($data['link']))
                            <a href="{{ $data['link'] }}" target="_blank">View</a>
                            @else
                            N/A
                            @endif
                        </td>
                        <td>{{ $notification->start_date }}</td>
                        <td>{{ $notification->end_date }}</td>
                        <td>
                            {{ now()->between($notification->start_date, $notification->end_date) ? 'Active' : 'Expired' }}
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


@include('library.script')
@endsection