<?php
	class admin extends common {
		function activate($password) {
			$id = $_SESSION['admin']['id'];
			$password = $this->mysql_prep($password);
			$array = array();
			$array['id'] = $id;
			$array['password'] = $password;
			$timeStamp = time();
			$change = self::updatePassword($array);
			
			if ($change){
				$sql = mysql_query("UPDATE admin SET status = 'ACTIVE', timeStamp = '".$timeStamp."' WHERE id = '".$id."'") or die (mysql_error());
				if ($sql) {
					$_SESSION['admin']['status'] = "ACTIVE";
					
					//add to log
					$logArray['object'] = get_class($this);
					$logArray['object_id'] = $_SESSION['admin']['id'];
					$logArray['owner'] = "admin";
					$logArray['owner_id'] = $_SESSION['admin']['id'];
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
			
			$sql = mysql_query("UPDATE admin SET status = 'INACTIVE', timeStamp = '".$timeStamp."' WHERE id = '".$id."'") or die (mysql_error());
			if ($sql) {
				
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = $_SESSION['admin']['id'];
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
			
			$sql = mysql_query("UPDATE admin SET status = 'DELETED', timeStamp = '".$timeStamp."' WHERE id = '".$id."'") or die (mysql_error());
			if ($sql) {
				
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = $_SESSION['admin']['id'];
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
			$sql = mysql_query("SELECT * FROM admin WHERE (`username` = '".$username."' OR `email` = '".$username."') AND password = '".sha1($password)."' AND `status` != 'DELETED'") or die (mysql_error());
			
			if (mysql_num_rows($sql) == 1) {
				$row = mysql_fetch_array($sql);
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
				$logArray['object_id'] = $_SESSION['admin']['id'];
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = $_SESSION['admin']['id'];
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
			$logArray['object_id'] = $_SESSION['admin']['id'];
			$logArray['owner'] = "admin";
			$logArray['owner_id'] = $_SESSION['admin']['id'];
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
			$sql = mysql_query("SELECT `id` FROM `admin` WHERE email = '".$email."' AND `status` != 'DELETED'") or die (mysql_error());
			$result = mysql_num_rows($sql);
			
			return $result;
		}
		
		function create ($array) {
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
				$sql = mysql_query("INSERT INTO admin (`username`, `password`, `name`, `email`, `adminType`, `phone`, `date_time`, `timeStamp`) VALUES ('".$username."', '".sha1($password)."', '".$name."', '".$email."', '".$adminType."', '".$phone."', '".$date_time."', '".$timeStamp."')") or die (mysql_error());
				
				$id = mysql_insert_id();
				
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = $_SESSION['admin']['id'];
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
				$mail['to'] = $title." <".$email.">";
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
			$sql = mysql_query("SELECT * FROM admin WHERE `username` = '".$key."'") or die (mysql_error()."sch");
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
			$adminType = $this->mysql_prep($array['adminType']);
			$timeStamp = time();
			$sql = mysql_query("UPDATE admin SET name = '".$name."', `adminType` = '".$adminType."', email = '".$email."', phone = '".$phone."', timeStamp = '".$timeStamp."' WHERE id = '".$id."'") or die (mysql_error());
			
			if ($sql) {
				if ($_SESSION['admin']['id'] == $id) {
					$_SESSION['admin']['name'] = $name;
					$_SESSION['admin']['phone'] = $phone;
					$_SESSION['admin']['email'] = $email;
					$_SESSION['admin']['adminType'] = $read;
					$_SESSION['admin']['timeStamp'] = $timeStamp;
				}
				
				
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = $_SESSION['admin']['id'];
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
			$timeStamp = time();
			
			if ($check == 1) {
				$data = $this->listOne($email, "email");
				$password = $this->createRandomPassword();
				
				$sql = mysql_query("UPDATE admin SET password = '".sha1($password)."', timeStamp = '".$timeStamp."' WHERE id = '".$data['id']."'") or die (mysql_error());
				
				
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
				$logArray['object_id'] = $id;
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = $_SESSION['admin']['id'];
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
			$timeStamp = time();
			
			$sql = mysql_query("UPDATE admin SET password = '".sha1($password)."', timeStamp = '".$timeStamp."' WHERE id = '".$id."'") or die (mysql_error());
			
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
				$logArray['owner_id'] = $_SESSION['admin']['id'];
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
			$sql = mysql_query("SELECT * FROM admin WHERE status != 'DELETED'") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['id'] = $row['id'];
					$result[$count]['username'] = $row['username'];
					$result[$count]['name'] = $row['name'];
					$result[$count]['email'] = $row['email'];
					$result[$count]['phone'] = $row['phone'];
					$result[$count]['adminType'] = $row['adminType'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['date_time'] = $row['date_time'];
					$result[$count]['timeStamp'] = $row['timeStamp'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function countAl() {
			$sql = mysql_query("SELECT COUNT(*) FROM admin WHERE status != 'DELETED'") or die (mysql_error());
			if ($sql) {				
				$row = mysql_fetch_array($sql);
				return $row[0];
			}
		}
		
		function sortList($tag, $id) {
			$id = $this->mysql_prep($id);
			$sql = mysql_query("SELECT * FROM admin WHERE `".$tag."` = '".$id."'") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['id'] = $row['id'];
					$result[$count]['username'] = $row['username'];
					$result[$count]['name'] = $row['name'];
					$result[$count]['email'] = $row['email'];
					$result[$count]['phone'] = $row['phone'];
					$result[$count]['adminType'] = $row['adminType'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['date_time'] = $row['date_time'];
					$result[$count]['timeStamp'] = $row['timeStamp'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function listOne($id, $tag='id') {
			$id = $this->mysql_prep($id);
			$sql = mysql_query("SELECT * FROM admin WHERE `".$tag."` = '".$id."' AND `status` != 'DELETED'") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				
				$row = mysql_fetch_array($sql);
				$result['id'] = $row['id'];
				$result['username'] = $row['username'];
				$result['password'] = $row['password'];
				$result['name'] = $row['name'];
				$result['email'] = $row['email'];
				$result['adminType'] = $row['adminType'];
				$result['status'] = $row['status'];
				$result['phone'] = $row['phone'];
				$result['date_time'] = $row['date_time'];
				$result['timeStamp'] = $row['timeStamp'];
				
				return $this->out_prep($result);
			} else {
				return false;
			}
		}
		
		function getOneField($id, $tag='id', $ref='name') {
			$data = $this->listOne($id, $tag);
			return $data[$ref];
		}
		
		function createAdminType($array) {
			$title = $this->mysql_prep($array['title']);
			$read = $this->mysql_prep($array['read']);
			$write = $this->mysql_prep($array['write']);
			$modify = $this->mysql_prep($array['modify']);
			$mainPage = $this->mysql_prep($array['mainPage']);
			$level = $this->mysql_prep($array['level']);
			$id = $this->mysql_prep($array['id']);
			$pages = implode(",", $array['pages']);
			$createTime = $modifyTime = time();
			$creator = $lastModified = $_SESSION['admin']['id'];
			
			if ($id != "") {
				$firstpart = "`id`, ";
				$secondPArt = "'".$id."', ";
				$log = "Modified admin type ".$title;
			} else {
				$firstpart = "";
				$secondPArt = "";
				$log = "created new admin type ".$title;
			}
			
			$sql = mysql_query("INSERT INTO admintypes (".$firstpart."`title`, `level`, `read`, `write`, `modify`, `mainPage`, `pages`, `createTime`, `modifyTime`, `creator`, `lastModified`) VALUES (".$secondPArt."'".$title."', '".$level."', '".$read."', '".$write."', '".$modify."', '".$mainPage."', '".$pages."', '".$createTime."', '".$modifyTime."', '".$creator."', '".$lastModified."') ON DUPLICATE KEY UPDATE `title` = '".$title."', `read` = '".$read."', `write` = '".$write."', `modify` = '".$modify."', `mainPage` = '".$mainPage."', `pages` = '".$pages."', `level` = '".$level."', `lastModified` = '".$lastModified."', `modifyTime` = '".$modifyTime."'") or die (mysql_error());
			
			if ($sql) {
				$id = mysql_insert_id();
				
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = $_SESSION['admin']['id'];
				$logArray['desc'] = "created new admin type ".$title;
				$logArray['create_time'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				return true;
			} else {
				return false;
			}
		}
		
		function listAdmintypes() {
			$sql = mysql_query("SELECT * FROM admintypes ORDER BY `title` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['id'] = $row['id'];
					$result[$count]['title'] = $row['title'];
					$result[$count]['read'] = $row['read'];
					$result[$count]['write'] = $row['write'];
					$result[$count]['modify'] = $row['modify'];
					$result[$count]['level'] = $row['level'];
					$result[$count]['mainPage'] = $row['mainPage'];
					$result[$count]['pages'] = $row['pages'];
					$result[$count]['creator'] = $row['creator'];
					$result[$count]['lastModified'] = $row['lastModified'];
					$result[$count]['createTime'] = $row['createTime'];
					$result[$count]['modifyTime'] = $row['modifyTime'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function listOneType($id) {
			$sql = mysql_query("SELECT * FROM admintypes WHERE id = '".$id."'") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				
				$row = mysql_fetch_array($sql);
				$result['id'] = $row['id'];
				$result['title'] = $row['title'];
				$result['read'] = $row['read'];
				$result['write'] = $row['write'];
				$result['modify'] = $row['modify'];
				$result['pages'] = $row['pages'];
				$result['level'] = $row['level'];
				$result['mainPage'] = $row['mainPage'];
				$result['creator'] = $row['creator'];
				$result['lastModified'] = $row['lastModified'];
				$result['createTime'] = $row['createTime'];
				$result['modifyTime'] = $row['modifyTime'];
				
				return $this->out_prep($result);
			} else {
				return false;
			}
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