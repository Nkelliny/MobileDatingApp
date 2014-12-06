<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//error_reporting(E_ALL);
class Mapps extends CI_Controller 
{
	const _FAVLIM = 10;
	const _BLOLIM = 5;
	
	public function index()
	{
		echo 'xxxxxx Mobile App Service Calls!';
		exit;
	}
	
	function ftest()
	{
		$test = $this->my_chatmanager->fwendstest();
	}
	
	function cleanData($service,$uri,$pvars='na')
	{
		
	}
	
	function test()
	{
		$type = $this->uri->segment(4,'na');
		$data['type'] = $type;
		$this->load->view('mapps_test',$data);
	}
	
	function fbshare()
	{
		$uid = $_POST['userid'];
		$msg = $this->my_stringmanager->getAppText($uid,'fb_share');
		$data = array('msg'=>$msg,'link'=>'http://www.xxxxxx.com/fb/'.$uid);
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	function distanceTest()
	{
		$userid = 19303;
		$query = $this->db->query('SELECT `id`,`cur_lat`,`cur_lon` FROM `users` WHERE `id` = "'.$userid.'"');
		$row = $query->row();
		$ulat = $row->cur_lat;
		$ulon = $row->cur_lon;
		$query = $this->db->query('SELECT `id`,`cur_lat`,`cur_lon` FROM `users` WHERE `utype` = "a"');
		$res = $query->result();
		$users_array = array();
		echo 'Total Users Pulled: '. count($res).'<br /><br />';
		foreach($res as $r)
		{
			$usr = self::getDisLimit($ulat,$ulon,$r->cur_lat,$r->cur_lon,200,'Km');
			if($usr != "na")
			{
				$tmp['id'] = $r->id;
				$tmp['distance'] = $usr;
				$users_array[] = $tmp;
			}
		}
		$dis = array();
		foreach ($users_array as $key => $row)
		{
    		$dis[$key] = $row['distance'];
		}
		array_multisort($dis, SORT_ASC, $users_array);
		$x=0;
		foreach($users_array as $u)
		{
			if($x <= 200)
			{
				echo 'USERID: '.$u['id'].'<br />Distance: '.$u['distance'].'Km<br /><br />';
				$x++;
			}
			else
			{
				break;
			}
		}
		exit;
	}
	
	function ckpush()
	{
		$uid = $_POST['userid'];
		$query = $this->db->query('SELECT `id`,`msg` FROM `adminPush` WHERE `uid` = "'.$uid.'" AND `status` = "1" LIMIT 1');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$msg = $row->msg;
			// update msg
			$query = $this->db->query('UPDATE `adminPush` SET `status` = "2", `rec` = "'.time().'" WHERE `id` = "'.$row->id.'"'); 
		}
		else
		{
			$msg = "No Message";
		}
		// show data
		$data = array("msg"=>$msg);
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	function getDisLimit($latitude1, $longitude1, $latitude2, $longitude2,$limit, $unit = 'Km')
	{
		$theta = $longitude1 - $longitude2; 
		$distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + 
		(cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * 
		cos(deg2rad($theta))); 
		$distance = acos($distance); 
		$distance = rad2deg($distance); 
		$distance = $distance * 60 * 1.1515; 
		switch($unit) 
		{ 
			case 'Mi': 
			break; 
			case 'Km' : 
			$distance = $distance * 1.609344; 
		} 
		if(round($distance,2) >= $limit)
		{
			return (round($distance,2)); 	
		}
		else
		{
			return "na";
		}
	}
	
	function countries()
	{
		$query = $this->db->query('SELECT `code`,`name` FROM `geocountry` ORDER BY `name`');
		$res = $query->result();
		$data = array("countries"=>$res);
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	function getcities()
	{
		$country = $this->security->xss_clean($_POST['country']);
		$txt = $this->security->xss_clean($_POST['txt']);
		if($txt != "")
		{
			$txtsql = 'AND `city` LIKE "'.$txt.'%"';
		}
		else
		{
			$txtsql = '';
		}
		//mail('xxxxxx@gmail.com','sql test','SELECT `id`,`city` FROM `geocity` WHERE `country` = "'.$country.'" '.$txtsql.' AND `city` != "" ORDER BY `city`');
		$query = $this->db->query('SELECT `id`,`city` FROM `geocity` WHERE `country` = "'.$country.'" '.$txtsql.' AND `city` != "" GROUP BY `city` ORDER BY `city`');
		if($query->num_rows() > 0)
		{
			$tmp = $query->result();
		}
		else
		{
			$tmp = "na";
		}
		$data = array("cities"=>$tmp);
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	function userslistlocation()
	{
		//error_reporting(E_ALL);
		$userid = $this->security->xss_clean($_POST['userid']);
		$city = $this->security->xss_clean($_POST['city']);
		$distance = $this->security->xss_clean($_POST['distance']);
		$limit = (@$_POST['limit'] ? ' LIMIT '.$_POST['limit'].',50' : ' LIMIT 0,50');
		// get user data
		$query = $this->db->query('SELECT `gender`,`seeking`,`cur_lat`,`cur_lon`,`diset`,`simg` FROM `users` WHERE `id` = "'.$userid.'"');
		$row = $query->row();
		$ulat = $row->cur_lat;
		$ulon = $row->cur_lon;
		$gender = $row->gender;
		$seeking = $row->seeking;
		$diset = $row->diset;
		$simg = $row->simg;
		$img_sql = '';
		if($simg == "1")
		{
			$img_sql = ' `haspic` = "1" AND ';
		}
		$agelmt = $this->my_usersmanager->getAgeLmt($userid);
		$match_age_sql = '';
		$match_age_sql = ' (`age` <= "'.$agelmt['max'].'" AND `age` >= "'.$agelmt['min'].'") AND ';
		// new multi gender seeking
		$gen_sql = '(';
		$genders = explode(',',$row->seeking);
		$x=1;
		foreach($genders as $g)
		{
			$gen_sql .= '`gender` = "'.$g.'" ';
			if(count($genders) > $x)
			{
				$gen_sql .= ' || ';
			}
			$x++;
		}
		$gen_sql .= ')';
		// new show only 
		$show_only_sql = ' (`showonly` = "" || `showonly` = "'.$gender.'" || `showonly` LIKE "'.$gender.',%" || `showonly` LIKE "%,'.$gender.'" || `showonly` LIKE "%,'.$gender.',%") AND ';
		// get lon lat from city
		$query = $this->db->query('SELECT `lon`,`lat` FROM `geocity` WHERE `id` = "'.$city.'"');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$lon = $row->lon;
			$lat = $row->lat;
			//echo 'using db lon / lat';
		}
		else
		{
			$lon = $ulon;
			$lat = $ulat;
			//echo 'using user lon lat';
		}
		$blocked = $this->my_usersmanager->getBlocksList($userid,"0");
		
		$query = $this->db->query('SELECT `id`,
			( 3959 * acos( cos( radians('.$ulat.') ) * cos( radians( `cur_lat` ) ) 
   			* cos( radians(`cur_lon`) - radians('.$ulon.')) + sin(radians('.$ulat.')) 
   			* sin( radians(`cur_lat`)))) AS distance 
		FROM `users` 
		WHERE (`type` = "normal" || `type` = "vip" || `type` = "mobile" || `type` = "suspicious") AND
		`id` != "'.$userid.'" AND 
		`isdel` != "1" AND 
		`utype` = "a" AND
		'.$match_age_sql.'
		'.$img_sql.'
		'.$gen_sql.' AND
		'.$show_only_sql.'
		`lastactivity` != 0
		'.($blocked != "na" ? $blocked : '').'
		HAVING distance >= "'.$distance.'" ORDER BY `lastactivity` DESC '.$limit.';');
		$users = $query->result();
		$users_data = array();
		foreach($users as $u)
		{
			$users_data[] = $this->my_usersmanager->getUserDataApp($userid,$u->id);
		}
		$data = array();
		$data['users'] = $users_data;
		$this->output
		->set_content_type('application/json')
    	->set_output(json_encode($data));
	}
	
	function subcron()
	{
		//$cur_time = time();
		//$query = $this->db->query('UPDATE `users` SET `subscribe` = "0" WHERE `expiration_date` <= "'.date('Y-m-d G:i:s', $cur_time).'"');
		//$updated = $query->affected_rows();
		//mail('xxxxxx@gmail.com','App Sub Cron Ran',$updated . ' Users Updated.');
	}
	
	function checksubscribe()
	{
		$userid = $this->security->xss_clean($_POST['userid']);
		$query = $this->db->query('SELECT `expiration_date` FROM `users` WHERE `id` = "'.$userid.'"');
		$row = $query->row();
		$cur_time = time();
		if($row->expiration_date == "0")
		{
			$exp_time = strtotime("+1 week");
		}
		else
		{
			$exp_time = ($row->expiration_date / 1000);
		}
		if($cur_time >= $exp_time)
		{
			$tmp = 'false';
			$exp = $exp_time;
		}
		else
		{
			$tmp = 'true';
			//$exp = (1000 * $exp_time);
			$exp = $exp_time;
			$query = $this->db->query('UPDATE `users` SET `expiration_date` = "'.(1000 * $exp_time).'" WHERE `id` = "'.$userid.'"');
		}
		$data = array("res"=>$tmp,'exp'=>$exp);
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	function appsubscribetest()
	{
		//error_reporting(E_ALL);
		$userid = $this->security->xss_clean($_POST['userid']);
		//$isdev = ($this->security->xss_clean(@$_POST['isdev']) == "1" ? "1" : "0");
		$transaction_receipt = $this->security->xss_clean($_POST['transaction_receipt']);
		$query = $this->db->query('SELECT `expiration_date` FROM `users` WHERE `id` = "'.$userid.'"');
		$row = $query->row();
		$cur_exp = $row->expiration_date;
		//$url = 'https://itunes.apple.com/verifyReceipt';
		$url = 'https://buy.itunes.apple.com/verifyReceipt';
		//$url = 'https://sandbox.itunes.apple.com/verifyReceipt';
		$encodedData = json_encode(Array('receipt-data' => $transaction_receipt,'password' => '25a862c9ad8b4cb4a3290cb60bbbf207'));
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
		$encodedResponse = curl_exec($ch);
		curl_close($ch);
		//Decode response data using json_decode method to get an object.
		$response = json_decode( $encodedResponse );
		//print_r($response);
		//exit;
		$ex_date = '';
		//echo "response status: " . $response->{'status'} . "\n";
		if($response->{'status'} == "21007")
		//if($response->{'status'} != 0 && $response->{'status'} != 21006)
		{
			//$tmp = "false";
			// try sandbox url...
			$url = 'https://sandbox.itunes.apple.com/verifyReceipt';
			$encodedData = json_encode(Array('receipt-data' => $transaction_receipt,'password' => '25a862c9ad8b4cb4a3290cb60bbbf207'));
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
			$encodedResponse = curl_exec($ch);
			curl_close($ch);
			//Decode response data using json_decode method to get an object.
			$response_b = json_decode( $encodedResponse );
			//print_r($response_b);
			if($response_b->{'status'} != 0 && $response_b->{'status'} != 21006)
			{
				//echo 'response from sandbox ' . $response_b->{'status'} . "\n";
				$tmp = "false";
			}
			else
			{
				if($response_b->{'receipt'}->{'in_app'})
				{
					//echo "IOS 7 SANDBOX \n";
					// IOS 7
					foreach($response_b->{'receipt'}->{'in_app'} as $r)
					{
						if($r->{'expires_date_ms'} > $cur_exp)
						{
							$ex_date = $r->{'expires_date_ms'};
						}
					}
					$tmp = "true";
				}
				else
				{
					//echo "IOS 6 SANDBOX \n";
					// IOS 6
					if($response_b->{'latest_receipt_info'}) 
					{
						$new_date = $response_b->{'latest_receipt_info'}->{'expires_date'};
					}
					else if($response_b->{'latest_expired_receipt_info'}) 
					{
						$new_date = $response_b->{'latest_expired_receipt_info'}->{'expires_date'};
					}
					else 
					{
						$new_date = $response_b->{'receipt'}->{'expires_date'};
					}
					if($new_date > $cur_exp) 
					{
						$ex_date = $new_date;
					}
					$tmp = "true";
				}
			}
		}
		else
		{
			if($response->{'receipt'}->{'in_app'})
			{
				//echo "IOS 7 NON SANDBOX \n";
				// IOS 7
				foreach($response->{'receipt'}->{'in_app'} as $r)
				{
					if($r->{'expires_date_ms'} > $cur_exp)
					{
						$ex_date = $r->{'expires_date_ms'};
					}
				}
				$tmp = "true";
			}
			else
			{
				//echo "IOS 6 NON SANDBOX \n";
				// IOS 6
				if($response->{'latest_receipt_info'}) 
				{
					//echo "latest_recipt_info" . $response->{'latest_receipt_info'}." expires_date".$response->{'latest_receipt_info'}->{'expires_date'}."\n";
					$new_date = $response->{'latest_receipt_info'}->{'expires_date'};
				}
				else if($response->{'latest_expired_receipt_info'}) 
				{
					//echo "latest_expired_recipt_info" . $response->{'latest_expired_receipt_info'}." expires_date".$response->{'latest_expired_receipt_info'}->{'expires_date'}."\n";
					$new_date = $response->{'latest_expired_receipt_info'}->{'expires_date'};
				}
				else 
				{
					//echo "recipt" . $response->{'receipt'}." expires_date".$response->{'receipt'}->{'expires_date'}."\n";
					$new_date = $response->{'receipt'}->{'expires_date'};
				}
    			if($new_date > $cur_exp) 
				{
    			    $ex_date = $new_date;
					//echo "check if new date is greater than current exp if so set exp to the new date: new_date:".$new_date . " cur_exp:" . $cur_exp."\n";
    			}
				$tmp = "true";
			}
		}
		//echo 'RESUT = cur_exp: '.$cur_exp . ' ex_date:' . $ex_date . "\n";
		if($cur_exp != $ex_date && $cur_exp < $ex_date && $ex_date != "")
		{
			$query = $this->db->query('UPDATE `users` SET `expiration_date` = "'.$ex_date.'" WHERE `id` = "'.$userid.'"');
		}
		$data = array("res"=>$tmp);
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	function appsubscribe()
	{
		//error_reporting(E_ALL);
		$userid = $this->security->xss_clean($_POST['userid']);
		//$isdev = ($this->security->xss_clean(@$_POST['isdev']) == "1" ? "1" : "0");
		$transaction_receipt = $this->security->xss_clean($_POST['transaction_receipt']);
		$query = $this->db->query('SELECT `expiration_date` FROM `users` WHERE `id` = "'.$userid.'"');
		$row = $query->row();
		$cur_exp = $row->expiration_date;
		//$url = 'https://itunes.apple.com/verifyReceipt';
		$url = 'https://buy.itunes.apple.com/verifyReceipt';
		//$url = 'https://sandbox.itunes.apple.com/verifyReceipt';
		$encodedData = json_encode(Array('receipt-data' => $transaction_receipt,'password' => '25a862c9ad8b4cb4a3290cb60bbbf207'));
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
		$encodedResponse = curl_exec($ch);
		curl_close($ch);
		//Decode response data using json_decode method to get an object.
		$response = json_decode( $encodedResponse );
		$ex_date = '';
		//echo "response status: " . $response->{'status'} . "\n";
		if($response->{'status'} == "21007")
		//if($response->{'status'} != 0 && $response->{'status'} != 21006)
		{
			//$tmp = "false";
			// try sandbox url...
			$url = 'https://sandbox.itunes.apple.com/verifyReceipt';
			$encodedData = json_encode(Array('receipt-data' => $transaction_receipt,'password' => '25a862c9ad8b4cb4a3290cb60bbbf207'));
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
			$encodedResponse = curl_exec($ch);
			curl_close($ch);
			//Decode response data using json_decode method to get an object.
			$response_b = json_decode( $encodedResponse );
			if($response_b->{'status'} != 0 && $response_b->{'status'} != 21006)
			{
				//echo 'response from sandbox ' . $response_b->{'status'} . "\n";
				$tmp = "false";
			}
			else
			{
				if($response_b->{'receipt'}->{'in_app'})
				{
					//echo "IOS 7 SANDBOX \n";
					// IOS 7
					foreach($response_b->{'receipt'}->{'in_app'} as $r)
					{
						if($r->{'expires_date_ms'} > $cur_exp)
						{
							$ex_date = $r->{'expires_date_ms'};
							//mail('xxxxxx@gmail.com','IOS 7 In app','product id: '.$r->{'product_id'});
							$sub = $this->my_usersmanager->addSubscription($r->{'product_id'},$userid);
						}
					}
					$tmp = "true";
				}
				else
				{
					//echo "IOS 6 SANDBOX \n";
					// IOS 6
					if($response_b->{'latest_receipt_info'}) 
					{
						$new_date = $response_b->{'latest_receipt_info'}->{'expires_date'};
					}
					else if($response_b->{'latest_expired_receipt_info'}) 
					{
						$new_date = $response_b->{'latest_expired_receipt_info'}->{'expires_date'};
					}
					else 
					{
						$new_date = $response_b->{'receipt'}->{'expires_date'};
					}
					if($new_date > $cur_exp) 
					{
						$ex_date = $new_date;
					}
					$tmp = "true";
				}
			}
		}
		else
		{
			if($response->{'receipt'}->{'in_app'})
			{
				//echo "IOS 7 NON SANDBOX \n";
				// IOS 7
				foreach($response->{'receipt'}->{'in_app'} as $r)
				{
					if($r->{'expires_date_ms'} > $cur_exp)
					{
						$ex_date = $r->{'expires_date_ms'};
						//mail('xxxxxx@gmail.com','IOS 7 In app','product id: '.$r->{'product_id'});
						$sub = $this->my_usersmanager->addSubscription($r->{'product_id'},$userid);
					}
				}
				$tmp = "true";
			}
			else
			{
				//echo "IOS 6 NON SANDBOX \n";
				// IOS 6
				if($response->{'latest_receipt_info'}) 
				{
					//echo "latest_recipt_info" . $response->{'latest_receipt_info'}." expires_date".$response->{'latest_receipt_info'}->{'expires_date'}."\n";
					$new_date = $response->{'latest_receipt_info'}->{'expires_date'};
				}
				else if($response->{'latest_expired_receipt_info'}) 
				{
					//echo "latest_expired_recipt_info" . $response->{'latest_expired_receipt_info'}." expires_date".$response->{'latest_expired_receipt_info'}->{'expires_date'}."\n";
					$new_date = $response->{'latest_expired_receipt_info'}->{'expires_date'};
				}
				else 
				{
					//echo "recipt" . $response->{'receipt'}." expires_date".$response->{'receipt'}->{'expires_date'}."\n";
					$new_date = $response->{'receipt'}->{'expires_date'};
				}
    			if($new_date > $cur_exp) 
				{
    			    $ex_date = $new_date;
					//echo "check if new date is greater than current exp if so set exp to the new date: new_date:".$new_date . " cur_exp:" . $cur_exp."\n";
    			}
				$tmp = "true";
			}
		}
		//echo 'RESUT = cur_exp: '.$cur_exp . ' ex_date:' . $ex_date . "\n";
		if($cur_exp != $ex_date && $cur_exp < $ex_date && $ex_date != "")
		{
			$query = $this->db->query('UPDATE `users` SET `expiration_date` = "'.$ex_date.'" WHERE `id` = "'.$userid.'"');
		}
		$data = array("res"=>$tmp);
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	function registerForNotificationsDroid()
	{
		$userid = $_POST['userid'];
		$gcm_regid = $_POST['gcm_regid'];
		$query = $this->db->query('UPDATE `users` SET `gcm_regid` = "'.$gcm_regid.'" WHERE `id` = "'.$userid.'"');
		$data = array("res"=>"true");
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	function sendPushNotificationsDroid()
	{
		$userid = $_POST['userid'];
		$msg = $_POST['msg'];
		
		$query = $this->db->query('SELECT `gcm_regid` FROM `users` WHERE `id` = "'.$userid.'"');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$gcm_regid = $row->gcm_regid;
			$browserKey = 'AIzaSyCSMGQm_GjItlMXac9SE47pxjOCu7Ihi2o';
			$apiKey = 'AIzaSyBLo29uNflHKBMWGvo01MTwAUUoAXj6bho';
			$url = 'https://android.googleapis.com/gcm/send';
 	
 	       	$fields = array(
 	           'registration_ids' => array($gcm_regid),
 	           'data' => array( "message" => $msg ),
 	       	);
    	   	$headers = array(
    	        'Authorization: key=' . $browserKey,
    	        'Content-Type: application/json'
    	    );
			/*
			$headers = array(
    	        'Authorization: key=' . $apiKey,
    	        'Content-Type: application/json'
    	    );
			*/
    	    // Open connection
    	    $ch = curl_init();
 	       // Set the url, number of POST vars, POST data
 	       curl_setopt($ch, CURLOPT_URL, $url);
 	       curl_setopt($ch, CURLOPT_POST, true);
 	       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
 	       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 	       // Disabling SSL Certificate support temporarly
 	       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 	       curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
 	       // Execute post
 	       $result = curl_exec($ch);
 	       if ($result === FALSE) {
 	           die('Curl failed: ' . curl_error($ch));
 	       }
 	       // Close connection
 	       curl_close($ch);
 	       echo "result: " .$result . time();
		}
		else
		{
			echo 'No gcm_regid';
		}
	}
	
	function registerForNotifications()
	{
		$userid = $this->security->xss_clean($_POST['userid']);
		$token = $this->security->xss_clean($_POST['device_token']);
		$isdev = $this->security->xss_clean($_POST['isdev']);
		$ispro = $this->security->xss_clean($_POST['ispro']);
		$query = $this->db->query('UPDATE `users` SET `device_token` = "'.$token.'", `isdev` = "'.$isdev.'", `ispro` = "'.$ispro.'" WHERE `id` = "'.$userid.'"');
		if($query->affected_rows() > 0)
		{
			$tmp = "true";
		}
		else
		{
			$tmp = "false";
		}
		$data = array("res"=>$tmp);
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	// testing
	function sendPushNotificationsAppleTest()
	{
		$userid = $_POST['userid'];
		$type = $_POST['type'];
		$text = $_POST['text'];
		$data = $_POST['data'];
		$query = $this->db->query('SELECT `device_token`,`isdev`,`ispro` FROM `users` WHERE `id` = "'.$userid.'"');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$token = $row->device_token;
			$isdev = $row->isdev;
			$ispro = $row->ispro;
			if($type == "1")
			{
				// message from user
				$msg = '{"aps" : {"alert":"'.$text.'","sound":"newMessageSound"},"type":'.$type.'}';
			}
			else if($type == "2")
			{
				// photo approved / declined
				$msg = '{"aps":{"alert":"'.$text.'"},"type":'.$type.',"is_approved":'.$data.'}'; // data 0/1
			}
			else if($type == "3")
			{
				// profile update
				$msg = '{"aps":{"alert":"'.$text.'",},"type":"'.$type.'"}';
			}
			else if($type == "4")
			{
				// custom message
				$msg = '{"aps":{"alert":"'.$text.'",},"type":"'.$type.'" }';
			}
			else if($type == "5")
			{
				// fav
				$msg = '{"aps":{"alert":"'.$text.'",},"type":"'.$type.'"}';
			}
			// send to apple
			$deviceToken = $token;			
			$passphrase = '';
			$message = $msg;
			if($isdev == "1")
			{
				if($ispro == "1")
				{
					$filename = $_SERVER['DOCUMENT_ROOT'].'/js/applecerts/dev/DevCertificates.pem';
				}
				else
				{
					$filename = $_SERVER['DOCUMENT_ROOT'].'/js/applecerts/dev/FreeDevCert.pem';
				}
				$pushurl = 'ssl://gateway.sandbox.push.apple.com:2195';
			}
			else
			{
				// check for free or pro version
				if($ispro == "1")
				{
					$filename = $_SERVER['DOCUMENT_ROOT'].'/js/applecerts/pro/ProductionCertificates.pem';
				}
				else
				{
					$filename = $_SERVER['DOCUMENT_ROOT'].'/js/applecerts/pro/FreeProductionCert.pem';
				}
				$pushurl = 'ssl://gateway.push.apple.com:2195';
			}
			//if (file_exists($filename)) {
				//echo "The file $filename exists";
			//} else {
				//echo "The file $filename does not exist";
			//}
			$ctx = stream_context_create();
			stream_context_set_option($ctx, 'ssl', 'local_cert', $filename);
			stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
			// Open a connection to the APNS server
			$res = '';
			$fp = stream_socket_client($pushurl, $err,$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
			if (!$fp)
			{
				$res = "Failed to connect: $err $errstr" . PHP_EOL.'<br />';
				echo $res . '<br />CERT: ' .$filename .'<br />URL: ' .$pushurl;
				exit;
			}
			else
			{
				$res .= 'Connected to APNS' . PHP_EOL.'<br />';
				// Create the payload body
				//$body['aps'] = array('alert' => $message,'sound' => 'default');
				// Encode the payload as JSON
				//$payload = json_encode($body);
				$payload = $message;
				// Build the binary notification
				$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
				// Send it to the server
				$result = fwrite($fp, $msg, strlen($msg));
				if (!$result)
				{
					$res .= 'Message not delivered' . PHP_EOL.'<br />';
				}
				else
				{
					$res .= 'Message successfully delivered<br />';
				}
				// Close the connection to the server
				fclose($fp);
				echo $res . '<br />CERT: ' .$filename .'<br />URL: ' .$pushurl;
				exit;
			}
		}
		else
		{
			$res .= 'failed, no token found' . ' cert used: ' . $filename;
			echo $res;
			exit;
		}
	}
	
	function msgpush()
	{
		//error_reporting(E_ALL);
		$msgs = $this->my_chatmanager->getOfflineMessages();
		//print_r($msgs);
		if($msgs != "na")
		{
			$sent = array();
			$free = array();
			$pro = array();
			foreach($msgs as $m)
			{
				$xml = simplexml_load_string($m->stanza);
				$to = explode('@',$xml->attributes()->to);
				$msg_to = str_replace('tj-','',$to[0]);
				$frm = explode('@',$xml->attributes()->from);
				$msg_from = str_replace('tj-','',$frm[0]);
				$body = $xml->body;
				// get user token / pro or not
				$query = $this->db->query('SELECT `device_token`,`ispro` FROM `users` WHERE `id` = "'.$msg_to.'"');
				if($query->num_rows() > 0)
				{
					$row = $query->row();
					$tmp['id']    = $msg_to;
					$tmp['type']  = 1;
					$tmp['from']  = $msg_from;
					$tmp['ispro'] = $row->ispro;
					$tmp['dt']    = $row->device_token;
					$tmp['body']  = $body;
					$tmp['msgid'] = $m->messageID;
					if($row->ispro == "1")
					{
						$pro[] = $tmp;
					}
					else
					{
						$free[] = $tmp;
					}
				}
			}
			$passphrase = '';
			$pushurl = 'ssl://gateway.push.apple.com:2195';
			// send push to pro
			//echo '<br /> pro <br />';
			//print_r($pro);
			if(count($pro) > 1)
			{
				$filename = $_SERVER['DOCUMENT_ROOT'].'/js/applecerts/pro/ProductionCertificates.pem';
				// connect
				$ctx = stream_context_create();
				stream_context_set_option($ctx, 'ssl', 'local_cert', $filename);
				stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
				$res = '';
				$fp = stream_socket_client($pushurl, $err,$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
				if (!$fp)
				{
					$res = "Failed to connect: $err $errstr" . PHP_EOL.'<br />';
					$res . '<br />CERT: ' .$filename .'<br />URL: ' .$pushurl;
					mail('xxxxxx@gmail.com','PUSH PRO FAIL',$res);
					//exit;
				}
				else
				{
					$res .= 'Connected to APNS' . PHP_EOL.'<br />';
					//$payload = $message;
					foreach($pro as $p)
					{
						$payload = '{"aps" : {"alert":"'.$p['body'].'","sound":"newMessageSound"},"type":1,"sender_id":"'.$p['from'].'"}';
						// Build the binary notification
						$msg = chr(0) . pack('n', 32) . pack('H*', $p['dt']) . pack('n', strlen($payload)) . $payload;
						// Send it to the server
						$result = fwrite($fp, $msg, strlen($msg));
						if (!$result)
						{
							$res .= 'Message not delivered' . PHP_EOL.' uid: '.$p['id'].'<br />';
						}
						else
						{
							$res .= 'Message successfully delivered Uid: '.$p['id'].'<br />';
							$sent[] = $p['msgid'];
						}
						usleep(200000);
					}
				}
				// Close the connection to the server
				fclose($fp);
				//mail('xxxxxx@gmail.com','Push Pro Success',$res);
			}
			//echo '<br /> free <br />';
			//print_r($free);
			// send push to free
			if(count($free) > 1)
			{
				$filename = $_SERVER['DOCUMENT_ROOT'].'/js/applecerts/pro/FreeProductionCert.pem';
				// connect
				$ctx = stream_context_create();
				stream_context_set_option($ctx, 'ssl', 'local_cert', $filename);
				stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
				$res = '';
				$fp = stream_socket_client($pushurl, $err,$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
				if (!$fp)
				{
					$res = "Failed to connect: $err $errstr" . PHP_EOL.'<br />';
					$res . '<br />CERT: ' .$filename .'<br />URL: ' .$pushurl;
					mail('xxxxxx@gmail.com','PUSH FREE FAIL',$res);
					//exit;
				}
				else
				{
					$res .= 'Connected to APNS' . PHP_EOL.'<br />';
					//$payload = $message;
					foreach($free as $p)
					{
						$payload = '{"aps" : {"alert":"'.$p['body'].'","sound":"newMessageSound"},"type":1,"sender_id":"'.$p['from'].'"}';
						// Build the binary notification
						$msg = chr(0) . pack('n', 32) . pack('H*', $p['dt']) . pack('n', strlen($payload)) . $payload;
						// Send it to the server
						$result = fwrite($fp, $msg, strlen($msg));
						if (!$result)
						{
							$res .= 'Message not delivered' . PHP_EOL.' uid: '.$p['id'].'<br />';
						}
						else
						{
							$res .= 'Message successfully delivered Uid: '.$p['id'].'<br />';
							$sent[] = $p['msgid'];
						}
						usleep(200000);
					}
				}
				// Close the connection to the server
				fclose($fp);
				//mail('xxxxxx@gmail.com','Push FREE Success',$res);
			}
			//echo '<br /> sent array <br />';
			if(count($sent) > 1)
			{
				//print_r($sent);
				$this->my_chatmanager->updatePushSent($sent);	
			}
		}
	}
	
	
	function sendPushNotificationsApple($userid,$type,$text,$data)
	{
		//$userid = $_POST['userid'];
		//$type = $_POST['type'];
		//$text = $_POST['text'];
		//$data = $_POST['data'];
		$mailmsg = '';
		$query = $this->db->query('SELECT `device_token`,`isdev`,`ispro` FROM `users` WHERE `id` = "'.$userid.'"');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$token = $row->device_token;
			$isdev = $row->isdev;
			$ispro = $row->ispro;
			if($type == "1")
			{
				// message from user
				$msg = '{"aps" : {"alert":"'.$data.'","sound":"newMessageSound"},"type":'.$type.',"sender_id":"'.$text.'"}';
				$mailmsg .= '{"aps" : {"alert":"'.$data.'","sound":"newMessageSound"},"type":'.$type.',"sender_id":"'.$text.'"}';
			}
			else if($type == "2")
			{
				// photo approved / declined
				$msg = '{"aps":{"alert":"'.$text.'"},"type":'.$type.',"is_approved":'.$data.'}'; // data 0/1
			}
			else if($type == "3")
			{
				// profile update
				$msg = '{"aps":{"alert":"'.$text.'",},"type":"'.$type.'"}';
			}
			else if($type == "4")
			{
				// custom message
				$msg = '{"aps":{"alert":"'.$text.'",},"type":"'.$type.'" }';
			}
			else if($type == "5")
			{
				// fav
				$msg = '{"aps":{"alert":"'.$text.'",},"type":"'.$type.'"}';
			}
			// send to apple
			$deviceToken = $token;			
			$passphrase = '';
			$message = $msg;
			if($isdev == "1")
			{
				if($ispro == "1")
				{
					$filename = $_SERVER['DOCUMENT_ROOT'].'/js/applecerts/dev/DevCertificates.pem';
				}
				else
				{
					$filename = $_SERVER['DOCUMENT_ROOT'].'/js/applecerts/dev/FreeDevCert.pem';
				}
				$pushurl = 'ssl://gateway.sandbox.push.apple.com:2195';
			}
			else
			{
				// check for free or pro version
				if($ispro == "1")
				{
					$filename = $_SERVER['DOCUMENT_ROOT'].'/js/applecerts/pro/ProductionCertificates.pem';
				}
				else
				{
					$filename = $_SERVER['DOCUMENT_ROOT'].'/js/applecerts/pro/FreeProductionCert.pem';
				}
				$pushurl = 'ssl://gateway.push.apple.com:2195';
			}
			//if (file_exists($filename)) {
				//echo "The file $filename exists";
			//} else {
				//echo "The file $filename does not exist";
			//}
			$ctx = stream_context_create();
			stream_context_set_option($ctx, 'ssl', 'local_cert', $filename);
			stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
			// Open a connection to the APNS server
			$res = '';
			$fp = stream_socket_client($pushurl, $err,$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
			if (!$fp)
			{
				$res = "Failed to connect: $err $errstr" . PHP_EOL.'<br />';
				$mailmsg .= $res . '<br />CERT: ' .$filename .'<br />URL: ' .$pushurl;
				//exit;
			}
			else
			{
				$res .= 'Connected to APNS' . PHP_EOL.'<br />';
				// Create the payload body
				//$body['aps'] = array('alert' => $message,'sound' => 'default');
				// Encode the payload as JSON
				//$payload = json_encode($body);
				$payload = $message;
				// Build the binary notification
				$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
				// Send it to the server
				$result = fwrite($fp, $msg, strlen($msg));
				if (!$result)
				{
					$res .= 'Message not delivered' . PHP_EOL.'<br />';
				}
				else
				{
					$res .= 'Message successfully delivered<br />';
				}
				// Close the connection to the server
				fclose($fp);
				$mailmsg .= $res . '<br />CERT: ' .$filename .'<br />URL: ' .$pushurl;
				//exit;
			}
		}
		else
		{
			$res .= 'failed, no token found' . ' cert used: ' . $filename;
			$mailmsg .= $res;
			//exit;
		}
		//mail('xxxx@fwends.com','APNS Push Test',$mailmsg);
	}
	
	function storecontacts()
	{
		$userid = $_POST['userid'];
		$contacts = $_POST['contacts'];
		$query = $this->db->query('SELECT `id` FROM `phone_contacts` WHERE `uid` = "'.$userid.'"');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$id = $row->id;
			$query = $this->db->query('UPDATE `phone_contacts` SET `contacts` = "'.mysql_real_escape_string($contacts).'" WHERE `uid` = "'.$userid.'"');
		}
		else
		{
			// new add
			$query = $this->db->query('INSERT INTO `phone_contacts` 
			(`id`,`uid`,`contacts`) 
			VALUES 
			(NULL,"'.$userid.'","'.mysql_real_escape_string($contacts).'")');
		}
		$data = array('res'=>"true");
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	function getcontacts()
	{
		$userid = $_POST['userid'];
		$query = $this->db->query('SELECT * FROM `phone_contacts` WHERE `uid` = "'.$userid.'"');
		$row = $query->row();
		$contacts = $row->contacts;
		$data = array('contacts'=>$contacts);
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	function addpromo()
	{
		$userid = $_POST['userid'];
		$promo = $_POST['promocode'];
		// check for correct promo code
		//mail('xxxxxx@gmail.com','test','uid:'.$userid.' promo:'.$promo);
		$query = $this->db->query('SELECT `id`,`type` FROM `promocodes` WHERE `code` = "'.mysql_real_escape_string($promo).'" AND `status` = "1" AND `used` = "0"');
		if($query->num_rows() > 0)
		{
			$promoData = $query->row();
			if($promoData->type == "Pro-Unlimited")
			{
				$exp_date = strtotime('13 August 2099');
				$query = $this->db->query('UPDATE `users` SET `expiration_date` = "'.(1000 * $exp_date).'" WHERE `id` = "'.$userid.'"');
				// set promo code as used
				$query = $this->db->query('UPDATE `promocodes` SET `used` = "1" WHERE `id` = "'.$promoData->id.'"');
			}
			// has code add to user
			$query = $this->db->query('UPDATE `users` SET `promo_code` = "'.mysql_real_escape_string($promo).'" WHERE `id` = "'.$userid.'"');
			//$msg = 'Your promo code has been added!';
			$msg = $this->my_stringmanager->getAppText($userid,'promo-code-added');
		}
		else
		{
			// non existing promo code
			// get cnt of attempt
			$query = $this->db->query('SELECT `promoct` FROM `users` WHERE `id` = "'.$userid.'"');
			$row = $query->row();
			$current_cnt = $row->promoct;
			if($current_cnt < 3)
			{
				// update cnt
				$query = $this->db->query('UPDATE `users` SET `promoct` = promoct + 1 WHERE `id` = "'.$userid.'"');
				//$msg = 'You have entered an incorrect promo code, please try again.';
				$msg = $this->my_stringmanager->getAppText($userid,'promo-code-wrong');
			}
			else
			{
				// can not add any more
				$msg = 'You have reached your limit to add your promo code. Please contact support for help.';
				$msg = $this->my_stringmanager->getAppText($userid,'promo-code-limit');
			}
		}
		$data = array('res'=>$msg);
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	function picapproved()
	{
		self::cleanData('picapproved',$_SERVER['REQUEST_URI']);
		//$uid = $this->security->xss_clean($this->uri->segment(3));
		$uid = $this->security->xss_clean($_POST['userid']);
		$query = $this->db->query('SELECT `picpath` FROM `users` WHERE `id` = "'.$uid.'" AND `haspic` = "1"');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			if($row->picpath != "")
			{
				$tmp = "true";
			}
			else
			{
				$tmp = "false";
			}
		}
		else
		{
			$tmp = "false";
		}
		self::cleanData('picapproved');
		$data = array('res'=>$tmp);
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	function syncp()
	{
		$userid    = $_POST['userid'];
		$sync_code = $_POST['sync_code'];
		$email     = $_POST['email'];
		$type      = $_POST['type'];
		mail('xxxxxx@gmail.com','test','userid: '.$userid . ' sync code: '.$sync_code.' email: '.$email.' type: '.$type);
		if($type == 'rec')
		{
			if($userid == "na")
			{
				// get and send sync code
				$query = $this->db->query('SELECT `sync_code` FROM `users` WHERE `email` = "'.$email.'"');
			}
			else
			{
				$query = $this->db->query('SELECT `sync_code` FROM `users` WHERE `id` = "'.$userid.'"');
			}
			$row = $query->row();
			$code = $row->sync_code;
			//$msg = 'Here is your sync code. To sync this profile with any device. Enter this code. Website users were automatically emailed the sync code, if you need to retrieve your sync code please contact us.';
			$msg = $this->my_stringmanager->getAppText($userid,'sync-code');
		}
		else if($type == "msync")
		{
			$query = $this->db->query('SELECT `id`,`email`,`sync_code` FROM `users` WHERE `email` = "'.$email.'"');
			if($query->num_rows() > 0)
			{
				// get and email sync code.
				$row = $query->row();
				if($row->sync_code != "")
				{
					$syncEmail = $row->sync_code;
				}
				else
				{
					$sc = $this->my_stringmanager->getSyncCode('tj-'.$row->id);
					$query = $this->db->query('UPDATE `users` SET `sync_code` = "'.$sc.'" WHERE `id` = "'.$row->id.'"');
					$syncEmail = $sc;
				}
				$snd = self::sendUserMail($email,'sync_code',$syncEmail);
				//mail($email,'xxxxxx Sync Code','Here is your sync code: ' . $syncEmail);
				$code = "true";
				//$msg = 'Your sync code has been emailed to the email you have entered.';
				$msg = $this->my_stringmanager->getAppText($userid,'sync-code-emailed');
			}
			else
			{
				$code = "false";
				//$msg = "The email you entered is not in our system. Please try again.";
				$msg = $this->my_stringmanager->getAppText($userid,'email-not-correct');
			}
		}
		else if($type == "sen")
		{
			// get userid from sync_code
			if($email == "na" && $sync_code != "na")
			{
				$query = $this->db->query('SELECT `id` FROM `users` WHERE `sync_code` = "'.mysql_real_escape_string($sync_code).'"');
			}
			else if($email != "na" && $sync_code == "na")
			{
				$query = $this->db->query('SELECT `id` FROM `users` WHERE `email` = "'.$email.'"');
			}
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				$new_id = $row->id;
				//mail('xxxxxx@gmail.com','test','test- new id = ' . $new_id);
				// get current device id.
				$query = $this->db->query('SELECT `device_id` FROM `users` WHERE `id` = "'.$userid.'"');
				$row = $query->row();
				$new_deviceid = $row->device_id;
				// update old account
				$query = $this->db->query('UPDATE `users` SET `type` = "synced", `device_id` = "SYNCED:'.$new_id.'" WHERE `id` = "'.$userid.'"');
				// update new account
				$query = $this->db->query('UPDATE `users` SET `device_id` = "'.$new_deviceid.'" WHERE `id` = "'.$new_id.'"');
				$code = "true";
				//$msg = "Your accounts have now been synced!";
				$msg = $this->my_stringmanager->getAppText($userid,'account-synced');
			}
			else
			{
				$code = "false";
				if($email != "na")
				{
					//$msg = "The email you entered is not in our system. Please try again.";
					$msg = $this->my_stringmanager->getAppText($userid,'email-not-correct');
				}
				else
				{
					//$msg = "The sync code you entered appears to be invalid. Please try again.";
					$msg = $this->my_stringmanager->getAppText($userid,'sync-code-wrong');
				}
			}
		}
		$data = array('code'=>$code,'msg'=>$msg);
		//self::cleanData('deleteprofile');
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	function reactivate()
	{
		$id = $_POST['userid'];
		$email = $_POST['email'];
		$code = $_POST['code'];
		//mail('xxxxxx@gmail.com','test',$id . ' : ' . $email . ' : ' . $code);
		if($email == "na" && $code != "na")
		{
			// check code and re-activate account
			$query = $this->db->query('SELECT `delcode` FROM `users` WHERE `id` = "'.$id.'"');
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				$delcode = $row->delcode;
				if($delcode == $code)
				{
					$query = $this->db->query('UPDATE `users` SET `delcode` = "", `isdel` = "0" WHERE `id` = "'.$id.'"');
					$res = 'true';
					//$tmp = 'Your account has been re-activated.';
					$tmp = $this->my_stringmanager->getAppText($userid,'re-activation');
				}
				else
				{
					$res = 'false';
					//$tmp = 'The code you have entered does not match, please try again.';
					$tmp = $this->my_stringmanager->getAppText($userid,'re-activation-error');
				}
			}
			else
			{
				$res = 'false';
				//$tmp = 'Please contact support, we do not have a delete code matching your user.';
				$tmp = $this->my_stringmanager->getAppText($userid,'no-delete-code');
			}
		}
		else
		{
			// add email and send code
			//$delcode = $this->my_stringmanager->genVerify(8);
			$query = $this->db->query('SELECT `email`,`delcode` FROM `users` WHERE `id` = "'.$id.'"');
			$row = $query->row();
			$delcode = $row->delcode;
			// check for xxxxxx email
			$mystring = $row->email;
			$findme   = 'xxxxxx.com';
			$pos = strpos($mystring, $findme);
			if ($pos === false) 
			{
				// check email if same email code,if not same let user know
				if($row->email == $email && $row->email != "")
				{
					// mail code
					//$query = $this->db->query('UPDATE `users` SET `email` = "'.$email.'" WHERE `id` = "'.$id.'"');
					mail($email,'xxxxxx Re-Activation Code','Here is your re-activation code. ' . $delcode);
					$res = 'true';
					//$tmp = 'Your re-activation code has been emailed to you!';
					$tmp = $this->my_stringmanager->getAppText($userid,'re-activation-code-emailed');
				}
				else if($row->email == "")
				{
					// mail code
					$query = $this->db->query('UPDATE `users` SET `email` = "'.$email.'" WHERE `id` = "'.$id.'"');
					mail($email,'xxxxxx Re-Activation Code','Here is your re-activation code. ' . $delcode);
					$res = 'true';
					//$tmp = 'Your re-activation code has been emailed to you!';
					$tmp = $this->my_stringmanager->getAppText($userid,'re-activation-code-emailed');
				}
				else
				{
					$res = 'false';
					//$tmp = 'Please contact support, the email you entered does not match the email in our system';
					$tmp = $this->my_stringmanager->getAppText($userid,'email-wrong');
				}
			}
			else
			{
				// update email and send code
				$query = $this->db->query('UPDATE `users` SET `email` = "'.$email.'" WHERE `id` = "'.$id.'"');
				mail($email,'xxxxxx Re-Activation Code','Here is your re-activation code. ' . $delcode);
				$res = 'true';
				//$tmp = 'Your re-activation code has been emailed to you!';
				$tmp = $this->my_stringmanager->getAppText($userid,'re-activation-code-emailed');
			}
		}
		$data = array('res'=>$res,'msg'=>$tmp);
		//self::cleanData('deleteprofile');
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	function deleteprofile()
	{
		$uid = $this->security->xss_clean($_POST['userid']);
		$reason = $this->security->xss_clean($_POST['reason']);
		// get del code
		$delcode = $this->my_stringmanager->genVerify(8);
		$query = $this->db->query('UPDATE `users` SET `isdel` = "1", `delreason` = "'.mysql_real_escape_string($reason).'",`lastactivity` = "'.time().'", `delcode` = "'.$delcode.'" WHERE `id` = "'.$uid.'"');
		$data = array('res'=>'true');
		//self::cleanData('deleteprofile');
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	function unblockeveryone()
	{
		self::cleanData('unblockeveryone',$_SERVER['REQUEST_URI']);
		//$uid = $this->security->xss_clean($this->uri->segment(3));
		$uid = $this->security->xss_clean($_POST['userid']);
		$query = $this->db->query('DELETE FROM `blocks` WHERE `blockedby` = "'.$uid.'"');
		$data = array('res'=>'true');
		//self::cleanData('unblockeveryone');
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	function updateViewCnt()
	{
		$uid = $this->security->xss_clean($_POST['userid']);
		$vid = $this->security->xss_clean($_POST['userid2']);
		//mail('xxxxxx@gmail.com','test','updateViewCnt:'.$uid.' : '.$vid);
		$la = self::updateLastActive($uid);
		if($uid != $vid)
		{
			//mail('xxxxxx@gmail.com','test','INSERT INTO `app_profile_views` (`id`,`userid`,`viewed`) VALUES (NULL,"'.$uid.'","'.$vid.'")');
			$query = $this->db->query('INSERT INTO `app_profile_views` (`id`,`userid`,`viewed`) VALUES (NULL,"'.$uid.'","'.$vid.'")');
			$query = $this->db->query('UPDATE `users` SET `views` = views + 1 WHERE `id` = "'.$uid.'"');
		}
		$data = array('res'=>'true');
		//self::cleanData('updateviewcnt');
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	function distance()
	{
		// default
		self::cleanData('distance',$_SERVER['REQUEST_URI']);
		$uid = $this->security->xss_clean($_POST['userid']);
		$vid = $this->security->xss_clean($_POST['userid2']);
		$unit = $this->security->xss_clean(@$_POST['unit']); // Mi or Km
		$query = $this->db->query('SELECT `cur_lat`,`cur_lon`,`diset` FROM `users` WHERE `id` = "'.$uid.'"');
		$user = $query->row();
		$query = $this->db->query('SELECT `cur_lat`,`cur_lon` FROM `users` WHERE `id` = "'.$vid.'"');
		$viewer = $query->row();
		//$distance = $this->my_geomanager->getDistance($user->cur_lat,$user->cur_lon,$viewer->cur_lat,$viewer->cur_lon);
		$distance = $this->my_usersmanager->getDistance($user->cur_lat,$user->cur_lon,$viewer->cur_lat,$viewer->cur_lon,(@$user->diset == "" ? "Km" : $user->diset));
		//mail('xxxxxx@gmail.com','dis test','distance: ' . $diatance);
		$data = array('res'=>$distance);
		
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	function onlinestatus()
	{
		self::cleanData('onlinestatus',$_SERVER['REQUEST_URI'],$_POST);
		$uid = $this->security->xss_clean($_POST['userid']);
		$status = $this->security->xss_clean($_POST['status']);
		$la = self::updateLastActive($uid);
		$query = $this->db->query('UPDATE `users` SET `mobile_online` = "'.$status.'" WHERE `id` = "'.$uid.'"');
		if($this->db->affected_rows() > 0)
		{
			$tmp =  "true";
		}
		else
		{
			$tmp = "false";
		}
		$data = array('res'=>$tmp);
		self::cleanData('onlinestatus');
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	function updateProfile()
	{
		$uid = $this->security->xss_clean($_POST['userid']);
		$fld = $this->security->xss_clean($_POST['fld']);
		$val = $this->security->xss_clean($_POST['val']);
		$la = self::updateLastActive($uid);
		if($uid == "19303")
		{
			mail('xxxxxx@gmail.com','social test','fld: ' . $fld . ' val: ' . $val);
		}
		if($fld == "simg")
		{
			$query = $this->db->query('UPDATE `users` SET `simg` = "'.$val.'" WHERE `id` = "'.$uid.'"');
			$msg = $this->my_stringmanager->getAppText($uid,'profile-updated');
			$data = array('res'=>$msg);
			$this->output
			->set_content_type('application/json')
    		->set_output(json_encode($data));
		}
		if($fld == "diset")
		{
			$query = $this->db->query('UPDATE `users` SET `diset` = "'.$val.'" WHERE `id` = "'.$uid.'"');
			//$msg = "Your profile has been updated";
			$msg = $this->my_stringmanager->getAppText($uid,'profile-updated');
			$data = array('res'=>$msg);
			$this->output
			->set_content_type('application/json')
    		->set_output(json_encode($data));
		}
		if($fld == "showonly")
		{
			$query = $this->db->query('UPDATE `users` SET `showonly` = "'.$val.'" WHERE `id` = "'.$uid.'"');
			//$msg = "Your profile has been updated";
			$msg = $this->my_stringmanager->getAppText($uid,'profile-updated');
			$data = array('res'=>$msg);
			$this->output
			->set_content_type('application/json')
    		->set_output(json_encode($data));
		}
		if($fld == "dislim")
		{
			//$msg = "Your profile has been updated";
			$msg = $this->my_stringmanager->getAppText($uid,'profile-updated');
			$query = $this->db->query('UPDATE `users` SET `dislim` = "'.$val.'" WHERE `id` = "'.$uid.'"');
			$data = array('res'=>$msg);
			$this->output
			->set_content_type('application/json')
    		->set_output(json_encode($data));
		}
		if($fld == "nickname")
		{
			// check for existing
			//$msg = "You Displayname has been updated!";
			$msg = $this->my_stringmanager->getAppText($uid,'displayname-update');
			$size = strlen($val);
			// check for suspicious
			$query = $this->db->query('SELECT `type` FROM `users` WHERE `id` = "'.$uid.'"');
			$row = $query->row();
			$suspicious = $row->type;
			if($suspicious == "suspicious")
			{
				$msg = 'xxxxxx Support has locked your account from changes, due to suspicious activity, please contact xxxxxx Support to have your account unlocked.';
			}
			else
			{
				if($size >= 3 && $size < 15)
				{	
					//$query = $this->db->query('SELECT `id` FROM `users` WHERE `nickname` = "'.mysql_real_escape_string($val).'"');
					//if($query->num_rows() > 0)
					//{
						//$msg = 'This displayname is already in use. Please try again.';
					//}
					//else
					//{
						// check for badwords
						$ckname = $this->my_stringmanager->filterNick($val);
						if($ckname != 'ok')
						{
							//$msg = 'Your displayname is not allowed. Please try again.';
							$msg = $this->my_stringmanager->getAppText($userid,'display-name-bad');
						}
						else
						{
							$url = $this->my_stringmanager->cleanForUrl($val);
							$query = $this->db->query('UPDATE `users` SET `nickname` = "'.mysql_real_escape_string($val).'", `url` = "'.$url.'" WHERE `id` = "'.$uid.'"');
							//$msg = 'Your displayname has been added!';
							$msg = $this->my_stringmanager->getAppText($uid,'display-name-added');
						}
					//}
				}
				else
				{
					//$msg = 'Your displayname needs to be 3 - 15 characters long.';
					$msg = $this->my_stringmanager->getAppText($uid,'display-name-limit');
				}
			}
			$data = array('res'=>$msg);
			$this->output
			->set_content_type('application/json')
    		->set_output(json_encode($data));
		} // end nickname
		else if($fld == "dis")
		{
			$query = $this->db->query('UPDATE `users` SET `show_dis` = "'.$val.'" WHERE `id` = "'.$uid.'"');
			//$msg = 'Your distance setting has been set.';
			$msg = $this->my_stringmanager->getAppText($uid,'profile-updated');
			$data = array('res'=>$msg);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
		}
		else if($fld == "headline")
		{
			// check for suspicious
			$query = $this->db->query('SELECT `type` FROM `users` WHERE `id` = "'.$uid.'"');
			$row = $query->row();
			$suspicious = $row->type;
			if($suspicious == "suspicious")
			{
				$msg = "xxxxxx Support has locked your account from changes, due to suspicious activity, please contact xxxxxx Support to have your account unlocked.";
			}
			else
			{
				$headline = $this->my_stringmanager->removeUnwanted($val);
				$query = $this->db->query('UPDATE `users` SET `headline` = "'.mysql_real_escape_string($headline).'", `status` = "2" WHERE `id` = "'.$uid.'"');
				//$msg = 'Your headline has been updated.';
				$msg = $this->my_stringmanager->getAppText($uid,'headline-updated');
			}
			$data = array('res'=>$msg);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
		} // end headline 
		else if($fld == "bio")
		{
			$query = $this->db->query('SELECT `type` FROM `users` WHERE `id` = "'.$uid.'"');
			$row = $query->row();
			$suspicious = $row->type;
			if($suspicious == "suspicious")
			{
				$msg = "xxxxxx Support has locked your account from changes, due to suspicious activity, please contact xxxxxx Support to have your account unlocked.";
			}
			else
			{
				$bio = $this->my_stringmanager->removeUnwanted($val);
				$query = $this->db->query('UPDATE `profile_data` SET `bio` = "'.mysql_real_escape_string($bio).'", `pstatus` = "2" WHERE `uid` = "'.$uid.'"');
				//$msg = 'Your profile has been updated!';
				$msg = $this->my_stringmanager->getAppText($uid,'profile-updated');
			}
			$data = array('res'=>$msg);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
		} // end bio 
		else if($fld == "relationship")
		{
			//mail('xxxxxx@gmail.com','test looking for',$fld . ':' . $val . ' : ' . $id);
			$query = $this->db->query('UPDATE `profile_data` SET `user_relationship` = "'.$val.'" WHERE `uid` = "'.$uid.'"');
			//$msg = 'Your profile has been updated!';
			$msg = $this->my_stringmanager->getAppText($uid,'profile-updated');
			$data = array('res'=>$msg);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
		}// end relationship 
		else if($fld == "lookingfor")
		{
			$query = $this->db->query('UPDATE `profile_data` SET `match_relationship` = "'.$val.'" WHERE `uid` = "'.$uid.'"');
			//$msg = 'Your profile has been updated!';
			$msg = $this->my_stringmanager->getAppText($uid,'profile-updated');
			$data = array('res'=>$msg);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
		} // end looking for 
		else if($fld == "ethnicity")
		{
			$query = $this->db->query('UPDATE `profile_data` SET `user_ethn` = "'.$val.'" WHERE `uid` = "'.$uid.'"');
			//$msg = 'Your profile has been updated!';
			$msg = $this->my_stringmanager->getAppText($uid,'profile-updated');
			$data = array('res'=>$msg);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
		} // end ethn
		else if($fld == "age")
		{
			$pts = explode(' ',$val);
			$dts = explode('-',$pts[0]);
			$y = ($dts[0] > 2014 ? ($dts[0] - 543) : $dts[0]);
			$m = $dts[1];
			$d = $dts[2];
			$age = $this->my_usersmanager->birthday($y.'-'.$m.'-'.$d);
			//mail('xxxxxx@gmail.com','test','test age '.$age. ' val: ' . $val . ' uid:' .$uid );
			$query = $this->db->query('UPDATE `users` SET `dob` = "'.$y.'-'.$m.'-'.$d.'", `age` = "'.($age > 99 ? 18 : $age).'" WHERE `id` = "'.$uid.'"');
			//$msg = 'Your profile has been updated!';
			$msg = $this->my_stringmanager->getAppText($uid,'profile-updated');
			$data = array('res'=>$msg);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
		} // end age
		 else if($fld == "height")
		{
			$query = $this->db->query('UPDATE `profile_data` SET `user_height` = "'.$val.'" WHERE `uid` = "'.$uid.'"');
			//$msg = 'Your profile has been updated!';
			$msg = $this->my_stringmanager->getAppText($uid,'profile-updated');
			$data = array('res'=>$msg);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
		} // end height 
		else if($fld == "weight")
		{
			$query = $this->db->query('UPDATE `profile_data` SET `user_weight` = "'.$val.'" WHERE `uid` = "'.$uid.'"');
			//$msg = 'Your profile has been updated!';
			$msg = $this->my_stringmanager->getAppText($uid,'profile-updated');
			$data = array('res'=>$msg);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
		} // end weight 
		else if($fld == "soc")
		{
			$pts = explode('||',$val);
			$typ = $pts[0];
			$url = $pts[1];
			mail('xxxxxx@gmail.com','test',$val);
			if($val != "(null)||https://www." && $val != "(null)||https://www.(null)(null)")
			{
				if($typ == "fb")
				{
					$dfld = 'facebook';	
				}
				if($typ == "tw")
				{
					$dfld = 'twitter';
				}
				if($type == "li")
				{
					$dfld = 'linkedin';
				}
				$query = $this->db->query('UPDATE `profile_data` SET `'.$dfld.'` = "'.mysql_real_escape_string($url).'" WHERE `uid` = "'.$uid.'"');
			}
			//$msg = 'Your social data has been updated!';
			$msg = $this->my_stringmanager->getAppText($uid,'profile-updated');
			$data = array('res'=>$msg);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
		} // end soc 
		else if($fld == "agelmt")
		{
			$query = $this->db->query('UPDATE `profile_data` SET `match_age` = "'.$val.'" WHERE `uid` = "'.$uid.'"');
			//$msg = 'Your profile has been updated!';
			$msg = $this->my_stringmanager->getAppText($uid,'profile-updated');
			$data = array('res'=>$msg);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
		}
		else if($fld == "seeking")
		{
			$query = $this->db->query('UPDATE `users` SET `seeking` = "'.$val.'" WHERE `id` = "'.$uid.'"');
			$query = $this->db->query('UPDATE `profile_data` SET `match_gender` = "'.$val.'" WHERE `uid` = "'.$uid.'"');
			//$msg = "Your profile has been updated!";
			$msg = $this->my_stringmanager->getAppText($uid,'profile-updated');
			$data = array('res'=>$msg);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
		}
		else if($fld == "gender")
		{
			$query = $this->db->query('UPDATE `users` SET `gender` = "'.$val.'" WHERE `id` = "'.$uid.'"');
			//$msg = "Your profile has been updated!";
			$msg = $this->my_stringmanager->getAppText($uid,'profile-updated');
			$data = array('res'=>$msg);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
		}
		else if($fld == "email")
		{
			$query = $this->db->query('UPDATE `users` SET `email` = "'.mysql_real_escape_string($val).'" WHERE `id` = "'.$uid.'"');
			$msg = "Your email has been updated.";
			$data = array('res'=>$msg);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
		}
		else if($fld == "fbimg")
		{
			$query = $this->db->query('UPDATE `users` SET `picpath` = "'.$val.'", `fbimg` = "2" WHERE `id` = "'.$uid.'"');
			$msg = $this->my_stringmanager->getAppText($userid,'pic-upload-success');
			$data = array('res'=>$msg);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
		}
	}
	
	function agefix()
	{
		//$val = '2530-11-18 17:00:00 +0000';
		$query = $this->db->query('SELECT `id`,`dob`,`age` FROM `users` WHERE `utype` = "a"');
		$users = $query->result();
		foreach($users as $u)
		{
			$val = $u->dob;
			if($val != "")
			{
				$pts = explode(' ',$val);
				$dts = explode('-',$pts[0]);
				if($dts[0] > 2014)
				{
					$y = ($dts[0] > 2014 ? ($dts[0] - 543) : $dts[0]);
					$m = $dts[1];
					$d = $dts[2];
					// get age
					$age = $this->my_usersmanager->birthday($y.'-'.$m.'-'.$d);
					if($age > 99 || $age < 18)
					{
						$age = 18;
					}
					$query = $this->db->query('UPDATE `users` SET `dob` = "'.$y.'-'.$m.'-'.$d.'", `age` = "'.$age.'" WHERE `id` = "'.$u->id.'"');
					echo $u->id . ' had their age and dob changed <br />';
				}
			}
		}
		//echo $y.'-'.$m.'-'.$d;
	}
	
	function uploadPhoto()
	{
		$uid = (!$_POST['userid'] ? $_POST['userId'] : $_POST['userid']);
		$fldnm = $_FILES['pic'];
		$la = self::updateLastActive($id);
		// check for pending images
		$query = $this->db->query('SELECT `id`,`status` FROM `waiting_images` WHERE `uid` = "'.$uid.'"');
		if($query->num_rows() > 0)
		{
			// user has pic pending approval
			//$msg = "You currently have a pic awaiting to be approved!";
			$msg = $this->my_stringmanager->getAppText($uid,'pic-awaiting-approval');
			$pic = "https://www.xxxxxx.com/images/pending_profile_user.png";
		}
		else
		{
			// upload pic
			$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . '/images/p_pics/';
			$config['allowed_types'] = '*';
			$config['overwrite'] = false;
			$new_file_name = time().'.'.end(explode('.',$_FILES['pic']['name']));
			$config['file_name'] = $new_file_name;
			$config['max_size']	= '0';
			$config['max_width']  = '0';
			$config['max_height']  = '0';
			$this->load->library('upload', $config);
			$field_name = "pic";
			if ( ! $this->upload->do_upload($field_name))
			{
				$error = array('error' => $this->upload->display_errors());
				$msg = 'upload_file: ' . $_FILES['pic']['name'] . ' new name: '.$new_file_name.' :: file size : '.$_FILES['pic']['size'] . ' :: ';
				foreach($error as $key=>$value)
				{
					$msg .= $key . '=>' .$value.'-----'; 
				}
				//mail('xxxxxx@gmail.com','imgtest',$msg);
			}
			else
			{
				$img = array('upload_data' => $this->upload->data());
				$profile_pic = '/images/p_pics/'.$img['upload_data']['file_name'];
				$query = $this->db->query('INSERT INTO `waiting_images` (`id`,`uid`,`status`,`path`) VALUES (NULL,"'.$uid.'","2","'.$profile_pic.'")');
				$query = $this->db->query('INSERT INTO `images` (`id`,`uid`,`ismain`,`status`,`path`) VALUES (NULL,"'.$uid.'","2","2","'.$profile_pic.'")');
				$query = $this->db->query('UPDATE `users` SET `haspic` = "3" WHERE `id` = "'.$uid.'"');
				//$msg = "Your photo has been uploaded and is waiting to be approved!";
				$msg = $this->my_stringmanager->getAppText($userid,'pic-upload-success');
				$pic = "https://www.xxxxxx.com/images/pending_profile_user.png";
			}
		}
		$data = array('res'=>$msg,'path'=>$pic);
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	function uploadPhotoB()
	{
		$uid = (!$_POST['userid'] ? $_POST['userId'] : $_POST['userid']);
		$fldnm = $_FILES['pic'];
		$la = self::updateLastActive($id);
		// check for pending images
		$query = $this->db->query('SELECT `id`,`status` FROM `waiting_images` WHERE `uid` = "'.$uid.'"');
		if($query->num_rows() > 0)
		{
			// user has pic pending approval
			$msg = "You currently have a pic awaiting to be approved!";
		}
		else
		{
			// upload pic
			$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . '/images/p_pics/';
			$config['allowed_types'] = '*';
			$config['overwrite'] = false;
			$new_file_name = time().'.'.end(explode('.',$_FILES['pic']['name']));
			$config['file_name'] = $new_file_name;
			$config['max_size']	= '0';
			$config['max_width']  = '0';
			$config['max_height']  = '0';
			$this->load->library('upload', $config);
			$field_name = "pic";
			if ( ! $this->upload->do_upload($field_name))
			{
				$error = array('error' => $this->upload->display_errors());
				$msg = 'upload_file: ' . $_FILES['pic']['name'] . ' new name: '.$new_file_name.' :: file size : '.$_FILES['pic']['size'] . ' :: ';
				foreach($error as $key=>$value)
				{
					$msg .= $key . '=>' .$value.'-----'; 
				}
				//mail('xxxxxx@gmail.com','imgtest',$msg);
			}
			else
			{
				$img = array('upload_data' => $this->upload->data());
				$profile_pic = '/images/p_pics/'.$img['upload_data']['file_name'];
				$query = $this->db->query('INSERT INTO `waiting_images` (`id`,`uid`,`status`,`path`) VALUES (NULL,"'.$uid.'","2","'.$profile_pic.'")');
				$msg = "Your photo has been uploaded and is waiting to be approved!";
			}
		}
		$data = array('res'=>$msg);
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	function getProfileValues()
	{
		$lang = "en";
		if(isset($_POST['userid']))
		{
			// get user lang
			$query = $this->db->query('SELECT `lang` FROM `users` WHERE `id` = "'.$_POST['userid'].'"');
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				$langs = $this->my_stringmanager->getlangs();
				if(in_array(strtolower($row->lang),$langs))
				{
					$lang = strtolower($row->lang);
				}
				else
				{
					$lang = "en";
				}
			}
			else
			{
				$lang = "en";
			}
		}
		self::cleanData('getprofilevalues',$_SERVER['REQUEST_URI']);
		$query = $this->db->query('SELECT * FROM `pfields` WHERE `mobile` = "y" AND `status` = "1"');
		$fields = $query->result();
		$field_values = array();
		//$la = self::updateLastActive($uid);
		//self::cleanData('getprofilevalues');
		if($lang == "en")
		{
			$pfld = "name";
		}
		else
		{
			$pfld = $lang."_name";
		}
		foreach($fields as $f)
		{
			$query = $this->db->query('SELECT * FROM `pfields_values` WHERE `fid` = "'.$f->id.'"');
			$values = $query->result();
			$varray = array();
			if($lang == "en")
			{
				$pval = "name";
			}
			else
			{
				$pval = $lang."_name";
			}
			foreach($values as $v)
			{
				$varray[] = array('value'=>$v->id,'txt'=>$v->$pval);
			}
			$field_values[strtolower(str_replace(' ','',$f->name))]['label'] = $f->$pfld;
			$field_values[strtolower(str_replace(' ','',$f->name))]['values'] = $varray;
		}
		//print_r($field_values);
		//exit;
		$this->output
		->set_content_type('application/json')
    	->set_output(json_encode($field_values));
	}
	
	function getUsersList()
	{	
		$userid = $this->security->xss_clean($_POST['userid']);
		$ltype = $this->security->xss_clean($_POST['ltype']);
		$limit = (@$_POST['limit'] ? ' LIMIT '.$_POST['limit'].',50' : ' LIMIT 0,50');
		$la = self::updateLastActive($userid);
		$query = $this->db->query('SELECT `gender`,`seeking`,`cur_lat`,`cur_lon`,`simg`,`ispro` FROM `users` WHERE `id` = "'.$userid.'"');
		$row = $query->row();
		$ulat = $row->cur_lat;
		$ulon = $row->cur_lon;
		$gender = $row->gender;
		$seeking = $row->seeking;
		$simg = $row->simg;
		$img_sql = '';
		$blocked = $this->my_usersmanager->getBlocksList($userid,"0");
		if($simg == "1" && $row->ispro == "1")
		{
			$img_sql = ' `haspic` = "1" AND ';
		}
		$agelmt = $this->my_usersmanager->getAgeLmt($userid);
		$match_age_sql = '';
		$match_age_sql = ' (`age` <= "'.$agelmt['max'].'" AND `age` >= "'.$agelmt['min'].'") AND ';
		$gen_sql = '(';
		$genders = explode(',',$row->seeking);
		$x=1;
		foreach($genders as $g)
		{
			$gen_sql .= '`gender` = "'.$g.'" ';
			if(count($genders) > $x)
			{
				$gen_sql .= ' || ';
			}
			$x++;
		}
		$gen_sql .= ')';
		// new show only 
		$show_only_sql = ' (`showonly` = "" || `showonly` = "'.$gender.'" || `showonly` LIKE "'.$gender.',%" || `showonly` LIKE "%,'.$gender.'" || `showonly` LIKE "%,'.$gender.',%") AND ';
		if($ltype == "r")
		{
			$blocked = $this->my_usersmanager->getBlocksList($userid,'r');
			$query = $this->db->query('SELECT `viewed` FROM `app_profile_views` WHERE `userid` = "'.$userid.'" '.($blocked != "na" ? $blocked : '').' GROUP BY `viewed` ORDER BY `tsview` DESC');
			if($query->num_rows() > 0)
			{
				$viewed = $query->result();
				$users_data = array();
				//mail('xxxxxx@gmail.com','test','total: ' . count($viewed));
				foreach($viewed as $v)
				{
					$tmp = $this->my_usersmanager->getUserDataApp($userid,$v->viewed);
					if($tmp != "na")
					{
						$users_data[] = $tmp;
					}
				}
			}
		}
		else if($ltype == "e")
		{
			//remove after fix is uploaded 
			$query = $this->db->query('SELECT `id`,`nickname`,
			( 3959 * acos( cos( radians('.$ulat.') ) * cos( radians( `cur_lat` ) ) 
   			* cos( radians(`cur_lon`) - radians('.$ulon.')) + sin(radians('.$ulat.')) 
   			* sin( radians(`cur_lat`)))) AS distance 
			FROM `users` 
			WHERE  (`type` = "normal" || `type` = "vip" || `type` = "mobile" || `type` = "suspicious") AND 
			`id` != "'.$userid.'" AND 
			`isdel` != "1" AND 
			`utype` = "a" AND
			'.$match_age_sql.'
			'.$img_sql.'
			'.$gen_sql.' AND
			'.$show_only_sql.'
			`lastactivity` != 0
			'.($blocked != "na" ? $blocked : '').'
			ORDER BY mobile_online,`lastactivity` DESC '.$limit.';');
			$users = $query->result();
			$users_data = array();
			foreach($users as $u)
			{
				$users_data[] = $this->my_usersmanager->getUserDataApp($userid,$u->id);
			}
		}
		else if($ltype == "n")
		{
			$query = $this->db->query('SELECT `id`,`nickname`,
			( 3959 * acos( cos( radians('.$ulat.') ) * cos( radians( `cur_lat` ) ) 
   			* cos( radians(`cur_lon`) - radians('.$ulon.')) + sin(radians('.$ulat.')) 
   			* sin( radians(`cur_lat`)))) AS distance 
			FROM `users`
			WHERE  (`type` = "normal" || `type` = "vip" || `type` = "mobile" || `type` = "suspicious") AND 
			`id`    != "'.$userid.'" AND 
			`isdel` != "1" AND 
			`utype` = "a" AND
			`lastactivity` > "'.strtotime("-1 week").'" AND
			'.$match_age_sql.'
			'.$img_sql.'
			'.$gen_sql.' AND
			'.$show_only_sql.'
			`lastactivity` > 0
			'.($blocked != "na" ? $blocked : '').'
			ORDER BY distance ASC '.$limit.';');
			$users = $query->result();
			$users_data = array();
			foreach($users as $u)
			{
				$users_data[] = $this->my_usersmanager->getUserDataApp($userid,$u->id);
			}
		}
		else if($ltype == "o")
		{
			$query = $this->db->query('SELECT `id`,`nickname`,
			( 3959 * acos( cos( radians('.$ulat.') ) * cos( radians( `cur_lat` ) ) 
   			* cos( radians(`cur_lon`) - radians('.$ulon.')) + sin(radians('.$ulat.')) 
   			* sin( radians(`cur_lat`)))) AS distance 
			FROM `users`
			WHERE  (`type` = "normal" || `type` = "vip" || `type` = "mobile" || `type` = "suspicious") AND 
			`id` != "'.$userid.'" AND 
			isdel != "1" AND 
			`utype` = "a" AND
			'.$match_age_sql.'
			'.$img_sql.'
			'.$gen_sql.' AND
			'.$show_only_sql.'
			`mobile_online` = 1
			'.($blocked != "na" ? $blocked : '').'
			ORDER BY distance ASC '.$limit.';');
			$users = $query->result();
			$users_data = array();
			foreach($users as $u)
			{
				$users_data[] = $this->my_usersmanager->getUserDataApp($userid,$u->id);
			}
		}
		else
		{	
			$query = $this->db->query('SELECT id, nickname,
		( 3959 * acos( cos( radians('.($ulat != "" ? $ulat : '13.7500').') ) * cos( radians( cur_lat ) ) 
		* cos( radians(cur_lon) - radians('.($ulon != "" ? $ulon : '100.4667').')) + sin(radians('.($ulat != "" ? $ulat : '13.7500').')) 
		* sin( radians(cur_lat)))) AS distance 
		FROM users
		WHERE  (type = "normal" || type = "vip" || type = "mobile" || `type` = "suspicious") AND
		`id` != "'.$userid.'" AND 
		haspic = "1" AND
		isdel != "1" AND 
		utype = "a" AND
		'.$gen_sql.' AND
		'.$img_sql.'
		'.$show_only_sql.'
		'.$match_age_sql.'
		`lastactivity` > 0
		'.($blocked != "na" ? $blocked : '').'
		ORDER BY mobile_online,`lastactivity` DESC '.$limit.';');
		
			$users = $query->result();
			$users_data = array();
			foreach($users as $u)
			{
				$users_data[] = $this->my_usersmanager->getUserDataApp($userid,$u->id);
			}
		}
		//self::cleanData('getuserslist');
		$data = array();
		$data['users'] = $users_data;
		$this->output
		->set_content_type('application/json')
    	->set_output(json_encode($data));
	}
	
	function lonlattest($lata,$lona,$latb,$lonb)
	{
		$distance = $this->my_geomanager->getDistance($lata,$lona,$latb,$lonb);
		echo $distance;
	}
	
	function supportcode()
	{
		$userid = $this->security->xss_clean($_POST['userid']);
		// generate random support ticket code
		$code = time().'-tj'.$userid;
		// add to db as new ticket
		$data['scode'] = $code;
		$this->output
		->set_content_type('application/json')
    	->set_output(json_encode($data));
	}
	
	function startnew()
	{
		$uid = $_POST['userid'];
		//mail('xxxxxx@gmail.com','test','UPDATE `users` SET `isdel` = "1" WHERE `id` = "'.$uid.'"');
		$this->db->query('UPDATE `users` SET `isdel` = "0" WHERE `id` = "'.$uid.'"');
		$data = array('res'=>'true');
		$this->output
		->set_content_type('application/json')
    	->set_output(json_encode($data));
	}
	
	function droidfl()
	{
		$uid     = $_POST['userid'];
		$lat     = $_POST['lat'];
		$lon     = $_POST['lon'];
		$gender  = $_POST['gender'];
		$seeking = $_POST['seeking'];
		$gcm_regid = $_POST['gcm_regid'];
		// update user with info
		$query = $this->db->query('UPDATE `users` SET 
		`gender`   = "'.$gender.'",
		`seeking`  = "'.$seeking.'",
		`showonly` = "'.$seeking.'",
		`join_lat` = "'.$lat.'",
		`join_lon` = "'.$lon.'",
		`cur_lat`  = "'.$lat.'",
		`cur_lon`  = "'.$lon.'",
		`gcm_regid` = "'.$gcm_regid.'",
		`appfrun`  = "1" WHERE `id` = "'.$uid.'"');
		// get terms 
		$query = $this->db->query('SELECT * FROM `app_text` WHERE `name` = "terms"');
		$item = $query->row();
		$data['res'] = $item->text;
		$this->output
		->set_content_type('application/json')
    	->set_output(json_encode($data));
	}
	
	function appstart()
	{
		$device_id = $this->security->xss_clean($_POST['devid']);
		$lat = $this->security->xss_clean($_POST['lat']);
		$lon = $this->security->xss_clean($_POST['lon']);
		$ip = $this->security->xss_clean($_SERVER['REMOTE_ADDR']);
		if(isset($_POST['ispro']))
		{
			$ispro = $_POST['ispro'];
		}
		else
		{
			$ispro = "2";
		}
		if(isset($_POST['ver']))
		{
			$ver = $this->security->xss_clean($_POST['ver']);
		}
		else
		{
			$ver = 'na';
		}
		if(isset($_POST['os']))
		{
			$os = $this->security->xss_clean($_POST['os']);
		}
		else
		{
			$os = 'ios';
		}
		if(isset($_POST['lang']))
		{
			$lang = $this->security->xss_clean(strtolower($_POST['lang']));
		}
		else
		{
			$lang = "en";
		}
		$idDel = 0;
		// check for version
		$verck = "ok";
		if($ver != "na")
		{
			if($ispro == "1")
			{
				$query = $this->db->query('SELECT `value` FROM `settings` WHERE `id` = "10"');
				$row = $query->row();
				$cur_ver = $row->value;
				if($ver != $cur_ver)
				{
					$verck = "update";
				}
			}
			else
			{
				$query = $this->db->query('SELECT `value` FROM `settings` WHERE `id` = "11"');
				$row = $query->row();
				$cur_ver = $row->value;
				if($ver != $cur_ver)
				{
					$verck = "update";
				}
			}
		}
		// check for current device id
		$query = $this->db->query('SELECT `id`,`isdel`,`type` FROM `users` WHERE `device_id` = "'.$device_id.'"');
		if($query->num_rows() > 0)
		{
			// found
			$row = $query->row();
			$userid = $row->id;
			if($row->isdel == 1)
			{
				$isDel = 1;	
				$delid = $row->id;
			}
			if($row->type == 'banned')
			{
				$isBan = "y";
			}
			if($row->type == 'suspended')
			{
				$isSus = "y";
			}
		}
		else
		{
			// new user
			$user_info = "new";
			$sync_code = $this->my_stringmanager->getSyncCode($device_id);
			$query = $this->db->query('INSERT INTO `users` 
			(`id`,`status`,`type`,`verified`,`jtype`,`utype`,`device_id`,`sync_code`,`join_lat`,`join_lon`,`cur_lat`,`cur_lon`,`lang`,`os`,`ver`,`joinip`,`pro_install`) 
			VALUES 
			(NULL,"1","normal","1","Mobile - APP","a","'.$device_id.'","'.$sync_code.'","'.$lat.'","'.$lon.'","'.$lat.'","'.$lon.'","'.$lang.'","'.$os.'","'.$ver.'","'.$ip.'","'.$ispro.'")');
			$userid = $this->db->insert_id();
			$query = $this->db->query('INSERT INTO `profile_data`(`id`,`uid`,`pstatus`) VALUES (NULL,"'.$userid.'","2")');
			// set users nickname
			$query = $this->db->query('UPDATE `users` SET `nickname` = "TJ'.$userid.'",`url` = "tj'.$userid.'" WHERE `id` = "'.$userid.'"');
			// add to chat server
			$user_chat->id = $userid;
			$user_chat->nickname = 'TJ-'.$userid;
			$user_chat->email = 'tj-'.$userid.'@xxxxxx.com';
			$added = $this->my_chatmanager->addUser($user_chat);
			// add to userSnoop
			$userSnoop = $this->my_usersmanager->userSnoop($userid,$lat,$lon);
			// add welcome message
			$welcomemsg = $this->my_stringmanager->getAppText($userid,'welcome-message');
			$query = $this->db->query('INSERT INTO `adminPush` (`id`,`uid`,`msg`,`status`,`sent`) VALUES (NULL,"'.$userid.'","'.mysql_real_escape_string($welcomemsg).'","1","'.time().'")');
			if($added['user'] == 'failed' || $added['group'] == "failed")
			{
				//mail('xxxxxx@gmail.com','xxxxxx XMPP FAIL!!!','User: '.$added['user'].' Group:'.$added['group']);
			}
			else
			{
				//mail('xxxxxx@gmail.com','xxxxxx XMPP FAIL!!!','User: '.$added['user'].' Group:'.$added['group']);
			}
			//$added = $this->my_chatmanager->addToXmpp($userid,$user_chat->nickname,$user_chat->email);
			$to = 'xxxx@xxxx.com';
			$subject = 'New Member Join Via xxxxxx App!';
			$msg = 'A new user has joined using the mobile app!';
			mail($to,$subject,$msg);
		}
		// update current location
		if(isset($_POST['ispro']))
		{
			$query = $this->db->query('UPDATE `users` SET `ispro` = "'.$ispro.'" WHERE `id` = "'.$userid.'"');
		}
		$query = $this->db->query('UPDATE `users` SET `cur_lat` = "'.$lat.'", `cur_lon` = "'.$lon.'", `ver` = "'.$ver.'" WHERE `id` = "'.$userid.'"');
		$userSnoop = $this->my_usersmanager->userSnoop($userid,$lat,$lon);
		$udip = $this->my_usersmanager->updateIp($userid,$ip);
		$query = $this->db->query('SELECT `appfrun` FROM `users` WHERE `id` = "'.$userid.'"');
		$row = $query->row();
		$frun = $row->appfrun;
		$data = array();
		$data['frun'] = ($frun == "" || $frun == "0" ? 'n' : 'y');
		if($isDel == 1)
		{
			$data['userid'] = 'Deleted';
			$data['delid'] = $userid;
		}
		else if($isBan == "y")
		{
			$data['userid'] = 'Banned - Support # TJ'.$userid;
			$data['delid'] = 'Your account has been banned, please contact xxxxxx Support.';
		}
		else if($isSus == "y")
		{
			$data['userid'] = 'Suspended - Support # TJ'.$userid;
			$data['delid'] = 'Your account has been suspended, please contact xxxxxx Support.';
		}
		else
		{
			$data['userid'] = $userid;
		}
		if($userid == "18321" || $userid == "35249")
		{
			$msg = "AppStart Values: ";
			foreach($_POST as $key=>$value)
			{
				$msg .= $key . " : " . $value . "\n";
			}
			mail('xxxxxx@gmail.com','TJ Test AppStart',$msg);
		}
		
		// server.fwends.co.uk
		// 204.93.185.46
		// server.fwends.co.uk
		$data['app_settings'] = array('active_chat'=>3,'inactive_chat'=>30,'xmpps' => 'server.fwends.co.uk','xmppass'=>'X;9T*WeG8yHH',"upload_contacts"=>"".$this->my_usersmanager->getAppSettings(5)."",'rate_after'=>''.$this->my_usersmanager->getAppSettings(6).'', 'rate_every'=>''.$this->my_usersmanager->getAppSettings(7).'','verck'=>$verck);
		$msg = "appstart\n";
		//foreach($_POST as $key=>$value)
		//{
			//$msg .= $key . ":" . $value . "\n";
		//}
		//$msg .= 'UserId: ' . $userid;
		//mail('xxxxxx@gmail.com','AppStart Test',$msg);
		$this->output
		->set_content_type('application/json')
    	->set_output(json_encode($data));
	}
	
	function updateLastActive($userid)
	{
		$query = $this->db->query('UPDATE `users` SET `lastactivity` = "'.time().'", `mobile_online` = "1" WHERE `id` = "'.$userid.'"');
	}
	
	function appload()
	{
		$userid = $this->security->xss_clean($_POST['userid']);
		$lang = (@$_POST['lang'] != "" ? strtolower($this->security->xss_clean(strtolower($_POST['lang']))) : 'en');
		$ispro = (isset($_POST['ispro']) ? $_POST['ispro'] : "0");
		$msg = "appload\n";
		$la = self::updateLastActive($userid);
		// added for google push
		// added for google only
		/*
		if($userid == "18321" || $userid == "35249")
		{
			$msg = "AppLoad Values: ";
			foreach($_POST as $key=>$value)
			{
				$msg .= $key . " : " . $value . "\n";
			}
			mail('xxxxxx@gmail.com','TJ Test Values',$msg);
		}
		*/
		$gcm_regid = (@$_POST['gcm_regid'] != "" ? $this->security->xss_clean($_POST['gcm_regid']) : 'na');
		if($gcm_regid != "na")
		{
			$query = $this->db->query('UPDATE `users` SET `gcm_regid` = "'.$gcm_regid.'" WHERE `id` = "'.$userid.'"');
			mail('xxxxxx@gmail.com','GCM TEST','USERID: ' . $userid . ' gcm_regid: ' . $gcm_regid);
			return true;
		}
		// get user info
		$test = @$_POST['test'];
		$la = self::updateLastActive($userid);
		$query = $this->db->query('UPDATE `users` SET `lang` = "'.$lang.'", `ispro` = "'.$ispro.'" WHERE `id` = "'.$userid.'"');
		$query = $this->db->query('SELECT * FROM `users` WHERE `id` = "'.$userid.'"');
		$user_data = $query->row();
		$query = $this->db->query('SELECT * FROM `profile_data` WHERE `uid` = "'.$userid.'"');
		$profile_data = $query->row();
		// data to send
		$tmp['userid']   = $user_data->id;
		$tmp['nickname'] = $user_data->nickname;
		$tmp['seeking']  = $this->my_usersmanager->getGenderValues($user_data->seeking,$userid);
		$tmp['relationship'] = $this->my_usersmanager->getPfieldValue($profile_data->user_relationship,$userid);
		$tmp['looking']  = $this->my_usersmanager->getPfieldValue($profile_data->match_relationship,$userid);
		$tmp['gender']   = $this->my_usersmanager->getPfieldValue($user_data->gender,$userid);
		$tmp['dob']      = $user_data->dob;
		$tmp['age']      = ($this->my_usersmanager->birthday($user_data->dob) > 99 ? 18 : $this->my_usersmanager->birthday($user_data->dob));
		$tmp['height']   = $this->my_usersmanager->getPfieldValue($profile_data->user_height,$userid);
		$tmp['weight']   = $this->my_usersmanager->getPfieldValue($profile_data->user_weight,$userid);
		$tmp['facebook'] = $profile_data->facebook;
		$tmp['twitter']  = $profile_data->twitter;
		$tmp['linkedin'] = $profile_data->linkedin;
		$tmp['bio']      = $profile_data->bio;
		$tmp['msgcnt']   = rand(0,5);
		$tmp['distance'] = "0";
		$tmp['favstatus'] = '0';
		$tmp['headline'] = $user_data->headline;
		$tmp['ethnicity'] = $this->my_usersmanager->getPfieldValue($profile_data->user_ethn,$userid);
		$tmp['match_age'] = $profile_data->match_age;
		$tmp['thumb'] = $this->my_usersmanager->getAppPicThumb($user_data->id);
		$tmp['pic'] = $this->my_usersmanager->getAppPic($user_data->id);
		$tmp['online'] = $this->my_usersmanager->getOnlineStatus($user_data->id);
		$tmp['show_dis'] = $user_data->show_dis;
		$tmp['dislim'] = $user_data->dislim;
		$tmp['showonly'] = $this->my_usersmanager->getGenderValues($user_data->showonly,$userid);
		$tmp['diset'] = $user_data->diset;
		$tmp['simg'] = $user_data->simg;
		$user_info = $tmp;
		// match age sql
		$match_age_sql = '';
		if($profile_data->match_age != "")
		{
			$pts = explode('-',$profile_data->match_age);
			$match_age_sql = 'AND (`age` <= "'.($pts[1] == 0 ? 99 : $pts[1]).'" AND `age` >= "'.($pts[0] == 0 ? 18 : $pts[0]).'") ';
		}
		else
		{
			$match_age_sql = 'AND (`age` <= "99" AND `age` >= "18") ';
		}
		// new multi gender
		$gen_sql = '(';
		$genders = explode(',',$user_data->seeking);
		$x=1;
		foreach($genders as $g)
		{
			$gen_sql .= '`gender` = "'.$g.'" ';
			if(count($genders) > $x)
			{
				$gen_sql .= ' || ';
			}
			$x++;
		}
		$gen_sql .= ')';
		// show only sql
		//$show_only_sql = ' (`showonly` = "" || `showonly` = "'.$user_data->gender.'" || `showonly` LIKE "'.$user_data->gender.',%" || `showonly` LIKE "%,'.$user_data->gender.'" || `showonly` LIKE "%,'.$user_data->gender.',%") AND ';
		/*
		if($userid == "26175")
		{
			mail('xxxxxx@gmail.com','test user','SELECT `id`, `nickname`,
		( 3959 * acos( cos( radians('.($user_data->cur_lat == "" || $user_data->cur_lat == "0" ? '13.7500' : $user_data->cur_lat ).') ) * cos( radians( `cur_lat` ) ) 
		* cos( radians(`cur_lon`) - radians('.($user_data->cur_lon == "" || $user_data->cur_lon == "0" ? '100.4667' : $user_data->cur_lon).')) + sin(radians('.($user_data->cur_lat == "" || $user_data->cur_lat == "0" ? '13.7500' : $user_data->cur_lat).')) 
		* sin( radians(`cur_lat`)))) AS distance
		FROM `users` 
		WHERE  (`type` = "normal" || `type` = "vip" || `type` = "mobile" || `type` = "synced") AND
		`id` != "'.$userid.'" AND 
		`haspic` = "1" AND
		`isdel` != "1" AND 
		`utype` = "a" AND 
		`lastactivity` > "'.strtotime("-1 week").'" AND 
		'.$gen_sql.' AND
		'.$show_only_sql.'
		'.$match_age_sql.' 
		ORDER BY `mobile_online`,`lastactivity` DESC LIMIT 50;');
		}
		*/
		$blocked = $this->my_usersmanager->getBlocksList($userid,"0");
		$query = $this->db->query('SELECT `id`, `nickname`,
		( 3959 * acos( cos( radians('.($user_data->cur_lat == "" || $user_data->cur_lat == "0" ? '13.7500' : $user_data->cur_lat ).') ) * cos( radians( `cur_lat` ) ) 
		* cos( radians(`cur_lon`) - radians('.($user_data->cur_lon == "" || $user_data->cur_lon == "0" ? '100.4667' : $user_data->cur_lon).')) + sin(radians('.($user_data->cur_lat == "" || $user_data->cur_lat == "0" ? '13.7500' : $user_data->cur_lat).')) 
		* sin( radians(`cur_lat`)))) AS distance
		FROM `users` 
		WHERE  (`type` = "normal" || `type` = "vip" || `type` = "mobile" || `type` = "synced" || `type` = "suspicious") AND
		`id` != "'.$userid.'" AND 
		`haspic` = "1" AND
		`isdel` != "1" AND 
		`utype` = "a" AND 
		`lastactivity` > "'.strtotime("-1 week").'" AND 
		'.$gen_sql.'
		'.$match_age_sql.' 
		'.($blocked != "na" ? $blocked : '').'
		ORDER BY `mobile_online`,`lastactivity` DESC LIMIT 50;');
		$users = $query->result();
		$users_data = array();
		foreach($users as $u)
		{
			$users_data[] = $this->my_usersmanager->getUserDataApp($userid,$u->id);
		}
		$data['user_info'] = $user_info;
		$data['near_friends'] = $users_data;
		//if($test == "ok")
		//{
			//print_r($users_data);
		//}
		//else
		//{
			$this->output
			->set_content_type('application/json')
    		->set_output(json_encode($data));
		//}
	}
	
	function favslist()
	{
		$userid = $this->security->xss_clean($_POST['userid']);
		$blocks = $this->my_usersmanager->getBlocksList($userid,'f');
		$query = $this->db->query('SELECT `owner` FROM `favs` WHERE `fav` = "'.$userid.'"');
		if($query->num_rows() > 0)
		{
			$users = $query->result();
			$users_data = array();
			foreach($users as $u)
			{
				$users_data[] = $this->my_usersmanager->getUserDataApp($userid,$u->owner);
			}
			$favs = $users_data;
		}
		else
		{
			$favs = "na";
		}
		$data['favs'] = $favs;
		$this->output
		->set_content_type('application/json')
    	->set_output(json_encode($data));
	}
	
	function apploadDroid()
	{
		$userid = $this->security->xss_clean($_POST['userid']);
		$msg = "appload\n";
		$la = self::updateLastActive($userid);
		
		// get user info
		$test = @$_POST['test'];
		$la = self::updateLastActive($userid);
		$query = $this->db->query('SELECT * FROM `users` WHERE `id` = "'.$userid.'"');
		$user_data = $query->row();
		$query = $this->db->query('SELECT * FROM `profile_data` WHERE `uid` = "'.$userid.'"');
		$profile_data = $query->row();
		// data to send
		$tmp['userid']   = $user_data->id;
		$tmp['nickname'] = $user_data->nickname;
		$tmp['seeking']  = $this->my_usersmanager->getPfieldValue($user_data->seeking);
		$tmp['relationship'] = $this->my_usersmanager->getPfieldValue($profile_data->user_relationship);
		$tmp['looking']  = $this->my_usersmanager->getPfieldValue($profile_data->match_relationship);
		$tmp['gender']   = $this->my_usersmanager->getPfieldValue($user_data->gender);
		$tmp['dob']      = $user_data->dob;
		$tmp['age']      = ($this->my_usersmanager->birthday($user_data->dob) > 99 ? 18 : $this->my_usersmanager->birthday($user_data->dob));
		$tmp['height']   = $this->my_usersmanager->getPfieldValue($profile_data->user_height);
		$tmp['weight']   = $this->my_usersmanager->getPfieldValue($profile_data->user_weight);
		$tmp['facebook'] = $profile_data->facebook;
		$tmp['twitter']  = $profile_data->twitter;
		$tmp['linkedin'] = $profile_data->linkedin;
		$tmp['bio']      = $profile_data->bio;
		$tmp['msgcnt']   = rand(0,5);
		$tmp['distance'] = "0";
		$tmp['favstatus'] = '0';
		$tmp['headline'] = $user_data->headline;
		$tmp['ethnicity'] = $this->my_usersmanager->getPfieldValue($profile_data->user_ethn);
		$tmp['match_age'] = $profile_data->match_age;
		$tmp['pic'] = $this->my_usersmanager->getAppPic($user_data->id);
		$tmp['online'] = $this->my_usersmanager->getOnlineStatus($user_data->id);
		$user_info = $tmp;
		// match age sql
		$match_age_sql = '';
		if($profile_data->match_age != "")
		{
			$pts = explode('-',$profile_data->match_age);
			$match_age_sql = ' (`age` <= "'.($pts[1] == 0 ? 99 : $pts[1]).'" AND `age` >= "'.($pts[0] == 0 ? 18 : $pts[0]).'") ';
		}
		else
		{
			$match_age_sql = ' (`age` <= "99" AND `age` >= "18") ';
		}
		$query = $this->db->query('SELECT `id`, `nickname`,
		( 3959 * acos( cos( radians('.($user_data->cur_lat != "" ? $user_data->cur_lat : '13.7500').') ) * cos( radians( `cur_lat` ) ) 
		* cos( radians(`cur_lon`) - radians('.($user_data->cur_lon != "" ? $user_data->cur_lon : '100.4667').')) + sin(radians('.($user_data->cur_lat != "" ? $user_data->cur_lat : '13.7500').')) 
		* sin( radians(`cur_lat`)))) AS distance
		FROM `users` 
		WHERE  (`type` = "normal" || `type` = "vip" || `type` = "mobile" || `type` = "synced" || `type` = "suspicious") AND
		`haspic` = "1" AND
		`isdel` != "1" AND 
		`utype` = "a" AND 
		'.($user_data->seeking > 0 ? '`gender` = "'.$user_data->seeking.'" AND' : '').'
		'.$match_age_sql.' 
		ORDER BY `mobile_online`,`lastactivity` DESC LIMIT 200;');
		$users = $query->result();
		$users_data = array();
		foreach($users as $u)
		{
			$users_data[] = $this->my_usersmanager->getUserDataApp($userid,$u->id);
		}
		$data['user_info'] = $user_info;
		$data['near_friends'] = $users_data;
		if($test == "ok")
		{
			print_r($users_data);
		}
		else
		{
			$this->output
			->set_content_type('application/json')
    		->set_output(json_encode($data));
		}
	}
	
	function getFavs()
	{
		self::cleanData('getfavs',$_SERVER['REQUEST_URI']);
		$userid = $this->security->xss_clean($_POST['userid']);
		$la = self::updateLastActive($userid);
		$query = $this->db->query('SELECT `cur_lat`,`cur_lon` FROM `users` WHERE `id` = "'.$userid.'"');
		$user = $query->row();
		$query = $this->db->query('SELECT `fav` FROM `favs` WHERE `owner` = "'.$userid.'"');
		if($query->num_rows() > 0)
		{
			$flist = $query->result();
			$favs = array();
			foreach($flist as $f)
			{
				$tmp = $this->my_usersmanager->getUserDataApp($userid,$f->fav);
				if($tmp != "na")
				{
					$favs[] = $tmp;
				}
			}
		}
		else
		{
			$favs['res'] = "na";
		}
		self::cleanData('getfavs');
		$this->output
		->set_content_type('application/json')
    	->set_output(json_encode($favs));
	}
	
	function updateLocation()
	{
		$id = $this->security->xss_clean($_POST['userid']);
		$lat = $this->security->xss_clean($_POST['lat']);
		$lon = $this->security->xss_clean($_POST['lon']);
		$userSnoop = $this->my_usersmanager->userSnoop($id,$lat,$lon);
		$query = $this->db->query('UPDATE `users` SET `cur_lat` = "'.$lat.'", `cur_lon` = "'.$lon.'" WHERE `id` = "'.$id.'"');
		if($this->db->affected_rows() > 0)
		{
			$data['res'] = 'true';
		}
		else
		{
			$data['res'] = 'false';
		}
		self::cleanData('updatelocation');
		$this->output
		->set_content_type('application/json')
    	->set_output(json_encode($data));
	}
	
	function getProfiles()
	{
		$userid = $this->security->xss_clean($_POST['userid']);
		$ids = $this->security->xss_clean($_POST['userids']);
		$uids = explode(',',$ids);
		$users_data = array();
		foreach($uids as $id)
		{
			//echo $id.'<br />';
			$users_data[] = $this->my_usersmanager->getUserDataApp($userid,$id);
		}
		$data['users'] = $users_data;
		$this->output
		->set_content_type('application/json')
    	->set_output(json_encode($data));
	}
	
	function getUserProfile()
	{
		self::cleanData('getuserprofile',$_SERVER['REQUEST_URI']);
		$id = $this->security->xss_clean($_POST['userid']);
		$uid = $this->security->xss_clean($_POST['userid2']);
		$la = self::updateLastActive($userid);
		$query = $this->db->query('SELECT * FROM `users` WHERE `id` = "'.$uid.'"');
		$user_data = $query->row();
		$query = $this->db->query('SELECT * FROM `profile_data` WHERE `uid` = "'.$uid.'"');
		$profile_data = $query->row();
		// data to send
		$tmp['userid']   = $user_data->id;
		$tmp['nickname'] = $user_data->nickname;
		$tmp['seeking']  = $this->my_usersmanager->getGenderValues($user_data->seeking,$id);
		$tmp['relationship'] = $this->my_usersmanager->getPfieldValue($profile_data->user_relationship,$id);
		$tmp['looking']  = $this->my_usersmanager->getPfieldValue($profile_data->match_relationship,$id);
		$tmp['gender']   = $this->my_usersmanager->getPfieldValue($user_data->gender,$id);
		$tmp['dob']      = $user_data->dob;
		$tmp['age']      = ($this->my_usersmanager->birthday($user_data->dob) > 99 ? 18 : $this->my_usersmanager->birthday($user_data->dob));
		$tmp['height']   = $this->my_usersmanager->getPfieldValue($profile_data->user_height,$id);
		$tmp['weight']   = $this->my_usersmanager->getPfieldValue($profile_data->user_weight,$id);
		$tmp['facebook'] = $profile_data->facebook;
		$tmp['twitter']  = $profile_data->twitter;
		$tmp['linkedin'] = $profile_data->linkedin;
		$tmp['bio']      = $profile_data->bio;
		$tmp['msgcnt']   = rand(0,5);
		$tmp['distance'] = "0";
		$tmp['favstatus'] = '0';
		$tmp['headline'] = $user_data->headline;
		$tmp['ethnicity'] = $this->my_usersmanager->getPfieldValue($profile_data->user_ethn,$id);
		$tmp['match_age'] = $profile_data->match_age;
		if($id == $uid)
		{
			$tmp['thumb'] = $this->my_usersmanager->getAppPicThumb($id);
			$tmp['pic'] = $this->my_usersmanager->getAppPic($id);
		}
		else
		{
			$tmp['thumb'] = $this->my_usersmanager->getAppPicThumb($user_data->id);
			$tmp['pic'] = $this->my_usersmanager->getAppPic($user_data->id);
		}
		$tmp['online'] = $this->my_usersmanager->getOnlineStatus($user_data->id);
		$tmp['show_dis'] = $user_data->show_dis;
		$tmp['dislim'] = $user_data->dislim;
		$tmp['showonly'] = $this->my_usersmanager->getGenderValues($user_data->showonly,$id);
		$tmp['diset'] = $user_data->diset;
		$user_info = $tmp;
		$data['user_info'] = $user_info;
		$this->output
		->set_content_type('application/json')
    	->set_output(json_encode($data));
	}
	
	function makeFavorite()
	{
		self::cleanData('makefavorite',$_SERVER['REQUEST_URI']);
		$owner = $this->security->xss_clean($_POST['userid']);
		$fav = $this->security->xss_clean($_POST['userid2']);
		$la = self::updateLastActive($owner);
		// check for pro and if not pro check limit
		$query = $this->db->query('SELECT `ispro` FROM `users` WHERE `id` = "'.$owner.'"');
		$row = $query->row();
		$ispro = $row->ispro;
		if($ispro != "1")
		{
			$query = $this->db->query('SELECT `id` FROM `favs` WHERE `owner` = "'.$owner.'"');
			$ttl = $query->num_rows();
		}
		if($ispro != "1" && $ttl == self::_FAVLIM)
		{
			$msg = "false";
		}
		else
		{
			$query = $this->db->query('SELECT `id` FROM `favs` WHERE `owner` = "'.$owner.'" AND `fav` = "'.$fav.'"');
			if($query->num_rows() > 0)
			{
				//$msg = 'This user is already in your favorites!';
				$msg = $this->my_stringmanager->getAppText($owner,'fav-already');
			}
			else
			{
				$query = $this->db->query('INSERT INTO `favs` (`id`,`owner`,`fav`) VALUES (NULL,"'.$owner.'","'.$fav.'")');
				//$msg = 'This user has been added to your favorites!';
				$msg = $this->my_stringmanager->getAppText($owner,'fav-added');
				// send push
				$push = self::sendPushNotificationsApple($fav,5,$this->my_usersmanager->getNickname($owner).' has added you as a favoriate.','na');
			}
		}
		$data = array('msg'=>$msg);
		$this->output
		->set_content_type('application/json')
    	->set_output(json_encode($data));
	}
	
	function getblocklist()
	{
		$userid = $this->security->xss_clean($_POST['userid']);
		$query = $this->db->query('SELECT `blocked` FROM `blocks` WHERE `blockedby` = "'.$userid.'"');
		if($query->num_rows() > 0)
		{
			$res = $query->result();
			$users = array();
			foreach($res as $u)
			{
				$users[] = $this->my_usersmanager->getUserDataApp($userid,$u->blocked);
			}
		}
		else
		{
			$users = "You currently have no blocked users.";
			$users = $this->my_stringmanager->getAppText($userid,'blocked-no');
		}
		$data = array('users'=>$users);
		self::cleanData('blockfriend',$_SERVER['REQUEST_URI']);
		$this->output
		->set_content_type('application/json')
    	->set_output(json_encode($data));
	}
	
	function blockFriend()
	{
		$blocker = $this->security->xss_clean($_POST['userid']);
		$blocked = $this->security->xss_clean($_POST['userid2']);
		$unblock = ($this->security->xss_clean(@$_POST['rem']) == "1" ? "1" : "0");
		$query = $this->db->query('SELECT `ispro` FROM `users` WHERE `id` = "'.$blocker.'"');
		$row = $query->row();
		$ispro = $row->ispro;
		if($unblock == "1")
		{
			$query = $this->db->query('DELETE FROM `blocks` WHERE `blocked` = "'.$blocked.'" AND `blockedby` = "'.$blocker.'"');
			//$msg = "This user is no longer blocked.";
			$msg = $this->my_stringmanager->getAppText($blocker,'blocked-remove');
		}
		else
		{
			if($ispro != "1")
			{
				$query = $this->db->query('SELECT `id` FROM `blocks` WHERE `blockedby` = "'.$blocker.'"');
				$ttl = $query->num_rows();
			}
			if($ispro != "1" && $ttl == self::_BLOLIM)
			{
				$msg = "false";
			}
			else
			{
				$query = $this->db->query('INSERT INTO `blocks` (`id`,`blocked`,`blockedby`) VALUES (NULL,"'.$blocked.'","'.$blocker.'")');
				//$msg = "The user has now been blocked.";
				$msg = $this->my_stringmanager->getAppText($blocker,'blocked-user');
			}
		}
		$data = array('msg'=>$msg);
		self::cleanData('blockfriend',$_SERVER['REQUEST_URI']);
		$this->output
		->set_content_type('application/json')
    	->set_output(json_encode($data));
	}
	
	function unfav()
	{
		$owner = $this->security->xss_clean($_POST['userid']);
		$fav = $this->security->xss_clean($_POST['userid2']);
		$query = $this->db->query('DELETE FROM `favs` WHERE `owner` = "'.$owner.'" AND `fav` = "'.$fav.'"');
		//$msg = 'The user has been removed from your favorites.';
		$msg = $this->my_stringmanager->getAppText($owner,'fav-remove');
		self::cleanData('unfav',$_SERVER['REQUEST_URI']);
		$data = array('msg'=>$msg);
		$this->output
		->set_content_type('application/json')
    	->set_output(json_encode($data));
	}
	
	function loadtextDroid()
	{
		$name = $this->security->xss_clean($_POST['name']);
		$query = $this->db->query('SELECT * FROM `app_text` WHERE `name` = "'.$name.'"');
		$item = $query->row();
		$tmp = array
		(
			'subject'=>$item->subject,
			'text' =>strip_tags(str_replace("<br>","\n",$item->text))
		);
		
		$this->output
		->set_content_type('application/json')
    	->set_output(json_encode($tmp));
	}
	
	function setstartinfo()
	{
		$gender = $this->security->xss_clean($_POST['gender']);
		$seeking = $this->security->xss_clean($_POST['seeking']);
		$userid = $this->security->xss_clean($_POST['userid']);
		$query = $this->db->query('UPDATE `users` SET `gender` = "'.$gender.'", `seeking` = "'.$seeking.'",`showonly` = "17,18,19", `appfrun` = "y" WHERE `id` = "'.$userid.'"');
		$tmp = array('res'=>'ok');
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($tmp));
	}
	
	function loadtext()
	{
		$name = $this->security->xss_clean($_POST['name']);
		$query = $this->db->query('SELECT * FROM `app_text` WHERE `name` = "'.$name.'"');
		if($query->num_rows() > 0)
		{
			$item = $query->row();
			$tmp = array
			(
				'subject'=>$item->subject,
				'text' =>$item->text
			);
		}
		else
		{
			echo "no sql result";
			exit;
		}
		echo $tmp['subject'].'|'.$tmp['text'];
	}
	
	function report()
	{
		//self::cleanData('report',$_SERVER['REQUEST_URI']);
		$reporter = $this->security->xss_clean($_POST['userid']);
		$reported = $this->security->xss_clean($_POST['userid2']);
		$rep_msg = $this->security->xss_clean($_POST['message']);
		//mail('xxxxxx@gmail.com','xxxxxx User-Reported!','Reproter: '.$reporter.' reported: '. $reported.' reason: '. $rep_msg);
		$query = $this->db->query('INSERT INTO `reported` (`id`,`reported`,`reporter`,`reason`,`status`) 
		VALUES 
		(NULL,"'.$reported.'","'.$reporter.'","'.mysql_real_escape_string($rep_msg).'","1")');
		if($this->db->insert_id() > 0)
		{
			//$msg = 'The user has been reported.';
			$msg = $this->my_stringmanager->getAppText($reporter,'reported');
		}
		else
		{
			//$msg = "The user has not been reported, please try again.";
			$msg = $this->my_stringmanager->getAppText($reporter,'reported-fail');
		}
		$data = array('msg'=>$msg);
		//self::cleanData('report');
		$this->output
		->set_content_type('application/json')
    	->set_output(json_encode($data));
	}
	
	function mailtest()
	{
		//$test = self::sendUserMail('xxxxxx@gmail.com','sync_code','23452342');
		print_r($test);
	}
	
	function sendUserMail($to,$type,$code=0)
	{
		$email_1 = 'no-reply@xxxxxx.com';
		$email_2 = 'no-reply@xxxxxx.com';
		$email_3 = 'no-reply@xxxxxx.com';
		$email_4 = 'no-reply@xxxxxx.com';
		$day_id = 0;
		// check current count
		$cdate = date('Y-m-d',time());
		$query = $this->db->query('SELECT `id`,`cnt` FROM `sync_mails` WHERE `day` = "'.$cdate.'"');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$day_id = $row->id;
			$cur_cnt = $row->cnt;
			if($cur_cnt >= 0 && $cur_cnt <= 450)
			{
				$from_email = $email_1;
			}
			else if($cur_cnt >= 451 && $cur_cnt <= 950)
			{
				$from_email = $email_2;
			}
			else if($cur_cnt >= 951 && $cur_cnt <= 1450)
			{
				$from_email = $email_3;
			}
			else
			{
				$from_email = $email_4;
			}
		}
		else
		{
			// new day add row
			$query = $this->db->query('INSERT INTO `sync_mails` (`id`,`day`,`cnt`) VALUES (NULL,"'.$cdate.'","0")');
			$day_id = $this->db->insert_id();
			$cur_cnt = 0;
			$from_email = $email_1;
		}
		$config = Array(
			'protocol'  => 'smtp',
			'smtp_host' => 'ssl://smtp.googlemail.com',
			'smtp_port' => 25,
			'smtp_user' => $from_email,
			'smtp_pass' => 'ilovexxxx',
			'mailtype'  => 'html', 
			'charset'   => 'iso-8859-1'
		);
		$this->load->library('email', $config);
		$this->email->set_mailtype("html");
		$this->email->set_newline("\r\n");
		// Set to, from, message, etc.
		$this->email->to($to); 
		if($type == 'test')
		{
			$this->email->from($from_email, 'xxxxxx test');
			$this->email->subject('Email Test');
			$msg = "<html>
			<head>
			<title>Test</title>
			</head>
			<body>
			<div align=\"center\">Test for email</div>
			</body>
			</html>";
		}
		else if($type == "sync_code")
		{
			$this->email->from($from_email, 'xxxxxx Sync Code');
			$this->email->subject('xxxxxx Sync Code: '.$code);
			$msg = '<html>
			<head>
			<title>xxxxxx</title>
			</head>
			<body>
			<table width="300" border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
			<td><img src="http://www.xxxxxx.com/images/mail_logo.png" width="114" height="114" alt="xxxxxx" title="xxxxxx" align="absmiddle" /><span style="font-size:36px; font-weight:bold; color:#CCC;">xxxxxx</span></td>
			</tr>
			<tr>
			<td><p>Copy and paste this sync code on the app!</p>
			<p align="center"><span style="font-size:24px; font-weight:bold; color:#000;">'.$code.'</span></p></td>
			</tr>
			<tr>
			<td><p>Thank you,<br>
			xxxxxx Support
			</p></td>
			</tr>
			</table>
			</body>
			</html>';
		}
		$this->email->message($msg);  
		$result = $this->email->send();
		if($result == "1")
		{
			// update cnt
			$query = $this->db->query('UPDATE `sync_mails` SET `cnt` = cnt + 1 WHERE `id` = "'.$day_id.'"');
		}
		return $result;
	}
	
	/*
	function agefix()
	{
		$query = $this->db->query('SELECT `id`,`dob`,`age` FROM `users` WHERE `age` = ""');
		$users = $query->result();
		foreach($users as $u)
		{
			$age = $age = $this->my_usersmanager->birthday($u->dob);
			$query = $this->db->query('UPDATE `users` SET `age` = "'.$age.'" WHERE `id` = "'.$u->id.'"');
			echo 'user: '.$u->id . ' has been added: '.$age.'<br />';
		}
	}
	*/
	function statuscron()
	{
		$lmt_time = strtotime('-5 hours');
		$query = $this->db->query('UPDATE `users` SET `mobile_online` = "2" WHERE `lastactivity` < "'.$lmt_time.'"');
		//echo 'Completed: ' . $this->db->affected_rows() . ' users set offline';
		//mail('xxxxxx@gmail.com','test-cron','Status Cron Ran');
	}
	
	function highest()
	{
		$msg = '';
		// get active last hour
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `lastactivity` > "'.strtotime('-1 Hour').'"');
		$total_active_girls = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `lastactivity` > "'.strtotime('-1 Hour').'"');
		$total_active_guys = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `lastactivity` > "'.strtotime('-1 Hour').'"');
		$total_active_ladyboys = $query->num_rows();
		// get highest ever free
		$query = $this->db->query('SELECT `value` FROM `settings` WHERE `id` = "3"');
		$row = $query->row();
		$ttl = ($total_active_girls + $total_active_guys + $total_active_ladyboys);
		$msg .= 'Total Active Free Hour: ' . $ttl . ' Highest: ' . $row->value."\n";
		if($ttl > $row->value)
		{
			$query = $this->db->query('UPDATE `settings` SET `value` = "'.$ttl.'" WHERE `id` = "3"');
			$msg .= "Active Free Hour Changed\n";
		}
		// get active last hour pro
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `lastactivity` > "'.strtotime('-1 Hour').'"');
		$total_active_girls = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `lastactivity` > "'.strtotime('-1 Hour').'"');
		$total_active_guys = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `lastactivity` > "'.strtotime('-1 Hour').'"');
		$total_active_ladyboys = $query->num_rows();
		
		// get highest ever pro
		$query = $this->db->query('SELECT `value` FROM `settings` WHERE `id` = "4"');
		$row = $query->row();
		$ttl = ($total_active_girls + $total_active_guys + $total_active_ladyboys);
		$msg .= 'Total Active Pro Hour: ' . $ttl . ' Highest: ' . $row->value."\n";
		if($ttl > $row->value)
		{
			$query = $this->db->query('UPDATE `settings` SET `value` = "'.$ttl.'" WHERE `id` = "4"');
			$msg .= "Active Pro Hour Changed\n";
		}
		
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `lastactivity` > "'.strtotime('-24 Hours').'"');
		$total_active_girls = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `lastactivity` > "'.strtotime('-24 Hours').'"');
		$total_active_guys = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `lastactivity` > "'.strtotime('-24 Hours').'"');
		$total_active_ladyboys = $query->num_rows();
		$ttl = $total_active_girls + $total_active_guys + $total_active_ladyboys;
		$query = $this->db->query('SELECT `value` FROM `settings` WHERE `id` = "1"');
		$row = $query->row();
		$msg .= 'Total Active Free 24: ' . $ttl . ' Highest: ' . $row->value."\n";
		if($ttl > $row->value)
		{
			$query = $this->db->query('UPDATE `settings` SET `value` = "'.$ttl.'" WHERE `id` = "1"');
			$msg .= "Active Free 24 Changed\n";
		}
		// get active 24 pro
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `lastactivity` > "'.strtotime('-24 Hours').'"');
		$total_active_girls = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `lastactivity` > "'.strtotime('-24 Hours').'"');
		$total_active_guys = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `lastactivity` > "'.strtotime('-24 Hours').'"');
		$total_active_ladyboys = $query->num_rows();
		$ttl = $total_active_girls + $total_active_guys + $total_active_ladyboys;
		$query = $this->db->query('SELECT `value` FROM `settings` WHERE `id` = "2"');
		$row = $query->row();
		$msg .= 'Total Active Pro 24: ' . $ttl . ' Highest: ' . $row->value."\n";
		if($ttl > $row->value)
		{
			$query = $this->db->query('UPDATE `settings` SET `value` = "'.$ttl.'" WHERE `id` = "2"');
			$msg .= "Active Pro 24 Changed\n";
		}
		//mail('xxxxxx@icloud.com','Stats xxxxxx',$msg);
	}
	
	function blockstest()
	{
		// get blocks
		$blocked = '';
		$blocks = array();
		$query = $this->db->query('SELECT `blocked` FROM `blocks` WHERE `blockedby` = "14665"');
		if($query->num_rows() > 0)
		{
			//$blocks = array();
			$res = $query->result();
			foreach($res as $r)
			{
				$blocks[] = $r->blocked;
			}
		}
		$query = $this->db->query('SELECT `blockedby` FROM `blocks` WHERE `blocked` = "14665"');
		if($query->num_rows() > 0)
		{
			$res = $query->result();
			foreach($res as $r)
			{
				$blocks[] = $r->blockedby;
			}
		}
		if(count($blocks) > 1)
		{
			$blocked = 'AND `id` NOT IN ('.implode(',',$blocks).') ';
		}
		echo 'Blocked Id\'s<br /><br />';
		print_r($blocks);
		echo '<br /><br /><br />';
		$query = $this->db->query('SELECT `id`, `nickname`,
		( 3959 * acos( cos( radians(12.90700563816066) ) * cos( radians( `cur_lat` ) ) 
		* cos( radians(`cur_lon`) - radians(100.8662102423321)) + sin(radians(12.90700563816066)) 
		* sin( radians(`cur_lat`)))) AS distance
		FROM `users` 
		WHERE  (`type` = "normal" || `type` = "vip" || `type` = "mobile" || `type` = "synced" || `type` = "suspicious") AND
		`id` != "1" AND 
		`haspic` = "1" AND
		`isdel` != "1" AND 
		`utype` = "a" AND 
		`lastactivity` > "1402201853" AND 
		(`gender` = "17" ) 
		 '.$blocked.'
		ORDER BY `mobile_online`,`lastactivity` DESC;');
		$res = $query->result();
		print_r($res);
	}
}

/* End of file mapps.php */
/* Location: ./application/controllers/mapps.php */