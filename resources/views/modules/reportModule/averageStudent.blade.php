@section('title', __('Student Average'))

<x-layouts.app>
    {{-- Display Student Performance in Graph --}}
    <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
        <div class="card">
            <div class="row row-bordered g-0">
                <div class="col-md-8">
                    <h5 class="card-header m-0 me-2 pb-3">Performance Graph</h5>
                    <div id="chart"></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
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
                        data: [{
                            x: 'category A',
                            y: 10
                        }, {
                            x: 'category B',
                            y: 18
                        }, {
                            x: 'category C',
                            y: 13
                        }]
                    }]
                }

                var chart = new ApexCharts(document.querySelector("#chart"), options);
                chart.render().then(() => {
                    console.log("Chart OK!");
                }).catch(e => {
                    console.error("Error:", e);
                });
            });
        </script>
    @endpush
</x-layouts.app>
