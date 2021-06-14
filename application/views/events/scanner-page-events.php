<!doctype html>

<html>

<head>
    <title>
        App
    </title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, height=device-height">
    <meta name="robots" content="NOINDEX,NOFOLLOW">


    <!-- Bootstrap v4.5.2 -->
    <link rel="stylesheet" href="<?=base_url();?>assets/bootstrap/css/bootstrap.min.css?v4.5.2">

    <!-- Sweetalert2 v10.3.5 -->
    <link rel="stylesheet" href="<?=base_url();?>assets/vendor/sweetalert2/dist/sweetalert2.min.css?v10.3.5">

    <!-- DataTables v1.10.22 -->
    <link rel="stylesheet" href="<?=base_url();?>assets/vendor/datatables/datatables.min.css?v1.10.22">

    <!-- DataTables v1.10.22 with Bootstrap v4.4.11 -->
    <link rel="stylesheet" href="<?=base_url();?>assets/vendor/datatables/datatables/css/dataTables.bootstrap4.min.css?v4.4.11">

    <link rel="stylesheet" href="<?=base_url();?>assets/vendor/toastr/toastr.min.css">

    <!-- jQuery v3.5.1 -->
    <script src="<?=base_url();?>assets/vendor/jquery/jquery-3.5.1.min.js?v3.5.1"></script>

    <!-- Bootstrap v4.5.2 -->
    <script src="<?=base_url();?>assets/bootstrap/js/bootstrap.min.js?v4.5.2"></script>

    <!-- Sweetalert2 v10.3.5 -->
    <script src="<?=base_url();?>assets/vendor/sweetalert2/dist/sweetalert2.min.js?v10.3.5"></script>

    <!-- DataTables v1.10.22 -->
    <script src="<?=base_url();?>assets/vendor/datatables/datatables.min.js?v1.10.22"></script>

    <!-- jQuery DataTables v1.10.22 -->
    <script src="<?=base_url();?>assets/vendor/datatables/datatables/js/jquery.dataTables.min.js?v1.10.22"></script>

    <!-- DataTables v1.10.22 with Bootstrap v4.4.11 -->
    <script src="<?=base_url();?>assets/vendor/datatables/datatables/js/dataTables.bootstrap4.min.js?v4.4.11"></script>

    <!-- Toastr -->
    <script src="<?=base_url();?>assets/vendor/toastr/toastr.min.js"></script>

    <style>

    	html {
  			scroll-behavior: smooth;
    	}

    	body {
			overflow-y: hidden;
    		background: url('<?=base_url();?>assets/background/background.jpg') no-repeat center fixed;
    		background-size: cover;
    		font-family: -apple-system, BlinkMacSystemFont;
    	}
		/* 
    	div.container-fluid {
    		padding-top: 130px;
    		padding-right: 60px;
    		padding-left: 80px;
    	} */

    	#logo-ans {
    		width: 75px;
    		height: auto;
    		float: right;
    	}

    	/* .title-date {
    		padding-top: 40px;
    	} */

    	/* .row-header {
    		padding-top: 60px;
    		top: 0;
    		position: fixed;
    		width: 90%;
    	} */

    	/* .row-footer {
    		margin-top: 275px;
    	}

    	.row-content {
    		height: 350px;
    		padding-top: 75px;
    	}

    	.img-person {
    		width: 450px;
    		height: 350px;
    		text-align: center;
    		padding:2px;
    		border:2px solid #e5e5e5;
    		border-radius: 2px;
    		vertical-align: middle;
    		display: table-cell;
    	} */

    	h1 {
    		font-size: 65px;
    	}

    	p {
    		margin-bottom: 5px;
    	}

    	h3 {
    		margin-bottom: 15px;
    	}
    	#tableAttend tbody {
    		font-size: 12px;
    	}

    	.form-control, .form-control:focus {
    		border-radius: 0;
    		border-color: white !important;
    		font-size: 20px;
    		box-shadow: 10px 10px 17px 2px rgba(0,0,0,0.31);
    	}

        .bg-green {
            background-color: #28a745;
            color: white;
            text-align: center;
        }

        .bg-red {
            background-color: #dc3545;
            color: white;
            text-align: center;
        }

        .clr-red {
            color: #dc3545;
        }

        .clr-fushia {
            color: #f012be;
        }

		.circle {
			background: lightblue;
			border-radius: 50%;
			width: 100px;
			height: 100px;
			text-align: center;
			font-size: 30px;
			padding-top: 25px;
		}

		#modal-xl {
			top: 10vw;
		}

		@media (min-width: 576px) {
			.modal-dialog {
				max-width: 500px !important;
				margin: 1.75rem auto;
			}
		}

		@media (min-width: 800px) {
			.modal-dialog {
				max-width: 700px !important;
				margin: 1.75rem auto;
			}
		}

		@media (min-width: 992px) {
			.modal-dialog {
				max-width: 800px !important;
				margin: 1.75rem auto;
			}
		}

		@media (min-width: 1200px) {
			.modal-dialog {
				max-width: 1100px !important;
				margin: 1.75rem auto;
			}
		}

    </style>

    <script>

        function insertRow(e) {

            var row = tableAttend.insertRow(1);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            var cell4 = row.insertCell(3);
            var cell5 = row.insertCell(4);
			var cell6 = row.insertCell(5);
			cell1.innerHTML = e.name;
			
			if (e.team != null) {
				cell2.innerHTML = e.department + ' (' + e.team + ')';
			} else {
				cell2.innerHTML = e.department;
			}

			// if (e.attend != '') {
			// 	var attend = 'OK';
			// } else {
			// 	var attend = '';
			// }

            cell3.innerHTML = e.division;
            cell4.innerHTML = e.shift;
			cell5.innerHTML = e.time_scan;
			cell6.innerHTML = e.attend;

        }

    	$(document).ready( function () {

			const Toast = Swal.mixin({
				toast: true,
				position: 'top-end',
				showConfirmButton: false,
				timer: 1500
			});

    		var timeOut;

			var input = document.getElementById("scan");

			input.addEventListener("keyup", function(event) {

                var time = "<?php echo date('Y-m-d H:i:s');?>";

				if (event.keyCode === 13) {

					clearTimeout(timeOut);

					event.preventDefault();

					$.ajax({
						url: "<?=site_url('events/scan/' . $event_id);?>",
						type: "post",
						data: {
							barcode: input.value,
							time: time
						}, 
						dataType: "json",
						success: function(data) {

							input.value = '';

							if (data.response == 'error-null') {

								toastr.error('Barcode tidak terdaftar');

							} else if (data.response == 'success' || data.response == 'success-out') {

								if (data.category == 0) {

									toastr.success('Anda berhasil absen event');
									insertRow(data);

								} else {

									if (data.category == 'Ketertarikan') {
										var x = 'Berapa persen ketertarikan mu terhadap event ini?';
									} else {
										var x = 'Berapa persen kepuasan mu terhadap event ini?';
									}

									$('#title').html(x);
									$('#data').val(JSON.stringify(data));
									$('#insert_id').val(data.insert_id);
									$('#modal-xl').modal('show');

								}

							} else if (data.response == 'exists') {

								toastr.info('Anda sudah absen');

							} else if (data.response == 'cant-scan') {

								toastr.error('Anda tidak dapat melakukan absen');

							}

						}
						// End of success function

					});

				}
				// End of keycode

			});
			// End of keyup

			$('#tableAttend').DataTable({
				// Data
                ajax: {
                    url: "<?=site_url('events/server_side_data_attend/' . $event_id);?>",
                    type: "POST"
                },
                processing: true,
                serverSide: true,
                paging: false,
                searching: false,
                info: false,
                ordering: false,
                scrollY: '23vw',
                scrollCollapse: true,
                "oLanguage": {
                    "sEmptyTable": "-"
                }

			});

		});
		// End of document function

		function insert_value(value) {

			var x = $('#data').val();
			var insert_id = $('#insert_id').val();

			$.ajax({
				url: "<?=site_url('events/insert_review/');?>",
				type: "post",
				data: {
					insert_id: insert_id,
					range: value
				}, 
				success: function (param) {

					x = JSON.parse(x);

					$('#modal-xl').modal('hide');

					setTimeout(function () {

						toastr.success('Anda berhasil absen event');
						insertRow(x);

					}, 500);

				}
			
			});

		}

		var myVar = setInterval(myTimer, 1000);

		function myTimer() {
			var d = new Date();
			var t = d.toLocaleTimeString();
            var h = d.getHours();
            var m = d.getMinutes();
            var s = d.getSeconds();

			document.getElementById("time").innerHTML = t;
		}

    </script>

</head>

<body>

	<div id="scanner-page" class="container-fluid" style="height: 100vh;">

		<div class="row row-header">

			<div class="col-md-6" style="padding-left:3vw;padding-top:3vw">
				<h3><?php echo date_format(date_create(), 'd F Y');?>
				<div id="time"></div>
			</h3>
			</div>
			<!-- /div.col -->

			<div class="col-md-6">
				<img src="<?=base_url();?>assets/background/logo-ans.png" id="logo-ans" />
			</div>
			<!-- /div.col -->

		</div>
		<!-- /div.row -->

		<div class="row row-content">

			<div class="col-md-8" style="padding-left:3vw">

				<table id="tableAttend" class="table table-sm">

					<thead>
						<tr>
							<th>Name</th>
							<th>Perusahaan</th>
							<th>Jabatan</th>
							<th>Shift</th>
                            <th>Waktu</th>
                            <th>Status</th>
						</tr>
					</thead>

					<tbody>
					</tbody>

				</table>

			</div>
			<!-- /div.col -->

			<div class="col-md-1">

			</div>
			<!-- /div.col -->

			<div class="col-md-3" style="position:absolute;bottom:10vh;right:0">
				<p>Barcode Scan :</p>
				<input type="text" class="form-control" name="barcode" id="scan" autofocus onblur="$(this).focus();">				
			</div>
			<!-- /div.col -->

		</div>
		<!-- /div.row -->

		<div class="row row-footer">

			<div class="col-md-6" style="position:absolute;bottom:0;left:0">
				for a better future life<br>
				&copy; 2010 - 2020
			</div>
			<!-- /div.col -->

			<div class="col-md-6" style="position:absolute;bottom:0;right:0">
				<br>
				<p class="float-right">www.ansena.com</p>
			</div>
			<!-- /div.col -->

		</div>
		<!-- /div.row -->

	</div>
	<!-- /div.container -->

	<div class="modal fade" id="modal-xl" data-backdrop="static">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-body">

					<input type="hidden" id="data">
					<input type="hidden" id="insert_id">

					<h3 class="text-center" id="title"></h3>

					<br>

					<div class="row">
						<div class="col-sm-1">
						</div>
						<div class="col-sm-2 text-center">
							<div class="circle text-center" onclick="insert_value(20)">
								20%
							</div>
						</div>
						<div class="col-sm-2 text-center">
							<div class="circle text-center" onclick="insert_value(40)">
								40%
							</div>
						</div>
						<div class="col-sm-2 text-center">
							<div class="circle text-center" onclick="insert_value(60)">
								60%
							</div>
						</div>
						<div class="col-sm-2 text-center">
							<div class="circle text-center" onclick="insert_value(80)">
								80%
							</div>
						</div>
						<div class="col-sm-2 text-center">
							<div class="circle text-center" onclick="insert_value(100)">
								100%
							</div>
						</div>
						<div class="col-sm-1">
						</div>
					</div>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

</body>

</html>