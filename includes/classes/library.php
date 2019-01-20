<?php
	class library extends common {
		function add($array) {
			$title = htmlentities(ucwords(strtolower($this->mysql_prep($array['title']))));
			$status = $this->mysql_prep($array['status']);
			$details = htmlentities($this->mysql_prep($array['details']));
			$create_time = $modify_time = time();
			$ref = $this->mysql_prep($array['ref']);
			
			global $db;
			$value_array = array(
							':title' => $title, 
							':details' => $details,
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
				$sql = $db->prepare("INSERT INTO `list_library` (".$firstpart."`title`, `details`, `status`, `create_time`, `modify_time`) 
				VALUES (".$secondPArt.":title, :details, :status, :create_time, :modify_time)
					ON DUPLICATE KEY UPDATE 
						`title` = :title,
						`details` = :details,
						`status` = :status,
						`modify_time` = :modify_time
					");
				$sql->execute($value_array);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}

			if ($sql) {
				$id = $db->lastInsertId();
				
				//mysql_query("ALTER TABLE list_library ADD FULLTEXT (details);") or die (mysql_error());
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = $_SESSION['admin']['id'];
				$logArray['desc'] = $tag;
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
				$sql = $db->prepare("DELETE FROM `list_library` WHERE `ref` =:id");
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
				$logArray['owner_id'] = $_SESSION['admin']['id'];
				$logArray['desc'] = "removed category id #".$id;
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
				$sql = $db->prepare("UPDATE `list_library` SET  `".$tag."` = :value, `modify_time` = '".$modDate."' WHERE `ref`=:id");
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
				$logArray['owner_id'] = $_SESSION['admin']['id'];
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
				$sql = $db->query("SELECT * FROM `list_library` ORDER BY `title` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				$result = array();
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['title'] = ucwords(strtolower($row['title']));
					$result[$count]['details'] = $row['details'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function listAllHome() {
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `list_library` WHERE `status` = 'active' ORDER BY `title` ASC");
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
					$result[$tag][$count]['details'] = $row['details'];
					$result[$tag][$count]['status'] = $row['status'];
					$result[$tag][$count]['create_time'] = $row['create_time'];
					$result[$tag][$count]['modify_time'] = $row['modify_time'];
				}
				ksort($result);
				return $this->out_prep($result);
			}
		}
		
		function quickSearch($val) {
			$val = $this->mysql_prep($val);
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `list_library` WHERE (`title` LIKE '%".$val."%' OR `details` LIKE '%".$val."%' OR MATCH(details) AGAINST ('".$val."')) AND `status` = 'active' ORDER BY `title` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				$result = array();
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result[$count]['title'] = ucwords(strtolower($row['title']));
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function fullSearch($val) {
			$val = $this->mysql_prep($val);

			global $db;
			try {
				$sql = $db->query("SELECT * FROM `list_library` WHERE (`title` LIKE '%".$val."%' OR `details` LIKE '%".$val."%' OR MATCH(details) AGAINST ('".$val."')) AND `status` = 'active' ORDER BY `title` ASC");
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
					$result[$tag][$count]['details'] = $row['details'];
					$result[$tag][$count]['status'] = $row['status'];
					$result[$tag][$count]['create_time'] = $row['create_time'];
					$result[$tag][$count]['modify_time'] = $row['modify_time'];
				}
				ksort($result);
				return $this->out_prep($result);
			}
		}
		
		function indexSearch($val) {
			$val = $this->mysql_prep($val);
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `list_library` WHERE `title` LIKE '".$val."%' AND `status` = 'active' ORDER BY `title` ASC");
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
					$result[$tag][$count]['details'] = $row['details'];
					$result[$tag][$count]['status'] = $row['status'];
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
				$sql = $db->query("SELECT * FROM `list_library` WHERE ref IN (".$list.") ORDER BY `title` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				$result = array();
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['title'] = ucwords(strtolower($row['title']));
					$result[$count]['details'] = $row['details'];
					$result[$count]['status'] = $row['status'];
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
				$sqlTag = " AND `".$tag3."` = :id3";
				$token[':id3'] = $id3;
			} else {
				$sqlTag .= "";
			}
			
			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM `list_library` WHERE `".$tag."` = :id".$sqlTag." ORDER BY `".$order."` ".$dir);
								
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
					$result[$count]['details'] = $row['details'];
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

			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM list_library WHERE `".$tag."` = :id ORDER BY `ref` DESC LIMIT 1");
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
					$result['details'] = $row['details'];
					$result['status'] = $row['status'];
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
	}
?>