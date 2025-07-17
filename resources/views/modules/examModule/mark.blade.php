@section('title', __('Exam Mark'))

<x-layouts.app>
    @push('styles')
        @vite(['resources/css/course.css'])
    @endpush

    @livewire('exam-table')

    @push('scripts')
        <script>
            function attachMarkListeners() {
                document.querySelectorAll('.update-mark').forEach(function(input) {
                    input.removeEventListener('change', handleMarkChange);
                    input.addEventListener('change', handleMarkChange);
                });
            }

            $(document).on('click', '.submit-mark-btn', handleMarkChange);

            function handleMarkChange(event) {
                event.preventDefault();
                let btn = $(event.currentTarget);
                let id = btn.data('id');
                let url = btn.data('url');

                let input = btn.closest('.input-group').find('.update-mark');
                let mark = parseInt(input.val());

                if (mark > 100) {
                    mark = 100;
                    input.val(100);
                } else if (mark < 0) {
                    mark = 0;
                    input.val(0);
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to update the mark to ${mark}.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, update it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(url, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                body: JSON.stringify({
                                    marks: mark,
                                    id: id
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: 'Mark updated successfully.',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                    Livewire.dispatch('refreshExamMarkTable');
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Failed to update mark.'
                                    });
                                }
                            })
                            .catch(err => {
                                console.error(err);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Something went wrong!'
                                });
                            });
                    } else {
                        // If cancelled, reset input value to previous
                        input.val(input[0].defaultValue);
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                attachMarkListeners();

                Livewire.hook('message.processed', (message, component) => {
                    attachMarkListeners();
                });
            });

            document.getElementById('student_id').addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const courseName = selectedOption.getAttribute('data-course-name') || '-';
                const courseCode = selectedOption.getAttribute('data-course-code') || '-';
                const stringCourse = `${courseCode} - ${courseName}`;
                document.getElementById('course').value = stringCourse;
            });

            document.getElementById('marks').addEventListener('input', function() {
                if (this.value > 100) {
                    this.value = 100;
                } else if (this.value < 0) {
                    this.value = 0;
                }
            });

            document.getElementById('marks').addEventListener('input', function() {
                const marks = parseFloat(this.value);
                let grade = '';

                if (!isNaN(marks)) {
                    if (marks >= 85) {
                        grade = 'A';
                    } else if (marks >= 80) {
                        grade = 'A-';
                    } else if (marks >= 75) {
                        grade = 'B+';
                    } else if (marks >= 70) {
                        grade = 'B';
                    } else if (marks >= 65) {
                        grade = 'B-';
                    } else if (marks >= 60) {
                        grade = 'C+';
                    } else if (marks >= 55) {
                        grade = 'C';
                    } else if (marks >= 50) {
                        grade = 'D';
                    } else {
                        grade = 'F';
                    }
                }

                document.getElementById('grade').value = grade;
            });
        </script>
    @endpush

    <!-- Add Student Mark Modal -->
    <div class="col-lg-4 col-md-6">
        <div class="modal fade" id="addStudentMarksModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form action="{{ route('exams.store') }}" method="post">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalStudentTitle">Add Student Marks</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="student_id" class="form-label">Student</label>
                                    <select id="student_id" name="student_id" class="form-select ">
                                        <option value="">Select Student</option>
                                        @foreach ($student as $std)
                                            <option value="{{ $std->id }}"
                                                data-course-name="{{ $std->course->course_name ?? '-' }}"
                                                data-course-code="{{ $std->course->course_code ?? '-' }}">
                                                {{ $std->matric_no }} -
                                                {{ $std->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="course" class="form-label">Course</label>
                                    <input type="text" id="course" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col-6 mb-3">
                                    <label for="subject_id" class="form-label">Subject</label>
                                    <select id="subject_id" name="subject_id" class="form-select ">
                                        <option value="">Select Subject</option>
                                        @foreach ($subject as $sbj)
                                            <option value="{{ $sbj->id }}">{{ $sbj->subject_code }} -
                                                {{ $sbj->subject_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="exam_id" class="form-label">Exam</label>
                                    <select id="exam_id" name="exam_id" class="form-select ">
                                        <option value="">Select Exam</option>
                                        @foreach ($exam as $exm)
                                            <option value="{{ $exm->id }}">{{ $exm->exam_code }} -
                                                {{ $exm->exam_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col-12 mb-3">
                                    <label for="lecturer_id" class="form-label">Requester</label>
                                    <select id="lecturer_id" name="lecturer_id" class="form-select">
                                        <option value="">Select Lecturer</option>
                                        @foreach ($lecturer as $ltr)
                                            <option value="{{ $ltr->id }}">{{ $ltr->lecturer_no }} -
                                                {{ $ltr->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col-6 mb-3">
                                    <label for="marks" class="form-label">Marks</label>
                                    <input type="number" id="marks" name="marks" min="0" max="100"
                                        class="form-control">
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="grade" class="form-label">Grade</label>
                                    <input type="text" id="grade" name="grade" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Close
                            </button>
                            <button type="submit" id="submitAddStudentBtn" class="btn btn-primary"><i
                                    class="bx bx-plus"></i>Mark</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
