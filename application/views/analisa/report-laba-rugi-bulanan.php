    <div class="row">

        <div class="col-sm-5">

            <div class="card">
                <div class="card-body">
                    
                    <table class="table table-sm table-borderless m-0" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:20%;font-size:16px;text-decoration:underline;text-decoration-color:blue">Laba Rugi</th>
                                <th style="width:80%" class="text-right"></th>
                            </tr>
                            <tr style="height:35px">
                                <td colspan="2"></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>Pendapatan</th>
                                <td class="text-right"><?php echo number_format($total_pendapatan);?></td>
                            </tr>
                            <tr>
                                <th>HPP</th>
                                <td class="text-right"><?php echo number_format($total_hpp);?></td>
                            </tr>
                            <tr class="p-0">
                                <td colspan="2" class="text-right pr-3">-</td>
                            </tr>
                            <tr>
                                <th>Margin</th>
                                <th class="text-right"><?php echo number_format($total_pendapatan-$total_hpp);?></th>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>

        </div>

        <div class="col-md-7">

            <div class="card">

                <div class="card-body">

                    <table class="table table-sm m-0 table-borderless" id="table-item" style="width:100%">
                        <thead>
                            <tr style="border-bottom:1px solid black">
                                <th>Tanggal</th>
                                <th>Event</th>
                                <th>HPP</th>
                                <th>Pendapatan</th>
                                <th>Margin</th>
                        <tbody>

                        <?php
                        foreach ($event as $row):
                            echo '<tr>';
                            echo '<td style="border-right:1px solid black">' . date('d M Y', strtotime($row->event_date)) . '</td>';
                            echo '<td style="border-right:1px solid black">' . $row->title . '</td>';
                            echo '<td style="border-right:1px solid black" class="text-right"><span class="hpp" id="hpp-' . $row->id . '" data-id="' . $row->id . '"></span></td>';
                            echo '<td style="border-right:1px solid black" class="text-right"><span class="income" id="income-' . $row->id . '" data-id="' . $row->id . '"></span></td>';
                            echo '<td class="text-right"><span class="margin" id="margin-' . $row->id . '" data-id="' . $row->id . '"></span></td>';
                            echo '</tr>';
                        endforeach;
                        ?>

                        </tbody>
                    </table>
                    
                </div>

            </div>

        </div>

    </div>

    <script>

        $(document).ready( function () {

            $('.hpp').each(function () {
                var id = $(this).data('id');

                $.ajax({
                    url: "<?=site_url('analisa/hpp_total_event');?>",
                    type: "post",
                    data: {
                        id: id
                    },
                    beforeSend: function() {
                        var loading = '<div class="overlay text-center"><div class="text-bold pt-2">Loading...</div></div>';
                        $('#hpp-' + id).html(loading);
                    },
                    success: function(response) {

                        if (response == 'error-null') {
                            Swal.fire({
                                position: 'center',
                                icon: 'warning',
                                title: 'Data tidak ditemukan',
                                showConfirmButton: false,
                                timer: 1000
                            });

                            $('#hpp-' + id).html('');
                        } else {
                            $('#hpp-' + id).html(response);
                            margin();
                        }
                    }
                })

            });

            $('.income').each(function () {
                var id = $(this).data('id');

                $.ajax({
                    url: "<?=site_url('analisa/income_total_event');?>",
                    type: "post",
                    data: {
                        id: id
                    },
                    beforeSend: function() {
                        var loading = '<div class="overlay text-center"><div class="text-bold pt-2">Loading...</div></div>';
                        $('#income-' + id).html(loading);
                    },
                    success: function(response) {

                        if (response == 'error-null') {
                            Swal.fire({
                                position: 'center',
                                icon: 'warning',
                                title: 'Data tidak ditemukan',
                                showConfirmButton: false,
                                timer: 1000
                            });

                            $('#income-' + id).html('');
                        } else {
                            $('#income-' + id).html(response);
                            margin();
                        }
                    }
                })

            });

        });

        function margin() {
            
            $('.margin').each(function () {
                var id = $(this).data('id');
                var hpp = $('#hpp-' + id).text();
                var income = $('#income-' + id).text();

                hpp = hpp.replace(/,/g, '');
                income = income.replace(/,/g, '');

                var margin = income - hpp;

                margin = number_format_0(margin);

                $('#margin-' + id).text(margin);

            });

        }

    </script>
