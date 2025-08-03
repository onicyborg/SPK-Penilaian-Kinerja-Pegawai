@extends('layouts.master')

@section('title', 'Manage Assessor')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Data Assessor</h4>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addAssessorModal">Tambah
                        Assessor</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="assessorTable" class="display table table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-info btn-edit" data-id="{{ $user->id }}" data-name="{{ $user->name }}" data-email="{{ $user->email }}" data-username="{{ $user->username }}">Edit</button>
                                            <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $user->id }}">Delete</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Assessor -->
    <div class="modal fade" id="addAssessorModal" tabindex="-1" role="dialog" aria-labelledby="addAssessorModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAssessorModalLabel">Tambah Assessor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addAssessorForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
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

    <!-- Modal Edit Assessor -->
    <div class="modal fade" id="editAssessorModal" tabindex="-1" role="dialog" aria-labelledby="editAssessorModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAssessorModalLabel">Edit Assessor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editAssessorForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="form-group">
                            <label for="edit_name">Nama</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_username">Username</label>
                            <input type="text" class="form-control" id="edit_username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_email">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_password">Password <small>(isi jika ingin ganti)</small></label>
                            <input type="password" class="form-control" id="edit_password" name="password">
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
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            var table = $('#assessorTable').DataTable();

            // Add Assessor
            $('#addAssessorForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ route('manage-assessor.store') }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(res) {
                        $('#addAssessorModal').modal('hide');
                        location.reload();
                        Swal.fire('Sukses', 'Assessor berhasil ditambahkan', 'success');
                        $('#addAssessorForm')[0].reset();
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON.message || 'Terjadi kesalahan',
                            'error');
                    }
                });
            });

            // Add Assessor
            $('#addAssessorForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ route('manage-assessor.store') }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(res) {
                        $('#addAssessorModal').modal('hide');
                        table.ajax.reload();
                        Swal.fire('Sukses', 'Assessor berhasil ditambahkan', 'success');
                        $('#addAssessorForm')[0].reset();
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON.message || 'Terjadi kesalahan',
                            'error');
                    }
                });
            });

            // Edit Assessor (open modal)
            $(document).on('click', '.btn-edit', function() {
                var data = $(this).data();
                $('#edit_id').val(data.id);
                $('#edit_name').val(data.name);
                $('#edit_username').val(data.username);
                $('#edit_email').val(data.email);
                $('#editAssessorModal').modal('show');
            });

            // Update Assessor
            $('#editAssessorForm').submit(function(e) {
                e.preventDefault();
                var id = $('#edit_id').val();
                $.ajax({
                    url: '/manage-assessor/' + id,
                    method: 'PUT',
                    data: $(this).serialize(),
                    success: function(res) {
                        $('#editAssessorModal').modal('hide');
                        Swal.fire('Sukses', 'Assessor berhasil diupdate', 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON.message || 'Terjadi kesalahan',
                            'error');
                    }
                });
            });

            // Delete Assessor
            $(document).on('click', '.btn-delete', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Yakin?',
                    text: 'Data assessor akan dihapus!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/manage-assessor/' + id,
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(res) {
                                Swal.fire('Sukses', 'Assessor berhasil dihapus',
                                    'success');
                                setTimeout(function() {
                                    location.reload();
                                }, 3000);
                            },
                            error: function(xhr) {
                                Swal.fire('Error', xhr.responseJSON.message ||
                                    'Terjadi kesalahan', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
