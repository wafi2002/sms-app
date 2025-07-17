@section('title', __('All Student'))

<x-layouts.app>

    @push('styles')
        @vite(['resources/css/student.css'])
    @endpush

    @livewire('student-table')

    @push('scripts')
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on('click', '.view-student-btn', function(e) {
                e.preventDefault();
                let url = $(this).data('url');

                $.ajax({
                    url: url,
                    type: 'GET',
                    beforeSend: function() {
                        // Show loading spinner
                        $('#viewStudentModal .modal-body').html(`
                            <div class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Loading student details...</p>
                            </div>
                        `);
                        $('#viewStudentModal').modal('show');
                    },
                    success: function(response) {
                        console.log(response);

                        // Get initials for avatar
                        let initials = response.name.split(' ').map(word => word.charAt(0)).join('')
                            .toUpperCase();

                        // Card Layout HTML
                        let html = `
                            <div class="row">
                                <div class="col-3 d-flex justify-content-center">
                                    <div class="student-avatar">
                                        ${initials}
                                    </div>
                                </div>
                                <div class="col-9">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-id-card me-2"></i>Matric No
                                        </div>
                                        <div class="info-value">${response.matric_no}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-user me-2"></i>Name
                                        </div>
                                        <div class="info-value">${response.name}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-phone-alt me-2"></i>Phone No
                                        </div>
                                        <div class="info-value">${response.phone_no}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-envelope me-2"></i>Email
                                        </div>
                                        <div class="info-value">${response.email}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-map-marker-alt me-2"></i>Address
                                        </div>
                                        <div class="info-value">${response.address}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-graduation-cap me-2"></i>Course
                                        </div>
                                        <div class="info-value">
                                            ${response.course_name} (${response.course_code})
                                            <span class="badge badge-custom ms-2">Active</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;

                        $('#viewStudentModal .modal-body').html(html);
                    },
                    error: function(xhr) {
                        $('#viewStudentModal .modal-body').html(`
                            <div class="text-center py-4">
                                <i class="fas fa-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 text-danger">Error Loading Data</h5>
                                <p class="text-muted">Failed to fetch student details. Please try again.</p>
                            </div>
                        `);
                    }
                });
            });

            $(document).on('click', '#submitAddStudentBtn', function(e) {
                e.preventDefault();

                $('.form-control, .form-select').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                // Simpan error
                let errors = [];

                const fields = [{
                        id: 'name',
                        label: 'Name'
                    },
                    {
                        id: 'email',
                        label: 'Email'
                    },
                    {
                        id: 'phone_no',
                        label: 'Phone Number'
                    },
                    {
                        id: 'ic_no',
                        label: 'IC Number'
                    },
                    {
                        id: 'matric_no',
                        label: 'Matric Number'
                    },
                    {
                        id: 'course_id',
                        label: 'Course'
                    },
                    {
                        id: 'gender',
                        label: 'Gender'
                    },
                    {
                        id: 'address',
                        label: 'Address'
                    }
                ];

                fields.forEach(field => {
                    const input = $('#' + field.id);
                    const value = input.val()?.trim();

                    if (!value) {
                        input.addClass('is-invalid');
                        input.after(`<div class="invalid-feedback">${field.label} is required.</div>`);
                        errors.push(`${field.label} is required.`);
                    }
                });

                const email = $('#email').val()?.trim();
                if (email && !/^\S+@\S+\.\S+$/.test(email)) {
                    $('#email').addClass('is-invalid');
                    $('#email').after('<div class="invalid-feedback">Invalid email format.</div>');
                    errors.push('Invalid email format.');
                }

                if (errors.length > 0) {
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to add a new student.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Add it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#addStudentModal form').submit();
                    }
                });
            });

            $(document).on('click', '#submitEditStudentBtn', function(e) {
                e.preventDefault();

                $('.form-control, .form-select').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                // Simpan error
                let errors = [];

                const fields = [{
                        id: 'edit_name',
                        label: 'Name'
                    },
                    {
                        id: 'edit_email',
                        label: 'Email'
                    },
                    {
                        id: 'edit_phone_no',
                        label: 'Phone Number'
                    },
                    {
                        id: 'edit_ic_no',
                        label: 'IC Number'
                    },
                    {
                        id: 'edit_matric_no',
                        label: 'Matric Number'
                    },
                    {
                        id: 'edit_course_id',
                        label: 'Course'
                    },
                    {
                        id: 'edit_gender',
                        label: 'Gender'
                    },
                    {
                        id: 'edit_address',
                        label: 'Address'
                    }
                ];

                fields.forEach(field => {
                    const input = $('#' + field.id);
                    const value = input.val()?.trim();

                    if (!value) {
                        input.addClass('is-invalid');
                        input.after(`<div class="invalid-feedback">${field.label} is required.</div>`);
                        errors.push(`${field.label} is required.`);
                    }
                });

                const email = $('#email').val()?.trim();
                if (email && !/^\S+@\S+\.\S+$/.test(email)) {
                    $('#email').addClass('is-invalid');
                    $('#email').after('<div class="invalid-feedback">Invalid email format.</div>');
                    errors.push('Invalid email format.');
                }

                if (errors.length > 0) {
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to update this student's details.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, update it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#editStudentModal form').submit();
                    }
                });
            });


            $(document).on('click', '.edit-student-btn', function(e) {
                e.preventDefault();
                let url = $(this).data('url');
                console.log(url);

                $.ajax({
                    url: url,
                    type: 'GET',
                    beforeSend: function() {
                        // Show loading spinner
                        $('#editStudentModal').modal('show');

                        $('#edit-student-loading').removeClass('d-none');
                        $('#edit-student-form-body').addClass('d-none');
                    },
                    success: function(response) {
                        console.log(response);
                        $('#edit_name').val(response.name);
                        $('#edit_email').val(response.email);
                        $('#edit_password').val(response.password);
                        $('#edit_ic_no').val(response.ic_no);
                        $('#edit_phone_no').val(response.phone_no);
                        $('#edit_matric_no').val(response.matric_no);
                        $('#edit_course_id').val(response.course_id).trigger('change');
                        $('#edit_gender').val(response.gender).trigger('change');
                        $('#edit_address').val(response.address);

                        let updateUrl = `/students/${response.id}`;
                        $('#editStudentModal form').attr('action', updateUrl);

                        $('#edit-student-loading').addClass('d-none');
                        $('#edit-student-form-body').removeClass('d-none');
                    },
                    error: function(xhr) {
                        $('#editStudentModal .modal-body').html(`
                            <div class="text-center py-4">
                                <i class="fas fa-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 text-danger">Error Loading Data</h5>
                                <p class="text-muted">Failed to fetch student details. Please try again.</p>
                            </div>
                        `);
                    }
                })
            })

            $(document).on('click', '.delete-student-btn', function(e) {
                e.preventDefault();
                let url = $(this).data('url');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This student will be deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // User confirmed deletion
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            success: function(response) {
                                Swal.fire(
                                    'Deleted!',
                                    'Student has been deleted.',
                                    'success'
                                );

                                location.reload();
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    'Failed to delete student.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            })
        </script>
    @endpush

</x-layouts.app>

@if (session('success'))
    <script>
        Swal.fire({
            title: 'Success!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            title: 'Error!',
            text: '{{ session('error') }}',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    </script>
@endif

<!-- Add Student Modal -->
<div class="col-lg-4 col-md-6">
    <div class="modal fade" id="addStudentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="{{ route('students.store') }}" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalStudentTitle">Add Student</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" id="name" name="name" class="form-control"
                                    placeholder="Enter Name" required />
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col mb-0">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" id="email" name="email" class="form-control"
                                    placeholder="xxxx@xxx.xx" required />
                            </div>
                            <div class="col mb-0">
                                <label for="phone_no" class="form-label">Phone No</label>
                                <input type="text" id="phone_no" name="phone_no" class="form-control"
                                    placeholder="012-xxxxxxxx" required />
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col mb-0">
                                <label for="ic_no" class="form-label">IC No</label>
                                <input type="text" id="ic_no" name="ic_no" class="form-control"
                                    placeholder="02xxxxxxxxxx" required />
                            </div>
                            <div class="col mb-3">
                                <label for="matric_no" class="form-label">Matric no</label>
                                <input type="text" id="matric_no" name="matric_no" class="form-control"
                                    placeholder="S1XXXXX" required />
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col mb-0">
                                <label for="course_id" class="form-label">Course</label>
                                <select id="course_id" name="course_id" class="form-select" required>
                                    <option value="" disabled>Select Course</option>
                                    @foreach ($course as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->course_code }} - {{ $item->course_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col mb-0">
                                <label for="gender" class="form-label">Gender</label>
                                <select name="gender" id="gender" class="form-select" required>
                                    <option value="" disabled>Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea id="address" name="address" class="form-control" placeholder="Road 2/9, Alaska" rows="4"
                                    cols="50" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" id="submitAddStudentBtn" class="btn btn-primary"><i
                                class="bx bx-plus"></i>Student</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Student Modal -->
<div class="col-lg-4 col-md-6">
    <div class="modal fade" id="viewStudentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalStudentTitle">Student Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card student-card">
                                <div class="card-body">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Student Modal -->
<div class="col-lg-4 col-md-6">
    <div class="modal fade" id="editStudentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="{{ route('students.update', 0) }}" method="post">
                @method('PUT')
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalStudentTitle">Edit Student</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="edit-student-loading" class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Loading student details...</p>
                        </div>
                        <div id="edit-student-form-body" class="d-none">
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="edit_name" class="form-label">Name</label>
                                    <input type="text" id="edit_name" name="name" class="form-control"
                                        placeholder="Enter Name" />
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col mb-0">
                                    <label for="edit_email" class="form-label">Email</label>
                                    <input type="text" id="edit_email" name="email" class="form-control"
                                        placeholder="xxxx@xxx.xx" />
                                </div>
                                <div class="col mb-0">
                                    <label for="edit_phone_no" class="form-label">Phone No</label>
                                    <input type="text" id="edit_phone_no" name="phone_no" class="form-control"
                                        placeholder="012-xxxxxxxx" />
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col mb-0">
                                    <label for="edit_ic_no" class="form-label">IC No</label>
                                    <input type="text" id="edit_ic_no" name="ic_no" class="form-control"
                                        placeholder="02xxxxxxxxxx" />
                                </div>
                                <div class="col mb-3">
                                    <label for="edit_matric_no" class="form-label">Matric no</label>
                                    <input type="text" id="edit_matric_no" name="matric_no" class="form-control"
                                        placeholder="S1XXXXX" />
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col mb-0">
                                    <label for="edit_course_id" class="form-label">Course</label>
                                    <select id="edit_course_id" name="course_id" class="form-select">
                                        <option value="" disabled>Select Course</option>
                                        @foreach ($course as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->course_code }} - {{ $item->course_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col mb-0">
                                    <label for="edit_gender" class="form-label">Gender</label>
                                    <select name="gender" id="edit_gender" class="form-select">
                                        <option value="" disabled>Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col mb-3">
                                    <label for="edit_address" class="form-label">Address</label>
                                    <textarea id="edit_address" name="address" class="form-control" placeholder="Road 2/9, Alaska" rows="4"
                                        cols="50"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" id="submitEditStudentBtn" class="btn btn-primary"><i
                                class="bx bx-edit"></i>Student</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
