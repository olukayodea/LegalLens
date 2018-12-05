<?php
	class sections extends common {
		function add($array) {
			$document = $this->mysql_prep($array['document']);
			$section_no = $this->mysql_prep($array['section_no']);
			$sub_section = $this->mysql_prep($array['sub_section']);
			$section_content = $this->mysql_prep($array['section_content']);
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
			
			$sql = mysql_query("INSERT INTO `sections` (".$firstpart."`document`,`section_no`, `section_content`,`tags`,`court`, `status`, `create_time`, `modify_time`) VALUES (".$secondPArt."'".$document."','".$section_no."','".$section_content."','".$tags."','".$court."','".$status."', '".$create_time."', '".$modify_time."') ON DUPLICATE KEY UPDATE `section_no` = '".$section_no."', `section_content` = '".$section_content."', `status` = '".$status."', `tags` = '".$tags."', `court` = '".$court."', `modify_time` = '".$modify_time."'") or die (mysql_error());
			
			if ($sql) {
				$id = mysql_insert_id();
				if ($ref == "") {
					$doc = new documents;
					$doc->modifyOne("status", "active", $document);
				}
				//mysql_query("ALTER TABLE sections ADD FULLTEXT (section_content);") or die (mysql_error());
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
			$sql = mysql_query("DELETE FROM `sections` WHERE ref = '".$id."'") or die (mysql_error());
			
			if ($sql) {
			
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = $_SESSION['admin']['id'];
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
			$modDate = time();
			$sql = mysql_query("UPDATE `sections` SET `".$tag."` = '".$value."', `modify_time` = '".$modDate."' WHERE ref = '".$id."'") or die (mysql_error());
			
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
			$sql = mysql_query("SELECT * FROM `sections` ORDER BY `ref` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
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
			$sql = mysql_query("SELECT * FROM `sections` WHERE ref IN (".$list.") ORDER BY `section_no` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
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
			
			$sql = mysql_query("SELECT * FROM `sections` WHERE `".$tag."` = '".$id."'".$sqlTag." ORDER BY `ref` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
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
			$sql = mysql_query("SELECT * FROM `sections` WHERE `".$tag."` = '".$id."' ORDER BY `ref` DESC LIMIT 1") or die (mysql_error());
			if ($sql) {
				$result = array();
				
				if (mysql_num_rows($sql) == 1) {
					$row = mysql_fetch_array($sql);
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
			$sql = mysql_query("SELECT `ref` FROM `sections` WHERE `ref` ".$sign." '".$id."' LIMIT 1") or die (mysql_error());
			
			if ($sql) {				
				if (mysql_num_rows($sql) == 1) {
					$row = mysql_fetch_array($sql);
					$result = $row['ref'];
					return $result;
				} else {
					return false;
				}
			}
			
		}
	}
?>