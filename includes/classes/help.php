<?php
class help extends common {
		function add($array, $fles, $adm=false) {
			$category = $this->mysql_prep($array['category']);
			$content = $this->mysql_prep($array['content']);
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
				$sql = mysql_query("INSERT INTO `help` (`category`,`content`, `parent_id`, `user_id`,`admin_id`,`response_id`,`create_time`,`modify_time`,`file`) VALUES ('".$category."','".$content."','".$parent_id."', '".$user_id."','".$admin_id."','".$response_id."','".$create_time."','".$modify_time."','".$upload_file."')") or die (mysql_error());
				
				if ($sql) {
					$id = mysql_insert_id();
					
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
						
			$image = $array["media_file"]["name"];
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
					$size=filesize($array['media_file']['tmp_name']);
					
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
			$modDate = time();
			
			$data = $this->getOne($id);
			
			$sql = mysql_query("DELETE FROM `help` WHERE ref = '".$id."'") or die (mysql_error());
			
			if ($sql) {
				return true;
			} else {
				return false;
			}
		}
		
		function modifyOne($tag, $value, $id) {
			$value = $this->mysql_prep($value);
			$id = $this->mysql_prep($id);
			$modDate = time();
			$sql = mysql_query("UPDATE `help` SET `".$tag."` = '".$value."' WHERE ref = '".$id."'") or die (mysql_error());
			
			if ($sql) {
				return true;
			} else {
				return false;
			}
		}
		
		function close($id) {
			$id = $this->mysql_prep($id);
			$modDate = time();
			$this->modifyOne("status", "2", $id);
			$sql = mysql_query("UPDATE `help` SET `status` = '2' WHERE parent_id = '".$id."'") or die (mysql_error());
			
			if ($sql) {
				return true;
			} else {
				return false;
			}
		}
		
		function listAll() {
			$sql = mysql_query("SELECT * FROM `help` ORDER BY `ref`, `status` DESC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['category'] = $row['category'];
					$result[$count]['content'] = $row['content'];
					$result[$count]['user_id'] = $row['user_id'];
					$result[$count]['parent_id'] = $row['parent_id'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['admin_id'] = $row['admin_id'];
					$result[$count]['response_id'] = $row['response_id'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$result[$count]['file'] = $row['file'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function sortAll($id, $tag, $tag2=false, $id2=false, $tag3=false, $id3=false, $order='ref', $dir="ASC") {
			$id = $this->mysql_prep($id);
			$id2 = $this->mysql_prep($id2);
			$id3 = $this->mysql_prep($id3);
			if ($tag2 != false) {
				$sqlTag = " AND `".$tag2."` = '".$id2."'";
			} else {
				$sqlTag = "";
			}
			if ($tag3 != false) {
				$sqlTag .= " AND `".$tag3."` = '".$id3."'";
			} else {
				$sqlTag .= "";
			}
			
			
			$sql = mysql_query("SELECT * FROM `help` WHERE `".$tag."` = '".$id."'".$sqlTag." ORDER BY `".$order."` ".$dir) or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['category'] = $row['category'];
					$result[$count]['content'] = $row['content'];
					$result[$count]['user_id'] = $row['user_id'];
					$result[$count]['parent_id'] = $row['parent_id'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['admin_id'] = $row['admin_id'];
					$result[$count]['response_id'] = $row['response_id'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$result[$count]['file'] = $row['file'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function getOne($id, $tag='ref') {
			$id = $this->mysql_prep($id);
			$sql = mysql_query("SELECT * FROM `help` WHERE `".$tag."` = '".$id."' ORDER BY `ref` DESC LIMIT 1") or die (mysql_error());
			if ($sql) {
				$result = array();
				
				if (mysql_num_rows($sql) == 1) {
					$row = mysql_fetch_array($sql);
					$result['ref'] = $row['ref'];
					$result['category'] = $row['category'];
					$result['content'] = $row['content'];
					$result['user_id'] = $row['user_id'];
					$result['parent_id'] = $row['parent_id'];
					$result['admin_id'] = $row['admin_id'];
					$result['response_id'] = $row['response_id'];
					$result['create_time'] = $row['create_time'];
					$result['modify_time'] = $row['modify_time'];
					$result['file'] = $row['file'];
					$result['status'] = $row['status'];
					return $this->out_prep($result);
				} else {
					return false;
				}
			}
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
				'&time='.urlencode($modify_time);
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