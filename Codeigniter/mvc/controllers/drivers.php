<?php

class Drivers extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $controller = strtolower(get_class($this));
        $data["form"] = $controller;
        $data["data_id"] = $_SESSION["appId"];
        $data["drivers"] = $this->model->getCurrentFile($data["data_id"]);
        $this->view->render($controller, $data);
    }

    public function test() {
        echo "asdfasfasdfasfasdfasdf";
    }

}
?>

