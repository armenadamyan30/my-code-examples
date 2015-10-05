<?php

class Error extends Controller{
	public function __construct() {
		parent::__construct();
	}	
	public function index(){
		$controller = strtolower(get_class($this));
		$this->view->render($controller, $data);
	}
}
?>

