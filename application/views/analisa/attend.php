
    <div class="row">

        <div class="col-md-12">

            <div class="card">

                <div class="card-body">

                    <div class="form-inline float-sm-left">

                        <div class="input-group p-1">
                            <input type="month" class="form-control" value="<?=date('Y-m');?>" name="month" id="month" placeholder="Pilih periode">
                        </div>

                        <div class="input-group p-1">
                            <div id="display-event">
                            <select class="form-control select2" name="event" id="event">
                                <option selected disabled>Pilih Event</option>
                                <?php
                                foreach ($events as $row) :
                                    echo '<option value="' . $row->id . '">' . $row->title . "</option>";
                                endforeach;
                                ?>
                            </select>
                            </div>
                        </div>
                        <div class="input-group p-1">
                            <select class="form-control select2" name="department" id="department">
                                <option selected disabled>Pilih Department</option>
                                <?php
                                echo '<option value="x">All Holding</option>';
                                foreach ($department as $row):
                                    echo '<option value="' . $row->id . '">' . $row->name . '</option>';
                                endforeach;
                                ?>
                            </select>
                        </div>
                        <div class="input-group p-1">
                            <button type="button" class="btn btn-primary" id="btn-submit">Submit</button>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-12">

            <div id="result"></div>

        </div>

    </div>

    <script>

        $(document).ready( function () {

            $('#month').on('change', function () {

                var value = $(this).val();

                $.ajax({
                    url: "<?=site_url('analisa/display_event');?>",
                    type: "post",
                    data: {
                        periode: value
                    },
                    success: function(html) {
                        $('#display-event').html(html);
                    }
                });
            });

            $('#btn-submit').on('click', function () {

                var department = $('#department');
                var event = $('#event');

                if (event.val() == '' || event.val() == null) {
                    Swal.fire({
                        position: 'center',
                        icon: 'warning',
                        title: 'Pilih event',
                        showConfirmButton: false,
                        timer: 1000
                    });

                    event.focus();

                    return false;

                }

                if (department.val() == '' || department.val() == null) {
                    Swal.fire({
                        position: 'center',
                        icon: 'warning',
                        title: 'Pilih department',
                        showConfirmButton: false,
                        timer: 1000
                    });

                    department.focus();

                    return false;

                }

                $.ajax({
                    url: "<?=site_url('analisa/load_data');?>",
                    type: "post",
                    data: {
                        event_id: event.val(),
                        department : department.val()
                    },
                    cache: false,
                    beforeSend: function() {
                        var loading = '<div class="overlay text-center"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>';
                        $('#result').html(loading);
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

                            $('#result').html('');
                        } else {
                            $('#result').html(response);
                        }
                    }

                });

            }); 

        });

    </script>