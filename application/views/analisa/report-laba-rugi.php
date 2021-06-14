

    <div class="row">
        <div class="col-sm-12">

            <div class="card card-primary">

                <div class="card-header">

                    <h3 class="card-title"><?php echo $event['title'];?></h3>

                </div>

            </div>

        </div>
    </div>

    <div class="row">

        <div class="col-sm-6">

            <div class="card">

                <div class="card-body">

                    <table id="table-detail" class="table table-sm m-0 table-borderless table-hover" style="width: 100%">  
                        <thead>
                            <tr style="border-bottom:1px solid">
                                <th colspan="2" style="font-size:16px">#<?php echo $event['event_category'];?></th>
                            </tr>
                        </thead>
                        <tbody>
                        
                            <?php
                            $category = '';
                            foreach ($eventsMaster as $row):

                                if ($category != $row->hpp_category AND $row->hpp_subcategory != '') {
                                    echo '<tr>';
                                    echo '<th>' . $row->hpp_category . '</th>';
                                    echo '<th class="text-right">';
                                    echo '<span class="hpp-group" id="hpp-group-' . $row->id . '" data-id="' . $row->id . '"></span>';
                                    echo '</th>';
                                    echo '</tr>';
                                    echo '<tr id="tr-' . $row->id . '" onclick="display_item(' . $row->id . ')">';
                                } elseif ($category != $row->hpp_category) {
                                    echo '<tr id="tr-' . $row->id . '" onclick="display_item(' . $row->id . ')">';
                                    echo '<th>' . $row->hpp_category . '</th>';
                                } else {
                                    echo '<tr id="tr-' . $row->id . '" onclick="display_item(' . $row->id . ')">';
                                }
                               
                                if ($row->hpp_subcategory != '') {
                                    echo '<td style="text-indent: 15px;">';
                                    echo $row->hpp_subcategory;
                                    echo '</td>';
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
                            <tr style="border-top:1px solid black">
                                <th class="pl-4">Total</th>
                                <th class="text-right pr-4"><span id="hpp-total"></span></th>
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

    <div class="row">

        <div class="col-sm-6">

            <div class="card">
                <div class="card-body">
                    
                    <table class="table table-sm table-hover table-borderless m-0" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:20%;font-size:16px;text-decoration:underline;text-decoration-color:blue">Laba Rugi</th>
                                <th style="width:80%" class="text-right"><?php echo $event['title'];?></th>
                            </tr>
                            <tr style="height:35px">
                                <td colspan="2"></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr onclick="get_income_detail('<?php echo $event['id'];?>')">
                                <th>Pendapatan</th>
                                <td class="text-right"><?php echo number_format($income);?></td>
                            </tr>
                            <tr onclick="hpp()">
                                <th>HPP</th>
                                <td class="text-right"><?php echo number_format($hpp);?></td>
                            </tr>
                            <tr class="p-0">
                                <td colspan="2" class="text-right pr-3">-</td>
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

        <div class="col-sm-6">
            <div id="income-detail"></div>
        </div>

    </div>

    <div class="row mb-5">
        <div class="col-sm-2 offset-sm-5">
            <a href="<?=site_url('analisa/print_labarugi/' . $event['id']);?>" target="_blank" class="btn bg-purple btn-block"><i class="fa fa-print"></i> Print</a>
        </div>
    </div>

    <script>

        function display_item(id) {

            var event_id = "<?php echo $event['id'];?>";

            $.ajax({
                url: "<?=site_url('analisa/display_item');?>",
                type: "post",
                data: {
                    id : id,
                    event_id: event_id
                },
                beforeSend: function() {
                    var loading = '<div class="overlay text-center"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>';
                    $('#result-detail').html(loading);
                },
                success: function (response) {
                    $('#table-detail tbody tr').removeClass('bg-green');
                    $('#tr-' + id).addClass('bg-green');
                    $('#result-detail').html(response);
                }
            });
        }

        function hpp() {
            window.scrollTo(0, 0);
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
                        $('#income-detail').html('<div class="card"><div class="card-body p-0"><table id="table-income" class="table table-sm table-borderless m-0"><thead><tr style="border-bottom:1px solid black"><th colspan="3">Pendapatan</th></th></thead><tbody></tbody></table></div></div>');
                        for (i = 0; i < data.length; i++) {
                            insert_row_2(data[i]);
                        }
                    }

                }

            })

        }

        function insert_row_2(data) {

			var tableIncome = document.getElementById('table-income');

			var row = tableIncome.insertRow(1);

			var cell1 = row.insertCell(0);
			var cell2 = row.insertCell(1);
			var cell3 = row.insertCell(2);

            cell2.className = 'text-center';
            cell3.className = 'text-right';

            cell1.innerHTML = data.income_date;
            cell2.innerHTML = data.income_from;
            cell3.innerHTML = number_format_0(data.nominal);

        }

        $(document).ready( function () {

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

    </script>
