@extends('layouts.master')

@section('title', 'Assessment Results')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-12">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('assessment.setting-criteria', $periode->id) }}">Setting
                            Criteria</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('assessment.assessment-employee', $periode->id) }}">Assessment
                            Process</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('assessment.results', $periode->id) }}">Assessment
                            Results</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('assessment.logs', $periode->id) }}">Assessment Logs</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="accordion" id="resultsAccordion">
                    @foreach ($saw_results as $result)
                        <div class="accordion__item">
                            <div class="accordion__header" data-toggle="collapse"
                                data-target="#collapse{{ $result->id }}">
                                <span class="accordion__header--text">
                                    <span class="badge badge-primary me-2" style="font-size:1.1em;"><i
                                            class="bi bi-trophy-fill"></i> {{ $result->rank }}</span>
                                    <span><b>{{ $result->employee->name }}</b> ({{ $result->employee->department->name }} -
                                        {{ $result->employee->position }})</span>
                                </span>
                                <span class="accordion__header--indicator" style="display:none;"></span>
                            </div>
                            <div id="collapse{{ $result->id }}" class="collapse accordion__body"
                                data-parent="#resultsAccordion">
                                <div class="accordion__body--text">
                                    <h6>Detail Perhitungan:</h6>
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Kriteria</th>
                                                <th>Skor</th>
                                                <th>Normalisasi</th>
                                                <th>Bobot</th>
                                                <th>Weighted</th>
                                                <th>Max Score</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($result->calculation_details['matrix'] as $criteriaId => $score)
                                                <tr>
                                                    <td>{{ $periode->criteria->where('id', $criteriaId)->first()->name ?? '-' }}
                                                    </td>
                                                    <td>{{ $score }}</td>
                                                    <td>{{ $result->normalized_scores[$criteriaId] ?? '-' }}</td>
                                                    <td>{{ $result->calculation_details['criteria_weights'][$criteriaId] ?? '-' }}%
                                                    </td>
                                                    <td>{{ $result->weighted_scores[$criteriaId] ?? '-' }}</td>
                                                    <td>{{ $result->calculation_details['max_scores'][$criteriaId] ?? '-' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <h6>Nilai Akhir:</h6>
                                    <b>{{ $result->final_score }}</b>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endpush
