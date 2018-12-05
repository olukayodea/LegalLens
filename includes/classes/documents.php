<?php
	class documents extends common {
		function add($array) {
			$categories = new categories;
			$title = (($this->mysql_prep($array['title'])));
			$cat = $this->mysql_prep($array['cat_id']);
			$category_name = $this->mysql_prep($array['cat']);
			$owner = $this->mysql_prep($array['owner']);
			$year = $this->mysql_prep($array['year']);
			$tags = $this->mysql_prep($array['tags']);
			$type = $this->mysql_prep($array['type']);
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
			
			$sql = mysql_query("INSERT INTO `documents` (".$firstpart."`title`, `cat`,`category_name`,`owner`,`year`, `tags`,`type`, `status`, `create_time`, `modify_time`) VALUES (".$secondPArt."'".$title."','".$cat."','".$category_name."','".$owner."','".$year."','".$tags."','".$type."','".$status."', '".$create_time."', '".$modify_time."') ON DUPLICATE KEY UPDATE `title` = '".$title."', `cat` = '".$cat."',`category_name` ='".$category_name."', `year` = '".$year."',`tags`='".$tags."',`owner`='".$owner."',`type`='".$type."',`status`='".$status."', `modify_time` = '".$modify_time."'") or die (mysql_error());
			
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
			$sql = mysql_query("DELETE FROM `documents` WHERE ref = '".$id."'") or die (mysql_error());
			$sql = mysql_query("DELETE FROM `sections` WHERE document = '".$id."'") or die (mysql_error());
			
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
			$sql = mysql_query("UPDATE `documents` SET `".$tag."` = '".$value."', `modify_time` = '".$modDate."' WHERE ref = '".$id."'") or die (mysql_error());
			
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
			$sql = mysql_query("SELECT * FROM `documents` ORDER BY `ref` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['title'] = ucwords(strtolower($row['title']));
					$result[$count]['cat'] = $row['cat'];
					$result[$count]['category_name'] = $row['category_name'];
					$result[$count]['owner'] = $row['owner'];
					$result[$count]['year'] = $row['year'];
					$result[$count]['tags'] = $row['tags'];
					$result[$count]['type'] = $row['type'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function listAllHome($type, $sort=false, $sortType=false, $filter="title", $filter2=false) {
			$categories = new categories;
			if ($sort != false) {
				$addition = "`".$sortType."` IN (".$categories->linkListListDef($sort).") AND ";
			} else {
				$addition = "";
			}
			
			$sql = mysql_query("SELECT * FROM `documents` WHERE `type` = '".$type."' AND ".$addition."`status` = 'active' ORDER BY `title` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				
				while ($row = mysql_fetch_array($sql)) {
					if (($filter2 != false) && (intval($filter2) == 0)) {
						$tag = substr(ucwords(strtolower($row[$filter2])), 0, 1);
					} else if ($filter == "title") {
						$tag = substr(ucwords(strtolower($row['title'])), 0, 1);
					} else {
						$tag = $row['year'];
					}
					$count = count($result[$tag]);
					$result[$tag][$count]['ref'] = $row['ref'];
					$result[$tag][$count]['title'] = ucwords(strtolower($row['title']));
					$result[$tag][$count]['year'] = $row['year'];
					$result[$tag][$count]['cat'] = $row['cat'];
					$result[$tag][$count]['category_name'] = $row['category_name'];
					$result[$tag][$count]['owner'] = $row['owner'];
					$result[$tag][$count]['tags'] = $row['tags'];
					$result[$tag][$count]['type'] = $row['type'];
					$result[$tag][$count]['status'] = $row['status'];
					$result[$tag][$count]['create_time'] = $row['create_time'];
					$result[$tag][$count]['modify_time'] = $row['modify_time'];
				}
				return $this->out_prep($result);
			}
		}
			
		
		function quickSearch($val, $type, $sort=false, $sortType=false) {
			$val = $this->mysql_prep($val);
			$categories = new categories;
			if ($sort != false) {
				$addition = "`".$sortType."` IN (".$categories->linkListListDef($sort).") AND ";
			} else {
				$addition = "";
			}
			$sql = mysql_query("SELECT * FROM `documents` WHERE (`documents`.`title` LIKE '%".$val."%' OR `documents`.`year` LIKE '%".$val."%' OR `documents`.`category_name` LIKE '%".$val."%' OR `documents`.`owner` LIKE '%".$val."%' OR `documents`.`tags` LIKE '%".$val."%') AND `type` = '".$type."' AND ".$addition."`status` = 'active' ORDER BY `title` ASC LIMIT 20") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['label'] = ucwords(strtolower($row['title']));
					$result[$count]['category'] = "Titles";
					$result[$count]['type'] = "Document";
					$result[$count]['code'] = $row['ref'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}		
		
		function quickSearchSections($val, $type, $sort=false, $sortType=false) {
			$val = $this->mysql_prep($val);
			$categories = new categories;
			if ($sort != false) {
				$addition = "`".$sortType."` IN (".$categories->linkListListDef($sort).") AND ";
			} else {
				$addition = "";
			}
			$sql = mysql_query("SELECT `documents`.`title`, `documents`.`ref` AS 'draft_ID', `sections`.`section_content`, `sections`.`tags`, `sections`.`ref` AS 'section_ID' FROM `documents`, `sections` WHERE `documents`.`ref` = `sections`.`document` AND ".$addition." `documents`.`status` = 'active' AND `sections`.`status` = 'active' AND `documents`.`type` = '".$type."' AND (`sections`.`tags` LIKE '%".$val."%' OR `sections`.`section_content` LIKE '%".$val."%' OR MATCH(`sections`.`section_content`) AGAINST ('".$val."')) ORDER BY `title` ASC LIMIT 20") or die (mysql_error());
			
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
		
		function fullSearch($val, $type, $sort=false, $sortType=false) {
			$val = $this->mysql_prep($val);
			$categories = new categories;
			if ($sort != false) {
				$addition = "`".$sortType."` IN (".$categories->linkListListDef($sort).") AND ";
			} else {
				$addition = "";
			}
			$sql = mysql_query("SELECT `documents`.`title`, `documents`.`ref`, `documents`.`cat`,  `documents`.`type`, `documents`.`year`, `documents`.`category_name`, `documents`.`owner`, `documents`.`tags`, `documents`.`status`, `documents`.`create_time`, `documents`.`modify_time` FROM `documents`, `sections` WHERE `documents`.`ref` = `sections`.`document` AND ".$addition."`documents`.`status` = 'active' AND `sections`.`status` = 'active' AND `documents`.`type` = '".$type."' AND (`documents`.`title` LIKE '%".$val."%' OR `documents`.`year` LIKE '%".$val."%' OR `documents`.`category_name` LIKE '%".$val."%' OR `documents`.`owner` LIKE '%".$val."%' OR `documents`.`tags` LIKE '%".$val."%' OR `sections`.`tags` LIKE '%".$val."%' OR `sections`.`section_content` LIKE '%".$val."%' OR MATCH(`sections`.`section_content`) AGAINST ('".$val."')) ORDER BY `title` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				
				while ($row = mysql_fetch_array($sql)) {
					$tag = substr(ucwords(strtolower($row['title'])), 0, 1);
					$count = count($result[$tag]);
					$result[$tag][$count]['ref'] = $row['ref'];
					$result[$tag][$count]['title'] = ucwords(strtolower($row['title']));
					$result[$tag][$count]['year'] = $row['year'];
					$result[$tag][$count]['cat'] = $row['cat'];
					$result[$tag][$count]['category_name'] = $row['category_name'];
					$result[$tag][$count]['owner'] = $row['owner'];
					$result[$tag][$count]['tags'] = $row['tags'];
					$result[$tag][$count]['type'] = $row['type'];
					$result[$tag][$count]['status'] = $row['status'];
					$result[$tag][$count]['create_time'] = $row['create_time'];
					$result[$tag][$count]['modify_time'] = $row['modify_time'];
				}
				return $this->out_prep($result);
			}
		}
		
		function indexSearch($val, $type, $sort=false, $sortType=false) {
			$val = $this->mysql_prep($val);
			$categories = new categories;
			if ($sort != false) {
				$addition = "`".$sortType."` IN (".$categories->linkListListDef($sort).") AND ";
			} else {
				$addition = "";
			}
			$sql = mysql_query("SELECT * FROM `documents` WHERE `title` LIKE '".$val."%' AND `type` = '".$type."' AND ".$addition."`status` = 'active' ORDER BY `title` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				
				while ($row = mysql_fetch_array($sql)) {
					$tag = substr(ucwords(strtolower($row['title'])), 0, 1);
					$count = count($result[$tag]);
					$result[$tag][$count]['ref'] = $row['ref'];
					$result[$tag][$count]['title'] = ucwords(strtolower($row['title']));
					$result[$tag][$count]['year'] = $row['year'];
					$result[$tag][$count]['cat'] = $row['cat'];
					$result[$tag][$count]['category_name'] = $row['category_name'];
					$result[$tag][$count]['owner'] = $row['owner'];
					$result[$tag][$count]['tags'] = $row['tags'];
					$result[$tag][$count]['type'] = $row['type'];
					$result[$tag][$count]['status'] = $row['status'];
					$result[$tag][$count]['create_time'] = $row['create_time'];
					$result[$tag][$count]['modify_time'] = $row['modify_time'];
				}
				return $this->out_prep($result);
			}
		}
		
		function lisstMultiple($array) {
			$list = implode(",", $array);
			$sql = mysql_query("SELECT * FROM `documents` WHERE ref IN (".$list.") ORDER BY `title` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['title'] = ucwords(strtolower($row['title']));
					$result[$count]['cat'] = $row['cat'];
					$result[$count]['category_name'] = $row['category_name'];
					$result[$count]['owner'] = $row['owner'];
					$result[$count]['year'] = $row['year'];
					$result[$count]['tags'] = $row['tags'];
					$result[$count]['type'] = $row['type'];
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
			
			$sql = mysql_query("SELECT * FROM `documents` WHERE `".$tag."` = '".$id."'".$sqlTag." ORDER BY `ref` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['title'] = ucwords(strtolower($row['title']));
					$result[$count]['cat'] = $row['cat'];
					$result[$count]['category_name'] = $row['category_name'];
					$result[$count]['owner'] = $row['owner'];
					$result[$count]['year'] = $row['year'];
					$result[$count]['tags'] = $row['tags'];
					$result[$count]['type'] = $row['type'];
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
			$sql = mysql_query("SELECT * FROM `documents` WHERE `".$tag."` = '".$id."' ORDER BY `ref` DESC LIMIT 1") or die (mysql_error());
			if ($sql) {
				$result = array();
				
				if (mysql_num_rows($sql) == 1) {
					$row = mysql_fetch_array($sql);
					$result['ref'] = $row['ref'];
					$result['title'] = ucwords(strtolower($row['title']));
					$result['cat'] = $row['cat'];
					$result['category_name'] = $row['category_name'];
					$result['owner'] = $row['owner'];
					$result['year'] = $row['year'];
					$result['tags'] = $row['tags'];
					$result['type'] = $row['type'];
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
		
		function counter($id, $from=false, $to=false) {
			$sections = new sections;
			if ($from != false) {
				$ad = " AND `date_time` BETWEEN ".$from." AND ".$to;
			}
			
			$sql = mysql_query("SELECT * FROM `counter_log` WHERE `type` = 'document' AND `id` IN (SELECT `ref` FROM `documents` WHERE `documents`.`owner` = '".$id."')".$ad);
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['id'] = $row['id'];
					$result[$count]['user_id'] = $row['user_id'];
					$result[$count]['title'] = $this->getOneField($row['id']);
					$result[$count]['section'] = $sections->getOneField($row['section']);
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