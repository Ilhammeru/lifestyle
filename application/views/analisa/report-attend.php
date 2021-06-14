
    <script src="<?=base_url();?>assets/vendor/chartjs/chart.min.js"></script>

    <style>
        .dt-buttons {
            margin-bottom: 12px !important;
        }
    </style>

    <div class="row">

        <?php
        if ($event['participant'] == 1) { ?>
        <div class="col-sm-6">

            <div class="card">

                <div class="card-header">
                    <h3 class="card-title">Data Tidak Datang</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body">

                    <table id="table-attend-null" class="table table-striped m-0 table-sm" style="width: 100%">

                        <thead>
                            <tr class="text-center" style="width: 100%">
                                <th>Nama</th>
                                <th>Department</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php

                            $no = 0;

                            if (count($attend_null) > 0) {
                            foreach ($attend_null as $row) :

                                $no += 1;
                                echo '<tr>';
                                echo '<td>' . $row->employee_name . '</td>';

                                echo '<td class="text-center">';
                                if ($row->team == '') {
                                echo $row->department;
                                } else {
                                echo $row->department . ' (' . $row->team . ')';
                                }
                                echo '</td>';

                                echo '<td class="text-center">';
                                
                                if ($row->attend == 'SAKIT' ||
                                    $row->attend == 'ALPA' ||
                                    $row->attend == 'IJIN' ||
                                    $row->attend == 'CUTI') {
                                        echo $row->attend;
                                }
                                echo '</td>';

                                echo '</tr>';
                            endforeach;
                            }
                            ?>

                        </tbody>

                        <tfoot>
                            <tr>
                                <th colspan="3">Total Tidak Datang <?php echo $no;?></th>
                            </tr>
                        </tfoot>

                    </table>

                </div>

            </div>

        </div>
        <?php } ?>

        <div class="col-md-6">

            <div class="card card-primary">

                <div class="card-header">

                    <h3 class="card-title">Kesimpulan</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>

                </div>

                <div class="card-body">

                    <table class="table table-striped table-sm" style="width: 100%">
                        
                        <thead>
                            <tr>
                                <th>Department (Team)</th>
                                <th class="text-center">Durasi</th>
                                <th class="text-center">Ketertarikan</th>
                                <th class="text-center">Kepuasan</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <?php

                            $detail = json_decode($event['detail'], TRUE);
                            $count_dept = 0;
                            foreach ($department as $row):

                                $key = $row->id;

                                if (isset($detail[$key])) {
                                    $count_dept += 1;

                                    echo '<tr>';
                                    echo '<td>' . $row->name . '</td>';
                                    echo '<td class="text-center"><span class="durasi" id="dur-' . $row->id . '" data-id="' . $row->id . '"></span></td>';
                                    echo '<td class="text-center"><span class="review" id="in-' . $row->id . '" data-id="' . $row->id . '"></span></td>';
                                    echo '<td class="text-center"><span class="review" id="out-' . $row->id . '" data-id="' . $row->id . '"></span></td>';
                                    echo '</tr>';

                                }

                            endforeach;
                            ?>
                        </tbody>

                        <tfoot>
                            <tr class="text-center">
                                <th></th>
                                <th><span id="total-dur"></span></th>
                                <th><span id="total-in"></span></th>
                                <th><span id="total-out"></span></th>
                            </tr>
                        </tfoot>

                    </table>

                </div>

            </div>

        </div>

        <div class="col-sm-12">

            <div class="card card-warning">

                <div class="card-header">
                    <h3 class="card-title">Data Tidak Clear</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                
                <div class="card-body">

                    <table id="table-attend-not-clear" class="table table-striped m-0 table-sm" style="width: 100%">

                        <thead>
                            <tr class="text-center" style="width: 100%">
                                <th>Nama</th>
                                <th>Department</th>
                                <?php
                                if ($event['report'] == 1) { ?>
                                <th>IN</th>
                                <th>OUT</th>
                                <th>Durasi</th>
                                <?php } ?>
                                <th>Ketertarikan</th>
                                <th>Kepuasan</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php

                            $review_in = 0;
                            $review_out = 0;
                            $review_diff = 0;
                            $count_in = 0;
                            $count_out = 0;
                            $count_dur = 0;

                            $review_in_p = 0;
                            $review_in_w = 0;
                            $review_out_p = 0;
                            $review_out_w = 0;
                            $count_in_p = 0;
                            $count_in_w = 0;
                            $count_out_p = 0;
                            $count_out_w = 0;

                            $count_all_p = 0;
                            $count_visit_p = 0;
                            $count_all_w = 0;
                            $count_visit_w = 0;

                            $no_1 = 0;

                            if (count($attend) > 0) {
                            foreach ($attend as $row) :

                                if ($row->time_diff == '00:00:00' || $row->time_diff > '12:00') {

                                $no_1 += 1;

                                if ($row->gender == 'p') {
                                    $count_all_p += 1;
                                } else {
                                    $count_all_w += 1;
                                }

                                echo '<tr>';
                                echo '<td>' . $row->employee_name . '</td>';

                                echo '<td class="text-center">';
                                if ($row->team == '') {
                                echo $row->department;
                                } else {
                                echo $row->department . ' (' . $row->team . ')';
                                }
                                echo '</td>';
                                
                                if ($event['report'] == 1) {
                                echo '<td class="text-center">';
                                if ($row->time_in == NULL) {
                                    echo '-';
                                } else {
                                    echo date('H:i', strtotime($row->time_in));
                                    $count_in += 1;
                                    $review_in += $row->review_in;
                                    if ($row->gender == 'p') {
                                        $count_in_p += 1;
                                        $review_in_p += $row->review_in;
                                        $count_visit_p += 1;
                                    } else {
                                        $count_in_w += 1;
                                        $review_in_w += $row->review_in;
                                        $count_visit_w += 1;
                                    }
                                }
                                echo '</td>';

                                if ($row->time_out == NULL) {
                                    echo '<td class="text-center">-</td>';
                                } else {
                                    if ($row->time_diff > '12:00') {
                                        echo '<td class="text-center"><i class="fa fa-check text-success"></i></td>';
                                    } else {
                                        if ($row->time_diff == '00:00:00') {
                                            echo '<td class="bg-warning"></td>';
                                        } else {
                                            echo '<td class="text-center">' . date('H:i', strtotime($row->time_out)) . '</td>';
                                        }
                                    }
                                    $count_out += 1;
                                    $review_out += $row->review_out;
                                    if ($row->gender == 'p') {
                                        $count_out_p += 1;
                                        $review_out_p += $row->review_out;
                                    } else {
                                        $count_out_w += 1;
                                        $review_out_w += $row->review_out;
                                    }
                                }
                                
                                if ($row->time_in == NULL || $row->time_out == NULL) {
                                    echo '<td class="text-center">-</td>';
                                } else {

                                    if ($row->time_diff > '12:00') {
                                        echo '<td class="text-center"><i class="fa fa-check text-success"></i></td>';
                                    } else {
                                        if ($row->time_diff == NULL) {
                                            echo '<td class="text-center">-</td>';
                                        } else {

                                            if ($row->time_diff == '00:00:00') {
                                                echo '<td class="bg-warning"></td>';
                                            } else {
                                                echo '<td class="text-center">' . date('H:i', strtotime($row->time_diff)) . '</td>';
                                            }
                                            $review_diff += strtotime($row->time_diff);
                                            $count_dur += 1;
                                        }
                                    }
                                }
                                }
                                echo '</td>';
                                echo '<td class="text-center">' . $row->review_in . '</td>';
                                echo '<td class="text-center">' . $row->review_out . '</td>';

                                echo '</tr>';

                                }

                            endforeach;
                            }
                            ?>

                        </tbody>

                        <tfoot>
                            <tr>
                                <th style="width: 25%">Total Data Tidak Clear <?php echo $no_1;?></th>
                                <th style="width: 15%"></th>
                                <?php
                                if ($event['report'] == 1) { ?>
                                <th style="width: 10%"></th>
                                <th style="width: 10%"></th>
                                <th style="width: 10%"></th>
                                <?php } ?>
                                <th style="width: 15%" class="text-center">
                                    <?php 
                                    if ($review_in == 0 || $count_in == 0) {
                                        
                                    } else {
                                        echo round($review_in/$count_in, 2);
                                    } 
                                    ?>
                                </th>
                                <th style="width: 15%" class="text-center">
                                    <?php
                                    if ($review_out == 0 || $count_in == 0) {

                                    } else {
                                            echo round($review_out/$count_out, 2);
                                    } ?>
                                </th>
                            </tr>
                        </tfoot>

                    </table>

                </div>

            </div>

        </div>

        <div class="col-sm-12">

            <div class="card card-success">

                <div class="card-header">
                    <h3 class="card-title">Data Clear</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                
                <div class="card-body">

                    <table id="table-attend" class="table table-striped m-0 table-sm" style="width: 100%">

                        <thead>
                            <tr class="text-center" style="width: 100%">
                                <th style="width: 25%">Nama</th>
                                <th style="width: 15%">Department</th>
                                <?php
                                if ($event['report'] == 1) { ?>
                                <th style="width: 10%">IN</th>
                                <th style="width: 10%">OUT</th>
                                <th style="width: 10%">Durasi</th>
                                <?php } ?>
                                <th style="width: 15%">Ketertarikan</th>
                                <th style="width: 15%">Kepuasan</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php

                            $review_in = 0;
                            $review_out = 0;
                            $review_diff = 0;
                            $count_in = 0;
                            $count_out = 0;
                            $count_dur = 0;

                            $review_in_p = 0;
                            $review_in_w = 0;
                            $review_out_p = 0;
                            $review_out_w = 0;
                            $count_in_p = 0;
                            $count_in_w = 0;
                            $count_out_p = 0;
                            $count_out_w = 0;

                            $count_all_p = 0;
                            $count_visit_p = 0;
                            $count_all_w = 0;
                            $count_visit_w = 0;

                            $no_2 = 0;

                            if (count($attend) > 0) {
                            foreach ($attend as $row) :

                                if ($row->time_diff != '00:00:00') {
                                    
                                    if($row->time_diff < '12:00') {

                                        $no_2 += 1;

                                        if ($row->gender == 'p') {
                                            $count_all_p += 1;
                                        } else {
                                            $count_all_w += 1;
                                        }

                                        echo '<tr>';
                                        echo '<td>' . $row->employee_name . '</td>';

                                        echo '<td class="text-center">';
                                        if ($row->team == '') {
                                        echo $row->department;
                                        } else {
                                        echo $row->department . ' (' . $row->team . ')';
                                        }
                                        echo '</td>';
                                        
                                        if ($event['report'] == 1) {
                                            echo '<td class="text-center">';
                                            if ($row->time_in == NULL) {
                                                echo '-';
                                            } else {
                                                echo date('H:i', strtotime($row->time_in));
                                                $count_in += 1;
                                                $review_in += $row->review_in;
                                                if ($row->gender == 'p') {
                                                    $count_in_p += 1;
                                                    $review_in_p += $row->review_in;
                                                    $count_visit_p += 1;
                                                } else {
                                                    $count_in_w += 1;
                                                    $review_in_w += $row->review_in;
                                                    $count_visit_w += 1;
                                                }
                                            }
                                            echo '</td>';
                                        }

                                        if ($event['report'] == 1) {
                                            if ($row->time_out == NULL) {
                                                echo '<td class="text-center">-</td>';
                                            } else {
                                                echo '<td class="text-center">' . date('H:i', strtotime($row->time_out)) . '</td>';
                                                
                                                $count_out += 1;
                                                $review_out += $row->review_out;
                                                if ($row->gender == 'p') {
                                                    $count_out_p += 1;
                                                    $review_out_p += $row->review_out;
                                                } else {
                                                    $count_out_w += 1;
                                                    $review_out_w += $row->review_out;
                                                }
                                            }
                                        }
                                        
                                        if ($event['report'] == 1) {
                                            if ($row->time_in == NULL || $row->time_out == NULL) {
                                                echo '<td class="text-center">-</td>';
                                            } else {

                                                if ($row->time_diff == NULL) {
                                                    echo '<td class="text-center">-</td>';
                                                } else {
                                                    echo '<td class="text-center">' . date('H:i', strtotime($row->time_diff)) . '</td>';
                                                    $review_diff += strtotime($row->time_diff);
                                                    $count_dur += 1;
                                                }
                                                
                                            }
                                            echo '</td>';
                                        }

                                        echo '<td class="text-center">' . $row->review_in . '</td>';
                                        echo '<td class="text-center">' . $row->review_out . '</td>';

                                        echo '</tr>';

                                    }
                                }

                            endforeach;
                            
                            }
                            ?>

                        </tbody>

                        <tfoot>
                            <tr>
                                <th style="width: 25%">Total Data Clear <?php echo $no_2;?></th>
                                <th style="width: 15%"></th>
                                <?php if ($event['report'] == 1) { ?>
                                <th style="width: 10%"></th>
                                <th style="width: 10%"></th>
                                <th style="width: 10%" class="text-center">
                                    <?php 
                                    if ($review_diff == 0 || $count_dur == 0) {
                                    } else{
                                        echo date('H:i', round($review_diff/$count_dur));
                                    } ?>
                                </th>
                                <?php } ?>
                                <th style="width: 15%" class="text-center">
                                    <?php 
                                    if ($review_in == 0 || $count_in == 0) {
                                    } else {
                                        echo round($review_in/$count_in, 2);
                                    }?>
                                </th>
                                <th style="width: 15%" class="text-center">
                                    <?php 
                                    if ($review_out == 0 || $count_out == 0) {
                                    } else {
                                        echo round($review_out/$count_out, 2);
                                    } ?>
                                </th>
                            </tr>
                        </tfoot>

                    </table>

                </div>

            </div>

        </div>

    </div>

    <script>

        $(document).ready( function () {

            var titleExport = 'Absen Event ';

            $('#table-attend-null').DataTable({
                paging: false,
                info: false,
                ordering: false,
                scrollY: '500px',
                scrollX: '1200px',
                scrollCollapse: true,
                searching: false,
                fixedColumns:   {
                    leftColumns: 1
                },
                // Buttons          
                buttons: [ 
                            {
                                extend: 'excel',
                                title: titleExport,
                                orientation: 'landscape',
                                footer: true
                            },
                            {
                                extend: 'pdf',
                                title: titleExport,
                                orientation: 'landscape',
                                footer: true
                            },
                            {
                                extend: 'print',
                                title: titleExport,
                                orientation: 'landscape',
                                footer: true
                            }
                        ],
                dom: 'Bfrtip'
            });

            $('#table-attend-not-clear').DataTable({
                paging: false,
                info: false,
                ordering: false,
                scrollY: '500px',
                scrollX: '1200px',
                scrollCollapse: true,
                searching: false,
                fixedColumns:   {
                    leftColumns: 1
                },
                // Buttons          
                buttons: [ 
                            {
                                extend: 'excel',
                                title: titleExport,
                                orientation: 'landscape',
                                footer: true
                            },
                            {
                                extend: 'pdf',
                                title: titleExport,
                                orientation: 'landscape',
                                footer: true
                            },
                            {
                                extend: 'print',
                                title: titleExport,
                                orientation: 'landscape',
                                footer: true
                            }
                        ],
                dom: 'Bfrtip'
            });

            $('#table-attend').DataTable({
                paging: false,
                info: false,
                ordering: false,
                scrollY: '500px',
                scrollX: '1200px',
                scrollCollapse: true,
                searching: false,
                fixedColumns:   {
                    leftColumns: 1
                },
                // Buttons          
                buttons: [ 
                            {
                                extend: 'excel',
                                title: titleExport,
                                orientation: 'landscape'
                            },
                            {
                                extend: 'pdf',
                                title: titleExport,
                                orientation: 'landscape'
                            },
                            {
                                extend: 'print',
                                title: titleExport,
                                orientation: 'landscape'
                            }
                        ],
                dom: 'Bfrtip'
            });

            review();

        });

        function review() {

            var count = 0;
            var total_in = 0;
            var total_out = 0;
            var total_dur = 0;
            $('.review').each( function () {

                count += 1;

                var id = $(this).data('id');
                var event_id = "<?php echo $event_id;?>";

                $.ajax({
                    url: "<?=site_url('analisa/get_kesimpulan');?>",
                    type: "post",
                    data: {
                        id: id,
                        event_id: event_id
                    },
                    dataType: "json",
                    beforeSend: function() {
                        var loading = 'Loading...';
                        $('#in-' + id).html(loading);
                        $('#out-' + id).html(loading);
                        $('#dur-' + id).html(loading);
                    },
                    success: function (response) {
                        $('#in-' + id).html(response.in_avg);
                        $('#out-' + id).html(response.out_avg);
                        $('#dur-' + id).html(response.dur);

                        total_in += response.in_avg;
                        total_out += response.out_avg;

                        var total_avg_in = Math.round((total_in/count)*100)/100;
                        var total_avg_out = Math.round((total_out/count)*100)/100;
                        
                        $('#total-in').html(total_avg_in);
                        $('#total-out').html(total_avg_out);
                    }
                });

            });

        }



    </script>