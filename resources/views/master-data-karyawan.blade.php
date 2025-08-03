@extends('layouts.master')

@section('title')
    Master Data Karyawan
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
                        <h4 class="card-title">Manajemen Karyawan</h4>
                        <div class="card-header-right">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#addEmployeeModal">
                                <i class="fa fa-plus"></i> Tambah Karyawan
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="masterEmployee" class="display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Photo</th>
                                        <th>Nama</th>
                                        <th>Posisi</th>
                                        <th>Departemen</th>
                                        <th>Jenis Kelamin</th>
                                        <th>TTL</th>
                                        <th>Hire Date</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employees as $employee)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if ($employee->photo != null)
                                                    <img src="{{ asset('storage/' . $employee->photo) }}" alt="Photo"
                                                        class="rounded-circle img-fluid" style="width: 50px; height: 50px;">
                                                @else
                                                    <img src="{{ asset('images/employee.png') }}" alt="Photo"
                                                        class="rounded-circle img-fluid" style="width: 50px; height: 50px;">
                                                @endif
                                            </td>
                                            <td>{{ $employee->name }}</td>
                                            <td>{{ $employee->position }}</td>
                                            <td>{{ $employee->department }}</td>
                                            <td>{{ $employee->gender == 'male' ? 'Laki-laki' : ($employee->gender == 'female' ? 'Perempuan' : '-') }}
                                            </td>
                                            <td>
                                                @if ($employee->born_date && $employee->born_place)
                                                    {{ $employee->born_place }}, {{ $employee->born_date }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ $employee->hire_date }}</td>
                                            <td>{{ $employee->phone }}</td>
                                            <td>{{ $employee->email }}</td>
                                            <td>
                                                @if ($employee->status == 'active')
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm"
                                                    onclick="editEmployee('{{ $employee->id }}')">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="deleteEmployee('{{ $employee->id }}', '{{ $employee->name }}')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Photo</th>
                                        <th>Nama</th>
                                        <th>Posisi</th>
                                        <th>Departemen</th>
                                        <th>Jenis Kelamin</th>
                                        <th>TTL</th>
                                        <th>Hire Date</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Status</th>
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

    <!-- Modal Tambah Karyawan -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="addEmployeeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEmployeeModalLabel">Tambah Karyawan Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('master-data-karyawan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="position">Posisi <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="position" name="position" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gender">Gender <span class="text-danger">*</span></label>
                                    <select class="form-control" id="gender" name="gender" required>
                                        <option value="">Pilih Gender</option>
                                        <option value="male">Laki-laki</option>
                                        <option value="female">Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="born_place">Tempat Lahir <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="born_place" name="born_place" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="born_date">Tanggal Lahir <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="born_date" name="born_date" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="department">Departemen <span class="text-danger">*</span></label>
                                    <select class="form-control" id="department" name="department" required>
                                        <option value="">Pilih Departemen</option>
                                        <option value="Human Resources">Human Resources</option>
                                        <option value="Finance">Finance</option>
                                        <option value="Accounting">Accounting</option>
                                        <option value="Marketing">Marketing</option>
                                        <option value="Sales">Sales</option>
                                        <option value="IT">IT</option>
                                        <option value="Production">Production</option>
                                        <option value="Quality Control">Quality Control</option>
                                        <option value="Logistics">Logistics</option>
                                        <option value="Procurement">Procurement</option>
                                        <option value="Legal">Legal</option>
                                        <option value="Research and Development">Research and Development</option>
                                        <option value="Customer Service">Customer Service</option>
                                        <option value="General Affairs">General Affairs</option>
                                        <option value="Business Development">Business Development</option>
                                        <option value="Internal Audit">Internal Audit</option>
                                        <option value="Engineering">Engineering</option>
                                        <option value="HSE">HSE (Health, Safety, Environment)</option>
                                        <option value="Warehouse">Warehouse</option>
                                        <option value="PPIC">PPIC (Production Planning and Inventory Control)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hire_date">Tanggal Masuk <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="hire_date" name="hire_date" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Telepon</label>
                                    <input type="text" class="form-control" id="phone" name="phone">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="active">Aktif</option>
                                        <option value="inactive">Tidak Aktif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="photo">Foto</label>
                                    <input type="file" class="form-control" id="photo" name="photo"
                                        accept="image/*">
                                </div>
                            </div>
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

    <!-- Modal Edit Karyawan -->
    <div class="modal fade" id="editEmployeeModal" tabindex="-1" role="dialog"
        aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEmployeeModalLabel">Edit Karyawan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editEmployeeForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_employee_id" name="id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_name">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_name" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_position">Posisi <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_position" name="position"
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_gender">Gender <span class="text-danger">*</span></label>
                                    <select class="form-control" id="edit_gender" name="gender" required>
                                        <option value="">Pilih Gender</option>
                                        <option value="male">Laki-laki</option>
                                        <option value="female">Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_born_place">Tempat Lahir <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_born_place" name="born_place"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_born_date">Tanggal Lahir <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="edit_born_date" name="born_date"
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_department">Departemen <span class="text-danger">*</span></label>
                                    <select class="form-control" id="edit_department" name="department" required>
                                        <option value="">Pilih Departemen</option>
                                        <option value="Human Resources">Human Resources</option>
                                        <option value="Finance">Finance</option>
                                        <option value="Accounting">Accounting</option>
                                        <option value="Marketing">Marketing</option>
                                        <option value="Sales">Sales</option>
                                        <option value="IT">IT</option>
                                        <option value="Production">Production</option>
                                        <option value="Quality Control">Quality Control</option>
                                        <option value="Logistics">Logistics</option>
                                        <option value="Procurement">Procurement</option>
                                        <option value="Legal">Legal</option>
                                        <option value="Research and Development">Research and Development</option>
                                        <option value="Customer Service">Customer Service</option>
                                        <option value="General Affairs">General Affairs</option>
                                        <option value="Business Development">Business Development</option>
                                        <option value="Internal Audit">Internal Audit</option>
                                        <option value="Engineering">Engineering</option>
                                        <option value="HSE">HSE (Health, Safety, Environment)</option>
                                        <option value="Warehouse">Warehouse</option>
                                        <option value="PPIC">PPIC (Production Planning and Inventory Control)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_hire_date">Tanggal Masuk <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="edit_hire_date" name="hire_date"
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_phone">Telepon</label>
                                    <input type="text" class="form-control" id="edit_phone" name="phone">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_email">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="edit_email" name="email" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_status">Status <span class="text-danger">*</span></label>
                                    <select class="form-control" id="edit_status" name="status" required>
                                        <option value="active">Aktif</option>
                                        <option value="inactive">Tidak Aktif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_photo">Foto</label>
                                    <input type="file" class="form-control" id="edit_photo" name="photo"
                                        accept="image/*">
                                    <small class="text-muted">Kosongkan jika tidak ingin mengubah foto</small>
                                </div>
                            </div>
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
        (function($) {
            "use strict"
            var table2 = $('#masterEmployee').DataTable({
                createdRow: function(row, data, index) {
                    $(row).addClass('selected')
                },

                "scrollY": "60vh",
                "scrollCollapse": true,
                "paging": false
            });

        })(jQuery);

        // Function to edit employee
        function editEmployee(id) {
            // Get employee data via AJAX
            $.ajax({
                url: '{{ route('master-data-karyawan.get', ':id') }}'.replace(':id', id),
                type: 'GET',
                success: function(response) {
                    // Populate form fields
                    $('#edit_employee_id').val(response.id);
                    $('#edit_name').val(response.name);
                    $('#edit_position').val(response.position);
                    $('#edit_department').val(response.department);
                    $('#edit_gender').val(response.gender);
                    $('#edit_born_place').val(response.born_place);
                    var bornDate = new Date(response.born_date);
                    $('#edit_born_date').val(bornDate.toISOString().slice(0, 10));
                    var hireDate = new Date(response.hire_date);
                    $('#edit_hire_date').val(hireDate.toISOString().slice(0, 10));
                    $('#edit_phone').val(response.phone);
                    $('#edit_email').val(response.email);
                    $('#edit_status').val(response.status);

                    // Show modal
                    $('#editEmployeeModal').modal('show');
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Gagal mengambil data karyawan',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }

        // Function to delete employee
        function deleteEmployee(id, name) {
            Swal.fire({
                title: 'Hapus Karyawan',
                text: `Apakah Anda yakin ingin menghapus karyawan ${name}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    // Create form and submit
                    var form = $('<form>', {
                        'method': 'POST',
                        'action': `{{ route('master-data-karyawan.delete', ':id') }}`.replace(':id', id)
                    });

                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': '_token',
                        'value': '{{ csrf_token() }}'
                    }));

                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': '_method',
                        'value': 'DELETE'
                    }));

                    $('body').append(form);
                    form.submit();
                }
            });
        }

        // Handle Edit Employee Form Submission
        $('#editEmployeeForm').on('submit', function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            var employeeId = $('#edit_employee_id').val();

            $.ajax({
                url: '{{ route('master-data-karyawan.update', ':id') }}'.replace(':id', employeeId),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#editEmployeeModal').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data karyawan berhasil diupdate!',
                        confirmButtonText: 'OK',
                        timer: 3000,
                        timerProgressBar: true
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
    </script>

    <!-- SweetAlert Messages -->
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK',
                timer: 3000,
                timerProgressBar: true
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            let errorMessages = '';
            @foreach ($errors->all() as $error)
                errorMessages += '{{ $error }}\n';
            @endforeach

            Swal.fire({
                icon: 'error',
                title: 'Validation Error!',
                text: errorMessages,
                confirmButtonText: 'OK'
            });
        </script>
    @endif
@endpush
