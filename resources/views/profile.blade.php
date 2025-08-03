@extends('layouts.master')

@section('title')
    Profile
@endsection

@push('styles')
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Informasi Profile</span>
                    <div>
                        <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#editProfileModal">Edit Profile</button>
                        <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#changePasswordModal">Ganti Password</button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Nama</th>
                            <td>{{ Auth::user()->name }}</td>
                        </tr>
                        <tr>
                            <th>Username</th>
                            <td>{{ Auth::user()->username }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ Auth::user()->email }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Profile -->
<div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editProfileForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_name">Nama</label>
                        <input type="text" class="form-control" id="edit_name" name="name" value="{{ Auth::user()->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_username">Username</label>
                        <input type="text" class="form-control" id="edit_username" name="username" value="{{ Auth::user()->username }}" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_email">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email" value="{{ Auth::user()->email }}" required>
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

<!-- Modal Ganti Password -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">Ganti Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="changePasswordForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="current_password">Password Lama</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">Password Baru</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password_confirmation">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function() {
    // Update Profile
    $('#editProfileForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route('profile.update') }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                $('#editProfileModal').modal('hide');
                Swal.fire('Sukses', res.message, 'success');
                setTimeout(function(){ location.reload(); }, 2000);
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON.message || 'Terjadi kesalahan', 'error');
            }
        });
    });

    // Update Password
    $('#changePasswordForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route('profile.update-password') }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                $('#changePasswordModal').modal('hide');
                Swal.fire('Sukses', res.message, 'success');
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON.message || 'Terjadi kesalahan', 'error');
            }
        });
    });
});
</script>
@endpush


@push('scripts')
@endpush
