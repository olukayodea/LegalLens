<?php
class help extends common {
		function add($array, $fles, $adm=false) {
			$category = htmlentities($this->mysql_prep($array['category']));
			$content = htmlentities($this->mysql_prep($array['content']));
			$parent_id = $this->mysql_prep($array['parent_id']);
			$user_id = $this->mysql_prep($array['user_id']);
			$admin_id = $this->mysql_prep($array['admin_id']);
			$response_id = $this->mysql_prep($array['response_id']);
			$create_time = $modify_time = time();
			if ($fles["media_file"]["error"] == 4) {
				$good = true;
				$upload_file = "";
			} else {
				$file_array = $this->uploadFile($fles);
				if ($file_array['title'] == "ERROR") {
					$good = false;
				} else {
					$good = true;
					$upload_file = $file_array['desc'];
				}
			}
			
			if ($good == true) {
				global $db;
				try {
					$sql = $db->prepare("INSERT INTO `help` (`category`,`content`,`parent_id`,`user_id`,`admin_id`,`response_id`,`file`,`create_time`,`modify_time`) 
					VALUES (:category,:content,:parent_id,:user_id,:admin_id,:response_id,:file,:create_time,:modify_time)");
					$sql->execute(array(
								':category' => $category, 
								':content' => $content, 
								':parent_id' => $parent_id,
								':user_id' => $user_id,
								':admin_id' => $admin_id,
								':response_id' => $response_id,
								':file' => $upload_file,
								':create_time' => $create_time,
								':modify_time' => $modify_time));
				} catch(PDOException $ex) {
					echo "An Error occured! ".$ex->getMessage(); 
				}
				if ($sql) {
					$id = $db->lastInsertId();
					
					$this->statusMail($id);
					if ($adm == false) {
						$notification = new notification;
						$notification_array['type'] = "help";
						$notification_array['type_id'] = $id;
						$notification_array['desc'] = "New Support Ticket";
						$notification->create($notification_array);
					}
					return $id;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
		
		function uploadFile($array) {
			ini_set("memory_limit", "200000000");
						
			$uploadedfile = $array['media_file']['tmp_name'];
			$msg = array();
			if ($array["media_file"]["error"] == 1) {
				$msg['title'] = "ERROR";
				$msg['desc'] = "The uploaded file exceeds the mazimum upload file limit";
			} else if ($array["media_file"]["error"] == 2 ) {
				$msg['title'] = "ERROR";
				$msg['desc'] = "The uploaded file exceeds the mazimum upload file limit";
			} else if ($array["media_file"]["error"] == 3) {
				$msg['title'] = "ERROR";
				$msg['desc'] = "The uploaded file was only partially uploaded, please re-upload file";
			} else if ($array["media_file"]["error"] == 4) {
				$msg['title'] = "ERROR";
				$msg['desc'] = "Missing file, please check the uploaded file and try again";
			} else if ($array["media_file"]["error"] == 6) {
				$msg['title'] = "ERROR";
				$msg['desc'] = "Missing a temporary folder, contact the website administrator";
			} else if ($array["media_file"]["error"] == 7) {
				$msg['title'] = "ERROR";
				$msg['desc'] = "Failed to write file to disk, contact the administrator";
			} else if ($array["media_file"]["error"] == 0) {
				$media_file = stripslashes($array['media_file']['name']);
				$uploadedfile = $array['media_file']['tmp_name']; 
				$extension = $this->getExtension($media_file);
				$extension = strtolower($extension);
				
				if($array['media_file']['size'] < 2097152) {
					//$size=filesize($array['media_file']['tmp_name']);
					
					$userDoc = "library/helpfiles/";
					$file = time().rand(100, 999).".".$extension;
					
					if(!is_dir($userDoc)) {
						mkdir($userDoc, 0777, true);
					}
					
					$newFile = $userDoc.$file;
					$move = move_uploaded_file($uploadedfile, $newFile);
					
					if ($move) {
						$msg['title'] = "OK";
						$msg['desc'] = $file;
					} else {
						$msg['title'] = "ERROR";
						$msg['desc'] = "an error occured";
					}
				} else {
					$msg['title'] = "ERROR";
					$msg['desc'] = "the file exceed 2MB is not allowed";
				}
			}
			return $msg;
		}
		
		function remove($id) {
			$id = $this->mysql_prep($id);
			
			global $db;
			try {
				$sql = $db->prepare("DELETE FROM `help` WHERE `ref` =:id");
				$sql->execute(
					array(
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
		
		function modifyOne($tag, $value, $id) {
			$value = $this->mysql_prep($value);
			$id = $this->mysql_prep($id);

			global $db;
			try {
				$sql = $db->prepare("UPDATE `help` SET  `".$tag."` = :value WHERE `ref`=:id");
				$sql->execute(
					array(
					':value' => $value,
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
		
		function close($id) {
			$id = $this->mysql_prep($id);
			$this->modifyOne("status", "2", $id);
			
			global $db;
			try {
				$sql = $db->prepare("UPDATE `help` SET  `status` = '2' WHERE `parent_id`=:id");
				$sql->execute(
					array(
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
		
		function listAll() {
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `help` ORDER BY `ref`, `status` DESC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		function sortAll($id, $tag, $tag2=false, $id2=false, $tag3=false, $id3=false, $order='ref', $dir="ASC") {
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
				$sql = $db->prepare("SELECT * FROM `help` WHERE `".$tag."` = :id".$sqlTag." ORDER BY `".$order."` ".$dir);
								
				$sql->execute($token);
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
				$sql = $db->prepare("SELECT * FROM help WHERE `".$tag."` = :id ORDER BY `ref` DESC LIMIT 1");
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
		
		function getOneField($id, $tag="ref", $cat_id="category") {
			$data = $this->getOne($id, $tag);
			return $data[$cat_id];
		}
		
		function status($va) {
			if ($va == 0) {
				return "New";
			} else if ($va == 1) {
				return "Opened";
			} else if ($va == 2) {
				return "Closed";
			}
		}
		
		function statusMail($id) {
			$users = new users;
			$data = $this->getOne($id);
			$user_data = $users->listOne($data['user_id']);
			$last_name = $user_data['last_name'];
			$other_names = $user_data['other_names'];
			$email = $user_data['email'];
			
			$client = $last_name." ".$other_names." <".$email.">";
			$subjectToClient = "LegalLens Help Notification";
			
			$contact = "LegalLens <".replyMail.">";
				
			$fields = 'subject='.urlencode($subjectToClient).
				'&last_name='.urlencode($last_name).
				'&other_names='.urlencode($other_names).
				'&email='.urlencode($email).
				'&status='.urlencode($data['status']).
				'&id='.urlencode($data['ref']).
				'&time='.urlencode(time());
			$mailUrl = URL."includes/emails/help_notfication.php?".$fields;
			$messageToClient = $this->curl_file_get_contents($mailUrl);
			
			$mail['from'] = $contact;
			$mail['to'] = $client;
			$mail['subject'] = $subjectToClient;
			$mail['body'] = $messageToClient;
			
			$alerts = new alerts;
			$alerts->sendEmail($mail);
		}
	}
?>