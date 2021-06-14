    
    <nav class="navbar navbar-expand-md fixed-top navbar-dark bg-dark">

        <img src="<?=base_url();?>assets/background/logo-ans.png" style="width: 40px; height: auto;">

        <button class="navbar-toggler p-0 border-0" type="button" data-toggle="offcanvas">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">

            <ul class="navbar-nav mr-auto">

                <li class="nav-item active">
                    <a class="nav-link" href="#">Dashboard <span class="sr-only">(current)</span></a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Events</a>
                    <div class="dropdown-menu" aria-labelledby="dropdown01">
                        <a class="dropdown-item" href="<?=site_url('events');?>">Data Event</a>
                        <a class="dropdown-item" href="<?=site_url('events/holiday');?>">Hari Libur</a>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Analisa</a>
                    <div class="dropdown-menu" aria-labelledby="dropdown01">
                        <a class="dropdown-item" href="<?=site_url('analisa/attend');?>">Analisa Absen</a>
                        <a class="dropdown-item" href="<?=site_url('analisa/laba_rugi');?>">Laba Rugi Event</a>
                        <a class="dropdown-item" href="<?=site_url('analisa/laba_rugi_bulanan');?>">Laba Rugi Bulanan</a>
                    </div>
                </li>

            </ul>
            <!-- /ul.navbar-nav -->

            <ul class="navbar-nav navbar-right">

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <?=$this->session->userdata('username');?>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="javascript:void(0)" id="btn-signout"
                       class="nav-link"
                       data-toggle="tooltip" data-placement="bottom" title="Logout">
                        <i class="fa fa-power-off"></i>
                        <span class="visible-xs">&nbsp;Logout</span>
                    </a>
                </li>

            </ul>
            <!-- /ul.navbar-nav -->

        </div>
        <!-- /div.navbar-collaps -->

    </nav>
    <!-- /nav.navbar -->