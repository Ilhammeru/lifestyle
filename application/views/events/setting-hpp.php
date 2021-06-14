
    <div class="row">

        <div class="col-sm-3">

            <div class="card">

                <div class="card-header">
                    <h3 class="card-title"><?php echo $event['title'];?></h3>
                </div>

                <div class="card-body p-0">

                    <table class="table table-sm m-0 table-hover" style="width: 100%">
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
                                    echo '<tr onclick="display_insert(' . $row->id . ')">';
                                    echo '<td></td>';
                                } elseif ($category != $row->hpp_category) {
                                    echo '<tr onclick="display_insert(' . $row->id . ')">';
                                    echo '<th>' . $row->hpp_category . '</th>';
                                } else {
                                    echo '<tr onclick="display_insert(' . $row->id . ')">';
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
                                <th colspan="3" class="text-right pr-4"><span id="hpp-total"></span></th>
                            </tr>
                        </tfoot>

                    </table>

                </div>

            </div>

        </div>

        <div class="col-md-6">
            <div id="result"></div>
        </div>

    </div>

    <script>

        function display_insert(id) {

            var event_id = "<?php echo $event['id'];?>";

            $.ajax({
                url: "<?=site_url('events/display_insert');?>",
                type: "post",
                data: {
                    id : id,
                    event_id: event_id
                },
                beforeSend: function() {
                    var loading = '<div class="overlay text-center"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>';
                    $('#result').html(loading);
                },
                success: function (response) {
                    $('#result').html(response);
                }
            });
        }

        $(document).ready( function () {
            
            hpp_x();

            hpp_group_x();

        });

        function hpp_x() {

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

        }

        function hpp_group_x() {

            var total = 0;
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
        }

    </script>