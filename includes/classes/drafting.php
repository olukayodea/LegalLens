<?php
	class drafting extends common {
		function add($array) {
			$categories = new categories;
			$title = ucfirst(strtolower($this->mysql_prep($array['title'])));
			$type = $this->mysql_prep($array['type']);
			$owner = $this->mysql_prep($array['owner']);
			$status = $this->mysql_prep($array['status']);
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
			
			$sql = mysql_query("INSERT INTO `drafting` (".$firstpart."`title`,`type`,`owner`, `status`, `create_time`, `modify_time`) VALUES (".$secondPArt."'".$title."','".$type."','".$owner."','".$status."', '".$create_time."', '".$modify_time."') ON DUPLICATE KEY UPDATE `title` = '".$title."',`type`='".$type."',`status`='".$status."', `modify_time` = '".$modify_time."'") or die (mysql_error());
			
			if ($sql) {
				$id = $db->lastInsertId();
				
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
			$sql = mysql_query("DELETE FROM `drafting` WHERE ref = '".$id."'") or die (mysql_error());
			$sql = mysql_query("DELETE FROM `drafting_sections` WHERE drafting = '".$id."'") or die (mysql_error());
			
			if ($sql) {
			
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = $_SESSION['admin']['id'];
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
			$sql = mysql_query("UPDATE `drafting` SET `".$tag."` = '".$value."', `modify_time` = '".$modDate."' WHERE ref = '".$id."'") or die (mysql_error());
			
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
			$sql = mysql_query("SELECT * FROM `drafting` ORDER BY `title` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
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
			$sql = mysql_query("SELECT * FROM `drafting` WHERE `type` = '".$type."' AND `status` = 'active' ORDER BY `title` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				
				while ($row = mysql_fetch_array($sql)) {
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
			$val = $this->mysql_prep($val);
			$sql = mysql_query("SELECT * FROM `drafting` WHERE `title` LIKE '%".$val."%' AND `type` = '".$type."' AND `status` = 'active' ORDER BY `title` ASC LIMIT 20") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
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
			$val = $this->mysql_prep($val);
			$sql = mysql_query("SELECT `drafting`.`title`, `drafting`.`ref` AS 'draft_ID', `drafting_sections`.`section_content`, `drafting_sections`.`tags`, `drafting_sections`.`ref` AS 'section_ID' FROM `drafting`, `drafting_sections` WHERE `drafting`.`ref` = `drafting_sections`.`drafting` AND `drafting`.`status` = 'active' AND `drafting_sections`.`status` = 'active' AND `drafting`.`type` = '".$type."' AND (`drafting_sections`.`tags` LIKE '%".$val."%' OR `drafting_sections`.`section_content` LIKE '%".$val."%' OR MATCH(`drafting_sections`.`section_content`) AGAINST ('".$val."')) ORDER BY `title` ASC LIMIT 20") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
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
			$sql = mysql_query("SELECT `drafting`.`title`, `drafting`.`ref`, `drafting`.`type`, `drafting`.`status`, `drafting`.`create_time`, `drafting`.`modify_time` FROM `drafting`, `drafting_sections` WHERE `drafting`.`ref` = `drafting_sections`.`drafting` AND `drafting`.`status` = 'active' AND `drafting_sections`.`status` = 'active' AND `drafting`.`type` = '".$type."' AND (`drafting`.`title` LIKE '%".$val."%' OR `drafting_sections`.`tags` LIKE '%".$val."%' OR `drafting_sections`.`tags` LIKE '%".$val."%' OR `drafting_sections`.`section_content` LIKE '%".$val."%' OR MATCH(`drafting_sections`.`section_content`) AGAINST ('".$val."')) ORDER BY `title` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				
				while ($row = mysql_fetch_array($sql)) {
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
			$sql = mysql_query("SELECT * FROM `drafting` WHERE `title` LIKE '".$val."%' AND `type` = '".$type."' AND `status` = 'active' ORDER BY `title` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				
				while ($row = mysql_fetch_array($sql)) {
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
			$sql = mysql_query("SELECT * FROM `drafting` WHERE ref IN (".$list.") ORDER BY `title` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
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
			
			$sql = mysql_query("SELECT * FROM `drafting` WHERE `".$tag."` = '".$id."'".$sqlTag." ORDER BY `ref` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
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
			$sql = mysql_query("SELECT * FROM `drafting` WHERE `".$tag."` = '".$id."' ORDER BY `ref` DESC LIMIT 1") or die (mysql_error());
			if ($sql) {
				$result = array();
				
				if (mysql_num_rows($sql) == 1) {
					$row = mysql_fetch_array($sql);
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
			
			$sql = mysql_query("SELECT * FROM `counter_log` WHERE `type` = 'drafting' AND `id` IN (SELECT `ref` FROM `drafting` WHERE `owner` = '".$id."')");
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
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