<div>
    <div class="card mb-3">
        <div class="card-header">Filter</div>
        <div class="card-body d-flex flex-row gap-5">
            @foreach ($courseOptions as $key => $options)
            <div wire:ignore>
                <h5 class="card-title">{{ $key }}</h5>
                <select class="form-select course-filter" id="{{ $key }}CourseSelect"
                    data-filter-key="{{ $key }}">
                    <option value="">All {{ ucfirst($key) }}</option>
                    @foreach ($options as $option)
                    <option value="{{ $option['id'] }}">{{ $option['course_code'] }} -
                        {{ $option['course_name'] }}
                    </option>
                    @endforeach
                </select>
            </div>
            @endforeach
            @foreach ($subjectOptions as $key => $options)
            <div wire:ignore>
                <h5 class="card-title">{{ $key }}</h5>
                <select class="form-select subject-filter" id="{{ $key }}SubjectSelect"
                    data-filter-key="{{ $key }}">
                    <option value="">All {{ ucfirst($key) }}</option>
                    @foreach ($options as $option)
                    <option value="{{ $option['id'] }}">{{ $option['subject_code'] }} -
                        {{ $option['subject_name'] }}
                    </option>
                    @endforeach
                </select>
            </div>
            @endforeach
        </div>
    </div>

    <x-responsive-table :columns="$columns" :rows="$rows" :showAddButton="true" :columnAlignments="$columnAlignments"
        :showModal="true" title="All Student Marks" buttonLabel="Add" addButtonIcon="bx bx-book-open" :paginator="$exam"
        modalTarget="#addStudentMarksModal" />
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', function() {
        $('.course-filter').select2();
        $('.subject-filter').select2();

        $('.course-filter').on('change', function() {
            const key = $(this).data('filter-key');
            const value = $(this).val();

            Livewire.dispatch('filter-changed', {
                key: key,
                value: value
            });
        });

        $('.subject-filter').on('change', function() {
            const key = $(this).data('filter-key');
            const value = $(this).val();

            Livewire.dispatch('filter-changed', {
                key: key,
                value: value
            });
        });
    });
</script>
@endpush
