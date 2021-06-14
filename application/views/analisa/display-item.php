
    <div class="card">

        <div class="card-body">

            <table class="table table-sm m-0 table-borderless" id="table-item" style="width:100%">
                <thead>
                    <?php if ($events_master['hpp_subcategory'] != '') { ?>
                    <tr>
                    <?php } else { ?>
                    <tr style="border-bottom:1px solid black">
                    <?php }?>
                        <th colspan="3" style="font-sze:16px"><?=$events_master['hpp_category'];?></th>
                    </tr>
                    <?php if ($events_master['hpp_subcategory'] != '') { ?>
                    <tr style="border-bottom:1px solid black">
                        <td colspan="3"><?=$events_master['hpp_subcategory'];?></td>
                    </tr>
                    <?php } ?>
                <thead>
                <tbody>
                </tbody>
            </table>
            
        </div>

    </div>

    <script>

        $(document).ready( function () {

            load_detail();

        });

        function insert_row(data) {

			var tableItem = document.getElementById('table-item');

            var countRow = tableItem.rows.length;
			var row = tableItem.insertRow(countRow);

			var cell1 = row.insertCell(0);
			var cell2 = row.insertCell(1);
			var cell3 = row.insertCell(2);

            cell2.className = 'text-center';
            cell3.className = 'text-right';

            cell1.innerHTML = data.item_name;
            cell2.innerHTML = data.item_qty + ' ' + data.item_satuan;
            cell3.innerHTML = number_format_0(data.item_qty * data.item_price);
		}

        function load_detail() {

            var master_id = "<?php echo $events_master['id'];?>";
            var event_id = "<?php echo $event_id;?>";

			$.ajax({
				url: "<?=site_url('events/server_side_data_detail_item');?>",
				type: "get",
				data: {
                    master_id: master_id,
                    event_id: event_id
				},
				dataType: "json",
				success: function (data) {

                    if (data != 'error-null') {

                        for (i = 0; i < data.length; i++) {
                            insert_row(data[i]);
                        }

                    } else {
                        var tableItem = document.getElementById('table-item');
                        var countRow = tableItem.rows.length;
			            var row = tableItem.insertRow(countRow);
			            var cell1 = row.insertCell(0);
                        cell1.innerHTML = 'Tidak terdapat data';
                    }

				}
			})

		}

    </script>