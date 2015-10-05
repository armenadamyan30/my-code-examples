<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Advertiser extends Main_Controller {
	
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct() 
	{   
		parent::__construct();
		header("Content-type: text/html; charset=utf-8");
		$this->load->helper('url');
		$this->load->helper('site');
		$this->load->helper('email');
		$this->load->model('site_model');
		$this->load->library('session');
		$this->load->library('email');
		$this->user_id = $this->session->userdata('user_id')?$this->session->userdata('user_id'):FALSE;
		$this->user_email   = $this->session->userdata('email')?$this->session->userdata('email'):FALSE;
		///if(!$this->user_id){
			//redirect(base_url());
		//}
		$ammount = $this->site_model->get_user_ammount($this->user_id);
		$this->ammount = $ammount;
		
	} 
	public $layout = 'default'; 
	public function adv($adv_id)
	{
		$data_for_view = array();
		$adv = $this->site_model->get_adv($adv_id);
		$data_for_view['adv'] = $adv;
		
		$session_id = $this->session->userdata['session_id'];
		$ip_address = $this->session->userdata['ip_address'];
		$today = date("Ymd");
		$adv = $this->site_model->get_adv($adv_id);
		if(!empty($adv)){
			$current_price = $adv['price'];
			$data = array(
				'item_id'    => $adv_id,
				'ip_address'  => $ip_address,
				'date' =>  $today,
				'current_price' => $current_price,
				'session_id' => $session_id
			);
			$this->site_model->visit_adv($data);
			$data_for_view['amount'] = $this->ammount;
			$random_not_url_advs = $this->site_model->get_random_not_url_advs(20);
			$data_for_view['random_not_url_advs'] = $random_not_url_advs;
		}
		
		$this->load->view('advertiser', $data_for_view);
	}
	public function request(){
		$this->layout = NULL;
		$url = isset($_GET['url'])?$_GET['url']:false;
		$widget_id = isset($_GET['k'])?$_GET['k']:false;
		$item_id = isset($_GET['item_id'])?$_GET['item_id']:false;
		$session_id = $this->session->userdata['session_id'];
		$ip_address = $this->session->userdata['ip_address'];
		$today = date("Ymd");
		$adv = $this->site_model->get_adv($item_id);
		
		if(!empty($adv)){
			$current_price = $adv['price'];
			$data = array(
				'item_id'    => $item_id,
				'ip_address'  => $ip_address,
				'date' =>  $today,
				'widget_id' => $widget_id,
				'current_price' => $current_price,
				'session_id' => $session_id
			);
			$this->site_model->visit_adv($data);
			$site_url = (trim($adv['site_url']) != '')?$adv['site_url']:false;
			if($site_url){
				redirect($site_url);
			}else{
				redirect($url);
			}
		}else{
			redirect(base_url());
		}
		//var_dump($_GET);
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */