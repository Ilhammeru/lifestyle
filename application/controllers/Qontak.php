<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/*
 * Class Qontak
 */
class Qontak extends CI_Controller {

    public function auth() {
        $this->load->view('qontak-auth');
    }

    public function setup() {
        $this->load->view('qontak-setup');
    }

    public function template() {
        $this->load->view('qontak-template');
    }

    public function send() {
        $this->load->view('qontak-send');
    }

}
/* End of file Qontak.php */
/* Location: ./application/controllers/Qontak.php/ */
