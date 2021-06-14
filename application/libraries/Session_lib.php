<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

/*
 * Class Session_lib
 */
class Session_lib {

	protected $CI;
	
	function __construct() {

		$this->CI =& get_instance();

	}
	// End of function __construct

	/**
	 * @param $username
	 */
	public function set_sessions($username) {

		// Get fields in permission table
		$permissionFields = $this->CI->db->list_fields('lf_permission');

		$fields = "";

		foreach ($permissionFields as $field) :

			if(substr($field, 0, 2) == 'p_') {

				$fields .= $field . ", ";

			}

		endforeach;

		// Get permission data
		$row = $this->CI->db->query("SELECT lf_users.dept_id,
											lf_users.id AS user_id,
											" . $fields . "
											lf_role.name AS role_name
											FROM lf_users
											JOIN lf_role ON lf_role.id = lf_users.role_id
											JOIN lf_permission ON lf_permission.role_id = lf_role.id
											WHERE username = '" . $username . "'")->row();

		// Set sessions
		$data = array(	
						'dept_id' => $row->dept_id,
						'user_id' => $row->user_id,
						'username' => $username,
						'role_name' => $row->role_name,
						'p_log_activity' => $row->p_log_activity,
						'p_role_report' => $row->p_role_report,
						'p_role_add' => $row->p_role_add,
						'p_role_edit' => $row->p_role_edit,
						'p_role_delete' => $row->p_role_delete,
						'p_user_report' => $row->p_user_report,
						'p_user_add' => $row->p_user_add,
						'p_user_edit' => $row->p_user_edit,
						'p_user_delete' => $row->p_user_delete
					);

		$this->CI->session->set_userdata($data);

	}
	// End of function set_sessions

	/**
	 * @return true or redirect
	 */
	public function check_session() {

		// is already sessions
		if (! $this->CI->session->userdata('user_id')) {

			$this->CI->session->set_flashdata('alert-error', 'Sesi telah berakhir');

			redirect('sessions/signin');

		} else {

			return true;

		}
		// End of if already sessions
		
	}
	// End of function check_session

	/**
	 * @return bool or redirect
	 */
	public function check_permission($key) {
		
		// is have permission
		if ($this->CI->session->userdata($key) == 1) {

			// 1 is have permission
			return true;

		} else {

			// 0 is not have permission => redirect to error page
			redirect('error_handler');

		}

	}
	// End of function check_permission

	/**
	 * @return bool or redirect
	 */
	public function check_two_permission($condition = '', $key1, $key2) {
		
		switch ($condition) :

			case 'or' :

				// is have permission
				if ($this->CI->session->userdata($key1) == 1 || $this->CI->session->userdata($key2) == 1) {

					// 1 is have permission
					return true;

				} else {

					// 0 is not have permission => redirect to error page
					redirect('error_handler');

				}

				break;

			case 'and' :

				// is have permission
				if ($this->CI->session->userdata($key1) == 1 && $this->CI->session->userdata($key2) == 1) {

					// 1 is have permission
					return true;

				} else {

					// 0 is not have permission => redirect to error page
					redirect('error_handler');

				}

				break;

		endswitch;

	}
	// End of function check_two_permission

}
/* End of file Session_lib.php */
/* Location: ./application/libraries/Session_lib.php/ */

