<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Staff
 */
class Tick_flick extends Admin_Controller {

	public function __construct() {
		parent::__construct();
        $this->data['menu_active_nav_name'] = 'checklist';
		$this->layouts->add_includes('js', 'public/app/controllers/admin/tick_flick/tick_flick.js');
        $this->access_params = ['access_token'=>$this->_identity['access_token']];
        $this->load->library('form_validation');
	}
    public function getStudnetData($year_level_id = null)
    {
        $this->data['students'] = $this->rest->get('students/get_students_by_year_id_with_results/'.$year_level_id, $this->access_params);
        $this->data['students'] = isset($this->data['students']->students) ? $this->data['students']->students: [];

    }
    public function storeTickFlick($year_level_id)
    {
        $this->load->model('TickFlickModel');
        $this->data['selection_matrices'] = $this->rest->post('selectionMatrix/getAllMatricesByYearId/'.$year_level_id, $this->access_params);
        $this->data['selection_matrices'] = isset($this->data['selection_matrices']->selection_matrices) ? $this->data['selection_matrices']->selection_matrices: [];

        $this->getStudnetData($year_level_id);

        $result = $this->TickFlickModel->allowDenyStudents($this->data['selection_matrices'],$this->data['students']);
        $this->data['selection_matrices'] = $result['matrix'];
        $this->data['students'] = $result['students'];
        if(!empty($this->TickFlickModel->false_selections))
        {

            $data['access_token'] = $this->_identity['access_token'];
            $data['data'] = $this->TickFlickModel->false_selections;
            $data['year_level_id'] = $year_level_id;
            $this->rest->post('tick_flick/store_items', $data);
        }
    }
    public function index($year_level_id = null)
    {

        $this->storeTickFlick($year_level_id);
        $this->data['tick_flicks'] = $this->rest->get('tick_flick/get_by_year_id_with_student_selection_matrix/'.$year_level_id, $this->access_params);
        $this->data['tick_flicks'] = isset($this->data['tick_flicks']->tick_flick_overrides) ? $this->data['tick_flicks']->tick_flick_overrides: [];

        $this->data['year_level_id'] = $year_level_id;
        $year_level = $this->rest->get('checklist/showYear/'.$year_level_id, $this->access_params);
        $this->data['year_level'] = isset($year_level->year_level) ? $year_level->year_level: [];
        $this->layouts->add_includes('js', 'public/app/controllers/admin/tick_flick/partials/by_student.js');
        $this->layouts->add_includes('js', 'public/app/controllers/admin/tick_flick/partials/by_selection.js');
        $this->layouts->add_includes('js', 'public/app/controllers/admin/tick_flick/partials/by_override.js');
        $this->layouts->add_includes('js', 'public/js/admin/tick_flick/index.js');

        $this->layouts->view('admin/tick_flick/index', $this->data, $this->layout);
    }
    public function getByStudent($year_level_id){
        $this->getStudnetData($year_level_id);
        $response['students'] = $this->data['students'];
        $response['_html'] = '';
        if($this->input->is_ajax_request()){
            $response['_html'] = $this->load->view('admin/tick_flick/by_student', '', TRUE);
        }
        print_r(json_encode($response));
        exit;
    }
    public function getBySelection($year_level_id){
        $this->getStudnetData($year_level_id);
        $response['students'] = $this->data['students'];
        $response['_html'] = '';
        if($this->input->is_ajax_request()){
            $response['_html'] = $this->load->view('admin/tick_flick/by_selection', '', TRUE);
        }
        print_r(json_encode($response));
        exit;
    }
    public function getByOverride($year_level_id){
        $this->getStudnetData($year_level_id);
        $response['students'] = $this->data['students'];
        $this->data['tick_flicks'] = $this->rest->get('tick_flick/get_by_year_id_with_student_selection_matrix/'.$year_level_id, $this->access_params);
        $this->data['tick_flicks'] = isset($this->data['tick_flicks']->tick_flick_overrides) ? $this->data['tick_flicks']->tick_flick_overrides: [];
        $response['tick_flicks'] = $this->data['tick_flicks'];
        $response['_html'] = '';
        if($this->input->is_ajax_request()){
            $response['_html'] = $this->load->view('admin/tick_flick/by_override', '', TRUE);
        }
        print_r(json_encode($response));
        exit;
    }
    public function StoreOrUpdateOverride()
    {
        $response['errors'] = array();
        $response['result'] = false;
        if($this->input->is_ajax_request()){

            $this->config->set_item('language', $this->data['lang']);
            $this->form_validation->set_rules('reason', "Reason", 'trim|max_length[255]');
            $this->form_validation->set_rules('year_level_id', "Year level ID", 'trim|required|integer');
            $this->form_validation->set_rules('student_user_id', "Student User ID", 'trim|required|integer');
            $this->form_validation->set_rules('selection_matrix_id', "Selection Matrix ID", 'trim|required|integer');
            $this->form_validation->set_rules('recommend', "Recommend", 'trim|required|integer');
            $this->form_validation->set_rules('eligible', "Eligible", 'trim|integer');
            $this->form_validation->set_rules('override', "Override", 'trim');
            $this->form_validation->set_rules('tick_flick', "Tick/Flick", 'trim');
            if ($this->form_validation->run() == false) {
                $response["errors"] = $this->form_validation->error_array();
            } else {
                $settings_data = $this->input->post();
                if($settings_data['action'] === 'store'){
                    $response['result'] = $this->rest->post('tick_flick/store', $settings_data);
                } elseif ($settings_data['action'] === 'update'){
                    $response['result'] = $this->rest->put('tick_flick/update/'.$settings_data['id'], $settings_data);
                } else {
                    print_r(json_encode($response));
                    exit;
                }
            }
        }
        print_r(json_encode($response));
        exit;
    }

}
