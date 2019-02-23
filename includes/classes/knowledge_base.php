<?php
	class knowledge_base extends common {
		function add($array) {
			$title = htmlentities(ucfirst(strtolower($this->mysql_prep($array['title']))));
			$category = implode(",",$array['category']);
			$content = htmlentities($this->mysql_prep($array['content']));
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

			global $db;
			$value_array = array(
							':title' => $title,
							':category' => $category,
							':content' => $content,
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
				$sql = $db->prepare("INSERT INTO `knowledge_base` (".$firstpart."`title`, `content`, `category`, `status`, `create_time`, `modify_time`)
				VALUES (".$secondPArt.":title, :content, :category, :status, :create_time, :modify_time)
					ON DUPLICATE KEY UPDATE 
					`title` = :title,
					`status` = :status,
					`category` = :category,
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
				$logArray['desc'] = "created knowledge_base ".$title;
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
				$sql = $db->prepare("DELETE FROM `knowledge_base` WHERE `ref` =:id");
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
				$logArray['desc'] = "removed knowledge_base Item with Ref ".$id;
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
				$sql = $db->prepare("UPDATE `knowledge_base` SET  `".$tag."` = :value, `modify_time` = :modifyTime WHERE `ref`=:id");
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
				return true;
			} else {
				return false;
			}
		}
		
		function listAll() {
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `knowledge_base` ORDER BY `ref` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['title'] = ucwords(strtolower($row['title']));
					$result[$count]['content'] = $row['content'];
					$result[$count]['category'] = $row['category'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function searchCategory($id) {
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `knowledge_base` WHERE category LIKE '%".$id."%' ORDER BY `title` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['title'] = ucwords(strtolower($row['title']));
					$result[$count]['content'] = $row['content'];
					$result[$count]['category'] = $row['category'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function search($id) {
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `knowledge_base` WHERE`status` = 'active' AND (`title` LIKE '%".$id."%' OR `content` LIKE '%".$id."%' OR MATCH(`content`) AGAINST ('".$id."')) ORDER BY `title` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['title'] = ucwords(strtolower($row['title']));
					$result[$count]['content'] = $row['content'];
					$result[$count]['category'] = $row['category'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function sortAll($id, $tag, $tag2=false, $id2=false, $tag3=false, $id3=false, $orderby = "ref", $dir="ASC", $limit=false) {
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

			if ($limit == true) {
				$limitTag = " LIMIT ".$limit;
			} else {
				$limitTag = "";
			}
			
			if ($orderby == "rand") {
				$order = "RAND()";
			} else {
				$order = "`".$orderby."`".$dir;
			}

			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM `knowledge_base` WHERE `".$tag."` = :id".$sqlTag." ORDER BY ".$order.$limitTag);
								
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
					$result[$count]['content'] = $row['content'];
					$result[$count]['category'] = $row['category'];
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
				$sql = $db->prepare("SELECT * FROM knowledge_base WHERE `".$tag."` = :id ORDER BY `ref` DESC LIMIT 1");
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
	}
	
	class knowledge_base_category extends common {
		function add($array) {
			$title = $this->mysql_prep($array['title']);
			$ref = $this->mysql_prep($array['ref']);
			
			global $db;
			$value_array = array(
							':title' => $title
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
				$sql = $db->prepare("INSERT INTO `knowledge_base_category` (".$firstpart."`title`)
				VALUES (".$secondPArt.":title)
					ON DUPLICATE KEY UPDATE 
						`title` = :title
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
				$logArray['desc'] = "created owner ".$title;
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
				$sql = $db->prepare("DELETE FROM `knowledge_base_category` WHERE `ref` =:id");
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
				$logArray['desc'] = "removed owner knowledge_base_category with Ref ".$id;
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
				$sql = $db->prepare("UPDATE `knowledge_base_category` SET  `".$tag."` = :value, `modify_time` = :modifyTime WHERE `ref`=:id");
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
				return true;
			} else {
				return false;
			}
		}
		
		function listAll() {
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `knowledge_base_category` ORDER BY `ref` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		function sortAll($id, $tag, $tag2=false, $id2=false, $tag3=false, $id3=false, $orderby = "ref", $dir="ASC", $limit=false) {
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

			if ($limit == true) {
				$limitTag = " LIMIT ".$limit;
			} else {
				$limitTag = "";
			}
			
			if ($orderby == "rand") {
				$order = "RAND()";
			} else {
				$order = "`".$orderby."`".$dir;
			}

			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM `knowledge_base_category` WHERE `".$tag."` = :id".$sqlTag." ORDER BY `".$order.$limitTag);
								
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
				$sql = $db->prepare("SELECT * FROM knowledge_base_category WHERE `".$tag."` = :id ORDER BY `ref` DESC LIMIT 1");
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
		
		function catToTex($id) {
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `knowledge_base_category` WHERE `ref` IN (".$id.") ORDER BY `title` ASC");								
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			$result = "";
			if ($sql) {
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result .= $row['title'].", ";
				}
				return trim(trim($result), ",");
				
			}
		}
		
		function catToTexLink($id,$mobile = false) {
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `knowledge_base_category` WHERE `ref` IN (".$id.") ORDER BY `title` ASC");								
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}

			if ($sql) {		
				$result = "";		
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					if($mobile)
					$result .= "<a href='".URL."mobilehelpAndSupport?c=".$row['ref']."'>".$row['title']."</a>, ";
					else
					$result .= "<a href='".URL."helpAndSupport?c=".$row['ref']."'>".$row['title']."</a>, ";
				}
				return trim(trim($result), ",");		
			}
		}
	}
?>