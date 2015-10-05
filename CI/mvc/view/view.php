<?php


class View {
    public $data;
    public function __construct($controller = NULL) {
		//parent::__construct();

		$this->data = "empty data";
    }
	public function render($_view, $_data = null) {
		if(isset($_data)) {
			$this->data = $_data;
		}
		$view= $_view;
		$data = $this->data;
		include_once "view/layout.php";
	}
    public function setinfo($data) {
        $this->data = $data;
    }
}
?>
