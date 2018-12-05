<?php
	class categories extends common {
		function add($array) {
			$title = ucfirst(strtolower($this->mysql_prep($array['title'])));
			$parent_id = $this->mysql_prep($array['cat_id']);
			$priority = $this->mysql_prep($array['priority']);
			$status = $this->mysql_prep($array['status']);
			$priority_code = (count($this->sortAll($parent_id, "parent_id")) + 1);
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
			
			$sql = mysql_query("INSERT INTO `categories` (".$firstpart."`title`, `parent_id`, `priority`, `status`, `create_time`, `modify_time`) VALUES (".$secondPArt."'".$title."','".$parent_id."','".$priority."','".$status."', '".$create_time."', '".$modify_time."') ON DUPLICATE KEY UPDATE `title` = '".$title."', `parent_id` = '".$parent_id."', `status` = '".$status."', `modify_time` = '".$modify_time."'") or die (mysql_error());
			
			if ($sql) {
				$id = mysql_insert_id();
				$post[$id] = $priority_code;
				$this->setPriority($post, $parent_id);
				
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
			$sql = mysql_query("DELETE FROM `categories` WHERE ref = '".$id."'") or die (mysql_error());
			$sql = mysql_query("DELETE FROM `categories` WHERE ref IN (".trim($this->listLinkList($id), ",").")") or die (mysql_error());
			
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
			$sql = mysql_query("UPDATE `categories` SET `".$tag."` = '".$value."', `modify_time` = '".$modDate."' WHERE ref = '".$id."'") or die (mysql_error());
			
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
			$sql = mysql_query("SELECT * FROM `categories` ORDER BY `ref` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['title'] = ucwords(strtolower($row['title']));
					$result[$count]['parent_id'] = $row['parent_id'];
					$result[$count]['priority'] = $row['priority'];
					$result[$count]['priority_code'] = $row['priority_code'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function lisstMultiple($array) {
			$list = implode(",", $array);
			$sql = mysql_query("SELECT * FROM `categories` WHERE ref IN (".$list.") ORDER BY `title` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['title'] = ucwords(strtolower($row['title']));
					$result[$count]['parent_id'] = $row['parent_id'];
					$result[$count]['priority'] = $row['priority'];
					$result[$count]['priority_code'] = $row['priority_code'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function getRandomIDs($limit=3) {
			$sql = mysql_query("SELECT `ref` FROM `categories` WHERE `parent_id` = 0 ORDER BY RAND() LIMIT ".$limit) or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
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
			
			$sql = mysql_query("SELECT * FROM `categories` WHERE `".$tag."` = '".$id."'".$sqlTag." ORDER BY `ref` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['title'] = ucwords(strtolower($row['title']));
					$result[$count]['parent_id'] = $row['parent_id'];
					$result[$count]['priority'] = $row['priority'];
					$result[$count]['priority_code'] = $row['priority_code'];
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
			$sql = mysql_query("SELECT * FROM `categories` WHERE `".$tag."` = '".$id."' ORDER BY `ref` DESC LIMIT 1") or die (mysql_error());
			if ($sql) {
				$result = array();
				
				if (mysql_num_rows($sql) == 1) {
					$row = mysql_fetch_array($sql);
					$result['ref'] = $row['ref'];
					$result['title'] = ucwords(strtolower($row['title']));
					$result['parent_id'] = $row['parent_id'];
					$result['priority'] = $row['priority'];
					$result['priority_code'] = $row['priority_code'];
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
		
		function gettree($id) {
			$ids = $this->sortAll($id, "parent_id");
			$result = "";
			if (count($ids) > 0) {
				$result .= "<ul>";
				for ($i = 0; $i < count($ids); $i++) {
					$result .= "<li><a href='#'>".strtolower($ids[$i]['title']."_".$ids[$i]['ref'])."</a>";
					$result .= $this->gettree($ids[$i]['ref']);
				}
				$result .= "</li>";
				$result .= "</ul>";
			}
			return $result;
		}
		
		function gettreeHome($id) {
			$ids = $this->sortAll($id, "parent_id");
			$result = "";
			if (count($ids) > 0) {
				$result .= "<ul>";
				for ($i = 0; $i < count($ids); $i++) {
					$result .= "	<li><a href='".URL."document?sort=".$ids[$i]['ref']."'>".strtolower($ids[$i]['title'])."</a>";
					$result .= $this->gettreeHome($ids[$i]['ref']);
				}
				$result .= "</li>";
				$result .= "</ul>";
			}
			return $result;
		}
		
		function showLink($id) {
			$list = $this->listLink($id);
			for ($i = (count($list) - 1); $i >= 0; $i--) {
				$link .= " ".$list[$i]['title'].' > ';
			}
			$link = trim($link, ">");
			$link = trim($link, " ");
			return $link;
		}
		
		function listLink($id) {
			$result = "";
			$count = 0;
			$loop = true;
			$one = $this->getOne($id);
			$result[$count]['ref'] = $one['ref'];
			$result[$count]['title'] = $one['title'];
			$id = $one['parent_id'];
			while ($id > 0) {
				$count++;
				
				$data = $this->getOne($id);
				if ($id > 0) {
					$result[$count]['ref'] = $id;
					$result[$count]['title'] = $data['title'];
				}
				$id = $data['parent_id'];
			}
			$count++;
			return $result;
		}
		
		function linkListListDef($id) {
			$result = $id.",";
			$result .= $this->listLinkList($id);
			return trim($result, ",");
		}
		
		function listLinkList($id, $count = 0) {
			$result = "";
			$ids = $this->sortAll($id, "parent_id");
			if (count($ids) > 0) {
				for ($i = 0; $i < count($ids); $i++) {
					$result .= $ids[$i]['ref'].",";
					$count++;
					$result .= $this->listLinkList($ids[$i]['ref'], $count);
				}
			}
			return trim($result);
		}
		
		function linkListListDefSearch($val) {
			$sql = mysql_query("SELECT `ref` FROM `categories` WHERE `title` LIKE '%".$val."%'") or die (mysql_error());
			
			if ($sql) {
				while ($row = mysql_fetch_array($sql)) {
					$result .= $this->listLinkList($row['ref']);
				}
			}
			
			return trim($result, ",");
		}
		
		function setPriority($array, $id) {
			/*$catList = $this->listLink($id);
			$list = "";
			for ($i = 0; $i < count($catList); $i++) {
				$list .= $catList[$i]['ref'];
			}
			foreach ($array as $key => $value) {
				$priority = $key.$list.$value;
				$this->modifyOne("priority", $priority, $key);
			}
			return true;*/
			
			$catList = $this->listLink($id);
			$list = "";
			for ($i = 0; $i < count($catList); $i++) {
				$list = $list + $this->getOneField($catList[$i]['ref'], "ref", "priority");
			}
			$total = count($array);
			$paent_id = $this->getParent($id);
			if (intval($paent_id['ref']) == 0) {
				$total_base = 100;
			} else {
				$total_base = $this->getOneField($paent_id['ref'], "ref", "priority");
			}
			foreach ($array as $key => $value) {
				$real = ($total+1)-$value;
				$priority = (ceil(($real/$total)*$total_base)+$list);
				$this->modifyOne("priority", $priority, $key);
				$this->modifyOne("priority_code", $value, $key);
			}
			return true;
		}
		
		function getParent($id) {
			$list = $this->listLink($id);
			$count = count($list)-1;
			$result = $list[$count];
			return $result;
		}
	}
?>