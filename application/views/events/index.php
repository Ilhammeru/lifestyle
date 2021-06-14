
    <style>
        .dt-buttons, .dataTables_info, .dataTables_paginate {
            padding: 4px;
        }
    </style>

    <div class="row">

        <div class="col-md-3">

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Buat Event</h3>
                </div>
                <form id="form-event" method="post" action="<?=site_url('events/add_event');?>">
                <div class="card-body">

                    <div class="form-group">
						<label for="event_name">Event</label>
						<input type="text" id="event_name" name="event_name"
							   class="form-control">
                    </div>

                    <div class="form-group">
						<label for="event">Kategori</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<input type="text" id="event_category" name="event_category"
									   class="form-control form-left">
							</div>
							<select id="select_category" name="select_category"
									class="form-control select2">
                                <option value="" selected disabled>Pilih Kategori</option>
                                <?php foreach ($category as $row) :
                                    echo '<option>' . $row->event_category . '</option>';
                                endforeach; ?>
							</select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="event_date">Tanggal</label>
                        <input type="date" id="event_date" name="event_date" 
                                class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="event_time">Jam Mulai</label>
                        <input type="time" id="event_time" name="event_time"
                                class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="event_time">Dresscode</label>
                        <input type="text" id="dresscode" name="dresscode"
                                class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="event_time">Tempat Event</label>
                        <input type="text" id="event_place" name="event_place"
                                class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="participant">Partisipan</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="participant" value="1" checked>
                            <label class="form-check-label">Semua</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="participant" value="0">
                            <label class="form-check-label">Perwakilan</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="report">Report</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="report" value="1" checked>
                            <label class="form-check-label">Durasi</label>
                        </div>
                    </div>
                    
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary float-right">Submit</button>
                </div>
                </form>
            </div>

        </div>

        <div class="col-md-5">

            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Data Event</h3>
                </div>
                <div class="card-body p-0">

                    <table id="table-event" class="table table-striped m-0" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Aksi</th>
                                <th>Tanggal</th>
                                <th>Event</th>
                                <th>Kategori</th>
                                <th>Jam Mulai</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                </div>
            </div>

            <div id="edit-event"></div>

        </div>
        <div class="col-md-4">

            <div id="setting-event"></div>

        </div>

    </div>

    <script>

        $(document).ready( function () {

            var titleExport = "Report Data Event";
            var columnExport = [ ':visible:not(.not-export-col)' ];

            $('#select_category').on('change', function () {
                var value = $(this).val();

                $('#event_category').val(value);

            });

            var tableEvent = $('#table-event').DataTable({
                // Data
                ajax: {
                    url: "<?=site_url('events/server_side_data');?>",
                    type: "POST"
                },
                processing: true,

                // Select
                select: false,

                scrollX: true,
                ordering: false,

                // Length
                lengthChange: false,
                lengthMenu: [
                    [ 10, 25, 50, 100],
                    [ '10 rows', '25 rows', '50 rows', '100 rows']
                ],
                columns: [
                            {
                                "width": "30%"
                            },
                            {
                                "width": "15%"
                            },
                            {
                                "width": "20%"
                            },
                            {
                                "width": "20%"
                            },
                            {
                                "width": "15%"
                            }
                        ],   
                columnDefs: [
                            { 
                                targets: [ 1 ],
                                type: "de_date"
                            }
                ],
                // Buttons          
                buttons: [ 
                            'pageLength',
                            {
                                extend: 'print',
                                title: titleExport,
                                exportOptions: {
                                    columns: columnExport
                                }
                            }
                        ],
                dom: 'Bfrtip',

            });

            $('#form-event').validate({

				rules: {
                    event_name : {
                        required: true
                    },
                    event_category : {
                        required: true
                    },
                    event_date : {
                        required: true
                    },
                    event_time : {
                        required: true
                    }
				},
				messages: {
                    event_name : {
                        required: 'Kolom event harus diisi'
                    },
                    event_category : {
                        required: 'Kolom kategori harus diisi'
                    },
                    event_date: {
                        required: 'Kolom tanggal harus diisi'
                    },
                    event_time: {
                        required: 'Kolom jam mulai harus diisi'
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
                                dataType: 'json',
					            success: function(data) {
					            	if (data.response == 'success') {

					            		setTimeout(function(){

											$('#form-event input').removeClass('is-valid');
											$('#form-event select').removeClass('is-valid');
											$(form)[0].reset();	

											$(".select2").val('').trigger('change') ;

                                        }, 1000);
                                        
                                        setTimeout(function() {
                                            
                                            $('#table-event').DataTable().ajax.reload();
                                            display_setting(data.insert_id);
                                        
                                        }, 1500);

					            		Swal.fire({
											position: 'center',
											icon: 'success',
											title: 'Data berhasil disimpan',
											showConfirmButton: false,
											timer: 1500
										});

					            	} else if (data.response == 'error-null') {

					            		setTimeout(function(){

											$('#form-event input').addClass('is-invalid');
											$('#form-event select').addClass('is-invalid');

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

        function display_setting(id) {

            $.ajax({
                url: "<?=site_url('events/setting_event');?>",
                type: "post",
                data: {
                    insert_id: id
                },  
                dataType: 'text',
                beforeSend: function() {
                    var loading = '<div class="overlay text-center"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>';
                    $('#setting-event').html(loading);
                },
                success: function(response) {
                    display_edit(id);

                    window.scrollTo(0, 0);

                    $('#setting-event').html(response);

                }
            })

        }

        function delete_event(id) {

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
                        url: "<?=site_url('events/delete_event');?>",
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
                                $('#table-event').DataTable().ajax.reload();
                            }, 2000);

                        }
                    });

                }
            });

        }

        function display_edit(id) {

            $.ajax({
                url: "<?=site_url('events/edit_event');?>",
                type: "post",
                data: {
                    insert_id: id
                },  
                beforeSend: function() {
                    var loading = '<div class="overlay text-center"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>';
                    $('#edit-event').html(loading);
                },
                success: function(response) {

                    $('#edit-event').html(response);

                }
            })

        }

    </script>