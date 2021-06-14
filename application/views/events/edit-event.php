    
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Edit Event</h3>
        </div>
        <form id="form-edit-event" method="post" action="<?=site_url('events/update_event/' . $event['id']);?>">
        <div class="card-body">

            <div class="form-group">
                <label for="event_name">Event</label>
                <input type="text" id="event_name" name="event_name"
                        class="form-control" value="<?php echo $event['title'];?>">
            </div>

            <div class="form-group">
                <label for="event">Kategori</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <input type="text" id="event_category" name="event_category"
                                class="form-control form-left" value="<?php echo $event['event_category'];?>">
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
                        class="form-control" value="<?php echo $event['event_date'];?>">
            </div>

            <div class="form-group">
                <label for="event_time">Jam Mulai</label>
                <input type="time" id="event_time" name="event_time"
                        class="form-control" value="<?php echo $event['event_time'];?>">
            </div>


            <div class="form-group">
                <label for="participant">Partisipan</label>
                <div class="form-check">
                    <?php
                    if ($event['participant'] == 1) { ?>
                    <input class="form-check-input" type="radio" name="participant" value="1" checked>
                    <?php } else { ?>
                    <input class="form-check-input" type="radio" name="participant" value="1">
                    <?php } ?>
                    <label class="form-check-label">Semua</label>
                </div>
                <div class="form-check">
                    <?php
                    if ($event['participant'] == 0) { ?>
                    <input class="form-check-input" type="radio" name="participant" value="0" checked>
                    <?php } else { ?>
                    <input class="form-check-input" type="radio" name="participant" value="0">
                    <?php } ?>
                    <label class="form-check-label">Perwakilan</label>
                </div>
            </div>

            <div class="form-group">
                <label for="report">Report</label>
                <div class="form-check">
                    <?php
                    if ($event['report'] == 1) {
                        $checked = 'checked';
                    } else {
                        $checked = '';
                    } ?>
                    <input class="form-check-input" type="checkbox" name="report" value="1" <?=$checked;?>>
                    <label class="form-check-label">Durasi</label>
                </div>
            </div>
            
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary float-right">Submit</button>
        </div>
        </form>
    </div>

    <script>

        $(document).ready( function () {

            $('#form-edit-event').validate({

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

											$('#form-edit-event input').removeClass('is-valid');
											$('#form-edit-event select').removeClass('is-valid');
											$(form)[0].reset();	

											$(".select2").val('').trigger('change') ;

                                        }, 1000);
                                        
                                        setTimeout(function() {
                                            
                                            $('#table-event').DataTable().ajax.reload();
                                        
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

											$('#form-edit-event input').addClass('is-invalid');
											$('#form-edit-event select').addClass('is-invalid');

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

    </script>