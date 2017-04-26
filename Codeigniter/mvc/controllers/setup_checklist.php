<?php

class Setup_checklist extends Controller{
	public function __construct() {
		parent::__construct();
	}	
	public function index(){
		$controller = strtolower(get_class($this));
        $data["form"] = $controller;
        $data["data_id"] = $_SESSION["appId"];
        $data["inputs"] = $this->model->get_inputs($data["form"], $data["data_id"]);
        $data["input_text"] = isset($data["inputs"]["input"]) ? $data["inputs"]["input"] : '';
        $data["common"] = array ();
        $common_fields = unserialize(FORMS_COMMON_FIELDS);
        foreach($common_fields as $field) {
            if(isset($data["inputs"][$field])) {
                $data["common"][$field] = $data["inputs"][$field];
            }
        }
        $data["input_check"] = $data["inputs"];
        unset($data["input_check"]["input"]);
		$this->view->render($controller, $data);
	}
    public function test() {
        echo "asdfasfasdfasfasdfasdf";
    }
}
?>

