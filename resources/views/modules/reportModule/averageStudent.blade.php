@section('title', __('Student Average'))

@php
$filterOptions = [
'student' => [
[
'id' => 1,
'matric_no' => 'CB21080',
'student_name' => 'Hatta Rahman bin Borhanuddin',
],
[
'id' => 2,
'matric_no' => 'CB21077',
'student_name' => 'Ahmad Kholid bin Khuzaini',
],
[
'id' => 3,
'matric_no' => 'CB21085',
'student_name' => 'Rizal Danial bin Mohd Romzi',
],
],
// Boleh tambah lagi filter jenis lain contoh 'department', 'lecturer', dll
];

@endphp

<x-layouts.app>
    <div class="card mb-3">
        <div class="card-header">Filter</div>
        <div class="card-body d-flex flex-row gap-5">
            @foreach ($filterOptions as $key => $options)
            <div>
                <h5 class="card-title">{{ ucfirst($key) }}</h5>
                <select class="form-select select2-filter" id="{{ $key }}Select"
                    data-filter-key="{{ $key }}">
                    <option value="">Select {{ ucfirst($key) }}</option>
                    @foreach ($options as $option)
                    <option value="{{ $option['id'] }}">{{ $option['matric_no'] }} -
                        {{ $option['student_name'] }}
                    </option>
                    @endforeach
                </select>
            </div>
            @endforeach
        </div>
    </div>
    {{-- Display Student Performance in Graph --}}
    <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
        <div class="card">
            <div class="row row-bordered g-0">
                <div class="col-md-8">
                    <h5 class="card-header m-0 me-2 pb-3">Performance Graph</h5>
                    <div id="chart"></div>
                </div>
                <div class="col-md-4">

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $('.select2-filter').select2({
                placeholder: 'Please select',
            });

            const studentPerformance = {
                1: [{
                        x: 'Math',
                        y: 80
                    },
                    {
                        x: 'Science',
                        y: 75
                    },
                    {
                        x: 'History',
                        y: 90
                    }
                ],
                2: [{
                        x: 'Math',
                        y: 65
                    },
                    {
                        x: 'Science',
                        y: 70
                    },
                    {
                        x: 'History',
                        y: 60
                    }
                ],
                3: [{
                        x: 'Math',
                        y: 95
                    },
                    {
                        x: 'Science',
                        y: 90
                    },
                    {
                        x: 'History',
                        y: 88
                    }
                ]
            };
            var options = {
                chart: {
                    type: 'bar',
                    height: 350
                },
                plotOptions: {
                    bar: {
                        horizontal: false
                    }
                },
                series: [{
                    name: 'Marks',
                    data: []
                }],
                xaxis: {
                    type: 'category'
                }
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();

            // Bila student dipilih
            $('#studentSelect').on('change', function() {
                var studentId = $(this).val();

                if (studentPerformance[studentId]) {
                    chart.updateSeries([{
                        name: 'Marks',
                        data: studentPerformance[studentId]
                    }]);
                } else {
                    chart.updateSeries([{
                        name: 'Marks',
                        data: []
                    }]);
                }
            });
        });
    </script>
    @endpush
</x-layouts.app>