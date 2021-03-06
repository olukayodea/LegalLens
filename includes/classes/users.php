<?php
	class users extends common {
		function activate($password, $ref=false) {
			if ($ref == false) {
				$ref = $_SESSION['users']['ref'];
			}
			$password = $this->mysql_prep($password);
			$array = array();
			$array['ref'] = $ref;
			$array['password'] = $password;
			$change = self::updatePassword($array);
			
			if ($change){
				global $db;
				try {
					$sql = $db->prepare("UPDATE `users` SET `status` = 'ACTIVE', `modify_time` = :modifyTime WHERE `ref`=:id");
					$sql->execute(
						array(
						':modifyTime' => time(),
						':id' => $ref)
					);
				} catch(PDOException $ex) {
					echo "An Error occured! ".$ex->getMessage(); 
				}

				if ($sql) {
					$_SESSION['users']['status'] = "ACTIVE";
					return true;
				} else {
					return false;
				}
			}
		}
		
		function passwordReset($email) {
			$check = $this->checkAccount($email);
			
			if ($check == 1) {
				$data = $this->listOne($email, "email");
				$password = $this->createRandomPassword();
								
				global $db;
				try {
					$sql = $db->prepare("UPDATE `users` SET `password` = :password, `status` = 'NEW', `modify_time` = :modifyTime WHERE `ref`=:id");
					$sql->execute(
						array(
						':password' => sha1($password),
						':modifyTime' => time(),
						':id' => $data['ref'])
					);
				} catch(PDOException $ex) {
					echo "An Error occured! ".$ex->getMessage(); 
				}
				$client = $data['name'];
				$subjectToClient = "Password Reset Notification";
				$contact = "LegalLens <".replyMail.">";
				
				$fields = 'subject='.urlencode($subjectToClient).
					'&last_name='.urlencode($data['last_name']).
					'&other_names='.urlencode($data['other_names']).
					'&email='.urlencode($data['email']).
					'&password='.urlencode($password);
				$mailUrl = URL."includes/emails/passwordNotificationUsr.php?".$fields;
				$messageToClient = $this->curl_file_get_contents($mailUrl);
				
				$mail['from'] = $contact;
				$mail['to'] = $client." <".$data['email'].">";
				$mail['subject'] = $subjectToClient;
				$mail['body'] = $messageToClient;
				
				$alerts = new alerts;
				$alerts->sendEmail($mail);
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = intval($data['id']);
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = intval($data['id']);
				$logArray['desc'] = "updated password";
				$logArray['create_time'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				return true;
			} else {
				return false;
			}
		}
		
		function deactivate($ref) {
			$ref = $this->mysql_prep($ref);
			global $db;
			try {
				$sql = $db->prepare("UPDATE `users` SET `status` = 'INACTIVE', `modify_time` = :modifyTime WHERE `ref`=:id");
				$sql->execute(
					array(
					':modifyTime' => time(),
					':id' => $ref)
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			if ($sql) {
				return true;
			} else {
				return false;
			}
		}
		
		function delete($ref) {
			$ref = $this->mysql_prep($ref);

			global $db;
			try {
				$sql = $db->prepare("UPDATE `users` SET `status` = 'DELETED', `modify_time` = :modifyTime WHERE `ref`=:id");
				$sql->execute(
					array(
					':modifyTime' => time(),
					':id' => $ref)
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			if ($sql) {
				return true;
			} else {
				return false;
			}
		}
		
		function loginCookie() {
			$usersControl = new usersControl;
			$hash = htmlspecialchars_decode($_COOKIE['hash']);
			$id = $usersControl->getOneField($hash, "hash", "user");
			
			if (intval($id) > 0) {				
				global $db;
				try {
					$sql = $db->prepare("SELECT * FROM users WHERE `ref`= :ref AND `status` != 'DELETED'");
					$sql->execute(
						array(
						':ref' => $id)
					);
				} catch(PDOException $ex) {
					echo "An Error occured! ".$ex->getMessage(); 
				}
				if ($sql->rowCount() == 1) {
					$usersControl = new usersControl;
					$row = $sql->fetch(PDO::FETCH_ASSOC);
					$_SESSION['users']['status'] = $row['status'];
					$_SESSION['users']['email'] = $row['email'];
					$_SESSION['users']['username'] = $row['username'];
					$_SESSION['users']['last_name'] = $row['last_name'];
					$_SESSION['users']['other_names'] = $row['other_names'];
					$_SESSION['users']['phone'] = $row['phone'];
					$_SESSION['users']['date_time'] = $row['date_time'];
					$_SESSION['users']['subscription_group'] = $row['subscription_group'];
					$_SESSION['users']['subscription_group_onwer'] = $row['subscription_group_onwer'];
					$_SESSION['users']['subscription_type'] = $row['subscription_type'];
					$_SESSION['users']['subscription'] = $row['subscription'];
					$_SESSION['users']['ref'] = $row['ref'];
					$_SESSION['users']['modify_time'] = $row['modify_time'];
					$_SESSION['users']['loginTime'] = time();
					
					return true;
				} else {
					unset($_COOKIE['hash']);
					return false;
				}
			} else {
				unset($_COOKIE['hash']);
				return false;
			}
		}
		
		function login($array) {
			$email = $this->mysql_prep($array['email']);
			$password = $this->mysql_prep($array['password']);
			
			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM `users` WHERE `email`= :email AND `password` = :password AND `status` != 'DELETED'");
				$sql->execute(
					array(
					':email' => $email,
					':password' => sha1($password))
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql->rowCount() == 1) {
				global $usersControl;
				$row = $sql->fetch(PDO::FETCH_ASSOC);
				$chechLoc = $usersControl->login($row['ref']);
				if ($chechLoc['status'] == 0) {
					$this->logout();
					return 10;
				} else {
					$this->addLog($row['ref']);
					$hash = $this->encode($row['ref']);
					setcookie("login_check", $hash, time()+(60*60*24*180), "/");
					$status = $row['status'];
					$_SESSION['users']['status'] = $row['status'];
					$_SESSION['users']['email'] = $row['email'];
					$_SESSION['users']['last_name'] = $row['last_name'];
					
					$_SESSION['users']['username'] = $row['username'];
					$_SESSION['users']['other_names'] = $row['other_names'];
					$_SESSION['users']['phone'] = $row['phone'];
					$_SESSION['users']['date_time'] = $row['date_time'];
					$_SESSION['users']['subscription_group'] = $row['subscription_group'];
					$_SESSION['users']['subscription_group_onwer'] = $row['subscription_group_onwer'];
					$_SESSION['users']['subscription_type'] = $row['subscription_type'];
					$_SESSION['users']['subscription'] = $row['subscription'];
					$_SESSION['users']['ref'] = $row['ref'];
					$_SESSION['users']['modify_time'] = $row['modify_time'];
					$_SESSION['users']['last_login'] = $row['last_login'];
					$_SESSION['users']['loginTime'] = time();
					if ($status == "NEW") {
						return 1;
					} else if ($status == "INACTIVE") {
						return 3;
					} else {
						return 2;
					}
				}
			} else {
				return 0;
			}
		}
		
		function loginMobile($array) {
			$email = $this->mysql_prep($array['email']);
			$password = $this->mysql_prep($array['password']);
			$mobile = $this->mysql_prep($array['mobile']);			

			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM users WHERE `email`= :email AND `password` = :password AND `status` != 'DELETED'");
				$sql->execute(
					array(
					':email' => $email,
					':password' => sha1($password))
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql->rowCount() == 1) {
				$usersControl = new usersControl;
				$row = $sql->fetch(PDO::FETCH_ASSOC);
				$chechLoc = $usersControl->login($row['ref'], $mobile);
				if ($chechLoc['status'] == 0) {
					$this->logoutApp($mobile);
					return 1;
				} else {
					$this->addLog($row['ref']);
					setcookie("ref", $row['ref'], time()+(60*60*24*365), "/");
					$subscriptions = new subscriptions;
					$result['ref'] = $row['ref'];
					$result['email'] = $row['email'];
					$result['last_name'] = $row['last_name'];
					$result['other_names'] = $row['other_names'];
					$result['phone'] = $row['phone'];
					$result['date_time'] = $row['date_time'];
					$result['subscription'] = $row['subscription'];
					$result['subscription_type'] = $subscriptions->getOneField($row['subscription_type']);
					$result['subscription_url'] = URL."mobile_subscription?id=".$row['ref'];
					$result['subscription_type_name'] = $subscriptions->getOneField($row['subscription_type'], "ref", "title");
					$result['subscription_url'] = URL."mobile_subscription?id=".$row['ref'];
					$result['last_subscription'] = $row['last_subscription'];
					$result['modify_time'] = $row['modify_time'];
					$result['status'] = $row['status'];
					$result['last_login'] = $row['last_login'];
					$result['loginTime'] = time();
					return $result;
				}
			} else {
				return 0;
			}
		}
		
		function logout() {
			$usersControl = new usersControl;
			$_SESSION = array();
			if(isset($_COOKIE[session_name()])) {
				setcookie(session_name(), '', time()-42000, '/');
			}
			setcookie("login_check", "", time()-42000);
			$usersControl->logout();
			unset($_COOKIE['hash']);
			setcookie("hash", "", time()-42000);
			unset($_COOKIE['ref']);
			setcookie("ref", "", time()-42000);
			session_destroy();
		}
		
		function logoutApp($hash) {
			$usersControl = new usersControl;
			$_SESSION = array();
			if(isset($_COOKIE[session_name()])) {
				setcookie(session_name(), '', time()-42000, '/');
			}
			setcookie("login_check", "", time()-42000);
			unset($_COOKIE['ref']);
			setcookie("ref", "", time()-42000);
			$usersControl->logout("hash", $hash);
			session_destroy();
		}
		
		function checkAccount($email) {
			$email = $this->mysql_prep($email);
			global $db;
			try {
				$sql = $db->prepare("SELECT `ref` FROM users WHERE `email`= :email");
				$sql->execute(
					array(
					':email' => $email)
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			return $sql->rowCount();
		}
		
		function create($array) {
			$password = $this->mysql_prep($array['password']);
			$last_name = ucfirst(strtolower($this->mysql_prep($array['last_name'])));
			$other_names = ucwords(strtolower($this->mysql_prep($array['other_names'])));
			$email = $this->mysql_prep($array['email']);
			$username = $this->username($last_name, $other_names);
			$phone = $this->mysql_prep($array['phone']);
			$subscription_type = "";
			$subscription = "";
			$subscription_group = "";
			$status = "ACTIVE";
			$date_time = time();
			$modify_time = time();
			
			if ($this->checkAccount($email) == 0) {

				global $db;
				try {
					$sql = $db->prepare("INSERT INTO `users` (`last_name`,`password`,`username`,`other_names`,`email`,`phone`,`subscription_group`,`subscription_type`,`subscription`,`status`,`date_time`,`modify_time`) VALUES (:last_name,:password,:username,:other_names,:email,:phone,:subscription_group,:subscription_type,:subscription,:status,:date_time,:modify_time)");
					$sql->execute(array(
								':last_name' => $last_name, 
								':password' => sha1($password), 
								':username' => $username,
								':other_names' => $other_names,
								':email' => $email,
								':phone' => $phone,
								':subscription_group' => $subscription_group,
								':subscription_type' => $subscription_type,
								':subscription' => $subscription,
								':status' => $status,
								':date_time' => $date_time,
								':modify_time' => $modify_time));
				} catch(PDOException $ex) {
					echo "An Error occured! ".$ex->getMessage(); 
				}
							
				$id = $db->lastInsertId();
				
				$client = $last_name." ".$other_names." <".$email.">";
				$subjectToClient = "LegalLens User Account";
				
				$contact = "LegalLens <".replyMail.">";
					
				$fields = 'subject='.urlencode($subjectToClient).
					'&last_name='.urlencode($last_name).
					'&other_names='.urlencode($other_names).
					'&username='.urlencode($username).
					'&email='.urlencode($email).
					'&phone='.urlencode($phone).
					'&password='.urlencode($password);
				$mailUrl = URL."includes/emails/welcome.php?".$fields;
				$messageToClient = $this->curl_file_get_contents($mailUrl);
				
				$mail['from'] = $contact;
				$mail['to'] = $client;
				$mail['subject'] = $subjectToClient;
				$mail['body'] = $messageToClient;
				
				$alerts = new alerts;
				$alerts->sendEmail($mail);
				
				$loginArray = array();
				$loginArray['email'] = $email;
				$loginArray['password'] = $password;
				$this->login($loginArray);
				
				return $id;
			} else {
				return false;
			}
		}
		
		function modifyOne($tag, $value, $id) {
			global $db;
			try {
				$sql = $db->prepare("UPDATE `users` SET  `".$tag."` = :value, `modify_time` = :modifyTime WHERE `ref`=:id");
				$sql->execute(
					array(
					':value' => $value,
					':modifyTime' => time(),
					':id' => $id)
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				return true;
			} else {
				return false;
			}
		}
		
		function update ($array) {
			$ref = $this->mysql_prep($array['ref']);
			$last_name = $this->mysql_prep($array['last_name']);
			$other_names = $this->mysql_prep($array['other_names']);
			$phone = $this->mysql_prep($array['phone']);

			global $db;
			try {
				$sql = $db->prepare("UPDATE `users` SET `last_name` = :last_name, `other_names` = :other_names, `phone` = :phone, `modify_time` = :modifyTime WHERE `ref`=:id");
				$sql->execute(
					array(
					':last_name' => $last_name,
					':other_names' => $other_names,
					':phone' => $phone,
					':modifyTime' => time(),
					':id' => $ref)
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
						
			if ($sql) {
				if ($ref == $_SESSION['users']['ref']) {
					$_SESSION['users']['last_name'] = $last_name;
					$_SESSION['users']['other_names'] = $other_names;
					$_SESSION['users']['phone'] = $phone;
				}
				return true;
			} else {
				return false;
			}
		}
		
		function updatePassword($array) {
			$ref = $this->mysql_prep($array['ref']);
			$password = $this->mysql_prep(trim($array['password']));

			global $db;
			try {
				$sql = $db->prepare("UPDATE `users` SET `password` = :password, `modify_time` = :modifyTime WHERE `ref`=:id");
				$sql->execute(
					array(
					':password' => sha1($password),
					':modifyTime' => time(),
					':id' => $ref)
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
						
			if ($sql) {
				return true;
			} else {
				return false;
			}
		}
		
		function listAll($limit=false) {
			if ($limit == true) {
				$add = " LIMIT ".$limit;
			} else {
				$add = "";
			}
			
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `users` WHERE status != 'DELETED' ORDER BY `ref` ASC".$add);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		function listAllActive() {			
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `users` WHERE status != 'DELETED' AND `subscription` > '".time()."' ORDER BY `ref` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		function sortAll($id, $tag, $tag2=false, $id2=false, $tag3=false, $id3=false, $order="ref") {
			$token = array(':id' => $id);
			if ($tag2 != false) {
				$sqlTag = " AND `".$tag2."` = :id2";
				$token[':id2'] = $id2;
			} else {
				$sqlTag = "";
			}
			if ($tag3 != false) {
				$sqlTag .= " AND `".$tag3."` = :id3";
				$token[':id3'] = $id3;
			} else {
				$sqlTag .= "";
			}
			
			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM `users` WHERE `".$tag."` = :id".$sqlTag." AND `status` != 'DELETED' ORDER BY `".$order."` ASC");
								
				$sql->execute($token);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
			return $this->out_prep($row);
		}

		function listAllAutoRenew() {			
			$time = time()+(60*60*24*3);
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `users` WHERE status != 'DELETED' AND `payment_frequency` = 'Renew' AND `payment_frequency_retry_count` <= 3 AND `payment_frequency_retry` < ".time()." AND `subscription` < '".$time."' ORDER BY `ref` ASC LIMIT 10");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		function listOne($ref, $tag='ref') {
			$ref = $this->mysql_prep($ref);
			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM users WHERE `".$tag."` = :id ORDER BY `ref` DESC LIMIT 1");
				$sql->execute(
					array(
					':id' => $ref)
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetch(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		function getOneField($id, $tag="ref", $ref="last_name") {
			$data = $this->listOne($id, $tag);
			return $data[$ref];
		}
		
		function createGroup ($array) {
			$sub_total = $this->mysql_prep($array['sub_total']);
			$sub_list = $this->mysql_prep($array['sub_list']);
			if ($sub_list < $sub_total) {
				$password = $this->createRandomPassword(10);
				$last_name = $this->mysql_prep($array['last_name']);
				$other_names = $this->mysql_prep($array['other_names']);
				$email = $this->mysql_prep($array['email']);
				$username = $this->username($last_name, $other_names);
				$subscription_type = $this->mysql_prep($array['subscription_type']);
				$subscription_group = $this->mysql_prep($array['subscription_group']);
				$subscription_group_onwer = $this->mysql_prep($array['subscription_group_onwer']);
				$subscription = $this->mysql_prep($array['subscription']);
				$date_time = $modify_time = time();
				
				if ($this->checkAccount($email) == 0) {
					global $db;
					try {
						$sql = $db->prepare("INSERT INTO `users` (`last_name`,`password`,`username`,`other_names`,`email`,`subscription_group`,`subscription_type`,`subscription`,`subscription_group_onwer`,`date_time`,`modify_time`) VALUES (:last_name,:password,:username,other_names,email,subscription_group,subscription_type,subscription,subscription_group_onwer,:date_time,:modify_time)");
						$sql->execute(array(
									':last_name' => $last_name, 
									':password' => $password, 
									':username' => $username,
									':other_names' => $other_names,
									':email' => $email,
									':subscription_group' => $subscription_group,
									':subscription_type' => $subscription_type,
									':subscription' => $subscription,
									':subscription_group_onwer' => $subscription_group_onwer,
									':date_time' => $date_time,
									':modify_time' => $modify_time));
					} catch(PDOException $ex) {
						echo "An Error occured! ".$ex->getMessage(); 
					}
								
					$id = $db->lastInsertId();
					
					//add to log
					$logArray['object'] = get_class($this);
					$logArray['object_id'] = $id;
					$logArray['owner'] = "users";
					$logArray['owner_id'] = $subscription_group_onwer;
					$logArray['desc'] = "created user account ".$last_name." ".$other_names." for group";
					$logArray['create_time'] = time();
					$system_log = new system_log;
					$system_log->create($logArray);
					
					$client = $last_name." ".$other_names." <".$email.">";
					$subjectToClient = "LegalLens User Account";
					
					$contact = "LegalLens <".replyMail.">";
						
					$fields = 'subject='.urlencode($subjectToClient).
						'&subscription_group_onwer='.urlencode($subscription_group_onwer).
						'&last_name='.urlencode($last_name).
						'&other_names='.urlencode($other_names).
						'&email='.urlencode($email).
						'&password='.urlencode($password);
					$mailUrl = URL."includes/emails/welcome_group.php?".$fields;
					$messageToClient = $this->curl_file_get_contents($mailUrl);
					
					$mail['from'] = $contact;
					$mail['to'] = $client;
					$mail['subject'] = $subjectToClient;
					$mail['body'] = $messageToClient;
					
					$alerts = new alerts;
					$alerts->sendEmail($mail);
					return $id;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
		
		function encode($value) {
			$len = strlen($value);
			$max = "";
			for ($j = 0; $j < $len; $j++) {
				$max .= 9;
			}
			 $random_value = rand(1, 20);
			for ($i = 0; $i <= 20; $i++) {
				$rand = rand(1, $max);
				if ($random_value == $i) {
					$data[$random_value] = $value;
				} else {
					$data[$i] = $rand;
				}
			}
			$data[21] = $random_value;
			$d = base64_encode(json_encode($data));
			return $d;
		}
		
		function decode($value) {
			$data = json_decode(base64_decode($value), true);
			
			$len = count($data) - 1;
			$pos = $data[$len];
			
			return $data[$pos];
		}
		
		function addLog($id) {
			$user = $this->mysql_prep($id);
			$data = $this->getOneField($id, "ref", "login_time");
			$timestamp = time();
			
			$this->modifyOne("last_login", $data, $user);
			$this->modifyOne("login_time", $timestamp, $user);
		}
		
		function username($last, $first) {
			$name = $last." ".$first;
			$username_temp = str_replace(" ","_", $name);
			$username_temp = str_replace("-","_", $username_temp);
			$username_temp = str_replace(",","_", $username_temp);
			$username_temp = str_replace("@","_", $username_temp);
			$username_temp = str_replace("'","_", $username_temp);
			$username_temp = strtolower($username_temp);
			$username = $this->confirmUnique($username_temp);
			
			return $username;
		}
		
		function createUnique($username) {
			$num = $username.rand(1, 999);
			return $num;
		}
		
		function confirmUnique($key) {
			$key = $this->mysql_prep($key);
			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM users WHERE `username`= :key");
				$sql->execute(
					array(
					':key' => $key)
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql->rowCount() == 0) {
				return $key;
			} else {
				return $this->confirmUnique($this->createUnique($key));
			}
		}
	}
?>