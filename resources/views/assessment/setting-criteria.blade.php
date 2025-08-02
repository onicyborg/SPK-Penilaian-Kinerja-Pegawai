@extends('layouts.master')

@section('title', 'Setting Criteria')

@push('styles')
    <link href="{{ asset('vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="{{ asset('vendor/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-12">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('assessment.setting-criteria', $periode->id) }}">Setting
                            Criteria</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $periode->status == 'draft' ? 'disabled' : '' }}" href="{{ route('assessment.assessment-employee', $periode->id) }}"
                            tabindex="-1" aria-disabled="true">Assessment Process</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $periode->status == 'draft' || $periode->status == 'active' ? 'disabled' : '' }}"
                            href="{{ route('assessment.results', $periode->id) }}" tabindex="-1" aria-disabled="true">Assessment Results</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Setting Criteria for: <b>{{ $periode->name }}</b></h4>
                    </div>
                    <div class="card-body">
                        <form id="criteriaForm">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-bordered" id="criteriaTable">
                                    <thead>
                                        <tr>
                                            <th>Nama Kriteria</th>
                                            <th>Deskripsi</th>
                                            <th>Bobot (%)</th>
                                            @if ($periode->status == 'draft')
                                                <th>Aksi</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($criteria as $c)
                                            <tr>
                                                <td>
                                                    @if ($periode->status == 'draft')
                                                        <input type="text" name="name[]" class="form-control"
                                                            value="{{ $c->name }}" required>
                                                    @else
                                                        {{ $c->name }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($periode->status == 'draft')
                                                        <input type="text" name="description[]" class="form-control"
                                                            value="{{ $c->description }}">
                                                    @else
                                                        {{ $c->description }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($periode->status == 'draft')
                                                        <input type="number" name="weight[]" class="form-control weight-field"
                                                            value="{{ $c->weight }}" min="1" max="100" required>
                                                    @else
                                                        {{ $c->weight }} %
                                                    @endif
                                                </td>
                                                @if ($periode->status == 'draft')
                                                    <td><button type="button" class="btn btn-danger btn-sm remove-row"><i
                                                                class="fa fa-trash"></i></button></td>
                                                @endif
                                            </tr>
                                        @empty
                                            <tr>
                                                <td><input type="text" name="name[]" class="form-control" required></td>
                                                <td><input type="text" name="description[]" class="form-control"></td>
                                                <td><input type="number" name="weight[]" class="form-control weight-field"
                                                        min="1" max="100" required></td>
                                                @if ($periode->status == 'draft')
                                                    <td><button type="button" class="btn btn-danger btn-sm remove-row"><i
                                                                class="fa fa-trash"></i></button></td>
                                                @endif
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            @if ($periode->status == 'draft')
                                                <td colspan="4">
                                                    <button type="button" class="btn btn-success btn-sm" id="addRow"><i
                                                            class="fa fa-plus"></i> Tambah Kriteria</button>
                                                </td>
                                            @endif
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            @if ($periode->status == 'draft')
                                <div class="mt-4 text-right">
                                    <button type="button" class="btn btn-secondary" id="saveDraft">Save to Draft</button>
                                    <button type="button" class="btn btn-primary" id="submitCriteria">Submit Criteria</button>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- SweetAlert2 JS -->
    <script src="{{ asset('vendor/sweetalert2/dist/sweetalert2.min.js') }}"></script>
    <script>
        $(function() {
            // Add row
            $('#addRow').click(function() {
                $('#criteriaTable tbody').append(`
            <tr>
                <td><input type="text" name="name[]" class="form-control" required></td>
                <td><input type="text" name="description[]" class="form-control"></td>
                <td><input type="number" name="weight[]" class="form-control weight-field" min="1" max="100" required></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa fa-trash"></i></button></td>
            </tr>
        `);
            });
            // Remove row, minimal 1 row
            $(document).on('click', '.remove-row', function() {
                if ($('#criteriaTable tbody tr').length > 1) {
                    $(this).closest('tr').remove();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Minimal 1 kriteria!',
                    });
                }
            });
            // Save to Draft
            $('#saveDraft').click(function(e) {
                e.preventDefault();
                submitCriteria('draft');
            });
            // Submit Criteria
            $('#submitCriteria').click(function(e) {
                e.preventDefault();
                // Validate weight sum
                let total = 0;
                $('.weight-field').each(function() {
                    total += parseInt($(this).val()) || 0;
                });
                if (total !== 100) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Total bobot harus 100%!',
                    });
                    return false;
                }
                // Konfirmasi submit
                Swal.fire({
                    icon: 'warning',
                    title: 'Submit Kriteria?',
                    text: 'Data yang sudah di submit tidak akan bisa di ubah lagi!',
                    showCancelButton: true,
                    confirmButtonText: 'Submit',
                }).then((result) => {
                    if (result.value) {
                        submitCriteria('submit');
                    }
                });
            });

            function submitCriteria(type) {
                let url = "{{ route('assessment.setting-criteria', $periode->id) }}";
                let data = $('#criteriaForm').serializeArray();
                data.push({
                    name: 'action',
                    value: type
                });
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('input[name=_token]').val()
                    },
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: res.message,
                                timer: 2000
                            }).then(() => {
                                if (type === 'submit') {
                                    location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: res.message || 'Gagal menyimpan data!',
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat menyimpan data.',
                        });
                    }
                });
            }
        });
    </script>
@endpush
