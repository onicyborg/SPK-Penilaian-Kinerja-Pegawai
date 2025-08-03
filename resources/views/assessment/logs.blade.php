@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Assessment Logs - {{ $periode->name }}</h4>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs mb-3">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('assessment.setting-criteria', $periode->id) }}">Setting
                                    Criteria</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $periode->status == 'draft' ? 'disabled' : '' }}"
                                    href="{{ route('assessment.assessment-employee', $periode->id) }}" tabindex="-1"
                                    aria-disabled="true">Assessment Process</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $periode->status == 'draft' || $periode->status == 'active' ? 'disabled' : '' }}"
                                    href="{{ route('assessment.results', $periode->id) }}" tabindex="-1"
                                    aria-disabled="true">Assessment Results</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="{{ route('assessment.logs', $periode->id) }}">Assessment
                                    Logs</a>
                            </li>
                        </ul>
                        <style>
                            .table td, .table th {
                                color: #000000 !important;
                            }
                        </style>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Waktu</th>
                                        <th>User</th>
                                        <th>Aksi</th>
                                        <th>Deskripsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($logs as $log)
                                        <tr>
                                            <td>{{ $log->created_at }}</td>
                                            <td>{{ $log->user->name ?? '-' }}</td>
                                            <td>{{ $log->action }}</td>
                                            <td>{{ $log->description }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Belum ada log assessment.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
