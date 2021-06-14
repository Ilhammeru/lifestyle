<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/*
 * Class Dashboard
 */
class Dashboard extends CI_Controller {

	function __construct() {

		parent::__construct();

		// Check session
		$this->session_lib->check_session();

	}
	// End of function __construct

	public function index() {

		$this->layout_lib->default_template('layouts/dashboard');
		
	}
	// End of function index

	/**
	 * @param post => event_id
	 */
	public function show_diagram() {

		$event_id = $this->input->post('event_id');

		$event = $this->db->query("SELECT title, detail FROM events WHERE id = " . $event_id)->row_array();

		$detail = json_decode($event['detail'], TRUE);

		$review_in_all_p = $this->db->query("SELECT AVG(IF(events_attend.attend = 'IN', review_events, 0)) AS 'review_in'
												FROM events_attend
												JOIN ac_payroll_item ON ac_payroll_item.id = events_attend.ac_payroll_item_id
												WHERE event_id = " . $event_id . "
												AND gender = 'p'
												AND review_events IS NOT NULL
												AND events_attend.attend = 'IN'
												GROUP BY event_id, events_attend.attend")->row_array();

		$review_in_all_w = $this->db->query("SELECT AVG(IF(events_attend.attend = 'IN', review_events, 0)) AS 'review_in'
												FROM events_attend
												JOIN ac_payroll_item ON ac_payroll_item.id = events_attend.ac_payroll_item_id
												WHERE event_id = " . $event_id . "
												AND gender = 'w'
												AND review_events IS NOT NULL
												AND events_attend.attend = 'IN'
												GROUP BY event_id, events_attend.attend")->row_array();

		$review_out_all_p = $this->db->query("SELECT AVG(IF(events_attend.attend = 'OUT', review_events, 0)) AS 'review_out'
												FROM events_attend
												JOIN ac_payroll_item ON ac_payroll_item.id = events_attend.ac_payroll_item_id
												WHERE event_id = " . $event_id . "
												AND gender = 'p'
												AND review_events IS NOT NULL
												AND events_attend.attend = 'OUT'
												GROUP BY event_id, events_attend.attend")->row_array();

		$review_out_all_w = $this->db->query("SELECT AVG(IF(events_attend.attend = 'OUT', review_events, 0)) AS 'review_out'
												FROM events_attend
												JOIN ac_payroll_item ON ac_payroll_item.id = events_attend.ac_payroll_item_id
												WHERE event_id = " . $event_id . "
												AND gender = 'w'
												AND review_events IS NOT NULL
												AND events_attend.attend = 'OUT'
												GROUP BY event_id, events_attend.attend")->row_array();

		$attr = array(
						'review_in_all_p' 	=> $review_in_all_p['review_in'],
						'review_in_all_w' 	=> $review_in_all_w['review_in'],
						'review_out_all_p'	=> $review_out_all_p['review_out'],
						'review_out_all_w'	=> $review_out_all_w['review_out']
					);

		$this->load->view('layouts/bar-chart', $attr);

	} 
	// End of function show_diagram

	public function load_modal_sign_out() {

		$this->load->view('layouts/modal-sign-out');
		
	}
	// End of function load_modal_sign_out

	public function destroy_sessions() {

		// Delete throttle
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->delete('lf_throttle');

		// Destroy session
		$this->session->sess_destroy();

		redirect('sessions/signin?logout=1');
		
	}
	// End of function destroy_sessions

}
/* End of file Dashboard.php */
/* Location: ./application/controllers/Dashboard.php/ */
