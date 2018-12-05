<?php
	class articles extends common {
		function add($array) {
			$title = $this->mysql_prep($array['title']);
			$status = $this->mysql_prep($array['status']);
			$type = $this->mysql_prep($array['type']);
			$owner = $this->mysql_prep($array['owner']);
			$year = $this->mysql_prep($array['year']);
			$create_time = $modify_time = time();
			$ref = $this->mysql_prep($array['ref']);
			
			if ($ref != "") {
				$firstpart = "`ref`, ";
				$secondPArt = "'".$ref."', ";
				$log = "Modified object ".$section_no;
			} else {
				$firstpart = "";
				$secondPArt = "";
				$log = "Created object ".$section_no;
			}
			
			$sql = mysql_query("INSERT INTO `articles` (".$firstpart."`title`, `status`,`year`,`owner`, `type`, `create_time`, `modify_time`) VALUES (".$secondPArt."'".$title."','".$status."','".$year."','".$owner."','".$type."', '".$create_time."', '".$modify_time."') ON DUPLICATE KEY UPDATE `title` = '".$title."', `status` = '".$status."', `type` = '".$type."',`owner` = '".$owner."'`year`='".$year."', `modify_time` = '".$modify_time."'") or die (mysql_error());
			
			if ($sql) {
				$id = mysql_insert_id();
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
			
			$data = $this->getOne($id);
			
			$sql = mysql_query("DELETE FROM `articles` WHERE ref = '".$id."'") or die (mysql_error());
			$sql = mysql_query("DELETE FROM `articles_sections` WHERE article = '".$id."'") or die (mysql_error());
			
			if ($sql) {
			
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = $_SESSION['admin']['id'];
				$logArray['desc'] = "removed artticle id #".$id;
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
			$sql = mysql_query("UPDATE `articles` SET `".$tag."` = '".$value."', `modify_time` = '".$modDate."' WHERE ref = '".$id."'") or die (mysql_error());
			
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
			$sql = mysql_query("SELECT * FROM `articles` ORDER BY `ref` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['title'] = $row['title'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['year'] = $row['year'];
					$result[$count]['owner'] = $row['owner'];
					$result[$count]['type'] = $row['type'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function listAllHome($type=false, $filter="title") {
			$sql = mysql_query("SELECT * FROM `articles` WHERE ".$addition."`status` = 'active' ORDER BY `title` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				
				while ($row = mysql_fetch_array($sql)) {
					if ($filter == "title") {
						$tag = substr(ucwords(strtolower($row['title'])), 0, 1);
					} else {
						$tag = $row['year'];
					}
					$count = count($result[$tag]);
					$result[$tag][$count]['ref'] = $row['ref'];
					$result[$tag][$count]['title'] = $row['title'];
					$result[$tag][$count]['status'] = $row['status'];
					$result[$tag][$count]['year'] = $row['year'];
					$result[$tag][$count]['owner'] = $row['owner'];
					$result[$tag][$count]['type'] = $row['type'];
					$result[$tag][$count]['create_time'] = $row['create_time'];
					$result[$tag][$count]['modify_time'] = $row['modify_time'];
				}
				ksort($result);
				return $this->out_prep($result);
			}
		}
		
		function quickSearchSections($val,$sort="ALL") {
			$val = $this->mysql_prep($val);
			if ($sort != "ALL") {
				$addition .= "`type` = '".$sort."' AND ";
			} else {
				$addition .= "";
			}
			$sql = mysql_query("SELECT `articles`.`title`, `articles`.`ref`, `articles_sections`.`section_content`, `articles_sections`.`tags`, `articles`.`type`, `articles_sections`.`ref` AS 'section_ID' FROM `articles`, `articles_sections` WHERE `articles`.`ref` = `articles_sections`.`article` AND `articles`.`status` = 'active' AND `articles_sections`.`status` = 'active' AND ".$addition."(`articles_sections`.`tags` LIKE '%".$val."%' OR `articles_sections`.`section_content` LIKE '%".$val."%' OR MATCH(`articles_sections`.`section_content`) AGAINST ('".$val."')) ORDER BY `title` ASC LIMIT 20") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['label'] = ucwords(strtolower($row['title']));
					$result[$count]['category'] = "Sections in ".$row['type']."s";
					$result[$count]['type'] = $row['type'];
					$result[$count]['code'] = $row['ref'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function quickSearch($val, $sort="ALL") {
			$val = $this->mysql_prep($val);
			if ($sort != "ALL") {
				$addition .= "`type` = '".$sort."' AND ";
			} else {
				$addition .= "";
			}
			$sql = mysql_query("SELECT * FROM `articles` WHERE (`year` LIKE '%".$val."%' OR `tags` LIKE '%".$val."%' OR `title` LIKE '%".$val."%') AND ".$addition."`status` = 'active' ORDER BY `title` ASC LIMIT 20") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['label'] = ucwords(strtolower($row['title']));
					$result[$count]['category'] = trim($row['type']);
					$result[$count]['type'] = $row['type'];
					$result[$count]['code'] = $row['ref'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}		
		
		function fullSearch($val, $sort="ALL", $filter="title") {
			if ($sort != "ALL") {
				$addition .= "`type` = '".$sort."' AND ";
			} else {
				$addition .= "";
			}
			$sql = mysql_query("SELECT `articles`.`title`, `articles`.`type`, `articles`.`create_time`, `articles`.`modify_time`, `articles`.`ref`, `articles_sections`.`section_content`, `articles_sections`.`tags`, `owner` FROM `articles`, `articles_sections` WHERE `articles`.`ref` = `articles_sections`.`article` AND `articles`.`status` = 'active' AND `articles_sections`.`status` = 'active' AND ".$addition."(`articles`.`year` LIKE '%".$val."%' OR `articles`.`title` LIKE '%".$val."%' OR `articles_sections`.`tags` LIKE '%".$val."%' OR `articles_sections`.`section_content` LIKE '%".$val."%' OR MATCH(`articles_sections`.`section_content`) AGAINST ('".$val."')) ORDER BY `title` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				
				while ($row = mysql_fetch_array($sql)) {
					if ($filter == "title") {
						$tag = substr(ucwords(strtolower($row['title'])), 0, 1);
					} else {
						$tag = $row['year'];
					}
					$count = count($result[$tag]);
					$result[$tag][$count]['ref'] = $row['ref'];
					$result[$tag][$count]['title'] = $row['title'];
					$result[$tag][$count]['year'] = $row['year'];
					$result[$tag][$count]['type'] = $row['type'];
					$result[$tag][$count]['owner'] = $row['owner'];
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
				$addition = "`type` = '".$type."' AND ";
			} else {
				$addition = "";
			}
			
			$sql = mysql_query("SELECT * FROM `articles` WHERE `title` LIKE '".$val."%' AND ".$addition."`status` = 'active' ORDER BY `title` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				
				while ($row = mysql_fetch_array($sql)) {
					if ($filter == "title") {
						$tag = substr(ucwords(strtolower($row['title'])), 0, 1);
					} else {
						$tag = $row['year'];
					}
					$count = count($result[$tag]);
					$result[$tag][$count]['ref'] = $row['ref'];
					$result[$tag][$count]['title'] = $row['title'];
					$result[$tag][$count]['status'] = $row['status'];
					$result[$tag][$count]['year'] = $row['year'];
					$result[$tag][$count]['type'] = $row['type'];
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
			$sql = mysql_query("SELECT * FROM `articles` WHERE ref IN (".$list.") ORDER BY `section_no` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['title'] = $row['title'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['year'] = $row['year'];
					$result[$count]['type'] = $row['type'];
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
			
			$sql = mysql_query("SELECT * FROM `articles` WHERE `".$tag."` = '".$id."'".$sqlTag." ORDER BY `ref` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['title'] = $row['title'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['year'] = $row['year'];
					$result[$count]['type'] = $row['type'];
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
			$sql = mysql_query("SELECT * FROM `articles` WHERE `".$tag."` = '".$id."' ORDER BY `ref` DESC LIMIT 1") or die (mysql_error());
			if ($sql) {
				$result = array();
				
				if (mysql_num_rows($sql) == 1) {
					$row = mysql_fetch_array($sql);
					$result['ref'] = $row['ref'];
					$result['title'] = $row['title'];
					$result['status'] = $row['status'];
					$result['year'] = $row['year'];
					$result['type'] = $row['type'];
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
			
			$sql = mysql_query("SELECT * FROM `counter_log` WHERE `type` = 'article' AND `id` IN (SELECT `ref` FROM `articles` WHERE `owner` = '".$id."')".$ad);
			
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