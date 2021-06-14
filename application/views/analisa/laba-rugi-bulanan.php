
    <div class="row">

        <div class="col-md-12">

            <div class="card">

                <div class="card-body">

                    <div class="form-inline float-sm-left">

                        <div class="input-group p-1">
                            <input type="month" class="form-control" value="<?=date('Y-m');?>" name="month" id="month" placeholder="Pilih periode">
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

            $('#btn-submit').on('click', function () {

                var month = $('#month').val();

                $.ajax({
                    url: "<?=site_url('analisa/load_data_laba_rugi_bulanan');?>",
                    type: "post",
                    data: {
                        month: month
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