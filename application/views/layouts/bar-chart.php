
    <script src="<?=base_url();?>assets/vendor/chartjs/chart.min.js"></script>

    <!-- BAR CHART -->
    <div class="card">
        <div class="card-header border-0">
            <div class="d-flex justify-content-between">
                <h3 class="card-title">All Holding</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="chart">
                <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
        </div>
        <!-- /.card-body -->
    </div>

    <script>

        $(document).ready( function () {

            var review_in_all_p = "<?php echo $review_in_all_p;?>";
            var review_in_all_w = "<?php echo $review_in_all_w;?>";
            var review_out_all_p = "<?php echo $review_out_all_p;?>";
            var review_out_all_w = "<?php echo $review_out_all_w;?>";

            var areaChartData = {
                labels  : ['Ketertarikan', 'Kepuasan'],
                datasets: [
                    {
                        label               : 'Pria',              
                        backgroundColor     : '#007bff',
                        borderColor         : '#007bff',
                        data                : [review_in_all_p, review_out_all_p]
                    },
                    {
                        label               : 'Wanita',
                        backgroundColor     : '#ced4da',
                        borderColor         : '#ced4da',
                        data                : [review_in_all_w, review_out_all_w]
                    }
                ]
            }

            //-------------
            //- BAR CHART -
            //-------------
            var barChartCanvas = $('#barChart').get(0).getContext('2d');
            var barChartData = jQuery.extend(true, {}, areaChartData);
            var temp0 = areaChartData.datasets[0];
            var temp1 = areaChartData.datasets[1];
            barChartData.datasets[0] = temp0;
            barChartData.datasets[1] = temp1;

            var barChartOptions = {
                responsive              : true,
                maintainAspectRatio     : false,
                datasetFill             : false,
                scales: { 
                    yAxes: [{
                            display: true,
                            ticks: {
                                beginAtZero: true,
                                steps: 10,
                                stepValue: 5,
                                max: 100
                            },
                            gridLines: {
                                drawBorder: false,
                                display      : true,
                                lineWidth    : '4px',
                                color        : 'rgba(0, 0, 0, .2)',
                                zeroLineColor: 'transparent'
                            }
                        }]
                },
            }

            var barChart = new Chart(barChartCanvas, {
                type: 'bar', 
                data: barChartData,
                options: barChartOptions
            });

        });

    </script>