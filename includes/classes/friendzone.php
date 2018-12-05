<?php
	class friendzone extends common {
		function add($array) {
			$ref = $this->mysql_prep($array['ref']);
			$id = $this->mysql_prep($array['id']);
			$create_time = $modify_time = time();
			
			if ($ref == $id) {
				return false;
			} else {
				if ($this->checkDuplicate($array)) {
					$sql = mysql_query("INSERT INTO `friendzone` (`user`, `friend_id`,`status`,`create_time`, `modify_time`) VALUES ('".$ref."','".$id."','1','".$create_time."', '".$modify_time."')") or die (mysql_error());
					if ($sql) {
						
						$ref_id = mysql_insert_id();
						$sql = mysql_query("INSERT INTO `friendzone` (`user`, `friend_id`,`status`,`ref_id`,`create_time`, `modify_time`) VALUES ('".$id."','".$ref."','0',".$ref_id.",'".$create_time."', '".$modify_time."')") or die (mysql_error());
						
						//send email
						$users = new users;
						$client = $users->getOneField($id, "ref", "last_name")." ".$users->getOneField($id, "ref", "other_names")." <".$users->getOneField($id, "ref", "email").">";
						$subjectToClient = "LegalLens Friendzone";
						
						$contact = "LegalLens <".replyMail.">";
							
						$fields = 'subject='.urlencode($subjectToClient).
							'&ref_id='.urlencode($ref_id);
						$mailUrl = URL."includes/emails/friend_zone.php?".$fields;
						$messageToClient = $this->curl_file_get_contents($mailUrl);
						
						$mail['from'] = $contact;
						$mail['to'] = $client;
						$mail['subject'] = $subjectToClient;
						$mail['body'] = $messageToClient;
						
						$alerts = new alerts;
						$alerts->sendEmail($mail);
					
						return true;
					} else {
						return false;
					}
				} else {
					return false;
				}
			}
		}
		
		function checkDuplicate($array) {
			$ref = $this->mysql_prep($array['ref']);
			$id = $this->mysql_prep($array['id']);
			
			$sql = mysql_query("SELECT * FROM `friendzone` WHERE `user` = '".$ref."' AND `friend_id` = '".$id."' ORDER BY `ref` ASC") or die (mysql_error());
			
			if (mysql_num_rows($sql) > 0) {
				return false;
			} else {
				return true;
			}
		}
		
		function approve($id) {
			$data = $this->getOne($id);
			$this->modifyOne("status", 2, $id);
			$this->modifyOne("status", 2, $data['ref_id']);
			
			return true;
		}
		
		function deny($id) {
			$data = $this->getOne($id);
			$this->remove($id);
			$this->remove($data['ref_id']);
			
			return true;
		}
		
		function remove($id) {
			$sql = mysql_query("DELETE FROM `friendzone` WHERE ref = '".$id."'") or die (mysql_error());
				
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
			$sql = mysql_query("UPDATE `friendzone` SET `".$tag."` = '".$value."', `modify_time` = '".$modDate."' WHERE ref = '".$id."'") or die (mysql_error());
			
			if ($sql) {
				return true;
			} else {
				return false;
			}
		}
		
		function getList($id) {
			
			$sql = mysql_query("SELECT * FROM `friendzone` WHERE `user` = '".$id."' ORDER BY `ref` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['user'] = $row['user'];
					$result[$count]['friend_id'] = $row['friend_id'];
					$result[$count]['ref_id'] = $row['ref_id'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function findName($value) {
			$sql = mysql_query("SELECT * FROM `users` WHERE (`last_name` LIKE '%".$value."%' OR `other_names` LIKE '%".$value."%' OR `email` LIKE '%".$value."%' OR `phone` LIKE '%".$value."%') AND status != 'DELETED' AND `subscription` > '".time()."' ORDER BY `last_name` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['last_name'] = $row['last_name'];
					$result[$count]['other_names'] = $row['other_names'];
					$count++;
				}
				return $this->out_prep($result);
			} else {
				return false;
			}
		}
		
		
		function sortAll($id, $tag, $tag2=false, $id2=false, $tag3=false, $id3=false) {
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
			
			$sql = mysql_query("SELECT * FROM `friendzone` WHERE `".$tag."` = '".$id."'".$sqlTag." ORDER BY `ref` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['user'] = $row['user'];
					$result[$count]['friend_id'] = $row['friend_id'];
					$result[$count]['ref_id'] = $row['ref_id'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function getOne($id, $tag='ref') {
			$id = $this->mysql_prep($id);
			$sql = mysql_query("SELECT * FROM `friendzone` WHERE `".$tag."` = '".$id."' ORDER BY `ref` DESC LIMIT 1") or die (mysql_error());
			if ($sql) {
				$result = array();
				
				if (mysql_num_rows($sql) == 1) {
					$row = mysql_fetch_array($sql);
					$result['ref'] = $row['ref'];
					$result['user'] = $row['user'];
					$result['friend_id'] = $row['friend_id'];
					$result['ref_id'] = $row['ref_id'];
					$result['status'] = $row['status'];
					$result['create_time'] = $row['create_time'];
					$result['modify_time'] = $row['modify_time'];
					return $this->out_prep($result);
				} else {
					return false;
				}
			}
		}
	}
?>