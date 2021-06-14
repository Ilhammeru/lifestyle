    

    <!-- Bootstrap v4.5.2 -->
    <link rel="stylesheet" href="<?=base_url();?>assets/bootstrap/css/bootstrap.min.css?v4.5.2">
    <!-- jQuery v3.5.1 -->
    <script src="<?=base_url();?>assets/vendor/jquery/jquery-3.5.1.min.js?v3.5.1"></script>
    <!-- Bootstrap v4.5.2 -->
    <script src="<?=base_url();?>assets/bootstrap/js/bootstrap.min.js?v4.5.2"></script>

     <div class="row">
        <div class="col-sm-12">

            <div class="card">

                <div class="card-header">

                    <h3 class="card-title"><?php echo $event['title'];?></h3>

                </div>

            </div>

        </div>
    </div>

    <div class="row p-4">

        <div class="col-sm-6">

            <div class="card">

                <div class="card-body p-0">

                    <table id="table-detail" class="table table-sm m-0 table-borderless table-hover" style="width: 100%">  
                        <thead>
                            <tr>
                                <th colspan="3" style="font-size:16px">#<?php echo $event['event_category'];?></th>
                            </tr>
                        </thead>
                        <tbody>
                        
                            <?php
                            $category = '';
                            foreach ($eventsMaster as $row):

                                if ($category != $row->hpp_category AND $row->hpp_subcategory != '') {
                                    echo '<tr>';
                                    echo '<th colspan="2">' . $row->hpp_category . '</th>';
                                    echo '<th class="text-right">';
                                    echo '<span class="hpp-group" id="hpp-group-' . $row->id . '" data-id="' . $row->id . '"></span>';
                                    echo '</th>';
                                    echo '</tr>';
                                    echo '<tr id="tr-' . $row->id . '">';
                                    echo '<td></td>';
                                } elseif ($category != $row->hpp_category) {
                                    echo '<tr id="tr-' . $row->id . '">';
                                    echo '<th>' . $row->hpp_category . '</th>';
                                } else {
                                    echo '<tr id="tr-' . $row->id . '">';
                                    echo '<td></td>';
                                }

                                echo '<td style="width: 80%">';
                                echo $row->hpp_subcategory;
                                echo '</td>';
                                if ($row->hpp_subcategory != '') {
                                echo '<td class="text-right">';
                                echo '<span class="hpp" id="hpp-' . $row->id . '" data-id="' . $row->id . '"></span>';
                                echo '</td>';
                                } else {
                                echo '<th class="text-right">';
                                echo '<span class="hpp" id="hpp-' . $row->id . '" data-id="' . $row->id . '"></span>';
                                echo '</th>';
                                }
                                echo '</tr>';

                                $category = $row->hpp_category;
                            
                            endforeach; ?>

                        </tbody>
                        
                        <tfoot>
                            <tr>
                                <th colspan="2">Total</th>
                                <th class="text-right"><span id="hpp-total"></span></th>
                            </tr>
                        </tfoot>

                    </table>

                </div>

            </div>

        </div>

        <div class="col-sm-6">
            <div id="result-detail"></div>
        </div>

    </div>
    
    <div class="row p-4">   

        <div class="col-sm-6">

            <div class="card">
                <div class="card-body p-0">
                    
                    <table class="table table-sm table-hover table-borderless m-0" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:20%;font-size:16px;text-decoration:underline;text-decoration-color:blue">Laba Rugi</th>
                                <th style="width:80%" class="text-right"><?php echo $event['title'];?></th>
                            </tr>
                            <tr style="height:50px">
                                <td colspan="2"></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>Pendapatan</th>
                                <td class="text-right"><?php echo number_format($income);?></td>
                            </tr>
                            <tr>
                                <th>HPP</th>
                                <td class="text-right"><?php echo number_format($hpp);?></td>
                            </tr>
                            <tr class="p-0">
                                <td colspan="2" class="text-right pr-2">-</td>
                            </tr>
                            <tr>
                                <th>Margin</th>
                                <th class="text-right"><?php echo number_format($income-$hpp);?></th>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>

        </div>

        <div class="col-md-6">
            <div id="income-detail"></div>
        </div>

    </div>

    <script>

        function display_item() {

            var event_id = "<?php echo $event['id'];?>";

            $.ajax({
                url: "<?=site_url('analisa/display_item_for_print');?>",
                type: "post",
                data: {
                    event_id: event_id
                },
                beforeSend: function() {
                    var loading = '<div class="overlay text-center"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>';
                    $('#result-detail').html(loading);
                },
                success: function (response) {
                    $('#result-detail').html(response);
                }
            });
        }

        function get_income_detail(id) {

            $.ajax({
                url: "<?=site_url('events/server_side_data_income/' . $event['id']);?>",
                dataType: "json",
                beforeSend: function() {
                    var loading = '<div class="overlay text-center"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>';
                    $('#income-detail').html(loading);
                },
                success: function (data) {
                    if (data == 'error-null') {
                        $('#income-detail').html('');
                    } else {
                        $('#income-detail').html('<div class="card"><div class="card-body p-0"><table id="table-income" class="table table-sm table-borderless m-0"><thead><tr><th>Pendapatan</th></th></thead><tbody></tbody></table></div></div>');
                        for (i = 0; i < data.length; i++) {
                            insert_row(data[i]);
                        }
                    }

                }

            })

        }

        function insert_row(data) {

			var tableIncome = document.getElementById('table-income');

			var row = tableIncome.insertRow(1);

			var cell1 = row.insertCell(0);
			var cell2 = row.insertCell(1);
			var cell3 = row.insertCell(2);

            cell1.innerHTML = data.income_date;
            cell2.innerHTML = data.income_from;
            cell3.innerHTML = number_format_0(data.nominal);

        }

        $(document).ready( function () {

            get_income_detail();

            display_item();

            var total = 0;
            $('.hpp').each( function () {

                var event_id = "<?php echo $event['id'];?>";
                var id = $(this).data('id');

                $.ajax({
                    url: "<?=site_url('events/get_total_subcategory');?>",
                    type: "post",
                    data: {
                        event_id: event_id,
                        id: id
                    },
                    beforeSend: function() {
                        var loading = 'Loading...';
                        $('#hpp-' + id).html(loading);
                    },
                    success: function (response) {
                        total += +response;
                        
                        $('#hpp-total').html(number_format_0(total));
                        $('#hpp-' + id).html(number_format_0(response));
                    }
                })

            });


            $('.hpp-group').each( function () {

                var event_id = "<?php echo $event['id'];?>";
                var id = $(this).data('id');

                $.ajax({
                    url: "<?=site_url('events/get_total_category');?>",
                    type: "post",
                    data: {
                        event_id: event_id,
                        id: id
                    },
                    beforeSend: function() {
                        var loading = 'Loading...';
                        $('#hpp-group-' + id).html(loading);
                    },
                    success: function (response) {
                        $('#hpp-group-' + id).html(number_format_0(response));
                    }
                })

            });

        });

        function number_format_0(x) {
            var dcml = 0;
            number = parseFloat(x).toFixed(dcml).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            //str = 'Rp ' + number;
            return number;
        }

    </script>