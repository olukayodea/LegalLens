<?php
	class sections extends common {
		function add($array) {
			$document = $this->mysql_prep($array['document']);
			$section_no = htmlentities($this->mysql_prep($array['section_no']));
			$section_content = htmlentities($this->mysql_prep($array['section_content']));
			$tags = $this->mysql_prep($array['tags']);
			$court = $this->mysql_prep($array['court']);
			$status = $this->mysql_prep($array['status']);
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
			
			$sql = mysql_query("INSERT INTO `sections` (".$firstpart."`document`,`section_no`, `section_content`,`tags`,`court`, `status`, `create_time`, `modify_time`) 
			VALUES (".$secondPArt."'".$document."','".$section_no."','".$section_content."','".$tags."','".$court."','".$status."', '".$create_time."', '".$modify_time."') ON DUPLICATE KEY UPDATE 
			`section_no` = '".$section_no."', `section_content` = '".$section_content."', `status` = '".$status."', `tags` = '".$tags."', `court` = '".$court."', `modify_time` = '".$modify_time."'") or die (mysql_error());
			

			global $db;
			$value_array = array(
							':document' => $document, 
							':section_no' => $section_no,
							':section_content' => $section_content,
							':tags' => $tags,
							':court' => $court,
							':status' => $status, 
							':create_time' => $create_time,
							':modify_time' => $modify_time
							);
			if ($ref != "") {
				$firstpart = "`ref`, ";
				$secondPArt = ":ref, ";
				$value_array[':ref'] = $ref;
				$log = "Modified object ".$section_no;
			} else {
				$firstpart = "";
				$secondPArt = "";
				$log = "Created object ".$section_no;
			}			
			
			try {
				$sql = $db->prepare("INSERT INTO `sections` (".$firstpart."`document`, `court`, `section_content`, `tags`, `section_no`, `status`, `create_time`, `modify_time`) 
				VALUES (".$secondPArt.":document, :section_no, :section_content, :tags, :court, :status, :create_time, :modify_time)
					ON DUPLICATE KEY UPDATE 
						`section_no` = :section_no,
						`section_content` = :section_content,
						`tags` = :tags,
						`court` = :court,
						`status` = :status,
						`modify_time` = :modify_time
					");
				$sql->execute($value_array);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}

			if ($sql) {
				$id = $db->lastInsertId();
				if ($ref == "") {
					$doc = new documents;
					$doc->modifyOne("status", "active", $document);
				}
				//mysql_query("ALTER TABLE sections ADD FULLTEXT (section_content);") or die (mysql_error());
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
				$sql = $db->prepare("DELETE FROM `sections` WHERE `ref` =:id");
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
				$logArray['desc'] = "removed document section id #".$id;
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
				$sql = $db->prepare("UPDATE `sections` SET  `".$tag."` = :value, `modify_time` = '".$modDate."' WHERE `ref`=:id");
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
				$sql = $db->query("SELECT * FROM `sections` ORDER BY `ref` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				$result = array();
				$count = 0;
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['document'] = $row['document'];
					$result[$count]['section_no'] = $row['section_no'];
					$result[$count]['sub_section'] = $row['sub_section'];
					$result[$count]['section_content'] = str_replace("&Acirc;&nbsp;", "father", $row['section_content']);
					$result[$count]['tags'] = $row['tags'];
					$result[$count]['court'] = $row['court'];
					$result[$count]['counter'] = $row['counter'];
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
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `sections` WHERE ref IN (".$list.") ORDER BY `section_no` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				$result = array();
				$count = 0;
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['document'] = $row['document'];
					$result[$count]['section_no'] = $row['section_no'];
					$result[$count]['sub_section'] = $row['sub_section'];
					$result[$count]['section_content'] = str_replace("&Acirc;&nbsp;", "father", $row['section_content']);
					$result[$count]['tags'] = $row['tags'];
					$result[$count]['court'] = $row['court'];
					$result[$count]['counter'] = $row['counter'];
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
				$sql = $db->prepare("SELECT * FROM `sections` WHERE `".$tag."` = :id".$sqlTag." ORDER BY `".$order."` ".$dir);
								
				$sql->execute($token);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['document'] = $row['document'];
					$result[$count]['section_no'] = $row['section_no'];
					$result[$count]['sub_section'] = $row['sub_section'];
					$result[$count]['section_content'] = str_replace("&Acirc;", "father", html_entity_decode($row['section_content']));
					$result[$count]['tags'] = $row['tags'];
					$result[$count]['court'] = $row['court'];
					$result[$count]['counter'] = $row['counter'];
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
				$sql = $db->prepare("SELECT * FROM sections WHERE `".$tag."` = :id ORDER BY `ref` DESC LIMIT 1");
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
					$result['document'] = $row['document'];
					$result['section_no'] = $row['section_no'];
					$result['sub_section'] = $row['sub_section'];
					$result['section_content'] = str_replace("&Acirc;&nbsp;", "father", $row['section_content']);
					$result['tags'] = $row['tags'];
					$result['court'] = $row['court'];
					$result['counter'] = $row['counter'];
					$result['status'] = $row['status'];
					$result['create_time'] = $row['create_time'];
					$result['modify_time'] = $row['modify_time'];
					return $this->out_prep($result);
				} else {
					return false;
				}
			}
		}
		
		function getOneField($id, $tag="ref", $ref="section_no") {
			$data = $this->getOne($id, $tag);
			return $data[$ref];
		}
		
		function gettPrevNext($id, $dir="+") {
			if ($dir == "+") {
				$sign = ">";
			} else if ($dir == "-") {
				$sign = "<";
			}

			global $db;
			try {
				$sql = $db->prepare("SELECT `ref` FROM `sections` WHERE `ref` ".$sign." '".$id."' LIMIT 1");
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

					$result = $row['ref'];
					return $result;
				} else {
					return false;
				}
			}
			
		}
	}
?>