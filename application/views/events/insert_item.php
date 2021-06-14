
    <div class="card">

        <div class="card-header">
            <h3 class="card-title"><b><?=$events_master['hpp_category'];?></b> <small><?=$events_master['hpp_subcategory'];?></h3>
        </div>
        <form id="form-item" method="post" action="<?=site_url('events/save_item');?>">
        <div class="card-body">

            <table id="table-item" style="width:100%">
                <?php 
                if ($count_events_hpp == 0) {?>
                <tr>
                    <td class="pb-2 pr-2 pl-2" style="width:45%">
                        <input typ="text" class="form-control input-sm item_name" name="item_name[]" id="item_nam" placeholder="Item">
                    </td>
                    <td class="pb-2 pr-2 pl-2" style="width:15%">
                        <input typ="text" class="form-control input-sm item_qty" name="item_qty[]" id="item_qty" placeholder="Jumlah">
                    </td>
                    <td class="pb-2 pr-2 pl-2" style="width:15%">
                        <input typ="text" class="form-control input-sm item_satuan" name="item_satuan[]" id="item_satuan" placeholder="Satuan">
                    </td>
                    <td class="pb-2 pr-2 pl-2" style="width:20%">
                        <input typ="text" class="form-control input-sm currency-rp item_price" name="item_price[]" id="item_price" placeholder="Asumsi">
                    </td>
                    <td class="pb-2 pr-2 pl-2" style="width:5%">
                    </td>
                </tr>
                <?php } ?>
            </table>
            
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary float-right">Submit</button>
            <a href="#" onclick="insert_row()" class="btn btn-success float-right mr-3"><i class="fa fa-plus"></i></a>
        </div>
        </form>

    </div>

    <script>

        function insert_row() {

            currency_rp();

			var tableItem = document.getElementById('table-item');
            var countRow = tableItem.rows.length;

			var row = tableItem.insertRow(countRow);

			var cell1 = row.insertCell(0);
			var cell2 = row.insertCell(1);
			var cell3 = row.insertCell(2);
			var cell4 = row.insertCell(3);
			var cell5 = row.insertCell(4);

            cell1.className = 'item_name pb-2 pr-2 pl-2';
            cell2.className = 'item_qty pb-2 pr-2 pl-2';
            cell3.className = 'item_satuan pb-2 pr-2 pl-2';
            cell4.className = 'item_price pb-2 pr-2 pl-2';
            cell5.className = 'pb-2 pr-2 pl-2';

            cell1.innerHTML = '<input typ="text" class="form-control input-sm item_name" name="item_name[]" placeholder="Item">';
            cell2.innerHTML = '<input typ="text" class="form-control input-sm item_qty" name="item_qty[]" placeholder="Jumlah">';
            cell3.innerHTML = '<input typ="text" class="form-control input-sm item_satuan" name="item_satuan[]" placeholder="Satuan">';
            cell4.innerHTML = '<input typ="text" class="form-control input-sm currency-rp item_price" name="item_price[]" placeholder="Asumsi">';

			cell5.innerHTML = '<a onclick="delete_row(this)" class="mr-1 ml-1"><i class="fa fa-times-circle text-red"></i></a>';

		}

        function insert_row_edit(data) {

            currency_rp();

			var tableItem = document.getElementById('table-item');

			var row = tableItem.insertRow(0);

			var cell1 = row.insertCell(0);
			var cell2 = row.insertCell(1);
			var cell3 = row.insertCell(2);
			var cell4 = row.insertCell(3);
			var cell5 = row.insertCell(4);

            cell1.className = 'item_name pb-2 pr-2 pl-2';
            cell2.className = 'item_qty pb-2 pr-2 pl-2';
            cell3.className = 'item_satuan pb-2 pr-2 pl-2';
            cell4.className = 'item_price pb-2 pr-2 pl-2';
            cell5.className = 'pb-2 pr-2 pl-2';

            cell1.innerHTML = '<input typ="text" class="form-control input-sm item_name" name="item_name[]" value="' + data.item_name + '" placeholder="Item">';
            cell2.innerHTML = '<input typ="text" class="form-control input-sm item_qty" name="item_qty[]" value="' + data.item_qty + '" placeholder="Jumlah">';
            cell3.innerHTML = '<input typ="text" class="form-control input-sm item_satuan" name="item_satuan[]" value="' + data.item_satuan + '" placeholder="Satuan">';
            cell4.innerHTML = '<input typ="text" class="form-control input-sm currency-rp item_price" name="item_price[]" value="' + data.item_price + '" placeholder="Asumsi">';

			cell5.innerHTML = '<a onclick="delete_row_db(this, "' + data.id + '")" class="mr-1 ml-1"><i class="fa fa-times-circle text-red"></i></a>';

		}

        function delete_row(param) {

			var i = param.parentNode.parentNode.rowIndex;
			document.getElementById("table-item").deleteRow(i);

		}

        function delete_row_db(param, id) {

            $.ajax({
                url: "<?=site_url('events/delete_item');?>",
                type: "post",
                data: {
                    id: id
                },
                success:function () {
			        var i = param.parentNode.parentNode.rowIndex;
			        document.getElementById("table-item").deleteRow(i);

                    setTimeout(function () {
                        hpp_x();
                        hpp_group_x();
                    }, 1000);
                }
            })

		}

        function currency_rp() {

            $('.currency-rp').inputmask("numeric", {

	            radixPoint: ".",
	            groupSeparator: ",",
	            digits: 2,
	            autoGroup: true,
	            rightAlign: false,
	            allowMinus: false,
	            oncleared: function () {
	                self.value('');
	            }

	        });

        }

        $(document).ready( function () {

            currency_rp();

            load_detail();

            $("#form-item").submit( function (e) {
			    e.preventDefault();

                $('.item_name').each(function () {

                    if ($(this).val() == '') {

                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Kolom item harus diisi!',
                        });

                    }

                });

                $('.item_qty').each(function () {

                    if ($(this).val() == '') {

                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Kolom Jumlah harus diisi!',
                        });

                    }

                });

                $('.item_satuan').each(function () {

                    if ($(this).val() == '') {

                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Kolom satuan harus diisi!',
                        });

                    }

                });

                $('.item_price').each(function () {

                    if ($(this).val() == '') {

                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Kolom asumsi harus diisi!',
                        });

                    }

                });

                var master_id = "<?php echo $events_master['id'];?>";
                var event_id = "<?php echo $event_id;?>";

                Swal.fire({
                    title: 'Data sudah benar?',
                    //text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#007bff',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Submit!'
                    }).then((result) => {

                    if (result.isConfirmed) {

                        $.ajax({
                            url: $(this).attr("action"),
                            type: $(this).attr("method"),
                            data: $(this).serialize()+'&master_id=' + master_id + '&event_id=' + event_id,
                            success: function(response) {

                                if (response == 'success') {

                                    Swal.fire({
                                        position: 'center',
                                        icon: 'success',
                                        title: 'Data berhasil disimpan',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });

                                    setTimeout(function() {
                                        hpp_x();
                                        hpp_group_x();
                                    }, 1500);

                                } else if (response == 'error') {
                                    
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Ada kesalahan!',
                                    });

                                }

                            }

                        });

                    }
                });

            });


        });

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
                            insert_row_edit(data[i]);
                        }

                    }

				}
			})

		}

    </script>