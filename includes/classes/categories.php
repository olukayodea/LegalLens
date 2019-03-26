<?php
	class categories extends common {
		function add($array) {
			$title = htmlentities(ucfirst(strtolower($this->mysql_prep($array['title']))));
			$parent_id = $this->mysql_prep($array['cat_id']);
			$priority = $this->mysql_prep($array['priority']);
			$status = $this->mysql_prep($array['status']);
			$priority_code = (count($this->sortAll($parent_id, "parent_id")) + 1);
			$create_time = $modify_time = time();
			$ref = $this->mysql_prep($array['ref']);
			
			global $db;
			$value_array = array(
							':title' => $title, 
							':parent_id' => $parent_id,
							':priority' => $priority,
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
				$sql = $db->prepare("INSERT INTO `categories` (".$firstpart."`title`, `parent_id`, `priority`, `status`, `create_time`, `modify_time`)
				VALUES (".$secondPArt.":title, :parent_id, :priority, :status, :create_time, :modify_time)
					ON DUPLICATE KEY UPDATE 
						`title` = :title,
						`parent_id` = :parent_id,
						`status` = :status,
						`modify_time` = :modify_time
					");
				$sql->execute($value_array);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
						
			if ($sql) {
				$id = $db->lastInsertId();
				$post[$id] = $priority_code;
				$this->setPriority($post, $parent_id);
				
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
				$sql = $db->prepare("DELETE FROM `categories` WHERE `ref` =:id");
				$sql->execute(
					array(
					':id' => $id)
				);
				$sql2 = $db->query("DELETE FROM `categories` WHERE ref IN (".trim($this->listLinkList($id), ",").")");
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
				$sql = $db->prepare("UPDATE `categories` SET  `".$tag."` = :value, `modify_time` = :modifyTime WHERE `ref`=:id");
				$sql->execute(
					array(
					':value' => $value,
					':modifyTime' => time(),
					':id' => $id)
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
		}
		
		function listAll() {
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `categories` ORDER BY `ref` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		function lisstMultiple($array) {
			$list = implode(",", $array);

			global $db;
			try {
				$sql = $db->query("SELECT * FROM `categories` WHERE ref IN (".$list.") ORDER BY `title` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			if ($sql) {
				$result = array();
				$count = 0;
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
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
			global $db;
			try {
				$sql = $db->query("SELECT `ref` FROM `categories` WHERE `parent_id` = 0 ORDER BY RAND() LIMIT ".$limit);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			if ($sql) {
				$result = array();
				$count = 0;
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result[$count]['ref'] = $row['ref'];
					$count++;
				}
				return $this->out_prep($result);
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
					$sql = $db->prepare("SELECT * FROM `categories` WHERE `".$tag."` = :id".$sqlTag." ORDER BY `".$order."` ASC");
									
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
				$sql = $db->prepare("SELECT * FROM categories WHERE `".$tag."` = :id ORDER BY `ref` DESC LIMIT 1");
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
		
		function gettreeCheckBox($id) {
			$ids = $this->sortAll($id, "parent_id", false, false, false, false, "title");
			$result = "";
			if (count($ids) > 0) {
				for ($i = 0; $i < count($ids); $i++) {
					$result .= "<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='checkbox' name='filter[]' id='filter_".$id."_".$i."' class='filter_".$id."' data-main='no' value='".$ids[$i]['ref']."'>&nbsp;".ucfirst(strtolower($ids[$i]['title']))."</label>";
				}
			}
			return $result;
		}
		
		function showLink($id) {
			$list = $this->listLink($id);
			$link = "";
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
			global $db;
			try {
				$sql = $db->query("SELECT `ref` FROM `categories` WHERE `title` LIKE '%".$val."%'");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			$result = "";
			if ($sql) {
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
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