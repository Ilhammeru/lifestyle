<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/** 
* @author Rivan
* @version v2.05.07
* @modify Ilham
* @updated 
*/

/*
 * Class Events
 */
class Events extends CI_Controller {

	function __construct() {

		parent::__construct();

		// Check session
		$this->session_lib->check_session();

	}
    // End of function __construct();
    
    public function index() {

		$attr = array(
						'category' => $this->db->query("SELECT DISTINCT event_category
															   FROM events
															   ORDER BY event_category IS NOT NULL")->result()
					);

        $this->layout_lib->default_template('events/index', $attr);
        
    }
	// End of function index

	public function scanner_page($event_id) {

		$attr = array(
						'event_id' => $event_id
					);

		$this->load->view('events/scanner-page-events', $attr);

	}
	// End of function scanner_page

	public function scan($event_id) {

		$datetime = date('Y-m-d H:i:s');
		$date = date('Y-m-d');
		$time = date('H:i:s');

		// Query Master
		$query = $this->db->query("SELECT ac_payroll_item.id, 
										date_contract,
										ac_payroll_item.name, 
										shift, 
										IF(team IS NOT NULL, CONCAT('t', ansena_team.id), CONCAT('d', ac_payroll_item.office)) AS 'ref_id',
										IF(is_pt = 1, CONCAT('PT. ', REPLACE(ansena_department.name, 'PT. ', '')), REPLACE(ansena_department.name, 'PT. ', '')) AS 'department',
										ansena_team.team,
										ansena_division.division
										FROM ac_payroll_item
										JOIN ansena_department ON ansena_department.id = ac_payroll_item.office
										JOIN ansena_division ON ansena_division.id = ac_payroll_item.division
										LEFT JOIN ansena_team_detail ON ansena_team_detail.ac_payroll_item_id = ac_payroll_item.id
										LEFT JOIN ansena_team ON ansena_team.id = ansena_team_detail.ansena_team_id
										WHERE is_active = 1
										AND barcode = '" . $this->input->post('barcode') . "'");

		if ($query->num_rows() > 0) {

			$event = $this->db->query("SELECT event_date, event_time, detail FROM events WHERE id = " . $event_id)->row_array();
			$arrayDetail = json_decode($event['detail'], TRUE);

			$result = $query->row_array();

			if (isset($arrayDetail[$result['ref_id']])) {

				// Query Attend
				$queryAttend = $this->db->query("SELECT id,
														time_scan,
														attend,
														review_events
														FROM events_attend
														WHERE event_id = " . $event_id . "
														AND ac_payroll_item_id = " . $result['id'] . "
														ORDER BY time_scan DESC
														LIMIT 1");

				if ($queryAttend->num_rows() > 0) {

					$resultAttend = $queryAttend->row_array();
					
					if ($resultAttend['attend'] == 'IN') {

						$data = array(
										'event_id'				=> $event_id,
										'ac_payroll_item_id' 	=> $result['id'],
										'time_scan' 			=> $datetime,
										'attend' 				=> 'OUT'
									);

						$this->db->insert('events_attend', $data);
						$insert_id = $this->db->insert_id();
						
						$category = 'Kepuasan';
						// if ($time >= date('H:i', strtotime('+45 minutes', strtotime($event['event_time'])))) {
						// 	$category = 'Kepuasan';
						// } else {
						// 	$category = '0';
						// }

						echo json_encode(
							array(
								'response' 		=> 'success-out',
								'name'	 		=> $result['name'],
								'department' 	=> $result['department'],
								'division' 		=> $result['division'],
								'team'			=> $result['team'],
								'shift'			=> $result['shift'],
								'time_scan' 	=> $time,
								'attend' 		=> 'OUT',
								'insert_id'		=> $insert_id,
								'category'		=> $category
							)
						);

					} elseif ($resultAttend['attend'] == 'OUT') {

						$data = array(
										'event_id'				=> $event_id,
										'ac_payroll_item_id' 	=> $result['id'],
										'time_scan' 			=> $datetime,
										'attend' 				=> 'IN'
									);

						$this->db->insert('events_attend', $data);
						$insert_id = $this->db->insert_id();

						if ($resultAttend['review_events'] != '') {
							$category = 'Ketertarikan';
						} else {
							$category = 0;
						}

						echo json_encode(
							array(
								'response' 		=> 'success',
								'name'	 		=> $result['name'],
								'department' 	=> $result['department'],
								'division' 		=> $result['division'],
								'team'			=> $result['team'],
								'shift'			=> $result['shift'],
								'time_scan' 	=> $time,
								'attend' 		=> 'IN',
								'insert_id'		=> $insert_id,
								'category'		=> $category
							)
						);

					}

				} else {

					$data = array(
									'event_id'				=> $event_id,
									'ac_payroll_item_id' 	=> $result['id'],
									'time_scan' 			=> $datetime,
									'attend' 				=> 'IN'
								);

					$this->db->insert('events_attend', $data);
					$insert_id = $this->db->insert_id();

					echo json_encode(
						array(
							'response' 		=> 'success',
							'name'	 		=> $result['name'],
							'department' 	=> $result['department'],
							'division' 		=> $result['division'],
							'team'			=> $result['team'],
							'shift'			=> $result['shift'],
							'time_scan' 	=> $time,
							'attend' 		=> 'IN',
							'insert_id'		=> $insert_id,
							'category'		=> 'Ketertarikan'
						)
					);

				}

			} else {

				echo json_encode(
					array(
						'response' 		=> 'cant-scan'
					)
				);

			}

		} else {

			echo json_encode(
				array(
					'response' 		=> 'error-null'
				)
			);

		}

	}
	// End of function scan

	public function insert_review() {
		
		$data = array(
						'review_events' 	=> $this->input->post('range')
					);

		$this->db->where('id', $this->input->post('insert_id'));
		$this->db->update('events_attend', $data);

	}
	// End of function insert_review
	
	/** 
	 * @param post => event_name
	 * @param post => event_category
	 * @param post => event_date
	 * @param post => event_time
	 */
	public function add_event() {

		if ($this->input->post('event_name') == '' ||
			$this->input->post('event_category') == '' ||
			$this->input->post('event_date') == '' ||
			$this->input->post('event_time') == '') {

			$data = array(
							'response' 		=> 'error-null'
						);

			echo json_encode($data);

		} else {

			$report = $this->input->post('report');

			if (empty($report)) {
				$report = 0;
			}

			$data = array(
							'title'			=> $this->input->post('event_name'),
							'event_category'=> $this->input->post('event_category'),
							'event_date'	=> $this->input->post('event_date'),
							'event_time'	=> $this->input->post('event_time'),
							'dresscode'		=> $this->input->post('dresscode'),
							'event_place'	=> $this->input->post('event_place'),
							'participant'	=> $this->input->post('participant'),
							'report'		=> $report,
							'created_time'	=> date('Y-m-d H:i:s'),
							'updated_time'	=> date('Y-m-d H:i:s'),
							'creator'		=> $this->session->userdata('user_id'),
							'updated_by'	=> $this->session->userdata('user_id')
						);

			$this->db->insert('events', $data);
			$insert_id = $this->db->insert_id();

			$data = array(
							'response' 		=> 'success',
							'insert_id'		=> $insert_id
						);

			echo json_encode($data);

		}

	}
	// End of function add_event

	public function edit_event() {

		$id = $this->input->post('insert_id');

		$attr = array(
						'category' => $this->db->query("SELECT DISTINCT event_category
															   FROM events
															   ORDER BY event_category IS NOT NULL")->result(),
						'event' => $this->db->query("SELECT id,
															title,
															event_category,
															event_date,
															event_time,
															participant,
															report
															FROM events
															WHERE id = " . $id)->row_array()
					);

		$this->load->view('events/edit-event', $attr);

	}
	// End of function edit_event

	/** 
	 * @param post => event_name
	 * @param post => event_category
	 * @param post => event_date
	 * @param post => event_time
	 */
	public function update_event($id) {

		if ($this->input->post('event_name') == '' ||
			$this->input->post('event_category') == '' ||
			$this->input->post('event_date') == '' ||
			$this->input->post('event_time') == '') {

			$data = array(
							'response' 		=> 'error-null'
						);

			echo json_encode($data);

		} else {

			$data = array(
							'title'			=> $this->input->post('event_name'),
							'event_category'=> $this->input->post('event_category'),
							'event_date'	=> $this->input->post('event_date'),
							'event_time'	=> $this->input->post('event_time'),
							'participant'	=> $this->input->post('participant'),
							'report'		=> $this->input->post('report'),
							'updated_time'	=> date('Y-m-d H:i:s'),
							'updated_by'	=> $this->session->userdata('user_id')
						);
			
			$this->db->where('id', $id);
			$this->db->update('events', $data);

			$data = array(
							'response' 		=> 'success',
							'insert_id'		=> $id
						);

			echo json_encode($data);

		}

	}
	// End of function update_event

	public function setting_event() {

		$attr = array(
						'department' => $this->db->query("SELECT CONCAT(IF(is_pt = 1, 
																		CONCAT('PT. ', REPLACE(name, 'PT. ', '')), 
																		REPLACE(name, 'PT. ', '')),
																		' (', team, ')') AS 'name',
															     CONCAT('t', ansena_team.id) AS 'id'
																 FROM ansena_team 
																 LEFT JOIN ansena_department ON ansena_department.id = ansena_team.dept_id
																 UNION ALL 
																 SELECT IF(is_pt = 1, 
																		CONCAT('PT. ', REPLACE(name, 'PT. ', '')), 
																		REPLACE(name, 'PT. ', '')) AS 'name',
																 CONCAT('d', ansena_department.id) AS 'id'
																 FROM ansena_department 
																 LEFT OUTER JOIN ansena_team ON ansena_team.dept_id = ansena_department.id 
																 WHERE ansena_team.id IS NULL
																 AND progress != 0")->result(),
						'events'	=> $this->db->query("SELECT detail
																FROM events
																WHERE id = " . $this->input->post('insert_id'))->row_array(),
						'id'		=> $this->input->post('insert_id')
					);

		$this->load->view('events/setting-event', $attr);

	}
	// End of function setting_event

	public function report_attend() {

		$dept_id = $this->input->get('dept_id');

		if (substr($dept_id, 0, 1) == 'd') {
			$dept_id = preg_replace('/[^0-9]/', '', $dept_id);

			$query = "SELECT ac_payroll_item.id,
							ac_payroll_item.name AS employee_name, 
							is_pt,
							ansena_department.name AS 'department',
							ansena_division.division, 
							ansena_team.team,
							IF(time_scan IS NOT NULL, MIN(time_scan), NULL) AS 'time_in',
							IF(time_scan IS NOT NULL, MAX(time_scan), NULL) AS 'time_out',
							IF(time_scan IS NOT NULL, TIMEDIFF(MAX(time_scan), MIN(time_scan)), NULL) AS 'time_diff',
							MAX(IF(events_attend.attend = 'IN', review_events, 0)) AS 'review_in',
							MAX(IF(events_attend.attend = 'OUT', review_events, 0)) AS 'review_out'
							FROM ac_payroll_item
							LEFT JOIN events_attend ON events_attend.ac_payroll_item_id = ac_payroll_item.id AND event_id = " . $this->input->get('event_id') . "
							JOIN ansena_department ON ansena_department.id = ac_payroll_item.office
							JOIN ansena_division ON ansena_division.id = ac_payroll_item.division
							LEFT JOIN ansena_team_detail ON ansena_team_detail.ac_payroll_item_id = ac_payroll_item.id
							LEFT JOIN ansena_team ON ansena_team.id = ansena_team_detail.ansena_team_id
							WHERE is_active = 1
							AND office = " . $dept_id . "
							GROUP BY ac_payroll_item.id
							ORDER BY ac_payroll_item.name ASC";

		} else {

			$dept_id = preg_replace('/[^0-9]/', '', $dept_id);

			$query = "SELECT ac_payroll_item.id,
							ac_payroll_item.name AS employee_name, 
							is_pt,
							ansena_department.name AS 'department',
							ansena_division.division, 
							ansena_team.team,
							IF(time_scan IS NOT NULL, MIN(time_scan), NULL) AS 'time_in',
							IF(time_scan IS NOT NULL, MAX(time_scan), NULL) AS 'time_out',
							IF(time_scan IS NOT NULL, TIMEDIFF(MAX(time_scan), MIN(time_scan)), NULL) AS 'time_diff',
							MAX(IF(events_attend.attend = 'IN', review_events, 0)) AS 'review_in',
							MAX(IF(events_attend.attend = 'OUT', review_events, 0)) AS 'review_out'
							FROM ac_payroll_item
							LEFT JOIN events_attend ON events_attend.ac_payroll_item_id = ac_payroll_item.id AND event_id = " . $this->input->get('event_id') . "
							JOIN ansena_department ON ansena_department.id = ac_payroll_item.office
							JOIN ansena_division ON ansena_division.id = ac_payroll_item.division
							LEFT JOIN ansena_team_detail ON ansena_team_detail.ac_payroll_item_id = ac_payroll_item.id
							LEFT JOIN ansena_team ON ansena_team.id = ansena_team_detail.ansena_team_id
							WHERE is_active = 1
							AND ansena_team.id = " . $dept_id . "
							GROUP BY ac_payroll_item.id
							ORDER BY ac_payroll_item.name ASC";

		}

		$result = $this->db->query($query)->result();

		$attr = array(
						'attend' => $result
					);

		$this->load->view('events/report-attend', $attr);

	}
	// End of function report_attend

	/**
	 * @param post => input_holding
	 */
	public function insert_holding() {

		$holding = $this->input->post('input_holding');
		if (count($holding) == 0) {
			echo 'error-null';
		} else {

			for ($i = 0; $i < count($holding); $i++) {

				$detail[$holding[$i]] = 1;

			}

			$data = array(
							'detail'		=> json_encode($detail),
							'updated_time'	=> date('Y-m-d H:i:s'),
							'updated_by'	=> $this->session->userdata('user_id')
						);

			$this->db->where('id', $this->input->post('id'));
			$this->db->update('events', $data);

			echo 'success';

		}

	}
	// End of function insert_holding

	public function setting_hpp($id) {

		$events_master = "SELECT id, hpp_category, hpp_subcategory
								 FROM events_master
								 ORDER BY sort_category, sort_subcategory ASC";

		$attr = array(
						'event'			=> $this->db->query("SELECT id, title, event_category
																	FROM events
																	WHERE id = " . $id)->row_array(),
						'eventsMaster'	=> $this->db->query($events_master)->result()
					);

		$this->layout_lib->default_template('events/setting-hpp', $attr);

	}
	// End of function setting_hpp

	public function display_insert() {

		$id = $this->input->post('id');
		$event_id = $this->input->post('event_id');

		$result = $this->db->query("SELECT id, item_name, item_qty, item_price
										   FROM events_hpp
										   WHERE event_id = " . $event_id . "
										   AND master_id = " . $id);

		$attr = array(
						'count_events_hpp' 	=> $result->num_rows(),
						'events_master'	=> $this->db->query("SELECT id, hpp_category, hpp_subcategory
																	FROM events_master
																	WHERE id = " . $id)->row_array(),
						'event_id'		=> $event_id
					);

		$this->load->view('events/insert_item', $attr);

	}
	// End of function display_insert

	/**
	 * @param post => item_name
	 * @param post => item_qty
	 * @param post => item_satuan
	 * @param post => item_price
	 * @param post => master_id
	 * @param post => event_id
	 */
	public function save_item() {

		$item_name 		= $this->input->post('item_name');
		$item_qty 		= $this->input->post('item_qty');
		$item_satuan	= $this->input->post('item_satuan');
		$item_price 	= $this->input->post('item_price');
		$master_id		= $this->input->post('master_id');
		$event_id		= $this->input->post('event_id');

		$this->db->trans_start();
		
		for ($i = 0; $i < count($item_name); $i++) {

			if ($item_name[$i] == '' ||
				$item_qty[$i] == '' ||
				$item_satuan[$i] == '' ||
				$item_price[$i] == '') {
					echo 'error';
			} else {

				$data[] = array(
								'master_id'		=> $master_id,
								'event_id'		=> $event_id,
								'item_name'		=> $item_name[$i],
								'item_qty'		=> $item_qty[$i],
								'item_satuan'	=> $item_satuan[$i],
								'item_price'	=> str_replace(',', '', str_replace('.00', '', $item_price[$i])),
								'created_time'	=> date('Y-m-d H:i:s'),
								'updated_time' 	=> date('Y-m-d H:i:s'),
								'creator'		=> $this->session->userdata('user_id'),
								'updated_by'	=> $this->session->userdata('user_id')
				);

			}

		}

		$check = $this->db->query("SELECT id FROM events_hpp WHERE master_id = " . $master_id . " AND event_id = " . $event_id);

		if ($check->num_rows() > 0) {

			$arrayWhere = array(
								'master_id'	=> $master_id,
								'event_id'	=> $event_id
							);

			$this->db->where($arrayWhere);
			$this->db->delete('events_hpp');
		}

		$this->db->insert_batch('events_hpp', $data);

		if ($this->db->trans_status() === false) {

			$this->db->trans_rollback();

			echo 'error';

		} else {

			$this->db->trans_commit();

			echo 'success';

		}

	}
	// End of function save_item

	public function get_total_subcategory() {

		$id = $this->input->post('id');
		$event_id = $this->input->post('event_id');

		$query = $this->db->query("SELECT SUM(item_price*item_qty) AS 'total'
										 FROM events_hpp
										 WHERE event_id = " . $event_id . "
										 AND master_id = " . $id . "
										 GROUP BY master_id");

		if ($query->num_rows() > 0) {
			$result = $query->row_array();
			$total = $result['total'];
		} else {
			$total = 0;
		}

		echo $total;

	}
	// End of function get_total_subcategory

	public function get_total_category() {

		$id = $this->input->post('id');
		$event_id = $this->input->post('event_id');

		$master = $this->db->query("SELECT hpp_category FROM events_master WHERE id = " . $id)->row_array();

		$hpp_category = $master['hpp_category'];

		$query = $this->db->query("SELECT SUM(item_price*item_qty) AS 'total'
										 FROM events_hpp
										 JOIN events_master ON events_master.id = events_hpp.master_id
										 WHERE event_id = " . $event_id . "
										 AND hpp_category = '" . $hpp_category . "'
										 GROUP BY hpp_category");

		if ($query->num_rows() > 0) {
			$result = $query->row_array();
			$total = $result['total'];
		} else {
			$total = 0;
		}

		echo $total;

	}
	// End of function get_total_category

	public function add_income($id) {

		$attr = array(
						'events' => $this->db->query("SELECT id, title
															 FROM events
															 WHERE id = " . $id)->row_array(),
						'department' 	=> $this->db->query("SELECT id,
																 	IF(is_pt = 1, CONCAT('PT. ', REPLACE(name, 'PT. ', '')), REPLACE(name, 'PT. ', '')) AS 'name' 
																 	FROM ansena_department
																 	ORDER BY sort ASC")->result()
					);

		$this->layout_lib->template_with_custom_navbar('events/navbar-sub-income', 'events/insert-income', $attr);

	}
	// End of function add_income

	public function insert_income($event_id) {

		if ($this->input->post('income_from') == '' ||
			$this->input->post('income_date') == ''||
			$this->input->post('nominal') == '') {
				echo 'error-null';
		} else {

			$data = array(
							'event_id'		=> $event_id,
							'income_from'	=> $this->input->post('income_from'),
							'income_date'	=> $this->input->post('income_date'),
							'nominal'		=> str_replace(',', '', str_replace('.00', '', $this->input->post('nominal'))),
							'created_time'	=> date('Y-m-d H:i:s'),
							'updated_time'	=> date('Y-m-d H:i:s'),
							'creator'		=> $this->session->userdata('user_id'),
							'updated_by'	=> $this->session->userdata('user_id')
			);

			$this->db->insert('events_income', $data);

			echo 'success';

		}

	}
	// End of function insert_income

	public function server_side_data() {

		$result = $this->db->query("SELECT id,
										   title,
										   event_category,
										   event_date,
										   event_time
										   FROM events
										   ORDER BY event_date DESC")->result();

		$data = array();

		foreach ($result as $rows) :

			$row = array();

			$btnDelete = '<a href="javascript:void(0)" class="btn btn-outline-danger btn-sm" onclick="delete_event(' . $rows->id . ')"><i class="fa fa-trash"></i></a>';

			$btnEdit = '<a href="javascript:void(0)" class="btn btn-outline-info btn-sm" onclick="display_setting(' . $rows->id . ')"><i class="fa fa-eye"></i></a>';

			$row[] = $btnEdit . ' ' . $btnDelete;

			$row[] = date('d.m.Y', strtotime($rows->event_date));

			$row[] = $rows->title;

			$row[] = $rows->event_category;

			$row[] = $rows->event_time;

			$data[] = $row;

		endforeach;

		$output = array(
						'data' => $data
					);

		echo json_encode($output);

	}
	// End of function server_side_data
	
	public function server_side_data_attend($id) {

		$query = $this->db->query("SELECT ac_payroll_item.id, 
						barcode, ac_payroll_item.name AS employee_name, 
						is_pt,
						ansena_department.name AS 'department',
						ansena_division.division, 
						ansena_team.team,
						ac_payroll_item.shift,
						DATE_FORMAT(time_scan, '%H:%i:%s') AS time_scan, 
						events_attend.attend
						FROM ac_payroll_item
						JOIN ansena_department ON ansena_department.id = ac_payroll_item.office
						JOIN ansena_division ON ansena_division.id = ac_payroll_item.division
						JOIN events_attend ON events_attend.ac_payroll_item_id = ac_payroll_item.id
						LEFT JOIN ansena_team_detail ON ansena_team_detail.ac_payroll_item_id = ac_payroll_item.id
						LEFT JOIN ansena_team ON ansena_team.id = ansena_team_detail.ansena_team_id
						WHERE events_attend.event_id = " . $id . "
						ORDER BY time_scan DESC");

		$results = $query->result();

		$data = array();

		foreach ($results as $rows) :

			$row = array();

			$row[] = $rows->employee_name;

			if ($rows->is_pt == 1) {
				$department = 'PT. ' . str_replace('PT. ', '', $rows->department);
			} else {
				$department = str_replace('PT. ', '', $rows->department);
			}

			if ($rows->team != '') {
				$department = $department . ' (' . $rows->team . ')';
			}

			$row[] = $department;

			$row[] = $rows->division;

			$row[] = $rows->shift;

			$row[] = $rows->time_scan;

			if ($rows->attend != NULL) {
			$row[] = 'OK';
			} else {
			$row[] = '';
			}

			$data[] = $row;

		endforeach;

		// Results
		$output = array(
						"data" => $data
					);

		echo json_encode($output);
		
	}
	// End of function server_side_data_attend

	public function server_side_data_detail_item() {

		$query = $this->db->query("SELECT id,
						 item_name,
						 item_qty,
						 item_satuan,
						 item_price
						 FROM events_hpp
						 WHERE event_id = " . $this->input->get('event_id') . "
						 AND master_id = " . $this->input->get('master_id') . "
						 ORDER BY id ASC");

		if ($query->num_rows() > 0) {

			$result = $query->result();

			$data = array();
			foreach ($result as $row) :

				$data[] = array(
								'id'			=> $row->id,
								'item_name' 	=> $row->item_name,
								'item_qty'		=> $row->item_qty,
								'item_satuan'	=> $row->item_satuan,
								'item_price'	=> $row->item_price
							);

			endforeach;

			echo json_encode($data);

		} else {
			echo json_encode('error-null');
		}

	}
	// End of function server_side_data_detail_item

	public function server_side_data_income($event_id) {

		$query = $this->db->query("SELECT events_income.id, 
										DATE_FORMAT(income_date, '%d %M %Y') AS 'income_date', 
										IF(income_from = 0, 'Donasi', IF(is_pt = 1, CONCAT('PT. ', REPLACE(name, 'PT. ', '')), REPLACE(name, 'PT. ', ''))) AS 'name', 
										nominal
										FROM events_income
										LEFT JOIN ansena_department ON ansena_department.id = events_income.income_from
										WHERE event_id = " . $event_id . "
										ORDER BY income_date DESC");

		if ($query->num_rows() > 0) {

			$result = $query->result();

			$data = array();
			foreach ($result as $row) :

				$data[] = array(
									'id' => $row->id,
									'income_date' => $row->income_date,
									'income_from' => $row->name,
									'nominal'	=> $row->nominal
						);

			endforeach;

			echo json_encode($data);

		} else {
			echo json_encode('error-null');
		}

	}
	// End of function server_side_data_income

	public function delete_event() {

		$id = $this->input->post('id');
		
		$this->db->where('id', $id);
		$this->db->delete('events');

		$this->db->where('event_id', $id);
		$this->db->delete('events_attend');

	}
	// End of function delete_event

	public function delete_item() {

		$this->db->where('id', $this->input->post('id'));
		$this->db->delete('events_hpp');

	}
	// End of function delete_item

	public function delete_income() {

		$this->db->where('id', $this->input->post('id'));
		$this->db->delete('events_income');

		echo 'success';

	}
	// End of function delete_income

	public function holiday() {
		$attr = [
			'title'	=> 'Hari libur'
		];
		$this->layout_lib->default_template('holiday/index', $attr);
	}

}
/* End of file Events.php */
/* Location: ./application/controllers/Events.php */
