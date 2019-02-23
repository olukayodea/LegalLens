<?php
	class clients extends common {
		function activate($password) {
			$id = $_SESSION['clients']['id'];
			$password = $this->mysql_prep($password);
			$array = array();
			$array['id'] = $id;
			$array['password'] = $password;
			$modify_time = time();
			$change = self::updatePassword($array);
			
			if ($change){
				
				global $db;
				try {
					$sql = $db->prepare("UPDATE `clients` SET  status = 'ACTIVE', `modify_time`=:modify_time WHERE `id`=:id");
					$sql->execute(
						array(':modify_time' => $modify_time, ':id' => $id)
					);
				} catch(PDOException $ex) {
					echo "An Error occured! ".$ex->getMessage(); 
				}

				if ($sql) {
					$_SESSION['clients']['status'] = "ACTIVE";
					
					//add to log
					$logArray['object'] = get_class($this);
					$logArray['object_id'] = $_SESSION['clients']['id'];
					$logArray['owner'] = "clients";
					$logArray['owner_id'] = $_SESSION['clients']['id'];
					$logArray['desc'] = "activated user account";
					$logArray['create_time'] = time();
					$system_log = new system_log;
					$system_log->create($logArray);
					return true;
				} else {
					return false;
				}
			}
		}
		
		function deactivate($id) {
			$id = $this->mysql_prep($id);
			$modify_time = time();
			
			global $db;
			try {
				$sql = $db->prepare("UPDATE `clients` SET  status = 'INACTIVE', `modify_time`=:modify_time WHERE `id`=:id");
				$sql->execute(
					array(':modify_time' => $modify_time, ':id' => $id)
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = intval($_SESSION['admin']['id']);
				$logArray['desc'] = "deactivated client account";
				$logArray['create_time'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				return true;
			} else {
				return false;
			}
		}
		
		function delete($id) {
			$id = $this->mysql_prep($id);
			$modify_time = time();

			global $db;
			try {
				$sql = $db->prepare("UPDATE `clients` SET  status = 'DELETED', `modify_time`=:modify_time WHERE `id`=:id");
				$sql->execute(
					array(':modify_time' => $modify_time, ':id' => $id)
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			if ($sql) {
				
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "clients";
				$logArray['owner_id'] = $_SESSION['clients']['id'];
				$logArray['desc'] = "deleted user account";
				$logArray['create_time'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				return true;
			} else {
				return false;
			}
		}
		
		function login($array) {
			$username = $this->mysql_prep($array['username']);
			$password = $this->mysql_prep($array['password']);
			
			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM `clients` WHERE (`email` = :username) AND `password` = :password AND `status` != 'DELETED'");
				$sql->execute(array(':username' => $username,
									':password' => sha1($password)));
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql->rowCount() == 1) {
				$row = $sql->fetch(PDO::FETCH_ASSOC);
				$status = $row['status'];
				$_SESSION['clients']['status'] = $row['status'];
				$_SESSION['clients']['name'] = $row['name'];
				$_SESSION['clients']['phone'] = $row['phone'];
				$_SESSION['clients']['company'] = $row['company'];
				$_SESSION['clients']['create_time'] = $row['create_time'];
				$_SESSION['clients']['email'] = $row['email'];
				$_SESSION['clients']['type'] = "CLIENTS";
				$_SESSION['clients']['id'] = $row['id'];
				$_SESSION['clients']['loginTime'] = time();
				$_SESSION['clients']['sessionTime'] = time() + 900;
				
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $_SESSION['clients']['id'];
				$logArray['owner'] = "clients";
				$logArray['owner_id'] = $_SESSION['clients']['id'];
				$logArray['desc'] = "initiate system account logiin";
				$logArray['create_time'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				if ($status == "NEW") {
					return 1;
				} else if ($status == "INACTIVE") {
					return 3;
				} else {
					return 2;
				}
			} else {
				return 0;
			}
		}
		
		function logout() {
			//add to log
			$logArray['object'] = get_class($this);
			$logArray['object_id'] = $_SESSION['clients']['id'];
			$logArray['owner'] = "clients";
			$logArray['owner_id'] = $_SESSION['clients']['id'];
			$logArray['desc'] = "logged out of account";
			$logArray['create_time'] = time();
			$system_log = new system_log;
			$system_log->create($logArray);
			$_SESSION = array();
			if(isset($_COOKIE[session_name()])) {
				setcookie(session_name(), '', time()-42000, '/');
			}
			session_destroy();
		}
		
		function checkAccount($email) {
			global $db;
			try {
				$sql = $db->prepare("SELECT `id` FROM `clients` WHERE `email` = :email AND `status` != 'DELETED'");
				$sql->execute(array(':email' => $email));
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}

			$result = $sql->rowCount();
			
			return $result;
		}
		
		function create ($array) {
			$password = $this->createRandomPassword();
			$name = $this->mysql_prep($array['name']);
			$email = $this->mysql_prep($array['email']);
			$phone = $this->mysql_prep($array['phone']);
			$company = $this->mysql_prep($array['company']);
			$create_time = time();
			$modify_time = time();
			
			if ($this->checkAccount($email) == 0) {
				global $db;

				try {
					$sql = $db->prepare("INSERT INTO `clients` (`password`, `name`, `email`, `company`, `phone`, `create_time`, `modify_time`) VALUES (:password, :name, :email, :company, :phone, :create_time, :modify_time)");
					$sql->execute(
						array(	':password' => sha1($password),
								':name' => $name,
								':email' => $email,
								':company' => $company,
								':phone' => $phone,
								':create_time' => $create_time,
								':modify_time' => $modify_time)
							);
				} catch(PDOException $ex) {
					echo "An Error occured! ".$ex->getMessage(); 
				}
				$id = $db->lastInsertId();
				
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "clients";
				$logArray['owner_id'] = $_SESSION['clients']['id'];
				$logArray['desc'] = "created user account ".$company;
				$logArray['create_time'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				
				$subjectToClient = "Client Account";
				
				
				$contact = "LegalLens Clients Services <".replyMail.">";
					
				$fields = 'subject='.urlencode($subjectToClient).
					'&name='.urlencode($name).
					'&company='.urlencode($company).
					'&password='.urlencode($password);
				$mailUrl = URL."includes/emails/welcome_clients.php?".$fields;
				$messageToClient = $this->curl_file_get_contents($mailUrl);
				
				$mail['from'] = $contact;
				$mail['to'] = $company." <".$email.">";
				$mail['subject'] = $subjectToClient;
				$mail['body'] = $messageToClient;
				
				$alerts = new alerts;
				$alerts->sendEmail($mail);				
				return $id;
			} else {
				return false;
			}
		}
		
		function createUnique($username) {
			$num = $username.rand(1, 999);
			return $num;
		}
		
		function confirmUnique($key) {
			global $db;
			try {
				$sql = $db->prepare("SELECT `id` FROM `clients` WHERE `name` = :username AND `status` != 'DELETED'");
				$sql->execute(array(':username' => $key));
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql->rowCount() == 0) {
				return $key;
			} else {
				return $this->confirmUnique($this->createUnique($key));
			}
		}
		
		function update ($array) {
			$id = $this->mysql_prep($array['id']);
			$name = $this->mysql_prep($array['name']);
			$email = $this->mysql_prep($array['email']);
			$phone = $this->mysql_prep($array['phone']);
			$company = $this->mysql_prep($array['company']);
			
			global $db;
			try {
				$sql = $db->prepare("UPDATE `clients` SET  `name` = :name, `company`=:company, `email`=:email, `phone`=:phone, `modify_time`=:modify_time WHERE `id`=:id");
				$sql->execute(
					array(':modify_time' => time(), 
					':name' => $name,
					':company' => $company,
					':email' => $email,
					':phone' => $phone,
					':id' => $id)
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}

			if ($sql) {
				if ($_SESSION['clients']['id'] == $id) {
					$_SESSION['clients']['name'] = $name;
					$_SESSION['clients']['phone'] = $phone;
					$_SESSION['clients']['email'] = $email;
					$_SESSION['clients']['company'] = $company;
					$_SESSION['clients']['modify_time'] = $modify_time;
				}
				
				
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "clients";
				$logArray['owner_id'] = $_SESSION['clients']['id'];
				$logArray['desc'] = "updated user account";
				$logArray['create_time'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				return true;
			} else {
				return false;
			}
		}
		
		function passwordReset($email) {
			$check = $this->checkAccount($email);
			
			if ($check == 1) {
				$data = $this->listOne($email, "email");
				$password = $this->createRandomPassword();
				
				global $db;
				try {
					$sql = $db->prepare("UPDATE `clients` SET  password = :password,`timeStamp`=:timeStamp WHERE `id`=:id");
					$sql->execute(
						array(':modify_time' => time(), 
						':password' => sha1($password),
						':id' => $data['id'])
					);
				} catch(PDOException $ex) {
					echo "An Error occured! ".$ex->getMessage(); 
				}				
				
				$client = $data['name'];
				$subjectToClient = "Password Reset Notification";
				$contact = "LegalLens Client Services <".replyMail.">";
				
				$fields = 'subject='.urlencode($subjectToClient).
					'&last_name='.urlencode($data['company']).
					'&email='.urlencode($data['email']).
					'&password='.urlencode($password);
				$mailUrl = URL."includes/emails/passwordNotificationClient.php?".$fields;
				$messageToClient = $this->curl_file_get_contents($mailUrl);
				
				$mail['from'] = $contact;
				$mail['to'] = $client." <".$data['email'].">";
				$mail['subject'] = $subjectToClient;
				$mail['body'] = $messageToClient;
				
				$alerts = new alerts;
				$alerts->sendEmail($mail);
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $data['id'];
				$logArray['owner'] = "clients";
				$logArray['owner_id'] = $_SESSION['clients']['id'];
				$logArray['desc'] = "updated password";
				$logArray['create_time'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				return true;
			} else {
				return false;
			}
		
		}
		
		function updatePassword($array) {
			$id = $this->mysql_prep($array['id']);
			$password = $this->mysql_prep(trim($array['password']));

			global $db;
			try {
				$sql = $db->prepare("UPDATE `clients` SET  password = :password,`timeStamp`=:timeStamp WHERE `id`=:id");
				$sql->execute(
					array(':modify_time' => time(), 
					':password' => sha1($password),
					':id' => $id)
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
						
			if ($sql) {
				
				$data = $this->listOne($id);
				$client = $data['name'];
				$subjectToClient = "Password Reset Notification";
				$contact = "LegalLens Client Services <".replyMail.">";
				
				$fields = 'subject='.urlencode($subjectToClient).
					'&last_name='.urlencode($data['company']).
					'&email='.urlencode($data['email']).
					'&password='.urlencode($this->hashPass($password));
				$mailUrl = URL."includes/emails/passwordNotificationClient.php?".$fields;
				$messageToClient = $this->curl_file_get_contents($mailUrl);
				
				$mail['from'] = $contact;
				$mail['to'] = $client." <".$data['email'].">";
				$mail['subject'] = $subjectToClient;
				$mail['body'] = $messageToClient;
				
				$alerts = new alerts;
				$alerts->sendEmail($mail);
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "clients";
				$logArray['owner_id'] = $_SESSION['clients']['id'];
				$logArray['desc'] = "updated password";
				$logArray['create_time'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				return true;
			} else {
				return false;
			}
		}
		
		function listAll() {
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `clients` WHERE `status` != 'DELETED' ORDER BY `company` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		function sortList($tag, $id) {
			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM clients WHERE `".$tag."` = :id");
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
		
		function listOne($id, $tag='id') {
			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM `clients` WHERE `".$tag."` = :id AND `status` != 'DELETED'");
				$sql->execute(
					array(
					':id' => $id)
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetch(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		function getOneField($id, $tag='id', $ref='company') {
			$data = $this->listOne($id, $tag);
			return $data[$ref];
		}
	}
?>