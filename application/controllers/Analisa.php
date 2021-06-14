<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/*
 * Class Analisa
 */
class Analisa extends CI_Controller {

    function __construct() {

		parent::__construct();

		// Check session
		$this->session_lib->check_session();

	}
	// End of function __construct

    public function attend() {

        $attr = array(
                        'events'    => $this->db->query("SELECT id, title
                                                             FROM events
                                                             WHERE DATE_FORMAT(event_date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')
                                                             ORDER BY event_date DESC")->result(),
                        'department' => $this->db->query("SELECT CONCAT(IF(is_pt = 1, CONCAT('PT. ', REPLACE(name, 'PT. ', '')), REPLACE(name, 'PT. ', '')), ' (', team, ')') AS 'name',
															     CONCAT('t', ansena_team.id) AS 'id'
																 FROM ansena_team 
																 LEFT JOIN ansena_department ON ansena_department.id = ansena_team.dept_id 
																 UNION ALL 
																 SELECT IF(is_pt = 1, CONCAT('PT. ', REPLACE(name, 'PT. ', '')), REPLACE(name, 'PT. ', '')) AS 'name',
																 CONCAT('d', ansena_department.id) AS 'id'
																 FROM ansena_department 
																 LEFT OUTER JOIN ansena_team ON ansena_team.dept_id = ansena_department.id 
																 WHERE ansena_team.id IS NULL")->result()
                        
                    );
        
        $this->layout_lib->template_with_custom_navbar('analisa/navbar-sub-attend', 'analisa/attend', $attr);
        
    }
    // End of function attend

    public function laba_rugi() {

        $attr = array(
                        'events'    => $this->db->query("SELECT id, title
                                                             FROM events
                                                             WHERE DATE_FORMAT(event_date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')
                                                             ORDER BY event_date DESC")->result()
                        
                    );
        
        $this->layout_lib->template_with_custom_navbar('analisa/navbar-sub-laba-rugi', 'analisa/laba-rugi', $attr);

    }
    // End of function laba_rugi
    

    public function load_data_laba_rugi() {

        $id = $this->input->post('event_id');

        $events_master = "SELECT id, hpp_category, hpp_subcategory
								 FROM events_master
                                 ORDER BY sort_category, sort_subcategory ASC";
                                 
        $hpp   = $this->db->query("SELECT SUM(item_qty*item_price) AS 'total_hpp'
                        FROM events_hpp
                        WHERE event_id = " . $id . "
                        GROUP BY event_id");

        $income = $this->db->query("SELECT SUM(nominal) AS 'total_income'
                        FROM events_income
                        WHERE event_id = " . $id . "
                        GROUP BY event_id");

        if ($hpp->num_rows() > 0) {
            $hpp = $hpp->row_array();
            $hpp = $hpp['total_hpp'];
        } else {
            $hpp = 0;
        }

        if ($income->num_rows() > 0) {
            $income = $income->row_array();
            $income = $income['total_income'];
        } else {
            $income = 0;
        }

		$attr = array(
						'event'			=> $this->db->query("SELECT id, title, event_category
																	FROM events
																	WHERE id = " . $id)->row_array(),
                        'eventsMaster'	=> $this->db->query($events_master)->result(),
                        'hpp'           => $hpp,
                        'income'        => $income
                    );
                    
        $this->load->view('analisa/report-laba-rugi', $attr);

    }
    // End of function load_data_laba_rugi

    public function display_item() {

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

		$this->load->view('analisa/display-item', $attr);

	}
    // End of function display_item

    public function display_event() {
        
        $periode = $this->input->post('periode');

        $events = $this->db->query("SELECT id, title
                                          FROM events
                                          WHERE DATE_FORMAT(event_date, '%Y-%m') = '" . $periode . "'")->result();

        $html = '<select class="form-control" id="event" name="event">';
        $html .= '<option value="" disabled selected>Pilih Event</option>';
        foreach ($events as $row) :
            $html .= '<option value="' . $row->id . '">' . $row->title . '</option>';
        endforeach;
        $html .= '</select>';

        echo $html;

    }
    // End of function display_event

    public function load_data() {

        $department = $this->input->post('department');

        if (substr($department, 0, 1) == 'x') {

            $query = "SELECT ac_payroll_item.id,
							ac_payroll_item.name AS employee_name, 
							is_pt,
                            gender,
							IF(is_pt = 1, CONCAT('PT. ', REPLACE(ansena_department.name, 'PT. ', '')), REPLACE(ansena_department.name, 'PT. ', '')) AS 'department',
							ansena_division.division, 
							ansena_team.team,
                            event_date,
                            event_time,
							IF(time_scan IS NOT NULL, MIN(time_scan), NULL) AS 'time_in',
							IF(time_scan IS NOT NULL, MAX(time_scan), NULL) AS 'time_out',
							IF(time_scan IS NOT NULL, TIMEDIFF(MAX(time_scan), MIN(time_scan)), NULL) AS 'time_diff',
							MAX(IF(events_attend.attend = 'IN', review_events, 0)) AS 'review_in',
							MAX(IF(events_attend.attend = 'OUT', review_events, 0)) AS 'review_out'
							FROM ac_payroll_item
							LEFT JOIN events_attend ON events_attend.ac_payroll_item_id = ac_payroll_item.id AND event_id = " . $this->input->post('event_id') . "
                            LEFT JOIN events ON events.id = events_attend.event_id
							JOIN ansena_department ON ansena_department.id = ac_payroll_item.office
							JOIN ansena_division ON ansena_division.id = ac_payroll_item.division
							LEFT JOIN ansena_team_detail ON ansena_team_detail.ac_payroll_item_id = ac_payroll_item.id
							LEFT JOIN ansena_team ON ansena_team.id = ansena_team_detail.ansena_team_id
                            WHERE is_active = 1
                            AND barcode IS NOT NULL
                            AND time_scan IS NOT NULL
                            AND ac_payroll_item.division != 20 
                            AND ac_payroll_item.division != 21
                            AND ac_payroll_item.division != 22
                            AND ac_payroll_item.division != 23
                            AND ac_payroll_item.division != 46
							GROUP BY ac_payroll_item.id
                            ORDER BY time_scan, ansena_department.name, ansena_team.team, ac_payroll_item.name ASC";
                            
            $query_2 = "SELECT ac_payroll_item.id,
							ac_payroll_item.name AS employee_name, 
							is_pt,
                            gender,
							IF(is_pt = 1, CONCAT('PT. ', REPLACE(ansena_department.name, 'PT. ', '')), REPLACE(ansena_department.name, 'PT. ', '')) AS 'department',
							ansena_division.division, 
							ansena_team.team,
                            event_date,
                            event_time,
							IF(events_attend.time_scan IS NOT NULL, MIN(events_attend.time_scan), NULL) AS 'time_in',
							IF(events_attend.time_scan IS NOT NULL, MAX(events_attend.time_scan), NULL) AS 'time_out',
							IF(events_attend.time_scan IS NOT NULL, TIMEDIFF(MAX(events_attend.time_scan), MIN(events_attend.time_scan)), NULL) AS 'time_diff',
							MAX(IF(events_attend.attend = 'IN', review_events, 0)) AS 'review_in',
							MAX(IF(events_attend.attend = 'OUT', review_events, 0)) AS 'review_out',
                            ansena_attend.attend
							FROM ac_payroll_item
							LEFT JOIN events_attend ON events_attend.ac_payroll_item_id = ac_payroll_item.id AND event_id = " . $this->input->post('event_id') . "
                            RIGHT JOIN events ON events.id = " . $this->input->post('event_id') . "
							JOIN ansena_department ON ansena_department.id = ac_payroll_item.office
							JOIN ansena_division ON ansena_division.id = ac_payroll_item.division
							LEFT JOIN ansena_team_detail ON ansena_team_detail.ac_payroll_item_id = ac_payroll_item.id
                            LEFT JOIN ansena_team ON ansena_team.id = ansena_team_detail.ansena_team_id
                            LEFT JOIN ansena_attend ON ansena_attend.ac_payroll_item_id = ac_payroll_item.id AND ansena_attend.attend != 'OUT' AND DATE_FORMAT(ansena_attend.time_scan, '%Y-%m-%d') = event_date
                            WHERE is_active = 1
                            AND barcode IS NOT NULL
                            AND events_attend.time_scan IS NULL
                            AND ac_payroll_item.division != 20 
                            AND ac_payroll_item.division != 21
                            AND ac_payroll_item.division != 22
                            AND ac_payroll_item.division != 23
                            AND ac_payroll_item.division != 46
							GROUP BY ac_payroll_item.id
                            ORDER BY events_attend.time_scan, ansena_department.name, ansena_team.team, ac_payroll_item.name ASC";

            $department = $this->db->query("SELECT CONCAT(IF(is_pt = 1, CONCAT('PT. ', REPLACE(name, 'PT. ', '')), REPLACE(name, 'PT. ', '')), ' (', team, ')') AS 'name',
															     CONCAT('t', ansena_team.id) AS 'id'
																 FROM ansena_team 
																 LEFT JOIN ansena_department ON ansena_department.id = ansena_team.dept_id 
																 UNION ALL 
																 SELECT IF(is_pt = 1, CONCAT('PT. ', REPLACE(name, 'PT. ', '')), REPLACE(name, 'PT. ', '')) AS 'name',
																 CONCAT('d', ansena_department.id) AS 'id'
																 FROM ansena_department 
																 LEFT OUTER JOIN ansena_team ON ansena_team.dept_id = ansena_department.id 
																 WHERE ansena_team.id IS NULL")->result();

        } elseif (substr($department, 0, 1) == 'd') {

            $dept_id = preg_replace('/[^0-9]/', '', $department);

			$query = "SELECT ac_payroll_item.id,
                            ac_payroll_item.name AS employee_name, 
                            is_pt,
                            gender,
                            IF(is_pt = 1, CONCAT('PT. ', REPLACE(ansena_department.name, 'PT. ', '')), REPLACE(ansena_department.name, 'PT. ', '')) AS 'department',
                            ansena_division.division, 
                            ansena_team.team,
                            event_date,
                            event_time,
                            IF(time_scan IS NOT NULL, MIN(time_scan), NULL) AS 'time_in',
                            IF(time_scan IS NOT NULL, MAX(time_scan), NULL) AS 'time_out',
                            IF(time_scan IS NOT NULL, TIMEDIFF(MAX(time_scan), MIN(time_scan)), NULL) AS 'time_diff',
                            MAX(IF(events_attend.attend = 'IN', review_events, 0)) AS 'review_in',
                            MAX(IF(events_attend.attend = 'OUT', review_events, 0)) AS 'review_out'
                            FROM ac_payroll_item
                            LEFT JOIN events_attend ON events_attend.ac_payroll_item_id = ac_payroll_item.id AND event_id = " . $this->input->post('event_id') . "
                            LEFT JOIN events ON events.id = events_attend.event_id
                            JOIN ansena_department ON ansena_department.id = ac_payroll_item.office
                            JOIN ansena_division ON ansena_division.id = ac_payroll_item.division
                            LEFT JOIN ansena_team_detail ON ansena_team_detail.ac_payroll_item_id = ac_payroll_item.id
                            LEFT JOIN ansena_team ON ansena_team.id = ansena_team_detail.ansena_team_id
                            WHERE is_active = 1
                            AND barcode IS NOT NULL
                            AND time_scan IS NOT NULL
                            AND ac_payroll_item.division != 20 
                            AND ac_payroll_item.division != 21
                            AND ac_payroll_item.division != 22
                            AND ac_payroll_item.division != 23
                            AND ac_payroll_item.division != 46
                            AND office = " . $dept_id . "
                            GROUP BY ac_payroll_item.id
                            ORDER BY ac_payroll_item.name ASC";

            $query_2 = "SELECT ac_payroll_item.id,
                                ac_payroll_item.name AS employee_name, 
                                is_pt,
                                gender,
                                IF(is_pt = 1, CONCAT('PT. ', REPLACE(ansena_department.name, 'PT. ', '')), REPLACE(ansena_department.name, 'PT. ', '')) AS 'department',
                                ansena_division.division, 
                                ansena_team.team,
                                event_date,
                                event_time,
                                IF(events_attend.time_scan IS NOT NULL, MIN(events_attend.time_scan), NULL) AS 'time_in',
                                IF(events_attend.time_scan IS NOT NULL, MAX(events_attend.time_scan), NULL) AS 'time_out',
                                IF(events_attend.time_scan IS NOT NULL, TIMEDIFF(MAX(events_attend.time_scan), MIN(events_attend.time_scan)), NULL) AS 'time_diff',
                                MAX(IF(events_attend.attend = 'IN', review_events, 0)) AS 'review_in',
                                MAX(IF(events_attend.attend = 'OUT', review_events, 0)) AS 'review_out',
                                ansena_attend.attend
                                FROM ac_payroll_item
                                LEFT JOIN events_attend ON events_attend.ac_payroll_item_id = ac_payroll_item.id AND event_id = " . $this->input->post('event_id') . "
                                RIGHT JOIN events ON events.id = " . $this->input->post('event_id') . "
                                JOIN ansena_department ON ansena_department.id = ac_payroll_item.office
                                JOIN ansena_division ON ansena_division.id = ac_payroll_item.division
                                LEFT JOIN ansena_team_detail ON ansena_team_detail.ac_payroll_item_id = ac_payroll_item.id
                                LEFT JOIN ansena_team ON ansena_team.id = ansena_team_detail.ansena_team_id
                                LEFT JOIN ansena_attend ON ansena_attend.ac_payroll_item_id = ac_payroll_item.id AND ansena_attend.attend != 'OUT' AND DATE_FORMAT(ansena_attend.time_scan, '%Y-%m-%d') = event_date
                                WHERE is_active = 1
                                AND barcode IS NOT NULL
                                AND events_attend.time_scan IS NULL
                                AND ac_payroll_item.division != 20 
                                AND ac_payroll_item.division != 21
                                AND ac_payroll_item.division != 22
                                AND ac_payroll_item.division != 23
                                AND ac_payroll_item.division != 46
                                AND office = " . $dept_id . "
                                GROUP BY ac_payroll_item.id
                                ORDER BY ac_payroll_item.name ASC";

            $department = $this->db->query("SELECT CONCAT('d', id) AS 'id', name 
                                                    FROM ansena_department
                                                    WHERE id = " . $dept_id)->result();

        } elseif (substr($department, 0, 1) == 't') {

            $dept_id = preg_replace('/[^0-9]/', '', $department);

			$query = "SELECT ac_payroll_item.id,
							ac_payroll_item.name AS employee_name, 
							is_pt,
                            gender,
							IF(is_pt = 1, CONCAT('PT. ', REPLACE(ansena_department.name, 'PT. ', '')), REPLACE(ansena_department.name, 'PT. ', '')) AS 'department',
							ansena_division.division, 
							ansena_team.team,
                            event_date,
                            event_time,
							IF(time_scan IS NOT NULL, MIN(time_scan), NULL) AS 'time_in',
							IF(time_scan IS NOT NULL, MAX(time_scan), NULL) AS 'time_out',
							IF(time_scan IS NOT NULL, TIMEDIFF(MAX(time_scan), MIN(time_scan)), NULL) AS 'time_diff',
							MAX(IF(events_attend.attend = 'IN', review_events, 0)) AS 'review_in',
							MAX(IF(events_attend.attend = 'OUT', review_events, 0)) AS 'review_out'
							FROM ac_payroll_item
							LEFT JOIN events_attend ON events_attend.ac_payroll_item_id = ac_payroll_item.id AND event_id = " . $this->input->post('event_id') . "
                            LEFT JOIN events ON events.id = events_attend.event_id
							JOIN ansena_department ON ansena_department.id = ac_payroll_item.office
							JOIN ansena_division ON ansena_division.id = ac_payroll_item.division
							LEFT JOIN ansena_team_detail ON ansena_team_detail.ac_payroll_item_id = ac_payroll_item.id
							LEFT JOIN ansena_team ON ansena_team.id = ansena_team_detail.ansena_team_id
							WHERE is_active = 1
                            AND barcode IS NOT NULL
                            AND time_scan IS NOT NULL
							AND ansena_team.id = " . $dept_id . "
							GROUP BY ac_payroll_item.id
                            ORDER BY ac_payroll_item.name ASC";
                            
            $query_2 = "SELECT ac_payroll_item.id,
                                ac_payroll_item.name AS employee_name, 
                                is_pt,
                                gender,
                                IF(is_pt = 1, CONCAT('PT. ', REPLACE(ansena_department.name, 'PT. ', '')), REPLACE(ansena_department.name, 'PT. ', '')) AS 'department',
                                ansena_division.division, 
                                ansena_team.team,
                                event_date,
                                event_time,
                                IF(events_attend.time_scan IS NOT NULL, MIN(events_attend.time_scan), NULL) AS 'time_in',
                                IF(events_attend.time_scan IS NOT NULL, MAX(events_attend.time_scan), NULL) AS 'time_out',
                                IF(events_attend.time_scan IS NOT NULL, TIMEDIFF(MAX(events_attend.time_scan), MIN(events_attend.time_scan)), NULL) AS 'time_diff',
                                MAX(IF(events_attend.attend = 'IN', review_events, 0)) AS 'review_in',
                                MAX(IF(events_attend.attend = 'OUT', review_events, 0)) AS 'review_out',
                                ansena_attend.attend
                                FROM ac_payroll_item
                                LEFT JOIN events_attend ON events_attend.ac_payroll_item_id = ac_payroll_item.id AND event_id = " . $this->input->post('event_id') . "
                                RIGHT JOIN events ON events.id = " . $this->input->post('event_id') . "
                                JOIN ansena_department ON ansena_department.id = ac_payroll_item.office
                                JOIN ansena_division ON ansena_division.id = ac_payroll_item.division
                                LEFT JOIN ansena_team_detail ON ansena_team_detail.ac_payroll_item_id = ac_payroll_item.id
                                LEFT JOIN ansena_team ON ansena_team.id = ansena_team_detail.ansena_team_id
                                LEFT JOIN ansena_attend ON ansena_attend.ac_payroll_item_id = ac_payroll_item.id AND ansena_attend.attend != 'OUT' AND DATE_FORMAT(ansena_attend.time_scan, '%Y-%m-%d') = event_date
                                WHERE is_active = 1
                                AND barcode IS NOT NULL
                                AND events_attend.time_scan IS NULL
                                AND ansena_team.id = " . $dept_id . "
                                GROUP BY ac_payroll_item.id
                                ORDER BY ac_payroll_item.name ASC";

            $department = $this->db->query("SELECT CONCAT('t', id) AS 'id', team AS 'name'
                                                 FROM ansena_team
                                                 WHERE id = " . $dept_id)->result();

        }

        $event = $this->db->query("SELECT detail, participant, report 
                                        FROM events WHERE id = " . $this->input->post('event_id'))->row_array();

        $attr = array(
                        'attend_null' => $this->db->query($query_2)->result(),
                        'attend'    => $this->db->query($query)->result(),
                        'event_id'  => $this->input->post('event_id'),
                        'event'     => $event,
                        'department' => $department
        );

        $this->load->view('analisa/report-attend', $attr);

    }
    // End of function load_data

    public function get_kesimpulan() {

        $event_id = $this->input->post('event_id');
        $ref_id = $this->input->post('id');

        if (substr($ref_id, 0, 1) == 'd') {

            $id = preg_replace('/[^0-9]/', '', $ref_id);

            $event_attend = $this->db->query("SELECT IF(events_attend.time_scan IS NOT NULL, TIMEDIFF(MAX(events_attend.time_scan), MIN(events_attend.time_scan)), NULL) AS 'time_diff',
                                                    MAX(IF(events_attend.attend = 'IN', review_events, 0)) AS 'in',
                                                    MAX(IF(events_attend.attend = 'OUT', review_events, 0)) AS 'out' 
                                                    FROM ac_payroll_item
                                                    JOIN ansena_department ON ansena_department.id = ac_payroll_item.office
                                                    JOIN events_attend ON events_attend.ac_payroll_item_id = ac_payroll_item.id AND event_id = " . $event_id . "
                                                    WHERE is_active = 1 
                                                    AND office = " . $id . "
                                                    AND time_scan IS NOT NULL
                                                    GROUP BY ac_payroll_item.id, office")->result();
        
        } else {

            $id = preg_replace('/[^0-9]/', '', $ref_id);

            $event_attend = $this->db->query("SELECT IF(events_attend.time_scan IS NOT NULL, TIMEDIFF(MAX(events_attend.time_scan), MIN(events_attend.time_scan)), NULL) AS 'time_diff',
                                                    MAX(IF(events_attend.attend = 'IN', review_events, 0)) AS 'in',
                                                    MAX(IF(events_attend.attend = 'IN', review_events, 0)) AS 'out' 
                                                    FROM ac_payroll_item
                                                    JOIN ansena_department ON ansena_department.id = ac_payroll_item.office
                                                    JOIN ansena_team_detail ON ansena_team_detail.ac_payroll_item_id = ac_payroll_item.id
                                                    JOIN events_attend ON events_attend.ac_payroll_item_id = ac_payroll_item.id AND event_id = " . $event_id . "
                                                    WHERE is_active = 1 
                                                    AND ansena_team_detail.ansena_team_id = " . $id . "
                                                    AND time_scan IS NOT NULL
                                                    GROUP BY ac_payroll_item.id, ansena_team_detail.ansena_team_id")->result();

        }

        $in_avg = 0;         
        $out_avg = 0;
        $dur = 0;
        $count = 0;    
        $count_dur = 0;                   
        foreach ($event_attend as $row) {
            $count += 1;
            $in_avg += $row->in;
            $out_avg += $row->out;
            $dur += strtotime($row->time_diff);
            if ($row->time_diff != '00:00:00') {
                $count_dur += 1;
            }
        }

        if ($in_avg != 0) {
            $in_avg = round($in_avg/$count, 2);
        }

        if ($out_avg != 0) {
            $out_avg = round($out_avg/$count, 2);
        }

        if ($count_dur != 0) {
            $dur = round($dur/$count_dur, 2);
        }

        $dur_time = $dur;

        if ($dur != null) {
            $dur = date('H:i:s', $dur);
        } else {
            $dur = 0;
        }

        $response = array(
                            'in_avg' => $in_avg,
                            'out_avg' => $out_avg,
                            'dur' => $dur,
                            'dur_time' => $dur_time
                        );

        echo json_encode($response);

    }
    // End of function get_kesimpulan

    public function print_labarugi($id) {

        $events_master = "SELECT id, hpp_category, hpp_subcategory
								 FROM events_master
                                 ORDER BY sort_category, sort_subcategory ASC";
                                 
        $hpp   = $this->db->query("SELECT SUM(item_qty*item_price) AS 'total_hpp'
                        FROM events_hpp
                        WHERE event_id = " . $id . "
                        GROUP BY event_id");

        $income = $this->db->query("SELECT SUM(nominal) AS 'total_income'
                        FROM events_income
                        WHERE event_id = " . $id . "
                        GROUP BY event_id");

        if ($hpp->num_rows() > 0) {
            $hpp = $hpp->row_array();
            $hpp = $hpp['total_hpp'];
        } else {
            $hpp = 0;
        }

        if ($income->num_rows() > 0) {
            $income = $income->row_array();
            $income = $income['total_income'];
        } else {
            $income = 0;
        }

		$attr = array(
						'event'			=> $this->db->query("SELECT id, title, event_category
																	FROM events
																	WHERE id = " . $id)->row_array(),
                        'eventsMaster'	=> $this->db->query($events_master)->result(),
                        'hpp'           => $hpp,
                        'income'        => $income
                    );

        $this->load->view('analisa/print-laba-rugi', $attr);

    }
    // End of function print_labarugi
    
    public function display_item_for_print() {
        
        $event_id = $this->input->post('event_id');

		$result = $this->db->query("SELECT hpp_category, hpp_subcategory, item_name, item_qty, item_satuan, item_price
										   FROM events_hpp
                                           JOIN events_master ON events_master.id = events_hpp.master_id
                                           WHERE event_id = " . $event_id . "
                                           ORDER BY hpp_category, item_name ASC");

		$attr = array(
                        'count_events_hpp' 	=> $result->num_rows(),
                        'events_hpp'    => $result->result()
					);

        $this->load->view('analisa/print-laba-rugi-detail', $attr);
        
    }
    // End of function display_item_for_print

    public function laba_rugi_bulanan() {

        $this->layout_lib->template_with_custom_navbar('analisa/navbar-sub-laba-rugi-bulanan', 'analisa/laba-rugi-bulanan');

    }
    // End of function laba_rugi_bulanan

    public function load_data_laba_rugi_bulanan() {

        $month = $this->input->post('month');

        $total_hpp = $this->db->query("SELECT SUM(item_qty*item_price) AS 'total_hpp'
                                            FROM events_hpp
                                            JOIN events ON events.id = events_hpp.event_id
                                            WHERE DATE_FORMAT(event_date, '%Y-%m') = '" . $month . "'
                                            GROUP BY DATE_FORMAT(event_date, '%Y-%m') = '" . $month . "'");

        if ($total_hpp->num_rows() > 0) {
            $total_hpp = $total_hpp->row_array();
            $total_hpp = $total_hpp['total_hpp'];
        } else {
            $total_hpp = 0;
        }

        $total_pendapatan = $this->db->query("SELECT SUM(nominal) AS 'total_pendapatan'
                                            FROM events_income
                                            JOIN events ON events.id = events_income.event_id
                                            WHERE DATE_FORMAT(event_date, '%Y-%m') = '" . $month . "'
                                            GROUP BY DATE_FORMAT(event_date, '%Y-%m') = '" . $month . "'");

        if ($total_pendapatan->num_rows() > 0) {
            $total_pendapatan = $total_pendapatan->row_array();
            $total_pendapatan = $total_pendapatan['total_pendapatan'];
        } else {
            $total_pendapatan = 0;
        }

        $event = $this->db->query("SELECT id, event_date, title
                                        FROM events
                                        WHERE DATE_FORMAT(event_date, '%Y-%m') = '" . $month . "'");

        $attr = array(
                        'month' => $month,
                        'total_hpp' => $total_hpp,
                        'total_pendapatan' => $total_pendapatan,
                        'event' => $event->result()
        );

        $this->load->view('analisa/report-laba-rugi-bulanan', $attr);

    }
    // End of function load_data_laba_rugi_bulanan

    public function hpp_total_event() {

        $event_id = $this->input->post('id');

        $result = $this->db->query("SELECT SUM(item_qty*item_price) AS 'total_hpp'
                                        FROM events_hpp
                                        WHERE event_id  = " . $event_id);

        if ($result->num_rows() > 0) {
            $result = $result->row_array();

            echo number_format($result['total_hpp']);
        } else {
            echo 0;
        }

    }
    // End of function hpp_total_event

    public function income_total_event() {

        $event_id = $this->input->post('id');

        $result = $this->db->query("SELECT SUM(nominal) AS 'total_pendapatan'
                                        FROM events_income
                                        WHERE event_id  = " . $event_id);

        if ($result->num_rows() > 0) {
            $result = $result->row_array();

            echo number_format($result['total_pendapatan']);
        } else {
            echo 0;
        }

    }
    // End of function income_total_event

}
/* End of file Analisa.php */
/* Location: ./application/controllers/Analisa.php */

