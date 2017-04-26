<?php

class Voideds extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $controller = strtolower(get_class($this));
        $data["form"] = $controller;
        $data["data_id"] = $_SESSION["appId"];
        $data["voideds"] = $this->model->getCurrentFile($data["data_id"], 'voideds');
        $this->view->render($controller, $data);
    }

    public function test() {
        echo "asdfasfasdfasfasdfasdf";
    }

}
?>

