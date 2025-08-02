@extends('layouts.errors')

@section('title')
    504 Gateway Timeout
@endsection

@section('content')
    <div class="mb-5">
        <a class="btn btn-primary" href="{{ route('dashboard') }}">Back to Home</a>
    </div>
    <h1 class="error-text font-weight-bold">504</h1>
    <h4 class="mt-4"><i class="fa fa-thumbs-down text-danger"></i> Gateway Timeout</h4>
    <p>Request took too long to process</p>
@endsection
