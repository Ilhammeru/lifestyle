
    <table id="table-attend" class="table table-striped m-0 table-sm" style="width: 100%">

        <thead>
            <tr class="text-center">
                <th>Nama</th>
                <th>Department</th>
                <th>IN</th>
                <th>OUT</th>
                <th>Durasi</th>
                <th>Ketertarikan</th>
                <th>Kepuasan</th>
            </tr>
        </thead>

        <tbody>

            <?php

            $review_in = 0;
            $review_out = 0;
            $count_in = 0;
            $count_out = 0;

            foreach ($attend as $row) :

                $review_in += $row->review_in;
                $review_out += $row->review_out;

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
                if ($row->time_in == NULL) {
                    echo '-';
                } else {
                    echo date('H:i', strtotime($row->time_in));
                    $count_in += 1;
                }
                echo '</td>';

                echo '<td class="text-center">';
                if ($row->time_out == NULL) {
                    echo '-';
                } else {
                    echo date('H:i', strtotime($row->time_out));
                    $count_out += 1;
                }
                echo '</td>';

                echo '<td class="text-center">';
                if ($row->time_in == NULL && $row->time_out == NULL) {
                    echo '-';
                } else {
                    echo date('H:i', strtotime($row->time_diff));
                }
                echo '</td>';

                echo '<td class="text-center">' . $row->review_in . '</td>';
                echo '<td class="text-center">' . $row->review_out . '</td>';

                echo '</tr>';
            endforeach;

                echo '<tr>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<th class="text-center">' . round($review_in/$count_in, 2) . '</th>';
                echo '<th class="text-center">' . round($review_out/$count_out, 2) . '</th>';
                echo '</tr>';
                ?>

        </tbody>

    </table>

    <script>

        $(document).ready( function () {

            var titleExport = 'Absen Event ';

            $('#table-attend').DataTable({
                paging: false,
                info: false,
                ordering: false,
                scrollY: '700px',
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


        });

    </script>