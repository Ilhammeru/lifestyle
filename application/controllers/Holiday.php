<?php defined('BASEPATH') OR exit ('No direct script allowed');

class Holiday extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function save_holiday() {
        $dateStart = $_POST['selected_date_start'];
        $reason = $_POST['holiday_reason'];

        if ($_POST['selected_date_end'] == '') {
            $dateEnd = $dateStart;
        } else {
            $dateEnd = $_POST['selected_date_end'];
        }

        $dataInput  = [
            'date_start'  => $dateStart,
            'date_end'      => $dateEnd,
            'reason'   => $reason
        ];

        $this->db->insert('holiday', $dataInput);

        echo 'success';
    }

    /**
    * @param selected_date_start
    * @param selected_date_end
    * @param holiday_reason
    * @param holiday_id
    */
    public function post_edit_holiday() {
        $dateStart = $_POST['selected_date_start'];
        $reason = $_POST['holiday_reason'];
        $id = $_POST['holiday_id'];

        if ($_POST['selected_date_end'] == '') {
            $dateEnd = $dateStart;
        } else {
            $dateEnd = $_POST['selected_date_end'];
        }

        $dataUpdate  = [
            'date_start'  => $dateStart,
            'date_end'      => $dateEnd,
            'reason'   => $reason
        ];

        $this->db->where('id', $id);
        $this->db->update('holiday', $dataUpdate);

        echo 'success';
    }
    // end of function post_edit_holiday

    public function get_holiday() {
        $query = $this->db->query("SELECT id, date_start, date_end, reason   
                                            FROM holiday");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $dateStart = $row->date_start;
                $dateEnd = $row->date_end;
                $reason = $row->reason;

                $formatShow[] = [
                    'start' => $dateStart,
                    'end'   => $dateEnd,
                    'title' => $reason
                ];
            }

            $data['holiday'] = $formatShow;
        }

        echo json_encode($data);
    }

    public function get_holiday_list($page) {
        $per_page = 5;
        if ($page != 0) {
            $page = ($page - 1) * $per_page;
        }

        $query = $this->db->query("SELECT id, date_start, date_end, reason   
                                            FROM holiday
                                            LIMIT $per_page OFFSET $page");

        $queryRows = $this->db->query("SELECT id FROM holiday")->num_rows();                                    

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $dateStart = date('d M', strtotime($row->date_start));
                $dateEnd = date('d M', strtotime($row->date_end));
                $reason = $row->reason;
                $id = $row->id;

                $formatShow[] = [
                    'start' => $dateStart,
                    'end'   => $dateEnd,
                    'title' => $reason,
                    'id'    => $id
                ];
            }

        }
        $data['holiday'] = $formatShow;

        // initialize pagination
        $config['base_url'] = base_url() . 'holiday/get_holiday_list';
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = $queryRows;
        $config['per_page'] = $per_page;

        $config['full_tag_open']    = '<div class="pagging text-center"><nav><ul class="pagination">';
        $config['full_tag_close']   = '</ul></nav></div>';
        $config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close']    = '</span></li>';
        $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
        $config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['next_tag_close']  = '<span aria-hidden="true"></span></span></li>';
        $config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['prev_tag_close']  = '</span></li>';
        $config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
        $config['first_tag_close'] = '</span></li>';
        $config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['last_tag_close']  = '</span></li>';

        $this->pagination->initialize($config);

        $data['pagination'] = $this->pagination->create_links();

        echo json_encode($data);
    }

    /**
    * @param id
    * return json
    */
    public function edit_holiday() {
        $id = $_POST['id'];

        $query = $this->db->query("SELECT id, date_start, date_end, reason 
                                        FROM holiday 
                                        WHERE id = $id")->row_array();

        $dateStart = $query['date_start'];
        $dateEnd = $query['date_end'];
        $reason = $query['reason'];

        $data = [
            'start' => $dateStart,
            'end'   => $dateEnd,
            'title' => $reason
        ];

        echo json_encode($data);
    }

    /**
    * @param get value
    * return json
    */
    public function search_holiday($val, $page = '') {

        $per_page = 5;
        if ($page != 0) {
            $page = ($page - 1) * $per_page;
        }

        $query = $this->db->query("SELECT id, date_start, date_end, reason
                                        FROM holiday 
                                        WHERE reason LIKE '%$val%'
                                        OR date_start LIKE '$val'
                                        OR date_end LIKE '$val'
                                        LIMIT $per_page OFFSET $page");

        $queryRows = $this->db->query("SELECT id 
                                            FROM holiday 
                                            WHERE reason LIKE '%$val%'
                                            OR date_start LIKE '$val'
                                            OR date_end LIKE '$val'")->num_rows();

        // initialize pagination
        $config['base_url'] = base_url() . 'holiday/get_holiday_list';
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = $queryRows;
        $config['per_page'] = $per_page;

        $config['full_tag_open']    = '<div class="pagging text-center"><nav><ul class="pagination">';
        $config['full_tag_close']   = '</ul></nav></div>';
        $config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close']    = '</span></li>';
        $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
        $config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['next_tag_close']  = '<span aria-hidden="true"></span></span></li>';
        $config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['prev_tag_close']  = '</span></li>';
        $config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
        $config['first_tag_close'] = '</span></li>';
        $config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['last_tag_close']  = '</span></li>';

        $this->pagination->initialize($config);
        $pagination = $this->pagination->create_links();

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $format[] = [
                    'start' => date('d M', strtotime($row->date_start)),
                    'end'   => date('d M', strtotime($row->date_end)),
                    'title' => $row->reason,
                    'id'    => $row->id
                ];
            }
        } else {
            $format = '';
        }

        $data['holiday']   = $format;
        $data['pagination'] = $pagination;

        echo json_encode($data);


    }
    // end of function search holiday
    
    /**
    * @param id
    * return json
    */
    public function delete_holiday() {
        $id = $_POST['id'];

        $this->db->where('id', $id);
        $this->db->delete('holiday');

        echo 'success';
    }
    // end of function delete_holiday
}