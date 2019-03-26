<?php
	class admin extends common {
		function activate($password) {
			$id = intval($_SESSION['admin']['id']);
			$password = $this->mysql_prep($password);
			$array = array();
			$array['id'] = $id;
			$array['password'] = $password;
			$timeStamp = time();
			$change = self::updatePassword($array);
			
			if ($change){
				global $db;
				try {
					$sql = $db->prepare("UPDATE `admin` SET  status = 'ACTIVE', `timeStamp`=:timeStamp WHERE `id`=:id");
					$sql->execute(
						array(':timeStamp' => $timeStamp, ':id' => $id)
					);
				} catch(PDOException $ex) {
					echo "An Error occured! ".$ex->getMessage(); 
				}

				if ($sql) {
					$_SESSION['admin']['status'] = "ACTIVE";
					
					//add to log
					$logArray['object'] = get_class($this);
					$logArray['object_id'] = intval($_SESSION['admin']['id']);
					$logArray['owner'] = "admin";
					$logArray['owner_id'] = intval($_SESSION['admin']['id']);
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
			$timeStamp = time();

			global $db;
			try {
				$sql = $db->prepare("UPDATE `admin` SET  status = 'INACTIVE', `timeStamp`=:timeStamp WHERE `id`=:id");
				$sql->execute(
					array(':timeStamp' => $timeStamp, ':id' => $id)
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
				$logArray['desc'] = "deactivated user account";
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
			$timeStamp = time();

			global $db;
			try {
				$sql = $db->prepare("UPDATE `admin` SET  status = 'DELETED', `timeStamp`=:timeStamp WHERE `id`=:id");
				$sql->execute(
					array(':timeStamp' => $timeStamp, ':id' => $id)
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
				$sql = $db->prepare("SELECT * FROM `admin` WHERE (`username` = :username OR `email` = :username) AND `password` = :password AND `status` != 'DELETED'");
				$sql->execute(array(':username' => $username,
									':password' => sha1($password)));
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql->rowCount() == 1) {
				$row = $sql->fetch(PDO::FETCH_ASSOC);
				$status = $row['status'];
				$_SESSION['admin']['status'] = $row['status'];
				$_SESSION['admin']['username'] = $row['username'];
				$_SESSION['admin']['name'] = $row['name'];
				$_SESSION['admin']['phone'] = $row['phone'];
				$_SESSION['admin']['date_time'] = $row['date_time'];
				$_SESSION['admin']['email'] = $row['email'];
				$_SESSION['admin']['adminType'] = $row['adminType'];
				$adminType = $this->listOneType($row['adminType']);
				$_SESSION['admin']['read'] = $adminType['read'];
				$_SESSION['admin']['write'] = $adminType['write'];
				$_SESSION['admin']['modify'] = $adminType['modify'];
				$_SESSION['admin']['pages'] = $adminType['pages'];
				$_SESSION['admin']['level'] = $adminType['level'];
				$_SESSION['admin']['mainPage'] = $adminType['mainPage'];
				$_SESSION['admin']['type'] = "ADMIN";
				$_SESSION['admin']['id'] = $row['id'];
				$_SESSION['admin']['timeStamp'] = $row['timeStamp'];
				$_SESSION['admin']['loginTime'] = time();
				$_SESSION['admin']['sessionTime'] = time() + 900;
				
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = intval($_SESSION['admin']['id']);
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = intval($_SESSION['admin']['id']);
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
			$logArray['object_id'] = intval($_SESSION['admin']['id']);
			$logArray['owner'] = "admin";
			$logArray['owner_id'] = intval($_SESSION['admin']['id']);
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
				$sql = $db->prepare("SELECT `id` FROM `admin` WHERE `email` = :email AND `status` != 'DELETED'");
				$sql->execute(array(':email' => $email));
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}

			return $sql->rowCount();
		}
		
		function create ($array) {
			global $db;
			$password = $this->createRandomPassword();
			$name = $this->mysql_prep($array['name']);
			$username_temp = str_replace(" ","", $name);
			$username_temp = str_replace("-","", $username_temp);
			$username_temp = str_replace(",","", $username_temp);
			$username_temp = strtolower($username_temp);
			$username = $this->confirmUnique($username_temp);
			$email = $this->mysql_prep($array['email']);
			$phone = $this->mysql_prep($array['phone']);
			$adminType = $this->mysql_prep($array['adminType']);
			$date_time = time();
			$timeStamp = time();
			
			if ($this->checkAccount($email) == 0) {

				try {
					$sql = $db->prepare("INSERT INTO `admin` (`username`, `password`, `name`, `email`, `adminType`, `phone`, `date_time`, `timeStamp`) VALUES (:username, :password, :name, :email, :adminType, :phone, :date_time, :timeStamp)");
					$sql->execute(
						array(	':username' => $username,
								':password' => sha1($password),
								':name' => $name,
								':email' => $email,
								':adminType' => $adminType,
								':phone' => $phone,
								':date_time' => $date_time,
								':timeStamp' => $timeStamp)
							);
				} catch(PDOException $ex) {
					echo "An Error occured! ".$ex->getMessage(); 
				}
				$id = $db->lastInsertId();
				
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = intval($_SESSION['admin']['id']);
				$logArray['desc'] = "created user account ".$username;
				$logArray['create_time'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				
				$client = $name." <".$email.">";
				$subjectToClient = "Administrator Account";
				
				
				$contact = "LegalLens Administrator <".replyMail.">";
					
				$fields = 'subject='.urlencode($subjectToClient).
					'&name='.urlencode($name).
					'&username='.urlencode($username).
					'&password='.urlencode($password);
				$mailUrl = URL."includes/emails/welcome_admin.php?".$fields;
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
		}
		
		function createUnique($username) {
			$num = $username.rand(1, 999);
			return $num;
		}
		
		function confirmUnique($key) {
				
			global $db;
			try {
				$sql = $db->prepare("SELECT `id` FROM `admin` WHERE `username` = :username AND `status` != 'DELETED'");
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

		function modifyOne($tag, $value, $id) {
			$value = $this->mysql_prep($value);
			$id = $this->mysql_prep($id);
			
			global $db;
			try {
				$sql = $db->prepare("UPDATE `admin` SET  `".$tag."` = :value, `timeStamp` = :modifyTime WHERE `id`=:id");
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
			$id = $this->mysql_prep($array['id']);
			$name = $this->mysql_prep($array['name']);
			$email = $this->mysql_prep($array['email']);
			$phone = $this->mysql_prep($array['phone']);
			$adminType = $this->mysql_prep($array['adminType']);
			$timeStamp = time();

			global $db;
			try {
				$sql = $db->prepare("UPDATE `admin` SET  name = :name, `adminType`=:adminType, `email`=:email, `phone`=:phone, `timeStamp`=:timeStamp WHERE `id`=:id");
				$sql->execute(
					array(':timeStamp' => time(), 
					':name' => $name,
					':adminType' => $adminType,
					':email' => $email,
					':phone' => $phone,
					':id' => $id)
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			if ($sql) {
				if ($_SESSION['admin']['id'] == $id) {
					$_SESSION['admin']['name'] = $name;
					$_SESSION['admin']['phone'] = $phone;
					$_SESSION['admin']['email'] = $email;
					$_SESSION['admin']['adminType'] = $adminType;
					$_SESSION['admin']['timeStamp'] = $timeStamp;
				}
				
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = intval($_SESSION['admin']['id']);
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
					$sql = $db->prepare("UPDATE `admin` SET  password = :password,`timeStamp`=:timeStamp WHERE `id`=:id");
					$sql->execute(
						array(':timeStamp' => time(), 
						':password' => sha1($password),
						':id' => $data['id'])
					);
				} catch(PDOException $ex) {
					echo "An Error occured! ".$ex->getMessage(); 
				}				
				
				$client = $data['name'];
				$subjectToClient = "Password Reset Notification";
				$contact = "LegalLens Administrator <".replyMail.">";
				
				$fields = 'subject='.urlencode($subjectToClient).
					'&last_name='.urlencode($data['name']).
					'&email='.urlencode($data['email']).
					'&password='.urlencode($password);
				$mailUrl = URL."includes/emails/passwordNotification.php?".$fields;
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
		
		function updatePassword($array) {
			$id = $this->mysql_prep($array['id']);
			$password = $this->mysql_prep(trim($array['password']));
	
			global $db;
			try {
				$sql = $db->prepare("UPDATE `admin` SET  password = :password,`timeStamp`=:timeStamp WHERE `id`=:id");
				$sql->execute(
					array(':timeStamp' => time(), 
					':password' => sha1($password),
					':id' => $array['id'])
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			if ($sql) {
				
				$data = $this->listOne($id);
				$client = $data['name'];
				$subjectToClient = "Password Reset Notification";
				$contact = "LegalLens Administrator <".replyMail.">";
				
				$fields = 'subject='.urlencode($subjectToClient).
					'&last_name='.urlencode($data['name']).
					'&email='.urlencode($data['email']).
					'&password='.urlencode($this->hashPass($password));
				$mailUrl = URL."includes/emails/passwordNotification.php?".$fields;
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
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = intval($_SESSION['admin']['id']);
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
				$sql = $db->query("SELECT * FROM `admin` WHERE `status` != 'DELETED'");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		function countAl() {
			global $db;
			try {
				$sql = $db->query("SELECT COUNT(*) FROM `admin` WHERE status != 'DELETED'");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
						
			return $sql->fetchColumn;
		}
		
		function sortList($tag, $id) {
			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM `admin` WHERE `".$tag."` = :id");
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
				$sql = $db->prepare("SELECT * FROM `admin` WHERE `".$tag."` = :id AND `status` != 'DELETED'");
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
		
		function getOneField($id, $tag='id', $ref='name') {
			$data = $this->listOne($id, $tag);
			return $data[$ref];
		}
		
		function createAdminType($array) {
			$title = $this->mysql_prep($array['title']);
			$id = $this->mysql_prep($array['id']);
			$pages = implode(",", $array['pages']);
			$country = implode(",", $array['country']);
			$createTime = $modifyTime = time();
			$creator = $lastModified = intval($_SESSION['admin']['id']);
			
			global $db;
			$value_array = array(':title' => $array['title'], 
							':level' => $array['level'], 
							':read' => intval($array['read']), 
							':write' => intval($array['write']), 
							':modify' => intval($array['modify']), 
							':mainPage' => $array['mainPage'], 
							':pages' => $pages, 
							':country' => $country,
							':createTime' => $createTime, 
							':modifyTime' => $modifyTime, 
							':creator' => $creator, 
							':lastModified' => $lastModified);
			if ($id != "") {
				$firstpart = "`id`, ";
				$secondPArt = ":id, ";
				$value_array[':id'] = $id;
				$log = "Modified admin type ".$title;
			} else {
				$firstpart = "";
				$secondPArt = "";
				$log = "created new admin type ".$title;
			}
			
			try {
				$sql = $db->prepare("INSERT INTO `admintypes` (".$firstpart."`title`,`level`,`read`,`write`,`modify`,`mainPage`,`pages`,`createTime`,`modifyTime`,`creator`,`lastModified`) VALUES (".$secondPArt.":title,:level,:read,:write,:modify,:mainPage,:pages,:createTime,:modifyTime,:creator,:lastModified)
					ON DUPLICATE KEY UPDATE 
						`title` = :title,
						`level` = :level,
						`read` = :read,
						`write` = :write,
						`modify` = :modify,
						`mainPage` = :mainPage,
						`pages` = :pages,
						`modifyTime` = :modifyTime,
						`creator` = :creator,
						`lastModified` = :lastModified
					
					");
				$sql->execute($value_array);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}

			if ($sql) {
				$id = $db->lastInsertId(); 
				
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = intval($_SESSION['admin']['id']);
				$logArray['desc'] = $log;
				$logArray['create_time'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				return true;
			} else {
				return false;
			}
		}
		
		function listAdmintypes() {
			global $db;
			try {
				$sql = $db->query("SELECT * FROM admintypes ORDER BY `title` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		function listOneType($id, $tag='id') {
			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM admintypes WHERE `".$tag."` = :id");
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
		
		function getOneTypeField($id, $tag='id', $ref='title') {
			$data = $this->listOneType($id, $tag);
			return $data[$ref];
		}
		
		function getReadable($val, $type="right") {
			if ($type == "right") {
				if ($val == "1") {
					$result = "true";
				} else {
					$result = "false";
				}
			} else if ($type == "level") {
				if ($val == 1) {
					$result = "Normal User";
				} else if ($val == 2) {
					$result = "Admin User";
				} else if ($val == 3) {
					$result = "Power User";
				} else if ($val == 4) {
					$result = "System User";
				} else {
					$result = $val;
				}
			}
			return $result;
		}
		
		function filter($array) {
			for ($i = 0; $i < count($array); $i++) {
				$tag = explode(".", $array[$i]);
				$list[$tag[0]] = $i;
			}
			$flipped = array_flip($list);
			
			return $flipped;
		}
	}
?>