@section('title', __('All Course'))

@php
    // Column key => label
    $columns = [
        'code' => 'Course Code',
        'name' => 'Course Name',
        'type' => 'Course Type',
        'credit' => 'Credit Hour',
    ];

    // Data rows
    $rows = [
        ['code' => 'CSC101', 'name' => 'Intro to Programming', 'type' => 'Compulsory', 'credit' => 3],
        ['code' => 'MTH202', 'name' => 'Discrete Math', 'type' => 'Elective', 'credit' => 3],
    ];

    // Actions (optional)
    $hasAction = true;
    $actions = [
        ['label' => 'Delete', 'icon' => 'bx bx-trash', 'url' => '#'],
    ];
@endphp

<x-layouts.app :title="__('Dashboard')">
    <select class="select2" style="width: 200px;">
        <option value="1">Option A</option>
        <option value="2">Option B</option>
    </select>

    <x-responsive-table :columns="$columns" :rows="$rows" :hasAction="$hasAction" :actions="$actions"
        title="Course Application" />

    <button type="button" class="btn btn-primary">
        <span class="tf-icons bx bx bx-book"></span>&nbsp; Register
    </button>
</x-layouts.app>

{{-- Push Scripts --}}
@section('page-script')
    <script>
        $(document).ready(function() {
            console.log("jQuery: ", typeof jQuery); // function
            console.log("Select2: ", typeof $.fn.select2); // function
            $('.select2').select2();
        });
    </script>
@endsection
