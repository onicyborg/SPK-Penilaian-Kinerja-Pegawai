@extends('layouts.master')

@section('title', 'Assessment Process')

@push('styles')
    <link href="{{ asset('vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">
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
                        <a class="nav-link active"
                            href="{{ route('assessment.assessment-employee', $periode->id) }}">Assessment Process</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $periode->status == 'draft' || $periode->status == 'active' ? 'disabled' : '' }}"
                            href="{{ route('assessment.results', $periode->id) }}" tabindex="-1"
                            aria-disabled="true">Assessment Results</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('assessment.logs', $periode->id) }}">Assessment Logs</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Assessment Process for: <b>{{ $periode->name }}</b></h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="employeeAssessmentTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Department</th>
                                        <th>Status Assessment</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employees as $employee)
                                        <tr>
                                            <td>{{ $employee->name }}</td>
                                            <td>{{ $employee->position }}</td>
                                            <td>{{ $employee->department }}</td>
                                            <td>
                                                @if (isset($employee->assessment) && count($employee->assessment) > 0)
                                                    <span class="badge badge-success">Saved</span>
                                                @else
                                                    <span class="badge badge-secondary">Not Yet</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                                    data-target="#employeeDetailModal"
                                                    data-employee='@json($employee)'><i
                                                        class="fa fa-user"></i></button>
                                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                                    data-target="#assessmentModal" data-employeeid="{{ $employee->id }}"
                                                    data-employee='@json($employee)'><i
                                                        class="fa fa-edit"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($periode->status == 'active')
                            <div class="mt-4 text-right">
                                <button type="button" class="btn btn-primary" id="submitAssessment"
                                    data-periode-id="{{ $periode->id }}"
                                    data-employees="{{ json_encode($employees) }}">Submit Assessment</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Employee -->
    <div class="modal fade" id="employeeDetailModal" tabindex="-1" role="dialog"
        aria-labelledby="employeeDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="employeeDetailModalLabel">Employee Detail</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="employeeDetailBody">
                    <!-- Filled by JS -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Assessment -->
    <div class="modal fade" id="assessmentModal" tabindex="-1" role="dialog" aria-labelledby="assessmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="assessmentForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="assessmentModalLabel">Assessment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="employee_id" id="assessment_employee_id">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Criteria</th>
                                    <th>Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($criteria as $c)
                                    <tr>
                                        <td>{{ $c->name }}</td>
                                        <td>
                                            <select name="score[{{ $c->id }}]" class="form-control" required>
                                                <option value="">Pilih Nilai</option>
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        @if ($periode->status == 'active')
                            <button type="submit" class="btn btn-primary">Save</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('vendor/sweetalert2/dist/sweetalert2.min.js') }}"></script>
    <script>
        $(function() {
            // Detail employee modal
            $('#employeeDetailModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var employee = button.data('employee');
                var html = '<div class="text-center mb-3">';
                html += '<img src="' + (employee.photo ? '/storage/' + employee.photo :
                        '/images/employee.png') +
                    '" alt="Photo" class="rounded-circle img-fluid" style="width: 100px; height: 100px;">';
                html += '</div>';
                html += '<table class="table table-bordered">';
                html += '<tr><th>Name</th><td>' + employee.name + '</td></tr>';
                html += '<tr><th>Position</th><td>' + employee.position + '</td></tr>';
                html += '<tr><th>Department</th><td>' + employee.department + '</td></tr>';
                html += '<tr><th>Hire Date</th><td>' + (employee.hire_date ? new Intl.DateTimeFormat(
                    'id-ID', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    }).format(new Date(employee.hire_date)) : '-') + '</td></tr>';
                html += '<tr><th>Phone</th><td>' + (employee.phone || '-') + '</td></tr>';
                html += '<tr><th>Status</th><td>' + employee.status + '</td></tr>';
                html += '<tr><th>Email</th><td>' + (employee.email || '-') + '</td></tr>';
                html += '<tr><th>Gender</th><td>' + (employee.gender == 'male' ? 'Laki-laki' : (employee
                    .gender == 'female' ? 'Perempuan' : '-')) + '</td></tr>';
                html += '<tr><th>Birth Date</th><td>' + (employee.born_date ? employee.born_place + ', ' +
                    new Intl.DateTimeFormat('id-ID', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    }).format(new Date(employee.born_date)) : '-') + '</td></tr>';
                html += '</table>';
                $('#employeeDetailBody').html(html);
            });
            // Assessment modal
            $('#assessmentModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var employee = button.data('employee');
                $('#assessment_employee_id').val(employee.id);
                // Reset all selects to empty
                $('#assessmentModal select[name^="score["]').val("");
                // Prefill jika sudah ada assessment
                if (employee.assessment && employee.assessment.length > 0) {
                    employee.assessment.forEach(function(item) {
                        var select = $('#assessmentModal select[name="score[' + item.criteria_id +
                            ']"]');
                        if (select.length) {
                            select.val(item.score);
                        }
                    });
                }
            });
            // Handle submit assessment
            $('#assessmentForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ route('assessment.store', $periode->id) }}',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                confirmButtonText: 'OK',
                                timer: 2000
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message,
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat menyimpan data.',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            // Submit Assessment
            $('#submitAssessment').on('click', function(e) {
                e.preventDefault();
                var periodeId = $(this).data('periode-id');
                var employees = $(this).data('employees');
                // console.log(employees);
                var hasUnfinished = false;
                $.each(employees, function(i, emp) {
                    if (!emp.assessment || emp.assessment.length === 0) {
                        hasUnfinished = true;
                        return false;
                    }
                });
                if (hasUnfinished) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Ada employee yang belum di assessment, harap lengkapi terlebih dahulu!',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Jika Anda submit assessment, maka Anda tidak akan bisa mengubahnya lagi. Pastikan Anda telah mengisi semua assessment dengan benar.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, saya yakin!'
                }).then((result) => {
                    if (result.value) {
                        Swal.fire({
                            title: 'Loading...',
                            allowOutsideClick: false,
                            showCancelButton: false,
                            showConfirmButton: false,
                            onOpen: () => {
                                swal.showLoading()
                            }
                        });
                        $.ajax({
                            url: '{{ route('assessment.store.saw', ':periode_id') }}'
                                .replace(':periode_id', periodeId),
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: response.message,
                                        confirmButtonText: 'OK',
                                        timer: 2000
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: response.message,
                                        confirmButtonText: 'OK'
                                    });
                                }
                            },
                            error: function(xhr) {
                                console.log(xhr);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Terjadi kesalahan saat menyimpan data.',
                                    confirmButtonText: 'OK'
                                });
                            },
                            complete: function() {
                                swal.hideLoading();
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
