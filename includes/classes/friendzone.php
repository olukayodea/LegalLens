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
					global $db;
					try {
						$sql = $db->prepare("INSERT INTO `friendzone` (`user`, `friend_id`,`status`,`create_time`, `modify_time`) 
						VALUES (:user, :friend_id, :status, :create_time, :modify_time )");
						$sql->execute(
							array(
								'::user, ' => $ref,
								':friend_id' => $id,
								':status' => 1,
								':create_time' => $create_time,
								':modify_time' => $modify_time)
						);
					} catch(PDOException $ex) {
						echo "An Error occured! ".$ex->getMessage(); 
					}
					if ($sql) {
						$ref_id = $db->lastInsertId();
						try {
							$sql = $db->prepare("INSERT INTO `friendzone` (`user`, `friend_id`,`ref_id`,`status`,`create_time`, `modify_time`) 
							VALUES (:user, :friend_id, :ref_id, :status, :create_time, :modify_time )");
							$sql->execute(
								array(
									'::user, ' => $id,
									':friend_id' => $ref,
									':ref_id' => $ref_id,
									':status' => 0,
									':create_time' => $create_time,
									':modify_time' => $modify_time)
							);
						} catch(PDOException $ex) {
							echo "An Error occured! ".$ex->getMessage(); 
						}
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

			global $db;
			try {
				$sql = $db->query("SELECT * FROM `friendzone` WHERE `user` = '".$ref."' AND `friend_id` = '".$id."' ORDER BY `ref` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql->rowCount() == 1) {			
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
			global $db;
			try {
				$sql = $db->prepare("DELETE FROM `friendzone` WHERE `ref` =:id");
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
				$sql = $db->prepare("UPDATE `friendzone` SET  `".$tag."` = :value, `modify_time` = :modifyTime WHERE `ref`=:id");
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
		
		function getList($id) {
			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM friendzone WHERE `user` = :id ORDER BY `ref` ASC");
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
		
		function findName($value) {
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `users` WHERE (`last_name` LIKE '%".$value."%' OR `other_names` LIKE '%".$value."%' OR `email` LIKE '%".$value."%' OR `phone` LIKE '%".$value."%') AND status != 'DELETED' AND `subscription` > '".time()."' ORDER BY `last_name` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
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
					$sql = $db->prepare("SELECT * FROM `friendzone` WHERE `".$tag."` = :id".$sqlTag." ORDER BY `".$order."` ASC");
									
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
				$sql = $db->prepare("SELECT * FROM pages WHERE `".$tag."` = :id ORDER BY `ref` DESC LIMIT 1");
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
	}
?>