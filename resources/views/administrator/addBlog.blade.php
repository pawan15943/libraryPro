
@extends('layouts.admin')
@section('content')
<style>
    .ck-editor__editable {
    min-height: 200px; /* Adjust height as needed */
}

</style>
<div class="card mb-4">
    <h4 class="mb-4">{{ isset($data) ? 'Edit Blog' : 'Create Blog' }}</h4>
    <form action="{{ isset($data) ? route('blog.store', $data->id) : route('blog.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if (isset($data))
            @method('PUT') 
        @endif

        <div>
            <label for="page_title">Page Title</label>
            <input 
                type="text" 
                id="page_title" 
                name="page_title" 
                class="form-control @error('page_title') is-invalid @enderror" 
                value="{{ old('page_title', $data->page_title ?? '') }}" 
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
                >{{ old('page_slug', $data->page_slug ?? '') }}</textarea>
        </div>
    
        <div class="form-group">
            <label for="page_content">Page Content</label>
            <textarea 
                id="page_content" 
                name="page_content" 
                class="form-control @error('page_content') is-invalid @enderror" 
                >{{ old('page_content', $data->page_content ?? '') }}</textarea>
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
                value="{{ old('meta_title', $data->meta_title ?? '') }}">
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
                class="form-control @error('meta_description') is-invalid @enderror">{{ old('meta_description', $data->meta_description ?? '') }}</textarea>
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
                class="form-control @error('meta_keyword') is-invalid @enderror">{{ old('meta_keyword', $data->meta_keyword ?? '') }}</textarea>
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
                class="form-control @error('meta_og') is-invalid @enderror">{{ old('meta_og', $data->meta_og ?? '') }}</textarea>
            @error('meta_og')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <!-- Tags -->
        <div>
            <label for="tags">Tags</label>
            <input 
                id="tags" 
                name="tags" 
                class="form-control @error('tags') is-invalid @enderror" 
                value="{{ old('tags', isset($data) ? implode(',', json_decode($data->tags, true)) : '') }}"
                >
            @error('tags')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <!-- categories -->
        <div>
            <label for="categories">Categories</label>
            <input 
                id="categories" 
                name="categories" 
                class="form-control @error('categories') is-invalid @enderror" 
                value="{{ old('categories') }}">
            @error('categories')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div>
            <label for="categories_id">Select Categories</label>
            <select name="categories_id[]" id="categories_id" class="form-control" multiple>
                @foreach($categories as $category)
                    <option 
                        value="{{ $category->id }}" 
                        >
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Header Image -->
        <div>
            <label for="header_image">Header Image</label>
            <input 
                type="file" 
                id="header_image" 
                name="header_image" 
                class="form-control @error('header_image') is-invalid @enderror">
            @error('header_image')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($data) ? 'Update' : 'Save' }}</button>
    </form>
</div>

<!-- Include Tagify JS & CSS -->
<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>

<script>
    // Initialize Tagify for Tags and Categories
    new Tagify(document.querySelector('#tags'));
    new Tagify(document.querySelector('#categories'));
</script>


<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>

<script>
    ClassicEditor.create( document.querySelector( '#page_content' ) )
        .catch( error => {
            console.error( error );
        } );
</script>

@endsection
