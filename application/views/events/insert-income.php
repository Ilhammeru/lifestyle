
    <div class="row">

        <div class="col-md-4">

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Tambah Pendapatan</h3>
                </div>
                <form id="form-add-income" method="post" action="<?=site_url('events/insert_income/' . $events['id']);?>">
                <div class="card-body">

                    <div class="form-group">
                        <label for="income_date">Tanggal</label>
                        <input type="date" id="income_date" name="income_date" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="income_from">Sumber Dana</label>
                        <select class="form-control select2" name="income_from" id="income_from">
                            <option value="" selected disabled>Pilih</option>
                            <option value="0">Donasi</option>
                            <?php
                            foreach ($department as $row) :
                                echo '<option value="' . $row->id .'">' . $row->name . '</option>';
                            endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="nominal">Nominal</label>
                        <input type="text" id="nominal" name="nominal" class="form-control currency-rp">
                    </div>

                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary float-right">Submit</button>
                </div>
                </form>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card card-secondary">

                <div class="card-header">
                    <h3 class="card-title">Data Pendapatan</h3>
                </div>

                <div class="card-body p-0">

                    <table id="table-income" class="table table-sm m-0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Sumber Dana</th>
                                <th>Nominal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                </div>

            </div>

        </div>

    </div>

    <script>

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

            tableIncome();

            $('#form-add-income').validate({

				rules: {
                    income_from: {
                        required: true
                    },
                    income_date: {
                        required: true
                    },
                    nominal: {
                        required: true
                    }
				},
				messages: {
                    income_from: {
                        required: 'Kolom sumber dana harus diisi'
                    },
                    income_date: {
                        required: 'Kolom tanggal harus diisi'
                    },
                    nominal: {
                        required: 'Kolom nominal harus diisi'
                    }
				},
				errorElement: 'span',
				errorPlacement: function (error, element) {
					error.addClass('invalid-feedback');
					element.closest('.form-group').append(error);
				},
				highlight: function (element, errorClass, validClass) {
					$(element).addClass('is-invalid');
				},
				unhighlight: function (element, errorClass, validClass) {
					$(element).removeClass('is-invalid');
				},
				submitHandler: function (form) {

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
					            url: form.action,
					            type: form.method,
                                data: $(form).serialize(),
					            success: function(response) {

					            	if (response == 'success') {

					            		setTimeout(function(){

											$('#form-add-income input').removeClass('is-valid');
											$('#form-add-income select').removeClass('is-valid');
											$(form)[0].reset();	

											$(".select2").val('').trigger('change') ;

                                        }, 1000);
                                        
                                        setTimeout(function() {
                                            reset_table();
                                            tableIncome();
                                        }, 1500);

					            		Swal.fire({
											position: 'center',
											icon: 'success',
											title: 'Data berhasil disimpan',
											showConfirmButton: false,
											timer: 1500
										});

					            	} else if (response == 'error-null') {

					            		setTimeout(function(){

											$('#form-add-income input').addClass('is-invalid');
											$('#form-add-income select').addClass('is-invalid');

										}, 1000);

					            		Swal.fire({
											icon: 'error',
											title: 'Oops...',
											text: 'Ada kesalahan!',
										});

					            	} 

					            }
					            // End of success

					        });
					        // End of ajax submit

	                   	}

	                });

				}
				// End of submitHandler

			});
            // End of form validate
            

        });

        function insert_row(data) {

            currency_rp();

			var tableIncome = document.getElementById('table-income');

			var row = tableIncome.insertRow(1);

			var cell1 = row.insertCell(0);
			var cell2 = row.insertCell(1);
			var cell3 = row.insertCell(2);
			var cell4 = row.insertCell(3);

            cell1.innerHTML = data.income_date;
            cell2.innerHTML = data.income_from;
            cell3.innerHTML = number_format_0(data.nominal);
            cell4.innerHTML = '<a href="#" class="text-danger" onclick="delete_income(this, ' + data.id + ')"><i class="fa fa-times-circle"></i></a>';

        }

        function tableIncome() {

            $.ajax({
                url: "<?=site_url('events/server_side_data_income/' . $events['id']);?>",
                dataType: "json",
                beforeSend: function() {
                    var loading = '<div class="overlay text-center"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>';
                    $('tbody').html(loading);
                },
                success: function (data) {
                    if (data == 'error-null') {
                        $('tbody').html('');
                    } else {
                        $('tbody').html('');
                        for (i = 0; i < data.length; i++) {
                            insert_row(data[i]);
                        }
                    }

                }

            })

        }

        function delete_income(i, id) {

            Swal.fire({
                title: 'Yakin menghapus data?',
                //text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#007bff',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Submit!'
                }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: "<?=site_url('events/delete_income');?>",
                        type: "post",
                        data: {
                            id: id
                        },
                        success: function () {

                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Data berhasil dihapus',
                                showConfirmButton: false,
                                timer: 1500
                            });

                            setTimeout(function () {
                                delete_row(i);
                            }, 2000);

                        }
                    });

                }
            });

        }

        function reset_table() {
            var tableIncome = document.getElementById('table-income');
            var countRow = tableIncome.rows.length;

            for (i = 1; i < countRow; i++) {
			    document.getElementById("table-income").deleteRow(i);
            }
        }

        function delete_row(param) {

			var i = param.parentNode.parentNode.rowIndex;
			document.getElementById("table-income").deleteRow(i);

		}


    </script>