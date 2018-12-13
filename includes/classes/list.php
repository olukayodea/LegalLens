<?php
	class listItem extends common {
		function add($array) {
			$title = ucwords(($this->mysql_prep($array['title'])));
			$type = $this->mysql_prep($array['type']);
			$pref = $this->mysql_prep($array['pref']);
			$status = $this->mysql_prep($array['status']);
			$court = $this->mysql_prep($array['court']);
			$year = $this->mysql_prep($array['year']);
			$state = $this->mysql_prep($array['state']);
			$details = $this->mysql_prep($array['details']);
			$create_time = $modify_time = time();
			$ref = $this->mysql_prep($array['ref']);
			
			if ($ref != "") {
				$firstpart = "`ref`, ";
				$secondPArt = "'".$ref."', ";
				$log = "Modified object ".$title;
			} else {
				$firstpart = "";
				$secondPArt = "";
				$log = "Created object ".$title;
			}
			$sql = mysql_query("INSERT INTO `list_db` (".$firstpart."`title`, `pref`,`type`,`year`, `court`,`state`,`details`, `status`, `create_time`, `modify_time`) VALUES (".$secondPArt."'".$title."','".$pref."','".$type."','".$year."','".$court."','".$state."','".$details."','".$status."', '".$create_time."', '".$modify_time."') ON DUPLICATE KEY UPDATE `title` = '".$title."', `pref` = '".$pref."', `type` = '".$type."', `court` = '".$court."',`state`='".$state."',`status`='".$status."',`year` = '".$year."',`details`='".$details."', `modify_time` = '".$modify_time."'") or die (mysql_error());
			
			if ($sql) {
				$id = $db->lastInsertId();
				
				mysql_query("ALTER TABLE list_db ADD FULLTEXT (details);") or die (mysql_error());
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
			$sql = mysql_query("DELETE FROM `list_db` WHERE ref = '".$id."'") or die (mysql_error());
			
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
			$modDate = time();
			$sql = mysql_query("UPDATE `list_db` SET `".$tag."` = '".$value."', `modify_time` = '".$modDate."' WHERE ref = '".$id."'") or die (mysql_error());
			
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
			$sql = mysql_query("SELECT * FROM `list_db` ORDER BY `ref` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['pref'] = $row['pref'];
					$result[$count]['title'] = (($row['title']));
					$result[$count]['type'] = $row['type'];
					$result[$count]['year'] = $row['year'];
					$result[$count]['court'] = $row['court'];
					$result[$count]['state'] = $row['state'];
					$result[$count]['details'] = $row['details'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function listAllHome($type, $sort=false, $sortType=false, $filter="title") {
			if ($sort != false) {
				$addition = "`".$sortType."` = '".$sort."' AND ";
			} else {
				$addition = "";
			}
			$sql = mysql_query("SELECT * FROM `list_db` WHERE `type` = '".$type."' AND ".$addition."`status` = 'active' ORDER BY `title` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				
				while ($row = mysql_fetch_array($sql)) {
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
			$sql = mysql_query("SELECT * FROM `list_db` WHERE (`title` LIKE '%".$val."%' OR `court` LIKE '%".$val."%' OR `state` LIKE '%".$val."%' OR `details` LIKE '%".$val."%' OR MATCH(details) AGAINST ('".$val."')) AND `type` = '".$type."' AND ".$addition."`status` = 'active' ORDER BY `title` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
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
			$sql = mysql_query("SELECT * FROM `list_db` WHERE (`title` LIKE '%".$val."%' OR `court` LIKE '%".$val."%' OR `state` LIKE '%".$val."%' OR `details` LIKE '%".$val."%' OR MATCH(details) AGAINST ('".$val."')) AND `type` = '".$type."' AND ".$addition."`status` = 'active' ORDER BY `title` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				
				while ($row = mysql_fetch_array($sql)) {
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
			$sql = mysql_query("SELECT * FROM `list_db` WHERE `title` LIKE '".$val."%' AND `type` = '".$type."' AND ".$addition."`status` = 'active' ORDER BY `title` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				
				while ($row = mysql_fetch_array($sql)) {
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
			$sql = mysql_query("SELECT * FROM `list_db` WHERE ref IN (".$list.") ORDER BY `title` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['pref'] = $row['pref'];
					$result[$count]['title'] = (($row['title']));
					$result[$count]['type'] = $row['type'];
					$result[$count]['year'] = $row['year'];
					$result[$count]['court'] = $row['court'];
					$result[$count]['state'] = $row['state'];
					$result[$count]['details'] = $row['details'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
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
			
			$sql = mysql_query("SELECT * FROM `list_db` WHERE `".$tag."` = '".$id."'".$sqlTag." ORDER BY `ref` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['pref'] = $row['pref'];
					$result[$count]['title'] = (($row['title']));
					$result[$count]['type'] = $row['type'];
					$result[$count]['year'] = $row['year'];
					$result[$count]['court'] = $row['court'];
					$result[$count]['state'] = $row['state'];
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
			$sql = mysql_query("SELECT * FROM `list_db` WHERE `".$tag."` = '".$id."' ORDER BY `ref` DESC LIMIT 1") or die (mysql_error());
			if ($sql) {
				$result = array();
				
				if (mysql_num_rows($sql) == 1) {
					$row = mysql_fetch_array($sql);
					$result['ref'] = $row['ref'];
					$result['pref'] = $row['pref'];
					$result['title'] = (($row['title']));
					$result['type'] = $row['type'];
					$result['year'] = $row['year'];
					$result['court'] = $row['court'];
					$result['state'] = $row['state'];
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
		
		function listCourt() {
			$sql = mysql_query("SELECT `court` FROM `list_db` GROUP BY `court` ORDER BY `court`") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['title'] = (($row['court']));
					$count++;
				}
				return $this->out_prep($result);
			}
		}
	}
?>