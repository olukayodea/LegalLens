<?php
	class caselaw_sections extends common {
		function add($array) {
			$caselaw = $this->mysql_prep($array['caselaw']);
			$parent_issue = $this->mysql_prep($array['parent_issue']);
			$section_content = htmlentities($this->mysql_prep($array['section_content']));
			$areas = implode(",",$array['areas']);
			$tags = $this->mysql_prep($array['tags']);
			$status = $this->mysql_prep($array['status']);
			$citation = $this->mysql_prep($array['citation']);
			$create_time = $modify_time = time();
			$ref = $this->mysql_prep($array['ref']);


			global $db;
			$value_array = array(
							':caselaw' => $caselaw, 
							':parent_issue' => $parent_issue, 
							':areas' => $areas,
							':section_content' => $section_content,
							':tags' => $tags,
							':status' => $status,
							':citation' => $citation,
							':create_time' => $create_time,
							':modify_time' => $modify_time
							);
			if ($ref != "") {
				$firstpart = "`ref`, ";
				$secondPArt = ":ref, ";
				$value_array[':ref'] = $ref;
				$log = "Modified object ";
			} else {
				$firstpart = "";
				$secondPArt = "";
				$log = "Created object ";
			}			
			
			try {
				$sql = $db->prepare("INSERT INTO `caselaw_sections` (".$firstpart."`caselaw`,`parent_issue`,`areas`, `section_content`,`tags`, `status`, `citation`, `create_time`, `modify_time`)
				VALUES (".$secondPArt.":caselaw, :parent_issue, :areas, :section_content, :tags, :status, :citation, :create_time, :modify_time)
					ON DUPLICATE KEY UPDATE 
						`parent_issue` = :parent_issue,
						`section_content` = :section_content,
						`status` = :status,
						`areas` = :areas,
						`citation` = :citation,
						`tags` = :tags,
						`modify_time` = :modify_time
					");
				$sql->execute($value_array);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
						
			if ($sql) {
				$id = $db->lastInsertId();

				if ($ref == "") {
					$doc = new caselaw;
					$doc->modifyOne("status", "active", $caselaw);
				}
				//mysql_query("ALTER TABLE caselaw_sections ADD FULLTEXT (section_content);") or die (mysql_error());
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
				$sql = $db->prepare("DELETE FROM `caselaw_sections` WHERE `ref` =:id");
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
				$logArray['desc'] = "removed caselaw section id #".$id;
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
				$sql = $db->prepare("UPDATE `caselaw_sections` SET  `".$tag."` = :value, `modify_time` = :modifyTime WHERE `ref`=:id");
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
				$sql = $db->query("SELECT * FROM `caselaw_sections` ORDER BY `ref` ASC");
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
				$sql = $db->query("SELECT * FROM `caselaw_sections` WHERE ref IN (".$list.") ORDER BY `parent_issue` ASC");
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
				$sql = $db->prepare("SELECT * FROM `caselaw_sections` WHERE `".$tag."` = :id".$sqlTag." ORDER BY `".$order."` ASC");
								
				$sql->execute($token);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
			return $this->out_prep($row);
		}
		
		function getOne($id, $tag='ref') {
			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM caselaw_sections WHERE `".$tag."` = :id ORDER BY `ref` DESC LIMIT 1");
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
		
		function getOneField($id, $tag="ref", $ref="parent_issue") {
			$data = $this->getOne($id, $tag);
			return $data[$ref];
		}
		
		function turnClickable($id) {
			$data = $this->getOne($id);
			$list = explode(",", $data['tags']);
			
			for ($i = 0; $i < count($list); $i++) {
				$array[trim($list[$i])] = '<a href="'.URL.'home?q='.urlencode(trim($list[$i])).'">'.trim($list[$i]).'</a>';
			}
			
			$result = str_replace(array_keys($array), array_values($array), $data['section_content']);
			
			return $result;
		}

		function turnClickableMobile($id) {
			$data = $this->getOne($id);
			$list = explode(",", $data['tags']);
			
			for ($i = 0; $i < count($list); $i++) {
				$array[trim($list[$i])] = '<a href="'.URL.'mobilehome?q='.urlencode(trim($list[$i])).'">'.trim($list[$i]).'</a>';
			}
			
			$result = str_replace(array_keys($array), array_values($array), $data['section_content']);
			
			return $result;
		}
	}
?>