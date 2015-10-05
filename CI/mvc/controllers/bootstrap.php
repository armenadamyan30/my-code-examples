<?php

class Bootstrap {

    public $controller;
    public $action;
    public $appId;

    public function __construct() {
        $this->dropURI(); //drops URI into parts
        $controller = isset($_GET['controller']) ? $_GET['controller'] : 'home';
        $this->controller = ucfirst(strtolower($controller));

//        unset($GLOBALS[_SESSION]["appId"]);

        //get or set application ID
        if (isset($_GET["data_id"])) {
            $this->appId = $_GET["data_id"];
            $_SESSION["appId"] = $this->appId;
        } else if (isset($_SESSION["appId"])) {
            $this->appId = $_SESSION["appId"];
        } else {
            $this->appId = $this->rand_applicationId();
            $_SESSION["appId"] = $this->appId;
        }

        $action = isset($_GET['action']) ? $_GET['action'] : 'index';
        $this->action = strtolower($action);
    }

    public function dropURI() {
        $uri = $_SERVER['REQUEST_URI'];

        $firstPart = strlen(URL_BASE_PATH);
        $lastPart = substr($uri, $firstPart);
        $getAttr = explode("/", $lastPart);
        $lAttr = count($getAttr);
		
        if ($getAttr[0] != '') {
            $_GET["controller"] = $getAttr[0];
        }
        if ($getAttr[1] != '') {
            $_GET["data_id"] = $getAttr[1];
        }
		if (isset($getAttr[2]) && $getAttr[2] == 'filled') {
            $_GET["filled"] = 1;
        }
    }

    public function start() {
        $controller = $this->controller; //controller
        $action = $this->action; //action

        if (!@include_once('controllers/' . strtolower($controller) . '.php')) {
            header("Location: " . BASE_URL . "error");
        }
        $run = new $controller();
		include_once 'model/model.php';
		$model = new Model();
		$readonly = $model->is_readonly_app($_GET["data_id"]);
		$_SESSION["readonly"] = $readonly;
        if (method_exists($run, $action)) {
            $run->$action();
        } else {
            header("Location: " . BASE_URL . "error");
        }
    }

    function rand_applicationId() {
        $min = 1000000;
        $max = 99999999999;

        return sha1(sha1(mt_rand($min, $max)) . sha1(mt_rand($min, $max)));
    }

}

?>
