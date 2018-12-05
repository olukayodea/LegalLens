<?php
	class usersControl extends common {
		function logVisit() {
			$browserDetector = new detectBrowser;
			$data['browser_number'] = $browserDetector->browser_detection('browser_number');
			$data['browser_name'] = $browserDetector->browser_detection('browser_name');
			$data['os'] = $browserDetector->browser_detection('os');
			$data['os_number'] = $browserDetector->browser_detection('os_number');
			$data['address'] = $_SERVER['REMOTE_ADDR'];
			$data['loc_city'] = $_SESSION['location_data']['loc_city'];
			$data['loc_region'] = $_SESSION['location_data']['loc_region'];
			$data['loc_country'] = $_SESSION['location_data']['loc_country'];
			$data['loc_continent'] = $_SESSION['location_data']['loc_continent'];
			$data['loc_lat'] = $_SESSION['location_data']['loc_lat'];
			$data['loc_long'] = $_SESSION['location_data']['loc_long'];
			
			return $data;
		}
		
		function logVisitMobile() {
			$data['browser_number'] = "";
			$data['browser_name'] = "Mobile App";
			$data['os'] = "Android";
			$data['os_number'] = "";
			$data['address'] = $_SERVER['REMOTE_ADDR'];
			$data['loc_city'] = $_SESSION['location_data']['loc_city'];
			$data['loc_region'] = $_SESSION['location_data']['loc_region'];
			$data['loc_country'] = $_SESSION['location_data']['loc_country'];
			$data['loc_continent'] = $_SESSION['location_data']['loc_continent'];
			$data['loc_lat'] = $_SESSION['location_data']['loc_lat'];
			$data['loc_long'] = $_SESSION['location_data']['loc_long'];
			
			return $data;
		}
		
		function add($array) {
			$user = $this->mysql_prep($array['user']);
			$hash = $this->mysql_prep($array['hash']);
			$browser_name = $this->mysql_prep($array['browser_name']);
			$browser_number = $this->mysql_prep($array['browser_number']);
			$os = $this->mysql_prep($array['os']);
			$address = $this->mysql_prep($array['address']);
			$os_number = $this->mysql_prep($array['os_number']);
			$loc_city = $this->mysql_prep($array['loc_city']);
			$loc_region = $this->mysql_prep($array['loc_region']);
			$loc_country = $this->mysql_prep($array['loc_country']);
			$loc_continent = $this->mysql_prep($array['loc_continent']);
			$loc_lat = $this->mysql_prep($array['loc_lat']);
			$loc_long = $this->mysql_prep($array['loc_long']);
			$create_time = $last_login = time();
			
			$sql = mysql_query("INSERT INTO `browser_log` (`user`, `hash`, `address`, `browser_name`,  `browser_number`, `os`, `os_number`, `loc_city`, `loc_region`, `loc_country`, `loc_continent`, `loc_lat`, `loc_long`, `create_time`, `last_login`) VALUES ('".$user."','".$hash."','".$address."','".$browser_name."','".$browser_number."','".$os."', '".$os_number."', '".$loc_city."', '".$loc_region."', '".$loc_country."', '".$loc_continent."', '".$loc_lat."', '".$loc_long."', '".$create_time."', '".$last_login."') ") or die (mysql_error()."hhhh");
			
			if ($sql) {
				$id = mysql_insert_id();
				
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "users";
				$logArray['owner_id'] = $user;
				$logArray['desc'] = "added new device on ".$data['browser_name']." on ".$data['os']." near ".$data['loc_city']." ".$data['loc_region']." ";
				$logArray['create_date'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				return $id;
			} else {
				return false;
			}
		}
		
		function modifyOne($tag, $value, $id) {
			$value = $this->mysql_prep($value);
			$id = $this->mysql_prep($id);
			$modDate = time();
			$sql = mysql_query("UPDATE `browser_log` SET `".$tag."` = '".$value."' WHERE ref = '".$id."'") or die (mysql_error());
			
			if ($sql) {
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "users";
				$logArray['owner_id'] = $_SESSION['users']['ref'];
				$logArray['desc'] = "modified fieeld ".$tag." with value ".$value." for entry ID ".$id;
				$logArray['create_date'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				return true;
			} else {
				return false;
			}
		}
		
		function hashID() {
			if (isset($_COOKIE['userID'])) {
				$rand = $_COOKIE['userID'];
			} else {
				$rand = rand(10000, 99999)."_".rand(10000, 99999);
				setcookie("userID", $userID, time()+(60*60*24*30*6), "/");
			}
			$agent = $_SERVER['HTTP_USER_AGENT'];
						
			$string = $rand.$agent;
			$result = base64_encode(sha1($string));
			
			setcookie("hash", $result, time()+(60*60*24*30*6), "/");
			
			return $result;
		}
		
		function getActive() {
			$sql = mysql_query("SELECT * FROM `browser_log` GROUP BY `user` ORDER BY `ref` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['user'] = $row['user'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function listUser($tag, $id) {
			$sql = mysql_query("SELECT * FROM `browser_log` WHERE `".$tag."` = '".$id."' ORDER BY `ref` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['user'] = $row['user'];
					$result[$count]['hash'] = $row['hash'];
					$result[$count]['browser_name'] = $row['browser_name'];
					$result[$count]['browser_number'] = $row['browser_number'];
					$result[$count]['os'] = $row['os'];
					$result[$count]['os_number'] = $row['os_number'];
					$result[$count]['address'] = $row['address'];
					$result[$count]['loc_city'] = $row['loc_city'];
					$result[$count]['loc_region'] = $row['loc_region'];
					$result[$count]['loc_country'] = $row['loc_country'];
					$result[$count]['loc_continent'] = $row['loc_continent'];
					$result[$count]['loc_lat'] = $row['loc_lat'];
					$result[$count]['loc_long'] = $row['loc_long'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['last_login'] = $row['last_login'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function getOne($id, $tag='ref') {
			$id = $this->mysql_prep($id);
			$sql = mysql_query("SELECT * FROM `browser_log` WHERE `".$tag."` = '".$id."' ORDER BY `ref` DESC LIMIT 1") or die (mysql_error());
			if ($sql) {
				$result = array();
				
				if (mysql_num_rows($sql) == 1) {
					$row = mysql_fetch_array($sql);
					$result['ref'] = $row['ref'];
					$result['user'] = $row['user'];
					$result['hash'] = $row['hash'];
					$result['browser_name'] = $row['browser_name'];
					$result['browser_number'] = $row['browser_number'];
					$result['os'] = $row['os'];
					$result['os_number'] = $row['os_number'];
					$result['address'] = $row['address'];
					$result['loc_city'] = $row['loc_city'];
					$result['loc_region'] = $row['loc_region'];
					$result['loc_country'] = $row['loc_country'];
					$result['loc_continent'] = $row['loc_continent'];
					$result['loc_lat'] = $row['loc_lat'];
					$result['loc_long'] = $row['loc_long'];
					$result['create_time'] = $row['create_time'];
					$result['last_login'] = $row['last_login'];
					return $this->out_prep($result);
				} else {
					return false;
				}
			}
		}
		
		function getOneField($id, $tag="ref", $ref="title") {
			$data = $this->getOne($id, $tag);
			return $data[$ref];
		}
		
		function login($id, $mobile=false) {
			$users = new users;
			if ($mobile == true) {
				$hash = $mobile;
			} else if (isset($_COOKIE['hash'])) {
				$hash = $this->mysql_prep(htmlspecialchars_decode($_COOKIE['hash']));
			} else {
				$hash = $this->hashID();
			}
			$data = $this->listUser("user", $id);
			
			if ($mobile == false) {
				$userData = $this->logVisit();
			} else {
				$userData = $this->logVisitMobile();	
			}
			
			$userDetails = $users->listOne($id);
			$userData['user'] = $id;
			$userData['hash'] = $hash;
			if (count($data) >= 3) {
				//maximum exceeded
				$client = $userDetails['last_name']." ".$userDetails['other_names']." <".$userDetails['email'].">";
				$subjectToClient = "LegalLens Login Notification";
				
				$contact = "LegalLens <".replyMail.">";
					
				$fields = 'subject='.urlencode($subjectToClient).
					'&id='.urlencode($id);
				$mailUrl = URL."includes/emails/login_error.php?".$fields;
				$messageToClient = $this->curl_file_get_contents($mailUrl);
				
				$mail['from'] = $contact;
				$mail['to'] = $client;
				$mail['subject'] = $subjectToClient;
				$mail['body'] = $messageToClient;
				
				$alerts = new alerts;
				$alerts->sendEmail($mail);
				
				$message['status'] = 0;
			} else {
				$find = $this->getOne($hash, "hash");
				
				if ($find == false) {
					//create new log, send welcome email
					$this->add($userData);
					
					$client = $userDetails['last_name']." ".$userDetails['other_names']." <".$userDetails['email'].">";
					$subjectToClient = "LegalLens Login Alert";
					
					$contact = "LegalLens <".replyMail.">";
						
					$fields = 'subject='.urlencode($subjectToClient).
						'&id='.urlencode($id).
						'&hash='.urlencode($hash);
					$mailUrl = URL."includes/emails/login_alert.php?".$fields;
					$messageToClient = $this->curl_file_get_contents($mailUrl);
					
					$mail['from'] = $contact;
					$mail['to'] = $client;
					$mail['subject'] = $subjectToClient;
					$mail['body'] = $messageToClient;
					
					$alerts = new alerts;
					$alerts->sendEmail($mail);
				
					$message['status'] = 1;
				} else {
					//modify login time
					$_SESSION['users']['ref'] = $id;
					$this->modifyOne("last_login", time(), $find['ref']);
					$message['status'] = 2;
				}
			}
			return $message;
		}
		
		function checkLogin($mobile=false) {
			if ($mobile == true) {
				$get = $this->getOne($mobile, "hash");
				
				if ($get) {
					return true;
				} else {
					return false;
				}
			} else if (isset($_COOKIE['hash'])) {
				$hash = htmlspecialchars_decode($_COOKIE['hash']);
				$get = $this->getOne($hash, "hash");
				
				if ($get) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
		
		function logout($tag='hash', $id=false) {
			if ($id == false ) {
				$hash = $this->mysql_prep(htmlspecialchars_decode($_COOKIE['hash']));
			} else {
				$hash = $id;
			}
			$data = $this->listUser("hash", $hash);
			
			$sql = mysql_query("DELETE FROM `browser_log` WHERE `".$tag."` = '".$hash."'") or die (mysql_error());
			setcookie("hash", "", time()-42000);
			unset($_COOKIE['hash']);
			if ($sql) {
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = $_SESSION['users']['ref'];
				$logArray['desc'] = "removed new device running ".$data['browser_name']." on ".$data['os']." near ".$data['loc_city']." ".$data['loc_region']." ";
				$logArray['create_date'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				return true;
			} else {
				return false;
			}
		}
	}
?>