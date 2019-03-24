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
						
			global $db;
			try {
				$sql = $db->prepare("INSERT INTO `browser_log` (`user`,`hash`,`address`,`browser_name`,`browser_number`,`os`,`os_number`,`loc_city`,`loc_region`,`loc_country`,`loc_continent`,`loc_lat`,`loc_long`,`create_time`,`last_login`) 
				VALUES (:user,:hash,:address,:browser_name,:browser_number,:os,:os_number,:loc_city,:loc_region,:loc_country,:loc_continent,:loc_lat,:loc_long,:create_time,:last_login)");
				$sql->execute(array(
							':user' => $user, 
							':hash' => $hash, 
							':address' => $address,
							':browser_name' => $browser_name,
							':browser_number' => $browser_number,
							':os' => $os,
							':os_number' => $os_number,
							':loc_city' => $loc_city,
							':loc_region' => $loc_region,
							':loc_country' => $loc_country,
							':loc_continent' => $loc_continent,
							':loc_lat' => $loc_lat,
							':loc_long' => $loc_long,
							':create_time' => $create_time,
							':last_login' => $last_login));
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				$id = $db->lastInsertId();
				
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "users";
				$logArray['owner_id'] = $user;
				$logArray['desc'] = "added new device on ".$array['browser_name']." on ".$array['os']." near ".$array['loc_city']." ".$array['loc_region']." ";
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
			
			global $db;
			try {
				$sql = $db->prepare("UPDATE `browser_log` SET  `".$tag."` = :value WHERE `ref`=:id");
				$sql->execute(
					array(
					':value' => $value,
					':id' => $id)
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "users";
				$logArray['owner_id'] = intval($_SESSION['users']['ref']);
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
				setcookie("userID", $rand, time()+(60*60*24*30*6), "/");
			}
			$agent = $_SERVER['HTTP_USER_AGENT'];
						
			$string = $rand.$agent;
			$result = base64_encode(sha1($string));
			
			setcookie("hash", $result, time()+(60*60*24*30*6), "/");
			
			return $result;
		}
		
		function getActive() {
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `browser_log` GROUP BY `user` ORDER BY `ref` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				$result = array();
				$count = 0;
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['user'] = $row['user'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function listUser($tag, $id) {
			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM `browser_log` WHERE `".$tag."` = :id ORDER BY `ref` ASC");
				$sql->execute(
					array(
					':id' => $id)
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		function getOne($id, $tag='ref') {
			$id = $this->mysql_prep($id);
			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM browser_log WHERE `".$tag."` = :id ORDER BY `ref` DESC LIMIT 1");
				$sql->execute(
					array(
					':id' => $id)
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$result = array();
			$row = $sql->fetch(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		function getOneField($id, $tag="ref", $ref="title") {
			$data = $this->getOne($id, $tag);
			return $data[$ref];
		}
		
		function login($id, $mobile=false) {
			global $users;
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
			
			global $db;
			try {
				$sql = $db->prepare("DELETE FROM `browser_log` WHERE `".$tag."` =:hash");
				$sql->execute(
					array(
					':hash' => $hash)
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			setcookie("hash", "", time()-42000);
			unset($_COOKIE['hash']);
			if ($sql) {
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = intval($_SESSION['users']['ref']);
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