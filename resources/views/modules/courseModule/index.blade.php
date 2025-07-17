@section('title', __('All Course'))

<x-layouts.app :title="__('Dashboard')">

    @push('styles')
        @vite(['resources/css/course.css'])
    @endpush

    @livewire('course-table');

    @push('scripts')
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on('click', '#submitAddCourseBtn', function(e) {
                e.preventDefault();

                $('.form-control, .form-select').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                // Simpan error
                let errors = [];

                const fields = [{
                        id: 'subject_name',
                        label: 'Subject Name'
                    },
                    {
                        id: 'subject_code',
                        label: 'Subject Code'
                    },
                    {
                        id: 'credit_hours',
                        label: 'Credit Hours'
                    },
                    {
                        id: 'class_id',
                        label: 'Class'
                    },
                    {
                        id: 'course_id',
                        label: 'Course'
                    },
                    {
                        id: 'lecturer_id',
                        label: 'Lecturer'
                    },
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

                if (errors.length > 0) {
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to add a new subject.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Add it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#addCourseModal form').submit();
                    }
                });
            });

            $(document).on('click', '#submitEditCourseBtn', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to update this subject's details.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, update it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#editCourseModal form').submit();
                    }
                });
            });

            $(document).on('click', '.view-course-btn', function(e) {
                e.preventDefault();
                let url = $(this).data('url');

                $.ajax({
                    url: url,
                    type: 'GET',
                    beforeSend: function() {
                        // Show loading spinner
                        $('#viewCourseModal .modal-body').html(`
                            <div class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Loading course details...</p>
                            </div>
                        `);
                        $('#viewCourseModal').modal('show');
                    },
                    success: function(response) {
                        console.log(response);
                        let lecturersHtml = '-';
                        if (response.lecturers && response.lecturers.length > 0) {
                            lecturersHtml = response.lecturers.map(function(lecturer) {
                                return `${lecturer.lecturer_name} (${lecturer.lecturer_no})<br><small>Expertise: ${lecturer.expertise}</small>`;
                            }).join('<br><br>');
                        }
                        // Card Layout HTML
                        let html = `
                            <div class="row">
                                <div class="col-6" style="border-right: 0.5px solid grey;">
                                    <div class="info-item mb-3">
                                        <div class="info-label">
                                            <i class="fas fa-barcode me-2"></i>Subject Code
                                        </div>
                                        <div class="info-value">${response.subject_code}</div>
                                    </div>
                                    <div class="info-item mb-3">
                                        <div class="info-label">
                                            <i class="fas fa-book me-2"></i>Subject Name
                                        </div>
                                        <div class="info-value">${response.subject_name}</div>
                                    </div>
                                    <div class="info-item mb-3">
                                        <div class="info-label">
                                            <i class="fas fa-clock me-2"></i>Credit Hours
                                        </div>
                                        <div class="info-value">${response.credit_hours}</div>
                                    </div>
                                    <div class="info-item mb-3">
                                        <div class="info-label">
                                            <i class="fas fa-share-nodes me-2"></i>Subject Pre-Requisite
                                        </div>
                                        <div class="info-value">${response.prereq_sub_code} - ${response.prereq_sub_name}</div>
                                    </div>
                                    <div class="info-item mb-3">
                                        <div class="info-label">
                                            <i class="fas fa-chalkboard-teacher me-2"></i>Lecturer Name
                                        </div>
                                        <div class="info-value">${lecturersHtml}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="info-item mb-3">
                                        <div class="info-label">
                                            <i class="fas fa-qrcode me-2"></i>Class Code
                                        </div>
                                        <div class="info-value">${response.class_code}</div>
                                    </div>
                                    <div class="info-item mb-3">
                                        <div class="info-label">
                                            <i class="fas fa-door-open me-2"></i>Class Name
                                        </div>
                                        <div class="info-value">${response.class_name}</div>
                                    </div>
                                    <div class="info-item mb-3">
                                        <div class="info-label">
                                            <i class="fas fa-location-dot me-2"></i>Class Location
                                        </div>
                                        <div class="info-value">${response.class_location}</div>
                                    </div>
                                    <div class="info-item mb-3">
                                        <div class="info-label">
                                            <i class="fas fa-building me-2"></i>Class Department
                                        </div>
                                        <div class="info-value">${response.class_department}</div>
                                    </div>
                                </div>
                            </div>
                        `;

                        $('#viewCourseModal .modal-body').html(html);
                    },
                    error: function(xhr) {
                        $('#viewCourseModal .modal-body').html(`
                            <div class="text-center py-4">
                                <i class="fas fa-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 text-danger">Error Loading Data</h5>
                                <p class="text-muted">Failed to fetch course details. Please try again.</p>
                            </div>
                        `);
                    }
                });
            });


            $(document).on('click', '.edit-course-btn', function(e) {
                e.preventDefault();
                let url = $(this).data('url');
                console.log(url);

                $.ajax({
                    url: url,
                    type: 'GET',
                    beforeSend: function() {
                        // Show loading spinner
                        $('#editCourseModal').modal('show');

                        $('#edit-course-loading').removeClass('d-none');
                        $('#edit-course-form-body').addClass('d-none');
                    },
                    success: function(response) {
                        console.log(response);
                        $('#edit_subject_code').val(response.subject_code);
                        $('#edit_subject_name').val(response.subject_name);
                        $('#edit_credit_hours').val(response.credit_hours);
                        $('#edit_prereq_sub_id').val(response.prereq_sub_id).trigger('change');
                        $('#edit_class_id').val(response.class_id).trigger('change');
                        $('#edit_lecturer_id').val(response.lecturer_id).trigger('change');
                        $('#edit_course_id').val(response.course_id).trigger('change');
                        $('#original_prereq_sub_id').val(response.prereq_sub_id);

                        let updateUrl = `/course/${response.id}`;
                        $('#editCourseModal form').attr('action', updateUrl);

                        $('#edit-course-loading').addClass('d-none');
                        $('#edit-course-form-body').removeClass('d-none');
                    },
                    error: function(xhr) {
                        $('#editCourseModal .modal-body').html(`
                            <div class="text-center py-4">
                                <i class="fas fa-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 text-danger">Error Loading Data</h5>
                                <p class="text-muted">Failed to fetch subject details. Please try again.</p>
                            </div>
                        `);
                    }
                })
            })

            $(document).ready(function() {
                $('#noPrereqCheckbox').on('change', function() {
                    console.log('ayam');
                    if ($(this).is(':checked')) {
                        $('#edit_prereq_sub_id').val('').prop('disabled', true);
                        $('#null_prereq').val(1);
                    } else {
                        $('#edit_prereq_sub_id').prop('disabled', false);
                        const originalValue = $('#original_prereq_sub_id').val();
                        $('#edit_prereq_sub_id').val(originalValue).trigger('change');
                        $('#null_prereq').val(0);
                    }
                });
            });

            $(document).on('click', '.delete-course-btn', function(e) {
                e.preventDefault();
                let url = $(this).data('url');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This subject will be deleted!",
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
                                    'Subject has been deleted.',
                                    'success'
                                );

                                location.reload();
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    'Failed to delete subject.',
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

<!-- Add Course Modal -->
<div class="col-lg-4 col-md-6">
    <div class="modal fade" id="addCourseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="{{ route('course.store') }}" method="post">
                @csrf
                <input type="hidden" name="null_prereq" id="null_prereq" value="0">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalSubjectTitle">Add Subject</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3">
                                <label for="subject_name" class="form-label">Name</label>
                                <input type="text" id="subject_name" name="subject_name" class="form-control"
                                    placeholder="Enter Subject Name" />
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col mb-3">
                                <label for="subject_code" class="form-label">Code</label>
                                <input type="text" id="subject_code" name="subject_code" class="form-control"
                                    placeholder="SUXXXX" />
                            </div>
                            <div class="col mb-0">
                                <label for="credit_hours" class="form-label">Credit Hours</label>
                                <input type="text" id="credit_hours" name="credit_hours" class="form-control"
                                    placeholder="3" />
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col mb-3">
                                <label for="prereq_sub_id" class="form-label">Pre-Requisite</label>
                                <select id="prereq_sub_id" name="prereq_sub_id" class="form-select">
                                    <option value="">Select Subject</option>
                                    @foreach ($subject as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->subject_code }} - {{ $item->subject_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="noPrereqCheckbox">
                                    <label class="form-check-label" for="noPrereqCheckbox">
                                        This course has no prerequisite
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col mb-3">
                                <label for="class_id" class="form-label">Class</label>
                                <select id="class_id" name="class_id" class="form-select">
                                    <option value="">Select Class</option>
                                    @foreach ($class as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->class_code }} - {{ $item->class_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col mb-0">
                                <label for="course_id" class="form-label">Course</label>
                                <select id="course_id" name="course_id" class="form-select">
                                    <option value="">Select Course</option>
                                    @foreach ($course as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->course_code }} - {{ $item->course_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col mb-0">
                                <label for="lecturer_id" class="form-label">Lecturer</label>
                                <select id="lecturer_id" name="lecturer_id" class="form-select">
                                    <option value="">Select Lecturer</option>
                                    @foreach ($lecturer as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->lecturer_no }} - {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" id="submitAddCourseBtn" class="btn btn-primary"><i
                                class="bx bx-edit me-2"></i>Subject</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Subject Modal -->
<div class="col-lg-4 col-md-6">
    <div class="modal fade" id="viewCourseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCourseTitle">Course Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card subject-card">
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

<!-- Edit Subject Modal -->
<div class="col-lg-4 col-md-6">
    <div class="modal fade" id="editCourseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="{{ route('course.update', 0) }}" method="post">
                @method('PUT')
                @csrf
                <input type="hidden" name="null_prereq" id="null_prereq" value="0">
                <input type="hidden" id="original_prereq_sub_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalSubjectTitle">Edit Subject</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="edit-course-loading" class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Loading subject details...</p>
                        </div>
                        <div id="edit-course-form-body" class="d-none">
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="edit_subject_name" class="form-label">Name</label>
                                    <input type="text" id="edit_subject_name" name="subject_name"
                                        class="form-control" placeholder="Enter Subject Name" />
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col mb-3">
                                    <label for="edit_subject_code" class="form-label">Code</label>
                                    <input type="text" id="edit_subject_code" name="subject_code"
                                        class="form-control" placeholder="SUXXXX" />
                                </div>
                                <div class="col mb-0">
                                    <label for="edit_credit_hours" class="form-label">Credit Hours</label>
                                    <input type="text" id="edit_credit_hours" name="credit_hours"
                                        class="form-control" placeholder="3" />
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col mb-3">
                                    <label for="edit_prereq_sub_id" class="form-label">Pre-Requisite</label>
                                    <select id="edit_prereq_sub_id" name="prereq_sub_id" class="form-select">
                                        <option value="">Select Subject</option>
                                        @foreach ($subject as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->subject_code }} - {{ $item->subject_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" id="noPrereqCheckbox">
                                        <label class="form-check-label" for="noPrereqCheckbox">
                                            This course has no prerequisite
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col mb-3">
                                    <label for="edit_class_id" class="form-label">Class</label>
                                    <select id="edit_class_id" name="class_id" class="form-select">
                                        <option value="">Select Class</option>
                                        @foreach ($class as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->class_code }} - {{ $item->class_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col mb-0">
                                    <label for="edit_course_id" class="form-label">Course</label>
                                    <select id="edit_course_id" name="course_id" class="form-select">
                                        <option value="">Select Course</option>
                                        @foreach ($course as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->course_code }} - {{ $item->course_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col mb-0">
                                    <label for="edit_lecturer_id" class="form-label">Lecturer</label>
                                    <select id="edit_lecturer_id" name="lecturer_id" class="form-select">
                                        <option value="">Select Lecturer</option>
                                        @foreach ($lecturer as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->lecturer_no }} - {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" id="submitEditCourseBtn" class="btn btn-primary"><i
                                class="bx bx-edit me-2"></i>Subject</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
