@extends('layouts.errors')

@section('title')
    403 Forbidden
@endsection

@section('content')
    <div class="mb-5">
        <a class="btn btn-primary" href="{{ route('dashboard') }}">Back to Home</a>
    </div>
    <h1 class="error-text font-weight-bold">403</h1>
    <h4 class="mt-4"><i class="fa fa-thumbs-down text-danger"></i> Forbidden</h4>
    <p>You do not have permission to access this page</p>
@endsection
