<?php
class Ajax extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        $action = isset($_POST['action']) ? $_POST['action'] : false;

        switch ($action) {
            case 'form':
                $form = isset($_POST['form_name']) ? $_POST['form_name'] : false;
                $data_id = isset($_POST['data_id']) ? $_POST['data_id'] : false;
                $filled = $_POST['filled'];
				$data_array = $_POST;

				if(isset($_POST['input1'])){
					if(!isset($_COOKIE[$data_id.'_input1'])){
						setcookie($data_id.'_input1', $_POST['input1'], time()+3600, "/");
					}
				}
				if(isset($_POST['input2_1'])){
					if(!isset($_COOKIE[$data_id.'_input2_1'])){
						setcookie($data_id.'_input2_1', $_POST['input2_1'], time()+3600, "/");
					}
				}
				if(isset($_POST['input2_2'])){
					if(!isset($_COOKIE[$data_id.'_input2_2'])){
						setcookie($data_id.'_input2_2', $_POST['input2_2'], time()+3600, "/");
					}
				}
				if(isset($_POST['input3'])){
					if(!isset($_COOKIE[$data_id.'_input3'])){
						setcookie($data_id.'_input3', $_POST['input3'], time()+3600, "/");
					}
				}
				if(isset($_POST['input4_1'])){
					if(!isset($_COOKIE[$data_id.'_input4_1'])){
						setcookie($data_id.'_input4_1', $_POST['input4_1'], time()+3600, "/");
					}
				}
				if(isset($_POST['input5'])){
					if(!isset($_COOKIE[$data_id.'_input5'])){
						setcookie($data_id.'_input5', $_POST['input5'], time()+3600, "/");
					}
				}

				if(isset($_POST['input6'])){
					if(!isset($_COOKIE[$data_id.'_input6'])){
						setcookie($data_id.'_input6', $_POST['input6'], time()+3600, "/");
					}
				}
				if(isset($_POST['input7'])){
					if(!isset($_COOKIE[$data_id.'_input7'])){
						setcookie($data_id.'_input7', $_POST['input7'], time()+3600, "/");
					}
				}

				if(isset($_POST['input11_1'])){
					if(!isset($_COOKIE[$data_id.'_input11_1'])){
						setcookie($data_id.'_input11_1', $_POST['input11_1'], time()+3600, "/");
					}
				}
				if(isset($_POST['input12_1'])){
					if(!isset($_COOKIE[$data_id.'_input12_1'])){
						setcookie($data_id.'_input12_1', $_POST['input12_1'], time()+3600, "/");
					}
				}
				if(isset($_POST['input13_1'])){
					if(!isset($_COOKIE[$data_id.'_input13_1'])){
						setcookie($data_id.'_input13_1', $_POST['input13_1'], time()+3600, "/");
					}
				}
				if(isset($_POST['input14'])){
					if(!isset($_COOKIE[$data_id.'_input14'])){
						setcookie($data_id.'_input14', $_POST['input14'], time()+3600, "/");
					}
				}
                unset($data_array['form_name'], $data_array['data_id'], $data_array['action']);
                $data_input = $data_array["input"];
                //Common for all forms
                $json_data = json_encode($data_array);
                $is_data_set = $this->model->is_data_exists($form, $data_id);
                $app_label = $data_input[0] != '' ? $data_input[0] : $data_id;

				if ($is_data_set) {
                    $this->model->update_form_table($form, $json_data, $app_label, $data_id, $filled);

                } else {
                    $this->model->insert_form_table($form, $json_data, $app_label, $data_id, $filled);
                    echo "Form inserted successfully";
                }
                $this->model->updade_other_forms_appropriate_fields($form, $data_array, $data_id);
				if($filled){
					echo "Form updated successfully";
				}else{
					echo "Not all the required fields on this form have been provided. Inputs have been saved. Please complete all fields in green and resubmit.";
				}
                break;
            case 'drivers':
                if (isset($_FILES["uploadFile"]["name"])) {
                    $appId = isset($_POST['data_id']) ? $_POST['data_id'] : false;
                    $img = $_FILES["uploadFile"]["name"];
                    $ext = pathinfo($img, PATHINFO_EXTENSION);
                    $img_name = $appId . "." . $ext;
                    $dest_file = BASE_PATH . "images/drivers/" . $img_name;
                    $tmpImg = $_FILES["uploadFile"]["tmp_name"];
                    $current = $this->model->getCurrentFile($appId);
                    if ($current) {
                        @unlink(BASE_PATH . "images/drivers/" . $current);
                    }
                    if (move_uploaded_file($tmpImg, $dest_file)) {
                        $this->model->updateFileTable($appId, $img_name, $current);
                        echo "File uploaded successfully";
                    } else {
                        echo "Couldn't upload. Please try again";
                    }
                } else {
                    echo "Please choose image file";
                }
                break;
			  case 'miscs':
				if(isset($_POST['delete_file'])){
					$image_name = $_POST['img_name'];
					$appId = isset($_POST['app_id']) ? $_POST['app_id'] : false;
					$current = $this->model->getCurrentFile($appId, 'miscs');
					if(!empty($current)){
						$img_array = json_decode($current, true);
						$img_exist = false;
						if(!empty($img_array)){
							foreach($img_array as $k => $v){
								if($v == $image_name){
									unset($img_array[$k]);
									$img_exist = true;
								}
							}
						}
						if($img_exist){
							$img_str = json_encode($img_array);
							$this->model->updateFileTable($appId, $img_str, true, 'miscs');
							@unlink(BASE_PATH . "images/miscs/" . $image_name);
						}else{
							echo "Wrong action";
						}

					}
					exit;
				}

                if(isset($_FILES["uploadFile"]["name"])){
                    $appId = isset($_POST['data_id']) ? $_POST['data_id'] : false;
					$allowed =  array('gif','png' ,'jpg', 'bmp');
                    $img_array = array();
				    for($i = 0; $i < count($_FILES['uploadFile']['name']); $i++){
                       	$ext = pathinfo($_FILES['uploadFile']['name'][$i], PATHINFO_EXTENSION);
						$ext = strtolower($ext);
						if(in_array($ext, $allowed)){
							$img_name = sha1(sha1(mt_rand(1000000, 999999999)).sha1(mt_rand(1000000, 999999999))).'.'.$ext;
							$dest_file = BASE_PATH . "images/miscs/" . $img_name;
							if(move_uploaded_file($_FILES['uploadFile']['tmp_name'][$i], $dest_file)) {
								$img_array[] = $img_name;
							}
						}
					}
                    $current = $this->model->getCurrentFile($appId, 'miscs');
					if(!empty($current)){
						$current_array = json_decode($current, true);
					}else{
						$current_array = array();
					}
					$new_array = array_merge($current_array, $img_array);
					$img_str = json_encode($new_array);
                    $this->model->updateFileTable($appId, $img_str, $current, 'miscs');
					echo "File uploaded successfully";
		        } else {
                    echo "Please choose image file";
                }
                break;

			case 'voideds':
                if(isset($_FILES["uploadFile"]["name"])){
                    $appId = isset($_POST['data_id']) ? $_POST['data_id'] : false;
                    $img = $_FILES["uploadFile"]["name"];
                    $ext = pathinfo($img, PATHINFO_EXTENSION);
                    $img_name = $appId . "." . $ext;
                    $dest_file = BASE_PATH . "images/voideds/" . $img_name;
                    $tmpImg = $_FILES["uploadFile"]["tmp_name"];
                    $current = $this->model->getCurrentFile($appId, 'voideds');
                    if ($current){
                        @unlink(BASE_PATH . "images/voideds/" . $current);
                    }
                    if(move_uploaded_file($tmpImg, $dest_file)) {
                        $this->model->updateFileTable($appId, $img_name, $current, 'voideds');
                        echo "File uploaded successfully";
                    } else {
                        echo "Couldn't upload. Please try again";
                    }
                } else {
                    echo "Please choose image file";
                }
                break;
            case 'get_forms_state':
                $form = array();

                // We need to check if the appId exists in the db, if not create it.
                $appId = isset($_POST['data_id']) ? $_POST['data_id'] : false;
                $result = $this->model->check_application_id($appId);
                if(!$result)
                    $this->model->insert_application_id($appId);

                // Not sure where FORMS comes from.
                $forms = unserialize(FORMS);
                foreach ($forms as $f) {
                    $form[$f] = $this->model->check_form_state($f, $appId);
                }

                $form['appId_read_only'] = ($result['read_only'])?$result['read_only']:0;
                echo json_encode($form);
                break;
			 case 'get_form_state':
                $appId = isset($_POST['app_id']) ? $_POST['app_id'] : false;
				$form_name = $_POST['form_name'];
				$is_complate = $this->model->check_form_state($form_name, $appId);
                echo json_encode(array('is_complate' => (int)$is_complate));
                break;
            case 'send_form_list':
                $url        = isset($_POST["url"]) ? $_POST["url"] : '';
                $appId         = isset($_POST["id"]) ? $_POST["id"] : '';
                $to = EMAIL;
                $subject = 'Forms list';
                //$message = 'Created forms list you can see here: '.$url . "?id=" . $appId;
                //$message .= ' <br/> Filled forms  list you can see here: '.$url . "?id=" . $appId.'&filled';
				$full_url = $url . "?id=" . $appId;

				$message = <<<HTML
A new completed application has been submitted. To review this application please click the link below:<br /><br />
<a href="$full_url">$full_url</a>

HTML;

				$headersfrom = 'MIME-Version: 1.0' . "\r\n";
				$headersfrom .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headersfrom .= 'From: test@inteliclic.com '. "\r\n";
                mail($to, $subject, $message, $headersfrom);

                $this->model->make_application_id_read_only($appId);

                echo "Thank you for submitting your BailSwipe Application. You will receive your Hardware within 5-7 business days and a BailSwipe consultant will call to help you get set up.";
                break;
        }

    }

}

?>