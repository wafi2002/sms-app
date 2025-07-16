@section('title', __('Exam Mark'))

<x-layouts.app>
    @livewire('exam-mark-table')

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
                let mark = input.val();

                console.log(id, url, mark);

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
        </script>
    @endpush
</x-layouts.app>

<!-- Add Student Modal -->
<div class="col-lg-4 col-md-6">
    <div class="modal fade" id="addStudentMarksModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="{{ route('students.store') }}" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalStudentTitle">Add Student Marks</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" id="name" name="name" class="form-control"
                                    placeholder="Enter Name" />
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col mb-0">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" id="email" name="email" class="form-control"
                                    placeholder="xxxx@xxx.xx" />
                            </div>
                            <div class="col mb-0">
                                <label for="phone_no" class="form-label">Phone No</label>
                                <input type="text" id="phone_no" name="phone_no" class="form-control"
                                    placeholder="012-xxxxxxxx" />
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col mb-0">
                                <label for="ic_no" class="form-label">IC No</label>
                                <input type="text" id="ic_no" name="ic_no" class="form-control"
                                    placeholder="02xxxxxxxxxx" />
                            </div>
                            <div class="col mb-3">
                                <label for="matric_no" class="form-label">Matric no</label>
                                <input type="text" id="matric_no" name="matric_no" class="form-control"
                                    placeholder="S1XXXXX" />
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col mb-0">
                                <label for="course_id" class="form-label">Course</label>
                                <select id="course_id" name="course_id" class="form-select">
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
                                <select name="gender" id="gender" class="form-select">
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
                                    cols="50"></textarea>
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
