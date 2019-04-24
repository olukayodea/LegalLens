<?php
	class drafting extends common {
		function add($array) {
			$title = htmlentities(ucfirst(strtolower($this->mysql_prep($array['title']))));
			$type = $this->mysql_prep($array['type']);
			$owner = $this->mysql_prep($array['owner']);
			$status = $this->mysql_prep($array['status']);
			$create_time = $modify_time = time();
			$ref = $this->mysql_prep($array['ref']);
			
			global $db;
			$value_array = array(
							':title' => $title, 
							':type' => $type,
							':owner' => $owner,
							':status' => $status, 
							':create_time' => $create_time,
							':modify_time' => $modify_time
							);
			if ($ref != "") {
				$firstpart = "`ref`, ";
				$secondPArt = ":ref, ";
				$value_array[':ref'] = $ref;
				$log = "Modified object ".$title;
			} else {
				$firstpart = "";
				$secondPArt = "";
				$log = "Created object ".$title;
			}			
			
			try {
				$sql = $db->prepare("INSERT INTO `list_library` (".$firstpart."`title`, `type`, `owner`, `status`, `create_time`, `modify_time`) 
				VALUES (".$secondPArt.":title, :type, :owner, :status, :create_time, :modify_time)
					ON DUPLICATE KEY UPDATE 
						`title` = :title,
						`type` = :type,
						`owner` = :owner,
						`status` = :status,
						`modify_time` = :modify_time
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
				$logArray['create_date'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				return $id;
			} else {
				return false;
			}
		}
		
		function remove($id) {
			$id = $this->mysql_prep($id);
			$modDate = time();
			
			global $db;
			try {
				$sql = $db->prepare("DELETE FROM `drafting` WHERE `ref` =:id");
				$sql->execute(
					array(
					':id' => $id)
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			try {
				$sql = $db->prepare("DELETE FROM `drafting_sections` WHERE `drafting` =:id");
				$sql->execute(
					array(
					':id' => $id)
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
				$logArray['desc'] = "removed document id #".$id;
				$logArray['create_date'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				return true;
			} else {
				return false;
			}
		}
		
		function modifyOne($tag, $value, $id) {
			$value = $this->mysql_prep($value);
			$id = $this->mysql_prep($id);
			$modDate = time();			
			
			global $db;
			try {
				$sql = $db->prepare("UPDATE `drafting` SET  `".$tag."` = :value, `modify_time` = '".$modDate."' WHERE `ref`=:id");
				$sql->execute(
					array(
					':value' => $value,
					':modify_time' => time(),
					':id' => $id)
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
				$logArray['desc'] = "Modified ".$tag." with ".$value;
				$logArray['create_date'] = time();
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
				$sql = $db->query("SELECT * FROM `drafting` ORDER BY `title` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				$result = array();
				$count = 0;
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['title'] = ucwords(strtolower($row['title']));
					$result[$count]['type'] = $row['type'];
					$result[$count]['owner'] = $row['owner'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function listAllHome($type) {
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `drafting` WHERE `type` = '".$type."' AND `status` = 'active' ORDER BY `title` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				$result = array();
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$tag = substr(ucwords(strtolower($row['title'])), 0, 1);
					$count = count($result[$tag]);
					$result[$tag][$count]['ref'] = $row['ref'];
					$result[$tag][$count]['title'] = ucwords(strtolower($row['title']));
					$result[$tag][$count]['type'] = $row['type'];
					$result[$tag][$count]['owner'] = $row['owner'];
					$result[$tag][$count]['status'] = $row['status'];
					$result[$tag][$count]['create_time'] = $row['create_time'];
					$result[$tag][$count]['modify_time'] = $row['modify_time'];
				}
				ksort($result);
				return $this->out_prep($result);
			}
		}		
		
		function quickSearch($val, $type) {
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `drafting` WHERE `title` LIKE '%".$val."%' AND `type` = '".$type."' AND `status` = 'active' ORDER BY `title` ASC LIMIT 20");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				$result = array();
				$count = 0;
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result[$count]['label'] = ucwords(strtolower($row['title']));
					$result[$count]['category'] = "Draft Clauses";
					$result[$count]['type'] = "Clauses";
					$result[$count]['code'] = $row['ref'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}		
		
		function quickSearchSections($val, $type) {
			global $db;
			try {
				$sql = $db->query("SELECT `drafting`.`title`, `drafting`.`ref` AS 'draft_ID', `drafting_sections`.`section_content`, `drafting_sections`.`tags`, `drafting_sections`.`ref` AS 'section_ID' FROM `drafting`, `drafting_sections` WHERE `drafting`.`ref` = `drafting_sections`.`drafting` AND `drafting`.`status` = 'active' AND `drafting_sections`.`status` = 'active' AND `drafting`.`type` = '".$type."' AND (`drafting_sections`.`tags` LIKE '%".$val."%' OR `drafting_sections`.`section_content` LIKE '%".$val."%' OR MATCH(`drafting_sections`.`section_content`) AGAINST ('".$val."')) ORDER BY `title` ASC LIMIT 20");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				$result = array();
				$count = 0;
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result[$count]['label'] = substr(ucwords(strtolower($row['section_content'])), 0, 50)."...";
					$result[$count]['category'] = "Section found in ".$row['title'];
					$result[$count]['type'] = "Section";
					$result[$count]['code'] = $row['section_ID'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function fullSearch($val, $type) {
			$val = $this->mysql_prep($val);
			global $db;
			try {
				$sql = $db->query("SELECT `drafting`.`title`, `drafting`.`ref`, `drafting`.`type`, `drafting`.`status`, `drafting`.`create_time`, `drafting`.`modify_time` FROM `drafting`, `drafting_sections` WHERE `drafting`.`ref` = `drafting_sections`.`drafting` AND `drafting`.`status` = 'active' AND `drafting_sections`.`status` = 'active' AND `drafting`.`type` = '".$type."' AND (`drafting`.`title` LIKE '%".$val."%' OR `drafting_sections`.`tags` LIKE '%".$val."%' OR `drafting_sections`.`tags` LIKE '%".$val."%' OR `drafting_sections`.`section_content` LIKE '%".$val."%' OR MATCH(`drafting_sections`.`section_content`) AGAINST ('".$val."')) ORDER BY `title` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				$result = array();
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$tag = substr(ucwords(strtolower($row['title'])), 0, 1);
					$count = count($result[$tag]);
					$result[$tag][$count]['ref'] = $row['ref'];
					$result[$tag][$count]['title'] = ucwords(strtolower($row['title']));
					$result[$tag][$count]['type'] = $row['type'];
					$result[$tag][$count]['status'] = $row['status'];
					$result[$tag][$count]['owner'] = $row['owner'];
					$result[$tag][$count]['create_time'] = $row['create_time'];
					$result[$tag][$count]['modify_time'] = $row['modify_time'];
				}
				ksort($result);
				return $this->out_prep($result);
			}
		}
		
		function indexSearch($val, $type) {
			$val = $this->mysql_prep($val);
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `drafting` WHERE `title` LIKE '".$val."%' AND `type` = '".$type."' AND `status` = 'active' ORDER BY `title` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}

			
			if ($sql) {
				$result = array();
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$tag = substr(ucwords(strtolower($row['title'])), 0, 1);
					$count = count($result[$tag]);
					$result[$tag][$count]['ref'] = $row['ref'];
					$result[$tag][$count]['title'] = ucwords(strtolower($row['title']));
					$result[$tag][$count]['type'] = $row['type'];
					$result[$tag][$count]['status'] = $row['status'];
					$result[$tag][$count]['owner'] = $row['owner'];
					$result[$tag][$count]['create_time'] = $row['create_time'];
					$result[$tag][$count]['modify_time'] = $row['modify_time'];
				}
				ksort($result);
				return $this->out_prep($result);
			}
		}
		
		function lisstMultiple($array) {
			$list = implode(",", $array);
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `drafting` WHERE ref IN (".$list.") ORDER BY `title` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}

			if ($sql) {
				$result = array();
				$count = 0;
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['title'] = ucwords(strtolower($row['title']));
					$result[$count]['type'] = $row['type'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['owner'] = $row['owner'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
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
				$sql = $db->prepare("SELECT * FROM `drafting` WHERE `".$tag."` = :id".$sqlTag." ORDER BY `".$order."` ".$dir);
								
				$sql->execute($token);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['title'] = ucwords(strtolower($row['title']));
					$result[$count]['type'] = $row['type'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['owner'] = $row['owner'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function getOne($id, $tag='ref') {
			$id = $this->mysql_prep($id);


			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM drafting WHERE `".$tag."` = :id ORDER BY `ref` DESC LIMIT 1");
				$sql->execute(
					array(
					':id' => $id)
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}

			if ($sql) {
				$result = array();
				
				if ($sql->rowCount() == 1) {
					$row = $sql->fetch(PDO::FETCH_ASSOC);
					$result['ref'] = $row['ref'];
					$result['title'] = ucwords(strtolower($row['title']));
					$result['type'] = $row['type'];
					$result['status'] = $row['status'];
					$result['owner'] = $row['owner'];
					$result['create_time'] = $row['create_time'];
					$result['modify_time'] = $row['modify_time'];
					return $this->out_prep($result);
				} else {
					return false;
				}
			}
		}
		
		function getOneField($id, $tag="ref", $ref="title") {
			$data = $this->getOne($id, $tag);
			return $data[$ref];
		}
		
		function counter($id, $from=false, $to=false) {
			
			if ($from != false) {
				$ad = " AND `date_time` BETWEEN ".$from." AND ".$to;
			}

			global $db;
			try {
				$sql = $db->query("SELECT * FROM `counter_log` WHERE `type` = 'drafting' AND `id` IN (SELECT `ref` FROM `drafting` WHERE `owner` = '".$id."')");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['id'] = $row['id'];
					$result[$count]['user_id'] = $row['user_id'];
					$result[$count]['title'] = $this->getOneField($row['id']);
					$result[$count]['section'] = "";
					$result[$count]['type'] = $row['type'];
					$result[$count]['date_time'] = $row['date_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function total($id, $from=false, $to=false) {
			$data = $this->counter($id, $from, $to);
			$total = 0;
			for ($i = 0; $i < count($data); $i++) {
				$total = $total + 1;
			}
			
			return $total;
		}
	}
?>