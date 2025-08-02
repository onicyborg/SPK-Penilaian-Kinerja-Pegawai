@extends('layouts.errors')

@section('title')
    400 Bad Request
@endsection

@section('content')
    <div class="mb-5">
        <a class="btn btn-primary" href="{{ route('dashboard') }}">Back to Home</a>
    </div>
    <h1 class="error-text font-weight-bold">400</h1>
    <h4 class="mt-4"><i class="fa fa-thumbs-down text-danger"></i> Bad Request</h4>
    <p>Your Request resulted in an error</p>
@endsection
