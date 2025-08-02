@extends('layouts.errors')

@section('title')
    404 Not Found
@endsection

@section('content')
    <div class="mb-5">
        <a class="btn btn-primary" href="{{ route('dashboard') }}">Back to Home</a>
    </div>
    <h1 class="error-text font-weight-bold">404</h1>
    <h4 class="mt-4"><i class="fa fa-thumbs-down text-danger"></i> Not Found</h4>
    <p>The page you are looking for doesn't exist or has been moved</p>
@endsection
