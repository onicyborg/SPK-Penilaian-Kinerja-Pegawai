@extends('layouts.master')

@section('title')
    Dashboard
@endsection

@push('styles')
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <!-- Employee Summary -->
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title mb-2">Total Karyawan</h5>
                        <h2 class="fw-bold">{{ $totalEmployees }}</h2>
                        <div class="d-flex justify-content-between mt-3">
                            <span class="badge badge-success">Aktif: {{ $activeEmployees }}</span>
                            <span class="badge badge-secondary">Nonaktif: {{ $inactiveEmployees }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Assessment Period Summary -->
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title mb-2">Periode Penilaian</h5>
                        <h2 class="fw-bold">{{ $totalPeriods }}</h2>
                        <div class="d-flex justify-content-between mt-3">
                            <span class="badge badge-warning">Draft: {{ $periodsByStatus['draft'] }}</span>
                            <span class="badge badge-info">Aktif: {{ $periodsByStatus['active'] }}</span>
                            <span class="badge badge-success">Selesai: {{ $periodsByStatus['completed'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Latest Completed Period -->
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title mb-2">Periode Terakhir Selesai</h5>
                        @if ($latestCompletedPeriod)
                            <div class="fw-bold">{{ $latestCompletedPeriod->name }}</div>
                            <div class="text-muted small">{{ $latestCompletedPeriod->description }}</div>
                            <div class="mt-2">Karyawan Dinilai: <span class="fw-bold">{{ $employeesAssessed }}</span>
                            </div>
                            <div>Kriteria: <span class="fw-bold">{{ $criteriaCount }}</span></div>
                        @else
                            <div class="text-muted">Belum ada periode selesai</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <!-- Top 3 Performers -->
            <div class="col-lg-6">
                <div class="card shadow h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0"><i class="bi bi-trophy-fill me-2"></i>Top 3 Performer</h5>
                    </div>
                    <div class="card-body">
                        @if ($topPerformers && count($topPerformers))
                            <ol class="list-group list-group-numbered">
                                @foreach ($topPerformers as $performer)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $performer->employee->photo ? asset('storage/' . $performer->employee->photo) : asset('images/employee.png') }}"
                                                class="rounded-circle me-3" width="40" height="40">
                                            <div>
                                                <div class="fw-bold">{{ $performer->employee->name }}</div>
                                                <small class="text-muted">{{ $performer->employee->position }} -
                                                    {{ $performer->employee->department }}</small>
                                            </div>
                                        </div>
                                        <span
                                            class="badge badge-{{ $performer->getPerformanceCategoryColor() }} fs-6">{{ number_format($performer->final_score * 100, 2) }}%</span>
                                    </li>
                                @endforeach
                            </ol>
                        @else
                            <div class="text-muted">Belum ada data performer</div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Performance Distribution Chart -->
            <div class="col-lg-6">
                <div class="card shadow h-100">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0"><i class="bi bi-bar-chart-fill me-2"></i>Distribusi Kinerja (Periode
                            Terakhir)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="performanceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <!-- Recent Logs -->
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header d-block bg-secondary text-white">
                        <h4 class="card-title"><i class="bi bi-clock-history me-2"></i>Aktivitas Terbaru</h4>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Waktu</th>
                                    <th>Pengguna</th>
                                    <th>Periode</th>
                                    <th>Aksi</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentLogs as $log)
                                    <tr>
                                        <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                                        <td>{{ $log->user->name ?? '-' }}</td>
                                        <td>{{ $log->assessmentPeriod->name ?? '-' }}</td>
                                        <td><span
                                                class="badge badge-{{ $log->getActionBadgeColor() }}">{{ $log->getFormattedAction() }}</span>
                                        </td>
                                        <td>{{ $log->description }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Tidak ada aktivitas terbaru</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('performanceChart').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: {!! json_encode(array_keys($performanceDistribution)) !!},
                    datasets: [{
                        data: {!! json_encode(array_values($performanceDistribution)) !!},
                        backgroundColor: [
                            '#198754', // Excellent - green
                            '#0dcaf0', // Very Good - cyan
                            '#0d6efd', // Good - blue
                            '#ffc107', // Fair - yellow
                            '#dc3545', // Poor - red
                        ],
                    }],
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        title: {
                            display: false,
                        }
                    }
                },
            });
        });
    </script>
@endpush
