@section('title', __('Exam Schedule'))

<x-layouts.app>

    <div id='calendar'></div>

    <div class="modal fade" id="calendarModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="{{ route('exams.scheduleStore') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterTitle">Exam Schedule</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalContent">
                        <div id="clickedDateInfo"></div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="schedule_title" class="form-label">Title</label>
                                <input type="text" id="schedule_title" name="schedule_title" class="form-control"
                                    placeholder="Enter Title" />
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col mb-3">
                                <label for="schedule_description" class="form-label">Description</label>
                                <textarea id="schedule_description" name="schedule_description" class="form-control" rows="4"
                                    placeholder="Conducting Biology Test ..."></textarea>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="datetime-local" id="start_date" class="form-control" id="start_date"
                                    name="start_date" placeholder="DD / MM / YY" />
                            </div>
                            <div class="col mb-0">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="datetime-local" id="end_date" class="form-control" id="end_date"
                                    name="end_date" placeholder="DD / MM / YY" />
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-6 mb-3">
                                <label for="subject" class="form-label">Course</label>
                                <select id="subject" name="subject_id" class="form-select ">
                                    <option value="">Select Subject</option>
                                    @foreach ($subject as $sbj)
                                        <option value="{{ $sbj['id'] }}">{{ $sbj['subject_code'] }} -
                                            {{ $sbj['subject_name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label for="class_exam" class="form-label">Location</label>
                                <select id="class_exam" name="class_id" class="form-select ">
                                    <option value="">Select Location</option>
                                    @foreach ($class as $cls)
                                        <option value="{{ $cls['id'] }}">{{ $cls['class_code'] }} -
                                            {{ $cls['class_name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-12 mb-3">
                                <label for="coordinator" class="form-label">Coordinator</label>
                                <select id="coordinator" name="lecturer_id" class="form-select ">
                                    <option value="">Select Coordinator</option>
                                    @foreach ($lecturer as $option)
                                        <option value="{{ $option['id'] }}">{{ $option['lecturer_no'] }} -
                                            {{ $option['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">Save Event</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('.form-select').select2({
                    allowClear: true,
                    dropdownParent: $('#calendarModal'),
                    width: '100%',
                    height: '100%'
                });
            });

            document.addEventListener('DOMContentLoaded', function() {
                const calendarEl = document.getElementById('calendar')
                var eventsFromBackend = @json($formattedEvents);
                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',

                    eventDidMount: function(info) {
                        var tooltip = new bootstrap.Tooltip(info.el, {
                            title: info.event.extendedProps.description || info.event.title,
                            placement: 'top',
                            trigger: 'hover',
                            container: 'body'
                        });
                    },

                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,listWeek'
                    },

                    views: {
                        dayGridMonth: {
                            titleFormat: {
                                year: 'numeric',
                                month: 'long'
                            }, // Contoh: "Julai 2025"
                            dayMaxEventRows: true,
                        },
                        timeGridWeek: {
                            titleFormat: {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric'
                            },
                            nowIndicator: true // Tunjuk line "sekarang"
                        },
                        listWeek: {
                            buttonText: 'list'
                        }
                    },



                    events: eventsFromBackend,

                    dateClick: function(info) {
                        const modalContent = document.getElementById('modalContent');
                        const modalElement = document.getElementById('calendarModal');

                        const clickedDateInfo = document.getElementById('clickedDateInfo');
                        if (clickedDateInfo) {
                            clickedDateInfo.innerHTML = '<p>Date: <strong>' + info
                                .dateStr + '</strong></p>';
                        }

                        const startDateInput = document.getElementById('start_date');
                        if (startDateInput) {
                            startDateInput.value = info.dateStr + 'T09:00';
                        }

                        if (modalElement) {
                            const modal = new bootstrap.Modal(modalElement);
                            modal.show();
                        }
                    }
                });
                calendar.render();
            });
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
