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
				$sql = mysql_query("UPDATE clients SET status = 'ACTIVE', modify_time = '".$modify_time."' WHERE id = '".$id."'") or die (mysql_error());
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
			
			$sql = mysql_query("UPDATE clients SET status = 'INACTIVE', modify_time = '".$modify_time."' WHERE id = '".$id."'") or die (mysql_error());
			if ($sql) {
				
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = $_SESSION['admin']['id'];
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
			
			$sql = mysql_query("UPDATE clients SET status = 'DELETED', modify_time = '".$modify_time."' WHERE id = '".$id."'") or die (mysql_error());
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
			$sql = mysql_query("SELECT * FROM clients WHERE (`email` = '".$username."') AND password = '".sha1($password)."' AND `status` != 'DELETED'") or die (mysql_error());
			
			if (mysql_num_rows($sql) == 1) {
				$row = mysql_fetch_array($sql);
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
			$logout_time = date("Y-m-d H:i:s");
			$session_id = session_id();
			$_SESSION = array();
			if(isset($_COOKIE[session_name()])) {
				setcookie(session_name(), '', time()-42000, '/');
			}
			session_destroy();
		}
		
		function checkAccount($email) {
			$email = $this->mysql_prep($email);
			$sql = mysql_query("SELECT `id` FROM `clients` WHERE email = '".$email."' AND `status` != 'DELETED'") or die (mysql_error());
			$result = mysql_num_rows($sql);
			
			return $result;
		}
		
		function checkStoreID($client_id) {
			$client_id = strtolower($this->mysql_prep($client_id));
			$sql = mysql_query("SELECT `id` FROM `clients` WHERE client_id = '".$client_id."' AND `status` != 'DELETED'") or die (mysql_error());
			$result = mysql_num_rows($sql);
			
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
				$sql = mysql_query("INSERT INTO clients (`password`, `name`, `email`, `company`, `phone`,`create_time`, `modify_time`) VALUES ('".sha1($password)."', '".$name."', '".$email."', '".$company."', '".$phone."','".$create_time."', '".$modify_time."')") or die (mysql_error());
				
				$id = $db->lastInsertId();
				
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "clients";
				$logArray['owner_id'] = $_SESSION['clients']['id'];
				$logArray['desc'] = "created user account ".$username;
				$logArray['create_time'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				
				$client = $name." <".$email.">";
				$subjectToClient = "Client Account";
				
				
				$contact = "LegalLens Clients Services <".replyMail.">";
					
				$fields = 'subject='.urlencode($subjectToClient).
					'&name='.urlencode($name).
					'&username='.urlencode($username).
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
			$key = $this->mysql_prep($key);
			$sql = mysql_query("SELECT * FROM clients WHERE `username` = '".$key."'") or die (mysql_error()."sch");
			if (mysql_num_rows($sql) == 0) {
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
			$modify_time = time();
			$sql = mysql_query("UPDATE clients SET name = '".$name."', `company` = '".$company."', email = '".$email."', phone = '".$phone."', modify_time = '".$modify_time."' WHERE id = '".$id."'") or die (mysql_error());
			
			if ($sql) {
				if ($_SESSION['clients']['id'] == $id) {
					$_SESSION['clients']['name'] = $name;
					$_SESSION['clients']['phone'] = $phone;
					$_SESSION['clients']['email'] = $email;
					$_SESSION['clients']['company'] = $read;
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
			$modify_time = time();
			
			if ($check == 1) {
				$data = $this->listOne($email, "email");
				$password = $this->createRandomPassword();
				
				$sql = mysql_query("UPDATE clients SET password = '".sha1($password)."', modify_time = '".$modify_time."' WHERE id = '".$data['id']."'") or die (mysql_error());
				
				
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
		
		function updatePassword($array) {
			$id = $this->mysql_prep($array['id']);
			$password = $this->mysql_prep(trim($array['password']));
			$modify_time = time();
			
			$sql = mysql_query("UPDATE clients SET password = '".sha1($password)."', modify_time = '".$modify_time."' WHERE id = '".$id."'") or die (mysql_error());
			
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
			$sql = mysql_query("SELECT * FROM clients WHERE status != 'DELETED' ORDER BY `company` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['id'] = $row['id'];
					$result[$count]['name'] = $row['name'];
					$result[$count]['email'] = $row['email'];
					$result[$count]['phone'] = $row['phone'];
					$result[$count]['company'] = $row['company'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function sortList($tag, $id) {
			$id = $this->mysql_prep($id);
			$sql = mysql_query("SELECT * FROM clients WHERE `".$tag."` = '".$id."'") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['id'] = $row['id'];
					$result[$count]['name'] = $row['name'];
					$result[$count]['email'] = $row['email'];
					$result[$count]['phone'] = $row['phone'];
					$result[$count]['company'] = $row['company'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function listOne($id, $tag='id') {
			$id = $this->mysql_prep($id);
			$sql = mysql_query("SELECT * FROM clients WHERE `".$tag."` = '".$id."' AND `status` != 'DELETED'") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				
				$row = mysql_fetch_array($sql);
				$result['id'] = $row['id'];
				$result['password'] = $row['password'];
				$result['name'] = $row['name'];
				$result['email'] = $row['email'];
				$result['company'] = $row['company'];
				$result['status'] = $row['status'];
				$result['phone'] = $row['phone'];
				$result['create_time'] = $row['create_time'];
				$result['modify_time'] = $row['modify_time'];
				
				return $this->out_prep($result);
			} else {
				return false;
			}
		}
		
		function getOneField($id, $tag='id', $ref='company') {
			$data = $this->listOne($id, $tag);
			return $data[$ref];
		}
	}
?>