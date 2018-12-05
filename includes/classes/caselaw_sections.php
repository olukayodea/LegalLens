<?php
	class caselaw_sections extends common {
		function add($array) {
			$caselaw = $this->mysql_prep($array['caselaw']);
			$parent_issue = $this->mysql_prep($array['parent_issue']);
			$section_content = $this->mysql_prep($array['section_content']);
			$areas = implode(",",$array['areas']);
			$tags = $this->mysql_prep($array['tags']);
			$status = $this->mysql_prep($array['status']);
			$citation = $this->mysql_prep($array['citation']);
			$create_time = $modify_time = time();
			$ref = $this->mysql_prep($array['ref']);
			
			if ($ref != "") {
				$firstpart = "`ref`, ";
				$secondPArt = "'".$ref."', ";
				$log = "Modified object";
			} else {
				$firstpart = "";
				$secondPArt = "";
				$log = "Created object ";
			}
			
			$sql = mysql_query("INSERT INTO `caselaw_sections` (".$firstpart."`caselaw`,`parent_issue`,`areas`, `section_content`,`tags`, `status`, `citation`, `create_time`, `modify_time`) VALUES (".$secondPArt."'".$caselaw."','".$parent_issue."','".$areas."','".$section_content."','".$tags."','".$status."','".$citation."', '".$create_time."', '".$modify_time."') ON DUPLICATE KEY UPDATE `parent_issue` = '".$parent_issue."', `section_content` = '".$section_content."', `status` = '".$status."',`areas` = '".$areas."',`citation`='".$citation."', `tags` = '".$tags."', `modify_time` = '".$modify_time."'") or die (mysql_error());
			
			if ($sql) {
				$id = mysql_insert_id();
				if ($ref == "") {
					$doc = new caselaw;
					$doc->modifyOne("status", "active", $caselaw);
				}
				//mysql_query("ALTER TABLE caselaw_sections ADD FULLTEXT (section_content);") or die (mysql_error());
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
			$sql = mysql_query("DELETE FROM `caselaw_sections` WHERE ref = '".$id."'") or die (mysql_error());
			
			if ($sql) {
			
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = $_SESSION['admin']['id'];
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
			$modDate = time();
			$sql = mysql_query("UPDATE `caselaw_sections` SET `".$tag."` = '".$value."', `modify_time` = '".$modDate."' WHERE ref = '".$id."'") or die (mysql_error());
			
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
			$sql = mysql_query("SELECT * FROM `caselaw_sections` ORDER BY `ref` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['caselaw'] = $row['caselaw'];
					$result[$count]['areas'] = $row['areas'];
					$result[$count]['parent_issue'] = $row['parent_issue'];
					$result[$count]['section_content'] = $row['section_content'];
					$result[$count]['tags'] = $row['tags'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['citation'] = $row['citation'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function lisstMultiple($array) {
			$list = implode(",", $array);
			$sql = mysql_query("SELECT * FROM `caselaw_sections` WHERE ref IN (".$list.") ORDER BY `parent_issue` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['caselaw'] = $row['caselaw'];
					$result[$count]['areas'] = $row['areas'];
					$result[$count]['parent_issue'] = $row['parent_issue'];
					$result[$count]['section_content'] = $row['section_content'];
					$result[$count]['tags'] = $row['tags'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['citation'] = $row['citation'];
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
			
			$sql = mysql_query("SELECT * FROM `caselaw_sections` WHERE `".$tag."` = '".$id."'".$sqlTag." ORDER BY `ref` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['caselaw'] = $row['caselaw'];
					$result[$count]['areas'] = $row['areas'];
					$result[$count]['parent_issue'] = $row['parent_issue'];
					$result[$count]['section_content'] = $row['section_content'];
					$result[$count]['tags'] = $row['tags'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['citation'] = $row['citation'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function getOne($id, $tag='ref') {
			$id = $this->mysql_prep($id);
			$sql = mysql_query("SELECT * FROM `caselaw_sections` WHERE `".$tag."` = '".$id."' ORDER BY `ref` DESC LIMIT 1") or die (mysql_error());
			if ($sql) {
				$result = array();
				
				if (mysql_num_rows($sql) == 1) {
					$row = mysql_fetch_array($sql);
					$result['ref'] = $row['ref'];
					$result['caselaw'] = $row['caselaw'];
					$result['areas'] = $row['areas'];
					$result['parent_issue'] = $row['parent_issue'];
					$result['section_content'] = $row['section_content'];
					$result['tags'] = $row['tags'];
					$result['status'] = $row['status'];
					$result['citation'] = $row['citation'];
					$result['create_time'] = $row['create_time'];
					$result['modify_time'] = $row['modify_time'];
					return $this->out_prep($result);
				} else {
					return false;
				}
			}
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