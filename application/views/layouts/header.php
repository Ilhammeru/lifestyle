<!doctype html>

<html>

<head>
    <title>
        App
    </title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width">
    <meta name="robots" content="NOINDEX,NOFOLLOW">

    <!-- START STYLES -->

    <!-- Bootstrap v4.5.2 -->
    <link rel="stylesheet" href="<?=base_url();?>assets/bootstrap/css/bootstrap.min.css?v4.5.2">

    <!-- Offcanvas -->
    <link rel="stylesheet" href="<?=base_url();?>assets/bootstrap/css/offcanvas.css">

    <!-- Font Awesome v5.14.0 -->
    <link rel="stylesheet" href="<?=base_url();?>assets/vendor/fontawesome/css/all.min.css?v5.14.0">

    <!-- Sweetalert2 v10.3.5 -->
    <link rel="stylesheet" href="<?=base_url();?>assets/vendor/sweetalert2/dist/sweetalert2.min.css?v10.3.5">

    <!-- Select2 v4.1.0 -->
    <link rel="stylesheet" href="<?=base_url();?>assets/vendor/select2/dist/css/select2.min.css?v4.1.0">

    <!-- DataTables v1.10.22 -->
    <link rel="stylesheet" href="<?=base_url();?>assets/vendor/datatables/datatables.min.css?v1.10.22">

    <!-- DataTables v1.10.22 with Bootstrap v4.4.11 -->
    <link rel="stylesheet" href="<?=base_url();?>assets/vendor/datatables/datatables/css/dataTables.bootstrap4.min.css?v4.4.11">

    <!-- Buttons v1.6.4 -->
    <link rel="stylesheet" href="<?=base_url();?>assets/vendor/datatables/buttons/css/buttons.bootstrap4.min.css?v1.6.4">

    <!-- Fixed Header v3.1.7 -->
    <link rel="stylesheet" href="<?=base_url();?>assets/vendor/datatables/fixedheader/css/fixedHeader.bootstrap4.min.css?v3.1.7">

    <!-- AdminLTE v3.0.5 -->
    <link rel="stylesheet" href="<?=base_url();?>assets/dist/css/adminlte.min.css?v3.0.5">

    <!-- calendar event css -->
    <link rel="stylesheet" href="<?= base_url();?>assets/vendor/fullcalendar/lib/main.css">

    <!-- END STYLES -->

    <!-- START PLUGINS -->

    <!-- jQuery v3.5.1 -->
    <script src="<?=base_url();?>assets/vendor/jquery/jquery-3.5.1.min.js?v3.5.1"></script>

    <!-- Bootstrap v4.5.2 -->
    <script src="<?=base_url();?>assets/bootstrap/js/bootstrap.min.js?v4.5.2"></script>

    <!-- Offcanvas -->
    <script src="<?=base_url();?>assets/bootstrap/js/offcanvas.js"></script>

    <!-- AdminLTE v3.0.5 -->
    <script src="<?=base_url();?>assets/dist/js/adminlte.min.js?v3.0.5"></script>

    <!-- jQuery Validate v1.19.2 -->
    <script src="<?=base_url();?>assets/vendor/jquery-validation/dist/jquery.validate.min.js?v1.19.2"></script>

    <!-- Input Mask 4.0.9 -->
    <script src="<?=base_url();?>assets/vendor/inputmask/jquery.inputmask.bundle.min.js?4.0.9"></script>

    <!-- Sweetalert2 v10.3.5 -->
    <script src="<?=base_url();?>assets/vendor/sweetalert2/dist/sweetalert2.min.js?v10.3.5"></script>

    <!-- Select2 v4.1.0 -->
    <script src="<?=base_url();?>assets/vendor/select2/dist/js/select2.min.js?v4.1.0"></script>

    <!-- DataTables v1.10.22 -->
    <script src="<?=base_url();?>assets/vendor/datatables/datatables.min.js?v1.10.22"></script>

    <!-- DataTables v1.10.22 with Bootstrap v4.4.11 -->
    <script src="<?=base_url();?>assets/vendor/datatables/datatables/js/dataTables.bootstrap4.min.js?v4.4.11"></script>

    <!-- Buttons v1.6.4 -->
    <script src="<?=base_url();?>assets/vendor/datatables/buttons/js/dataTables.buttons.min.js?v1.6.4"></script>
    <script src="<?=base_url();?>assets/vendor/datatables/buttons/js/buttons.bootstrap4.min.js?v1.6.4"></script>

    <script src="<?=base_url();?>assets/vendor/datatables/buttons/js/buttons.print.min.js?v1.6.4"></script>

    <!-- Fixed Header v3.1.7 -->
    <script src="<?=base_url();?>assets/vendor/datatables/fixedheader/js/dataTables.fixedHeader.min.js?v3.1.7"></script>
    <script src="<?=base_url();?>assets/vendor/datatables/fixedheader/js/fixedHeader.bootstrap4.min.js?v3.1.7"></script>

    <script src="<?=base_url();?>assets/vendor/datatables/date-de.js"></script>

    <!-- Calendar event -->
    <script src="<?= base_url();?>assets/vendor/fullcalendar/lib/main.min.js"></script>

    <!-- END PLUGINS -->

    <style>

        .navbar {
            padding: .5rem 1rem !important;
        }

        .content-wrapper {
            padding-top: 15px;
        }

        span.visible-xs {
            display: none;
        }

        .is-invalid {
            color: #dc3545 !important;
        }

        .select2 {
            width: 100%;
        }

        table.table {
            width: 100%;
            font-size: 14px;
        }
        
        .dataTables_filter {
            display: none; 
        }

        .form-control.form-left {
            border-bottom-right-radius: 0;
            border-top-right-radius: 0;
        }

        .form-control.is-valid, .form-control.is-invalid {
            padding-right: 0.5rem;
        }
        
        .list-hover:hover {
            background: #6c757d;
            color: #fff;
        }

        input, textarea {
            text-transform: capitalize;
        }

        .col-list {
            max-height: 467px;
        }

        .card-body-list {
            max-height: 550px;
        }

        .block {
            display: inline-block;
        }

        .dropdown-item:focus, .dropdown-item:hover {
            color: white;
            text-decoration: none;
            background-color: #343a40;
        }

        .back-to-top-left {
            bottom: 1.25rem;
            position: fixed;
            left: 1.25rem;
            z-index: 1032;
        }

        .pr-24 {
            padding-right: 24px !important;
        }

        @keyframes blinker {
            50% {
                opacity: 75%;
            }
        }

        .due-date {
            background-color: #ffbdc8 !important;
            animation: blinker 2s linear infinite;
        }
        
        @media (max-width: 375px) {

            span.visible-xs {
                display: inline;
            }

        }

    </style>

    <script>

        $(function () {

            $(document).on('click', '#btn-signout', function () {
                $('div#modal-placehorder').load("<?=site_url('dashboard/load_modal_sign_out');?>");
            });

        });

    </script>

</head>
<body class="bg-light layout-top-nav">

<div class="wrapper">
