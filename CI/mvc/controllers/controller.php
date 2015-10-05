<?php

class Controller{
	public $model;
	protected $view;
	
	public function __construct(){
		include_once 'model/model.php';
		$model = new Model();
		$this->model = $model;
		
		include_once 'view/view.php';
		$view = new View();
		$this->view = $view;
		$this->readonly = $_SESSION["readonly"];
		$this->view->readonly = $_SESSION["readonly"];
	}
};

?>