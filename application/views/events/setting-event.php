    
    <p><a href="<?=site_url('events/scanner_page/' . $id);?>" target="_blank" class="btn btn-info btn-block">Link Absen</a></p>

    <p><a href="<?=site_url('events/setting_hpp/' . $id);?>" target="_blank" class="btn btn-primary btn-block">Tambah HPP</a></p>

    <p><a href="<?=site_url('events/add_income/' . $id);?>" target="_blank" class="btn btn-primary btn-block">Tambah Pendapatan</a></p>

    <div class="card">

        <div class="card-header">
            <h3 class="card-title">Pilih Holding</h3>
        </div>
        <form id="form-holding">
        <div class="card-body p-0 table-responsive" style="max-height: 500px">
            
            <table class="table table-striped m-0 table-sm">
            <?php

            if ($events['detail'] == '') {
                $arrayDetail = array();
            } else {
                $arrayDetail = json_decode($events['detail'], TRUE);
            }

            foreach ($department as $row) :
                echo '<tr>';

                if (isset($arrayDetail[$row->id])) {
                    $checked = 'checked';
                } else {
                    $checked = '';
                }

                echo '<td><input type="checkbox" class="holding" name="input_holding[]" value="' . $row->id . '" ' . $checked . '></td>';
                echo '<td>' . $row->name . '</td>';
                echo '</tr>';
            endforeach;
            ?>
            <tr>
                <td>
                    <input type="checkbox" class="all-holding" name="all-holding[]" onclick="select_all()">
                </td>
                <td>Semua</td>
            </tr>
            </table>

        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary float-right">Submit</button>
        </div>
        </form>

    </div>

    <script>

        $(document).ready( function () {

            $("#form-holding").submit( function (e) {
                e.preventDefault();

                var id = "<?php echo $id;?>";

                var value = 0;
                
                $('.holding').each( function () {

                    if ($(this).prop('checked') == true) {
                        value = 1;
                    }
                
                });

                if (value == 0) {

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Pilih holding yang akan mengikuti event!',
                    });

                } else {

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
                                url: "<?=site_url('events/insert_holding');?>",
                                data: $(this).serialize() + '&id=' + id,
                                type: "post",
                                success: function(response) {

                                    if (response == 'success') {

                                        Swal.fire({
											position: 'center',
											icon: 'success',
											title: 'Data berhasil disimpan',
											showConfirmButton: false,
											timer: 1000
										});

										setTimeout(function(){  
                                            
										}, 1500);

                                    } else {

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

                }

            });

        });

        function select_all() {
            $('.holding').prop('checked', true);
        }

    </script>