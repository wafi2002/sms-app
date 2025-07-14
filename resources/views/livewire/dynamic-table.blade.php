<div>
    <div class="card mb-3">
        <div class="card-header">Filter</div>
        <div class="card-body d-flex flex-row gap-5">
            @foreach ($filterOptions as $key => $options)
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

    <x-responsive-table :columns="$columns" :rows="$rows" :hasAction="$hasAction" :actions="$actions" :showAddButton="true"
        :showModal="true" title="All Students" buttonLabel="Add" addButtonIcon="bx bx-user"
        modalTarget="#addStudentModal" />
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
