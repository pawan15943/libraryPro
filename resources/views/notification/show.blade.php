@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')


<div class="col-lg-12">
    <h4 class="mb-4">Notification List</h4>
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


@include('library.script')
@endsection