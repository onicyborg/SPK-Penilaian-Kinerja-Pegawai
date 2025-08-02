@extends('layouts.errors')

@section('title')
    500 Internal Server Error
@endsection

@section('content')
    <div class="mb-5">
        <a class="btn btn-primary" href="{{ route('dashboard') }}">Back to Home</a>
    </div>
    <h1 class="error-text font-weight-bold">500</h1>
    <h4 class="mt-4"><i class="fa fa-thumbs-down text-danger"></i> Internal Server Error</h4>
    <p>Something went wrong on our end</p>
@endsection
