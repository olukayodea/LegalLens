<?php
	class regulations extends common {
		function add($array) {
			$title = htmlentities($this->mysql_prep($array['title']));
			$status = $this->mysql_prep($array['status']);
			$regulator = $this->mysql_prep($array['regulator']);
			$type = $this->mysql_prep($array['type']);
			$year = $this->mysql_prep($array['year']);
			$tags = $this->mysql_prep($array['tags']);
			$create_time = $modify_time = time();
			$ref = $this->mysql_prep($array['ref']);			

			global $db;
			$value_array = array(
							':title' => $title, 
							':regulator' => $regulator, 
							':type' => $type, 
							':year' => $year, 
							':tags' => $tags, 
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
				$sql = $db->prepare("INSERT INTO `regulations` (".$firstpart."`title`, `status`, `regulator`,`year`, `type`, `tags`, `create_time`, `modify_time`) 
				VALUES (".$secondPArt.":title, :status, :regulator, :year, :type, :tags, :create_time, :modify_time)
					ON DUPLICATE KEY UPDATE 
						`title` = :title,
						`status` = :status,
						`regulator` = :regulator,
						`type` = :type,
						`tags` = :tags,
						`year` = :year,
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
			global $db;
			try {
				$sql = $db->prepare("DELETE FROM `regulations` WHERE `ref` =:id");
				$sql->execute(
					array(
					':id' => $id)
				);
				$sql2 = $db->prepare("DELETE FROM `regulations_sections` WHERE `regulations` =:id");
				$sql2->execute(
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
				$logArray['desc'] = "removed regulator id #".$id;
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
			
			global $db;
			try {
				$sql = $db->prepare("UPDATE `regulations` SET  `".$tag."` = :value, `modify_time` = :modify_time WHERE `ref`=:id");
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
				$sql = $db->query("SELECT * FROM `regulations` ORDER BY `ref` DESC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		function listAllHome($type=false, $filter="title") {
			if ($type != false) {
				$addition = "`regulator` = '".$type."' AND ";
			} else {
				$addition = "";
			}
			
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `regulations` WHERE ".$addition."`status` = 'active' ORDER BY `title` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				$result = array();
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					if ($filter == "title") {
						$tag = substr(ucwords(strtolower($row['title'])), 0, 1);
					} else {
						$tag = $row['year'];
					}
					$count = count($result[$tag]);
					$result[$tag][$count]['ref'] = $row['ref'];
					$result[$tag][$count]['title'] = $row['title'];
					$result[$tag][$count]['status'] = $row['status'];
					$result[$tag][$count]['regulator'] = $row['regulator'];
					$result[$tag][$count]['year'] = $row['year'];
					$result[$tag][$count]['type'] = $row['type'];
					$result[$tag][$count]['tags'] = $row['tags'];
					$result[$tag][$count]['create_time'] = $row['create_time'];
					$result[$tag][$count]['modify_time'] = $row['modify_time'];
				}
				ksort($result);
				return $this->out_prep($result);
			}
		}
		
		function quickSearchSections($val, $type=false,$sort="ALL") {
			$val = $this->mysql_prep($val);
			if ($type != false) {
				$addition = "`regulations`.`regulator` = '".$type."' AND ";
			} else {
				$addition = "";
			}
			if ($sort != "ALL") {
				$addition .= "`type` = '".$sort."' AND ";
			} else {
				$addition .= "";
			}			

			global $db;
			try {
				$sql = $db->query("SELECT `regulations`.`title`, `regulations`.`ref`, `regulations_sections`.`section_content`, `regulations_sections`.`tags`, `regulations`.`type`, `regulations_sections`.`ref` AS 'section_ID' FROM `regulations`, `regulations_sections` WHERE `regulations`.`ref` = `regulations_sections`.`regulations` AND `regulations`.`status` = 'active' AND `regulations_sections`.`status` = 'active' AND ".$addition."(`regulations`.`tags` LIKE '%".$val."%' OR `regulations_sections`.`tags` LIKE '%".$val."%' OR `regulations_sections`.`section_content` LIKE '%".$val."%' OR MATCH(`regulations_sections`.`section_content`) AGAINST ('".$val."')) ORDER BY `title` ASC LIMIT 20");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				$result = array();
				$count = 0;
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result[$count]['label'] = ucwords(strtolower($row['title']));
					$result[$count]['category'] = "Sections in ".$row['type']."s";
					$result[$count]['type'] = $row['type'];
					$result[$count]['code'] = $row['ref'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function quickSearch($val, $type=false,$sort="ALL") {
			$val = $this->mysql_prep($val);
			if ($type != false) {
				$addition = "`regulator` = '".$type."' AND ";
			} else {
				$addition = "";
			}
			if ($sort != "ALL") {
				$addition .= "`type` = '".$sort."' AND ";
			} else {
				$addition .= "";
			}			

			global $db;
			try {
				$sql = $db->query("SELECT * FROM `regulations` WHERE (`year` LIKE '%".$val."%' OR `tags` LIKE '%".$val."%' OR `title` LIKE '%".$val."%') AND ".$addition."`status` = 'active' ORDER BY `title` ASC LIMIT 20");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				$result = array();
				$count = 0;
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result[$count]['label'] = ucwords(strtolower($row['title']));
					$result[$count]['category'] = trim($row['type']);
					$result[$count]['type'] = $row['type'];
					$result[$count]['code'] = $row['ref'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}		
		
		function fullSearch($val, $type=false,$sort="ALL", $filter="title") {
			$val = $this->mysql_prep($val);
			if ($type != false) {
				$addition = "`regulations`.`regulator` = '".$type."' AND ";
			} else {
				$addition = "";
			}
			if ($sort != "ALL") {
				$addition .= "`type` = '".$sort."' AND ";
			} else {
				$addition .= "";
			}			

			global $db;
			try {
				$sql = $db->query("SELECT `regulations`.`title`, `regulations`.`regulator`, `regulations`.`tags`, `regulations`.`create_time`, `regulations`.`modify_time`, `regulations`.`ref`, `regulations_sections`.`section_content`, `regulations_sections`.`tags` FROM `regulations`, `regulations_sections` WHERE `regulations`.`ref` = `regulations_sections`.`regulations` AND `regulations`.`status` = 'active' AND `regulations_sections`.`status` = 'active' AND ".$addition."(`regulations`.`year` LIKE '%".$val."%' OR `regulations`.`title` LIKE '%".$val."%' OR `regulations`.`tags` LIKE '%".$val."%' OR `regulations_sections`.`tags` LIKE '%".$val."%' OR `regulations_sections`.`section_content` LIKE '%".$val."%' OR MATCH(`regulations_sections`.`section_content`) AGAINST ('".$val."')  OR MATCH(`regulations`.`tags`) AGAINST ('".$val."')  OR MATCH(`regulations_sections`.`tags`) AGAINST ('".$val."')) ORDER BY `title` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				$result = array();
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					if ($filter == "title") {
						$tag = substr(ucwords(strtolower($row['title'])), 0, 1);
					} else {
						$tag = $row['year'];
					}
					$count = count($result[$tag]);
					$result[$tag][$count]['ref'] = $row['ref'];
					$result[$tag][$count]['title'] = $row['title'];
					$result[$tag][$count]['regulator'] = $row['regulator'];
					$result[$tag][$count]['year'] = $row['year'];
					$result[$tag][$count]['type'] = $row['type'];
					$result[$tag][$count]['tags'] = $row['tags'];
					$result[$tag][$count]['create_time'] = $row['create_time'];
					$result[$tag][$count]['modify_time'] = $row['modify_time'];
				}
				ksort($result);
				return $this->out_prep($result);
			}
		}
		
		function indexSearch($val, $type=false, $filter="title") {
			$val = $this->mysql_prep($val);
			if ($type != false) {
				$addition = "`regulator` = '".$type."' AND ";
			} else {
				$addition = "";
			}			

			global $db;
			try {
				$sql = $db->query("SELECT * FROM `regulations` WHERE `title` LIKE '".$val."%' AND ".$addition."`status` = 'active' ORDER BY `title` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				$result = array();
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					if ($filter == "title") {
						$tag = substr(ucwords(strtolower($row['title'])), 0, 1);
					} else {
						$tag = $row['year'];
					}
					$count = count($result[$tag]);
					$result[$tag][$count]['ref'] = $row['ref'];
					$result[$tag][$count]['title'] = $row['title'];
					$result[$tag][$count]['status'] = $row['status'];
					$result[$tag][$count]['regulator'] = $row['regulator'];
					$result[$tag][$count]['year'] = $row['year'];
					$result[$tag][$count]['type'] = $row['type'];
					$result[$tag][$count]['tags'] = $row['tags'];
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
				$sql = $db->query("SELECT * FROM `regulations` WHERE ref IN (".$list.") ORDER BY `section_no` ASC");
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
				$sqlTag = " AND `".$tag3."` = :id3";
				$token[':id3'] = $id3;
			} else {
				$sqlTag .= "";
			}
			
			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM `regulations` WHERE `".$tag."` = :id".$sqlTag." ORDER BY `".$order."` ".$dir);
								
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
				$sql = $db->prepare("SELECT * FROM regulations WHERE `".$tag."` = :id ORDER BY `ref` DESC LIMIT 1");
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
		
		function getOneField($id, $tag="ref", $ref="title") {
			$data = $this->getOne($id, $tag);
			return $data[$ref];
		}
		
		function counter($id, $from=false, $to=false) {
			if ($from != false) {
				$ad = " AND `date_time` BETWEEN ".$from." AND ".$to;
			}
			$sql = mysql_query("SELECT * FROM `counter_log` WHERE `type` = 'regulations' AND `id` IN (SELECT `ref` FROM `regulations` WHERE.`owner` = '".$id."')".$ad);
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['id'] = $row['id'];
					$result[$count]['user_id'] = $row['user_id'];
					$result[$count]['title'] = $this->getOneField($row['id']);
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