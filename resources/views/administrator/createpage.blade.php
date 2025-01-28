@extends('layouts.admin')
@section('content')

<style>
    .ck-editor__editable {
    min-height: 200px; /* Adjust height as needed */
}

</style>
<div class="card mb-4">
    <div class="row">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <h4 class="mb-4">{{ isset($page) ? 'Edit Page' : 'Create Page' }}</h4>
        <form action="{{ isset($page) ? route('page.store', $page->id) : route('page.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if (isset($page))
                @method('PUT') <!-- Use PUT for updating existing page -->
            @endif
        
            <div>
                <label for="page_title">Page Title</label>
                <input 
                    type="text" 
                    id="page_title" 
                    name="page_title" 
                    class="form-control @error('page_title') is-invalid @enderror" 
                    value="{{ old('page_title', $page->page_title ?? '') }}" 
                    required
                >
                @error('page_title')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        
            <div>
                <label for="page_slug">Page Slug</label>
                <textarea 
                    id="page_slug" 
                    name="page_slug" 
                    class="form-control" 
                    >{{ old('page_slug', $page->page_slug ?? '') }}</textarea>
            </div>
        
            <div class="form-group">
                <label for="page_content">Page Content</label>
                <textarea 
                    id="page_content" 
                    name="page_content" 
                    class="form-control @error('page_content') is-invalid @enderror" 
                    rows="10">{{ old('page_content', $page->page_content ?? '') }}</textarea>
                @error('page_content')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        
            <div>
                <label for="meta_title">Meta Title</label>
                <input 
                    type="text" 
                    id="meta_title" 
                    name="meta_title" 
                    class="form-control @error('meta_title') is-invalid @enderror" 
                    value="{{ old('meta_title', $page->meta_title ?? '') }}">
                @error('meta_title')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        
            <div>
                <label for="meta_description">Meta Description</label>
                <textarea 
                    id="meta_description" 
                    name="meta_description" 
                    class="form-control @error('meta_description') is-invalid @enderror">{{ old('meta_description', $page->meta_description ?? '') }}</textarea>
                @error('meta_description')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        
            <div>
                <label for="meta_keyword">Meta Keyword</label>
                <textarea 
                    id="meta_keyword" 
                    name="meta_keyword" 
                    class="form-control @error('meta_keyword') is-invalid @enderror">{{ old('meta_keyword', $page->meta_keyword ?? '') }}</textarea>
                @error('meta_keyword')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        
            <div>
                <label for="meta_og">Meta OG</label>
                <textarea 
                    id="meta_og" 
                    name="meta_og" 
                    class="form-control @error('meta_og') is-invalid @enderror">{{ old('meta_og', $page->meta_og ?? '') }}</textarea>
                @error('meta_og')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        
            <button type="submit" class="btn btn-primary">{{ isset($page) ? 'Update' : 'Save' }}</button>
        </form>
        
        
        
    </div>
</div>
<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>

<script>
    ClassicEditor.create( document.querySelector( '#page_content' ) )
        .catch( error => {
            console.error( error );
        } );
</script>

@endsection