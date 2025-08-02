@extends('layouts.master')

@section('title')
    Manajemen Assessment Periode
@endsection

@push('styles')
    <!-- DataTables CSS -->
    <link href="{{ asset('vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="{{ asset('vendor/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Manajemen Assessment Periode</h4>
                        <div class="card-header-right">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#addPeriodeModal">
                                <i class="fa fa-plus"></i> Tambah Periode
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="assessmentPeriodeTable" class="display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Created By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($periodes as $periode)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $periode->name }}</td>
                                            <td>{{ $periode->description }}</td>
                                            <td>
                                                @if ($periode->status == 'active')
                                                    <span class="badge badge-success">Active</span>
                                                @elseif ($periode->status == 'completed')
                                                    <span class="badge badge-info">Completed</span>
                                                @else
                                                    <span class="badge badge-secondary">Draft</span>
                                                @endif
                                            </td>
                                            <td>{{ $periode->creator->name ?? '-' }}</td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm"
                                                    onclick="editPeriode('{{ $periode->id }}', '{{ $periode->name }}', `{{ $periode->description }}`, '{{ $periode->status }}')">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="deletePeriode('{{ $periode->id }}', '{{ $periode->name }}')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                                <a href="{{ route('assessment.setting-criteria', $periode->id) }}"
                                                    class="btn btn-info btn-sm">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Created By</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Periode -->
    <div class="modal fade" id="addPeriodeModal" tabindex="-1" role="dialog" aria-labelledby="addPeriodeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="addPeriodeForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPeriodeModalLabel">Tambah Assessment Periode</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Periode</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea class="form-control" name="description" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Periode -->
    <div class="modal fade" id="editPeriodeModal" tabindex="-1" role="dialog" aria-labelledby="editPeriodeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editPeriodeForm">
                    @csrf
                    <input type="hidden" name="id" id="edit_periode_id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPeriodeModalLabel">Edit Assessment Periode</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Periode</label>
                            <input type="text" class="form-control" name="name" id="edit_periode_name" required>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea class="form-control" name="description" id="edit_periode_description" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- DataTables JS -->
    <script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <!-- SweetAlert2 JS -->
    <script src="{{ asset('vendor/sweetalert2/dist/sweetalert2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#assessmentPeriodeTable').DataTable();
        });

        // Modal Edit Periode
        function editPeriode(id, name, description) {
            $('#edit_periode_id').val(id);
            $('#edit_periode_name').val(name);
            $('#edit_periode_description').val(description);
            $('#editPeriodeModal').modal('show');
        }

        // Handle Add Periode
        $('#addPeriodeForm').on('submit', function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: '{{ route('assessment-periode.store') }}',
                type: 'POST',
                data: formData,
                success: function(response) {
                    $('#addPeriodeModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Assessment Periode berhasil ditambahkan!',
                        confirmButtonText: 'OK',
                        timer: 2000
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = 'Terjadi kesalahan:';
                    if (errors) {
                        $.each(errors, function(key, value) {
                            errorMessage += '\n- ' + value[0];
                        });
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMessage,
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        // Handle Edit Periode
        $('#editPeriodeForm').on('submit', function(e) {
            e.preventDefault();
            var id = $('#edit_periode_id').val();
            var formData = $(this).serialize();
            $.ajax({
                url: '{{ url('assessment-periode/update') }}/' + id,
                type: 'POST',
                data: formData,
                success: function(response) {
                    $('#editPeriodeModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Assessment Periode berhasil diupdate!',
                        confirmButtonText: 'OK',
                        timer: 2000
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = 'Terjadi kesalahan:';
                    if (errors) {
                        $.each(errors, function(key, value) {
                            errorMessage += '\n- ' + value[0];
                        });
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMessage,
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        // Handle Delete Periode
        function deletePeriode(id, name) {
            Swal.fire({
                title: 'Hapus Assessment Periode',
                text: `Apakah Anda yakin ingin menghapus periode ${name}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '{{ url('assessment-periode/delete') }}/' + id,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Assessment Periode berhasil dihapus!',
                                confirmButtonText: 'OK',
                                timer: 2000
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Gagal menghapus Assessment Periode!',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endpush
