@extends('layouts.admin')
@section('content')

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="row">
    <div class="col-lg-12">
        <a href="{{ route('add-page') }}" class="btn btn-primary">Create New Page</a>
        <div class="table-responsive">
            <table class="table text-center datatable dataTable" id="datatable">
             
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pages as $page)
                            <tr>
                                <td>{{ $page->id }}</td>
                                <td>{{ $page->page_title }}</td>
                                <td>{{ $page->page_slug }}</td>
                                <td>
                                    <a href="{{ route('page.edit', $page) }}" class="btn btn-sm btn-warning">Edit</a>
                                   
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>
    </div>
</div>


@endsection