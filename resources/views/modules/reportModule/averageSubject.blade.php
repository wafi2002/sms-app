@section('title', __('Subject Average'))

<x-layouts.app>
    <x-responsive-table :columns="$columns" :rows="$rows" :title="$title" :showAddButton="$showAddButton" :hasAction="$hasAction"
        :showExportButton="$showExportButton" :exportActions="$exportActions" :columnAlignments="$columnAlignments" />

    <div id="averageChart" class="mt-10"></div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                console.log(@json(collect($rows)))
                const subjectNames = @json(collect($rows)->pluck('name'));
                const averageMarks = @json(collect($rows)->pluck('average_mark'));

                const options = {
                    chart: {
                        type: 'bar',
                        height: 350
                    },
                    series: [{
                        name: 'Average Mark',
                        data: averageMarks
                    }],
                    xaxis: {
                        categories: subjectNames
                    },
                    title: {
                        text: 'Subject Average Marks',
                        align: 'center'
                    },
                    plotOptions: {
                        bar: {
                            dataLabels: {
                                position: 'top',
                            }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        offsetY: -20,
                        style: {
                            fontSize: '12px',
                            colors: ["#304758"]
                        }
                    }
                };

                const chart = new ApexCharts(document.querySelector("#averageChart"), options);
                chart.render();
            });
        </script>
    @endpush
</x-layouts.app>
