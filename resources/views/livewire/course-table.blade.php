<div>
    <div class="card mb-3">
        <div class="card-header">Filter</div>
        <div class="card-body d-flex flex-row gap-5">
            @foreach ($courseOptions as $key => $options)
                <div wire:ignore>
                    <h5 class="card-title">{{ $key }}</h5>
                    <select class="form-select select2-filter" id="{{ $key }}Select"
                        data-filter-key="{{ $key }}">
                        <option value="">Select {{ ucfirst($key) }}</option>
                        @foreach ($options as $option)
                            <option value="{{ $option['id'] }}">{{ $option['course_code'] }} -
                                {{ $option['course_name'] }}</option>
                        @endforeach
                    </select>
                </div>
            @endforeach
        </div>
    </div>

    <x-responsive-table :columns="$columns" :rows="$rows" :hasAction="$hasAction" :actions="$actions" :showAddButton="true" :columnAlignments="$columnAlignments"
        :showModal="true" title="All Course" buttonLabel="Add" addButtonIcon="bx bx-book-open" :paginator="$subject"
        modalTarget="#addCourseModal" />
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', function() {
            $('.select2-filter').select2();

            $('.select2-filter').on('change', function() {
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
