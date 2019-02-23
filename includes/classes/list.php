<?php
	class listItem extends common {
		function add($array) {
			$title = htmlentities(ucwords(($this->mysql_prep($array['title']))));
			$type = $this->mysql_prep($array['type']);
			$pref = $this->mysql_prep($array['pref']);
			$status = $this->mysql_prep($array['status']);
			$court = $this->mysql_prep($array['court']);
			$year = $this->mysql_prep($array['year']);
			$file_data = $this->mysql_prep($array['file_data']);
			$state = $this->mysql_prep($array['state']);
			$details = htmlentities($this->mysql_prep($array['details']));
			$create_time = $modify_time = time();
			$ref = $this->mysql_prep($array['ref']);
				
			global $db;
			$value_array = array(
							':title' => $title, 
							':pref' => $pref, 
							':status' => $status, 
							':court' => $court, 
							':type' => $type, 
							':details' => $details,
							':year' => $year, 
							':state' => $state, 
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
			$dupPart = "";
			if ($file_data != "") {
				$firstpart .= "`file`, ";
				$secondPArt .= "'".$file_data."', ";
				$dupPart .= "`file` = '".$file_data."', ";
			}
			
			try {
				$sql = $db->prepare("INSERT INTO `list_db` (".$firstpart."`title`,`pref`,`state`, `status`, `court`,`year`, `type`, `details`, `create_time`, `modify_time`)
				VALUES (".$secondPArt.":title, :pref, :state, :status, :court, :year, :type, :details, :create_time, :modify_time)
					ON DUPLICATE KEY UPDATE 
						".$dupPart."
						`title` = :title,
						`pref` = :pref,
						`status` = :status,
						`court` = :court,
						`type` = :type,
						`year` = :year,
						`state` = :state,
						`details` = :details,
						`modify_time` = :modify_time
					");
				$sql->execute($value_array);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				$id = $db->lastInsertId();
				
				//mysql_query("ALTER TABLE list_db ADD FULLTEXT (details);") or die (mysql_error());
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
				$sql = $db->prepare("DELETE FROM `list_db` WHERE `ref` =:id");
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
				$sql = $db->prepare("UPDATE `list_db` SET  `".$tag."` = :value, `modify_time` = :modifyTime WHERE `ref`=:id");
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
				$sql = $db->query("SELECT * FROM `list_db` ORDER BY `ref` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		function listAllHome($type, $sort=false, $sortType=false, $filter="title") {
			if ($sort != false) {
				$addition = "`".$sortType."` = '".$sort."' AND ";
			} else {
				$addition = "";
			}
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `list_db` WHERE `type` = '".$type."' AND ".$addition."`status` = 'active' ORDER BY `title` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}

			if ($sql) {
				$result = array();
				$count = 0;
				
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					if ($filter == "title") {
						$tag = substr(ucwords(strtolower($row['title'])), 0, 1);
					} else if ($filter == "state") {
						$tag = ucwords(strtolower($row['state']));
					} else {
						$tag = $row['year'];
					}
					$count = count($result[$tag]);
					$result[$tag][$count]['ref'] = $row['ref'];
					$result[$tag][$count]['pref'] = $row['pref'];
					$result[$tag][$count]['title'] = (($row['title']));
					$result[$tag][$count]['type'] = $row['type'];
					$result[$tag][$count]['year'] = $row['year'];
					$result[$tag][$count]['court'] = $row['court'];
					$result[$tag][$count]['state'] = $row['state'];
					$result[$tag][$count]['details'] = $row['details'];
					$result[$tag][$count]['status'] = $row['status'];
					$result[$tag][$count]['create_time'] = $row['create_time'];
					$result[$tag][$count]['modify_time'] = $row['modify_time'];
				}
				ksort($result);
				return $this->out_prep($result);
			}
		}
		
		function quickSearch($val, $type, $sort=false, $sortType=false) {
			$val = $this->mysql_prep($val);
			if ($sort != false) {
				$addition = "`".$sortType."` = '".$sort."' AND ";
			} else {
				$addition = "";
			}

			global $db;
			try {
				$sql = $db->query("SELECT * FROM `list_db` WHERE (`title` LIKE '%".$val."%' OR `court` LIKE '%".$val."%' OR `state` LIKE '%".$val."%' OR `details` LIKE '%".$val."%' OR MATCH(details) AGAINST ('".$val."')) AND `type` = '".$type."' AND ".$addition."`status` = 'active' ORDER BY `title` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}

			if ($sql) {
				$result = array();
				$count = 0;
				
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result[$count]['title'] = (($row['title']));
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function fullSearch($val, $type, $sort=false, $sortType=false, $filter="title") {
			$val = $this->mysql_prep($val);
			if ($sort != false) {
				$addition = "`".$sortType."` = '".$sort."' AND ";
			} else {
				$addition = "";
			}
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `list_db` WHERE (`title` LIKE '%".$val."%' OR `court` LIKE '%".$val."%' OR `state` LIKE '%".$val."%' OR `details` LIKE '%".$val."%' OR MATCH(details) AGAINST ('".$val."')) AND `type` = '".$type."' AND ".$addition."`status` = 'active' ORDER BY `title` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}

			if ($sql) {
				$result = array();
				$count = 0;
				
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					if ($filter == "title") {
						$tag = substr(ucwords(strtolower($row['title'])), 0, 1);
					} else if ($filter == "state") {
						$tag = ucwords(strtolower($row['state']));
					} else {
						$tag = $row['year'];
					}
					$count = count($result[$tag]);
					$result[$tag][$count]['ref'] = $row['ref'];
					$result[$tag][$count]['pref'] = $row['pref'];
					$result[$tag][$count]['title'] = (($row['title']));
					$result[$tag][$count]['type'] = $row['type'];
					$result[$tag][$count]['year'] = $row['year'];
					$result[$tag][$count]['court'] = $row['court'];
					$result[$tag][$count]['state'] = $row['state'];
					$result[$tag][$count]['details'] = $row['details'];
					$result[$tag][$count]['status'] = $row['status'];
					$result[$tag][$count]['create_time'] = $row['create_time'];
					$result[$tag][$count]['modify_time'] = $row['modify_time'];
				}
				ksort($result);
				return $this->out_prep($result);
			}
		}
		
		function indexSearch($val, $type, $sort=false, $sortType=false, $filter="title") {
			$val = $this->mysql_prep($val);
			if ($sort != false) {
				$addition = "`".$sortType."` = '".$sort."' AND ";
			} else {
				$addition = "";
			}

			global $db;
			try {
				$sql = $db->query("SELECT * FROM `list_db` WHERE `title` LIKE '".$val."%' AND `type` = '".$type."' AND ".$addition."`status` = 'active' ORDER BY `title` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}

			if ($sql) {
				$result = array();
				$count = 0;
				
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					if ($filter == "title") {
						$tag = substr(ucwords(strtolower($row['title'])), 0, 1);
					} else if ($filter == "state") {
						$tag = ucwords(strtolower($row['state']));
					} else {
						$tag = $row['year'];
					}
					$count = count($result[$tag]);
					$result[$tag][$count]['ref'] = $row['ref'];
					$result[$tag][$count]['pref'] = $row['pref'];
					$result[$tag][$count]['title'] = (($row['title']));
					$result[$tag][$count]['type'] = $row['type'];
					$result[$tag][$count]['year'] = $row['year'];
					$result[$tag][$count]['court'] = $row['court'];
					$result[$tag][$count]['state'] = $row['state'];
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
				$sql = $db->query("SELECT * FROM `list_db` WHERE ref IN (".$list.") ORDER BY `section_no` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
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
				$sqlTag = " AND `".$tag3."` = :id3";
				$token[':id3'] = $id3;
			} else {
				$sqlTag .= "";
			}
			
			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM `list_db` WHERE `".$tag."` = :id".$sqlTag." ORDER BY `".$order."` ASC");
								
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
				$sql = $db->prepare("SELECT * FROM list_db WHERE `".$tag."` = :id ORDER BY `ref` DESC LIMIT 1");
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
		
		function listCourt() {
			global $db;
			try {
				$sql = $db->query("SELECT `court` FROM `list_db` GROUP BY `court` ORDER BY `court`");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			if ($sql) {
				$result = array();
				$count = 0;
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result[$count]['title'] = (($row['court']));
					$count++;
				}
				return $this->out_prep($result);
			}
		}
	}
?>