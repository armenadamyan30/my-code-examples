<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Advertisers extends Main_Controller {
	
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
		$this->load->helper('download');
		$this->load->model('site_model');
		$this->load->library('session');
		$this->load->library('email');
		$this->user_id = $this->session->userdata('user_id')?$this->session->userdata('user_id'):FALSE;
		$this->user_email   = $this->session->userdata('email')?$this->session->userdata('email'):FALSE;
		if(!$this->user_id){
			redirect(base_url());
		}
		$ammount = $this->site_model->get_user_ammount($this->user_id);
		$this->ammount = $ammount;
		
		$this->title =$this->translations['advertiser'].' | marmalads.com';
	} 
	public $layout = 'default'; 
	public function index()
	{
		if(!$this->user_id){
			redirect(base_url());
		}
		//var_dump(333);exit; 'rrrrrrrr';
		//$this->load->view('settings', $data_for_view);
	}
	public function home()
	{
		$data_for_view = array();
		$advertisers = $this->site_model->get_advertiser($this->user_id);
		$data_for_view['advertisers_home'] =  true;
		//echo "<pre>";
		//print_r($advertisers);
		$statuses = $this->site_model->get_site_statuses();
		$data_for_view['statuses']   = $statuses;
		$data_for_view['advertisers'] = $advertisers;
		$data_for_view['amount'] =  $this->ammount;
		$this->load->view('advertisers_home', $data_for_view);
	}
	public function adv_st($adv_id){
		$data_for_view = array();
		if(isset($_POST['statistika'])){
			$from_date = $_POST['from_date'];
			$to_date = $_POST['to_date'];
			redirect(base_url().'adv_st/'.$adv_id.'/?from='.$from_date.'&to='.$to_date);
		}
		$get= $this->input->get();
		$from_date = isset($get['from'])?$get['from']:false;
		$to_date   = isset($get['to'])?$get['to']:false;
		$data_for_view['from_date'] = $from_date?$from_date:'';
		$data_for_view['to_date']   = $to_date?$to_date:'';
		
		
		if($from_date && $to_date){
			$statistika = $this->site_model->get_adv_statistika($adv_id, $from_date ,$to_date);
		}else{
			$to_day = date('Ymd');
			$month_ago = date("Ymd", strtotime( '-30 days' ) );
			$statistika = $this->site_model->get_adv_statistika($adv_id, $month_ago ,$to_day);
		}
		$data_for_view['statistika'] =  $statistika;
		$data_for_view['amount'] =  $this->ammount;
		
		$user_id = $this->session->userdata('user_id');
		if (is_dir(FCPATH.'archiv/'.$user_id.'/'.$adv_id)) {
			$files=scandir(FCPATH.'archiv/'.$user_id.'/'.$adv_id);			
			foreach($files as $file){
				if(strpos($file, 'adv_') !== false){
					$data_for_view['adv_files'][] = ltrim($file, "adv_"); 					
				}				
			}
		}
		if(isset($_POST['download_archiv'])){				
			$name = $this->input->post('download_archiv', TRUE);			
			$data = file_get_contents(FCPATH.'archiv/'.$user_id.'/'.$adv_id.'/adv_'.$name); 
			//var_dump($data);
			force_download($name, $data);
		}
		
		
		$this->load->view('adv_st', $data_for_view);
	}
	public function add_ad(){
		//var_dump(random(8));
		$data_for_view = array();
		
		if(isset($_POST['advertiser']) && $_POST['advertiser'] == 'add'){
			$title = $_POST['title'];
			$description = $_POST['description'];
			$site_url = $_POST['site_url'];
			$price = $_POST['price'];
			$global_error = false;
			$errors = array();
			if(trim($site_url) != '' && !filter_var($site_url, FILTER_VALIDATE_URL)){
				$errors['url_error'] = true;
				$global_error  = true;
			}
			if(trim($title) == ''){
				$errors['title_error'] = true;
				$global_error  = true;
			}
			if(trim($description) == ''){
				$errors['description_error'] = true;
				$global_error  = true;
			}
			if(trim($price) == '' || !preg_match('/^[0-9]+$/', $price) || $price<1){
				$errors['price_error'] = 'Wrong price';
				$global_error  = true;
			}
			$allowedExts = array("gif", "jpeg", "jpg", "png");
			$temp = explode(".", $_FILES["image"]["name"]);
			$extension = end($temp);
			if ((($_FILES["image"]["type"] == "image/gif")
			|| ($_FILES["image"]["type"] == "image/jpeg")
			|| ($_FILES["image"]["type"] == "image/jpg")
			|| ($_FILES["image"]["type"] == "image/pjpeg")
			|| ($_FILES["image"]["type"] == "image/x-png")
			|| ($_FILES["image"]["type"] == "image/png"))
			&& ($_FILES["image"]["size"] < 1000000)
			&& in_array($extension, $allowedExts)){
			    if ($_FILES["image"]["error"] > 0)
				{
					$errors['image_error'] = $_FILES["image"]["error"];
					$global_error  = true;
				}
			    else
				{
					//echo "Upload: " . $_FILES["file"]["name"] . "<br>";
					//echo "Type: " . $_FILES["file"]["type"] . "<br>";
					//echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
					//echo "Stored in: " . $_FILES["file"]["tmp_name"];
				}
			}
			else
			{
			    $errors['image_error'] = 'Wrong file';
				$global_error  = true;
			}
			
			
			$data_for_view['errors'] = $errors;
			if(!$global_error){
				$img_url = "images1/advertisers/".$this->user_id.'/'.random(8).'.'.$extension;
				if(!file_exists("images1/advertisers/".$this->user_id.'/')){
					mkdir("images1/advertisers/".$this->user_id.'/', 0777, true);
				}
				
				move_uploaded_file($_FILES["image"]["tmp_name"], $img_url);
				$data = array(
					'title'       => $title,
					'item_id' => mt_rand(10000000, 99999999),
					'user_id'     => $this->user_id,
					'description' => $description,
					'site_url'    => $site_url,
					'img_url'     => $img_url,
					'date'        => time(),
					'price'       => $price,
					'percentage' => 50
				);
				$this->site_model->add_advertiser($data);
				$data_for_view['success'] = 'success';
				//redirect(base_url().'add_ad/success');
				redirect(base_url().'home');
			}
		}
		$data_for_view['amount'] =  $this->ammount;
		$this->load->view('add_advertiser', $data_for_view);	
	}
	public function edit_ad($item_id){
		$data_for_view = array();
		if(isset($_POST['advertiser']) && $_POST['advertiser'] == 'edit'){
			$title = $_POST['title'];
			$description = $_POST['description'];
			$site_url = $_POST['site_url'];
			$price = $_POST['price'];
			$global_error = false;
			$errors = array();
			if(trim($site_url) != '' && !filter_var($site_url, FILTER_VALIDATE_URL)){
				$errors['url_error'] = true;
				$global_error  = true;
			}
			if(trim($title) == ''){
				$errors['title_error'] = true;
				$global_error  = true;
			}
			if(trim($description) == ''){
				$errors['description_error'] = true;
				$global_error  = true;
			}
			if(trim($price) == '' || !preg_match('/^[0-9]+$/', $price) || $price<1){
				$errors['price_error'] = true;
				$global_error  = true;
			}
			$image_upload = false;
			if(isset($_FILES['image']) && $_FILES["image"]["size"] > 1 ){
				$allowedExts = array("gif", "jpeg", "jpg", "png");
				$temp = explode(".", $_FILES["image"]["name"]);
				$extension = end($temp);
				if ((($_FILES["image"]["type"] == "image/gif")
				|| ($_FILES["image"]["type"] == "image/jpeg")
				|| ($_FILES["image"]["type"] == "image/jpg")
				|| ($_FILES["image"]["type"] == "image/pjpeg")
				|| ($_FILES["image"]["type"] == "image/x-png")
				|| ($_FILES["image"]["type"] == "image/png"))
				&& ($_FILES["image"]["size"] < 1000000)
				&& in_array($extension, $allowedExts)){
					if ($_FILES["image"]["error"] > 0)
					{
						$errors['image_error'] = $_FILES["image"]["error"];
						$global_error  = true;
					}
					else
					{
						$image_upload = true;
					}
				}
				else
				{
					$errors['image_error'] = 'Wrong file';
					$global_error  = true;
				}
			}
			
			$data_for_view['errors'] = $errors;
			if(!$global_error){
				if($image_upload){
					$img_url = "images1/advertisers/".$this->user_id.'/'.random(8).'.'.$extension;
					if(!file_exists("images1/advertisers/".$this->user_id.'/')){
						mkdir("images1/advertisers/".$this->user_id.'/', 0777, true);
					}
					move_uploaded_file($_FILES["image"]["tmp_name"], $img_url);
				}
				$data = array(
					'title'       => $title,
					'user_id'     => $this->user_id,
					'description' => $description,
					'site_url'    => $site_url,
					'price'       => $price,
					'status'      => 0
				);
				if($image_upload){
					$data['img_url'] = $img_url;
				}
				$this->site_model->edit_advertiser($data, $item_id);
				$data_for_view['success'] = 'success';
				//redirect(base_url().'add_ad/success');
				redirect(base_url().'home');
			}
			
		}
		$advertiser = $this->site_model->get_advertiser($this->user_id, $item_id);
		$data_for_view['advertiser'] = $advertiser;
		$data_for_view['amount'] =  $this->ammount;
		$this->load->view('edit_advertiser', $data_for_view);
	}
	public function success(){
		$data_for_view = array();
		$data_for_view['amount'] =  $this->ammount;
		$this->load->view('add_advertiser_success', $data_for_view);
	}
	public function update_daily_widgets(){
		$this->site_model->update_daily_widgets();
		exit;
	}
	public function add_amount(){
		$data_for_view = array();
		/* Generate real order id */
		$string="0123456789";
		$trans_id = "";
		for($i = 0; $i < 9; $i++)
		{
			$index = mt_rand(0, 9);
			$trans_id .= $string[$index];
		}
		
		
		
		$data_for_view['trans_id'] =  $trans_id;
		$temp_order_data = array(
			'trans_id' => $trans_id,
			'user_id' => $this->user_id
		);
		//var_dump($temp_order_data);
		$this->site_model->add_order_temp($temp_order_data);
		
		
		$data_for_view['amount'] =  $this->ammount;
		$this->load->view('add_amount', $data_for_view);
	}
	public function add_amount_success(){
		$data_for_view = array();
		
		$data_for_view['amount'] =  $this->ammount;
		$this->load->view('add_amount_success', $data_for_view);
	}
	public function add_amount_failed(){
		$data_for_view = array();
		
		$data_for_view['amount'] =  $this->ammount;
		$this->load->view('add_amount_failed', $data_for_view);
	}
}
?>