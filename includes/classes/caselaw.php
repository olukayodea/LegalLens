<?php
	class caselaw extends common {
		function add($array, $datafiles) {
			$title = $this->mysql_prep($array['title']);
			$status = $this->mysql_prep($array['status']);
			$court = $this->mysql_prep($array['court']);
			$reporter = $this->mysql_prep($array['reporter']);
			$owner = $this->mysql_prep($array['owner']);
			$year = $this->mysql_prep($array['year']);
			$areas = implode(',', $array['area']);
			$create_time = $modify_time = time();
			$ref = $this->mysql_prep($array['ref']);
			$updateFile = $this->mysql_prep($array['file']);
			
			if ($updateFile == 1) {
				$up_file = false;
			} else {
				$up_file = true;
			}
			
			if ($up_file == true) {
				$file = $this->uploadFile($datafiles);
				if ($file['title'] == "ERROR") {
					$message = $file['desc'];
					$op_file = false;
				} else {
					$file_data = $file['desc'];
					$op_file = true;
				}
			} else {
				$file_data = "";
				$op_file = true;
			}
			
			if ($ref != "") {
				$firstpart = "`ref`, ";
				echo $secondPArt = "'".$ref."', ";
				$log = "Modified object ".$section_no;
			} else {
				$firstpart = "";
				$secondPArt = "";
				$log = "Created object ".$section_no;
			}
			
			if ($file_data != "") {
				$firstpart .= "`file`, ";
				$secondPArt .= "'".$file_data."', ";
				$dupPart .= "`file` = '".$file_data."', ";
			}
			
			if ($op_file == true) {
					$sql = mysql_query("INSERT INTO `caselaw` (".$firstpart."`title`,`areas`,`owner`, `status`, `court`,`year`, `reporter`, `create_time`, `modify_time`) VALUES (".$secondPArt."'".$title."','".$areas."','".$owner."','".$status."','".$court."','".$year."','".$reporter."', '".$create_time."', '".$modify_time."') ON DUPLICATE KEY UPDATE ".$dupPart."`title` = '".$title."', `areas`='".$areas."',`status` = '".$status."', `court` = '".$court."', `reporter` = '".$reporter."', `year`='".$year."',`owner` = '".$owner."', `modify_time` = '".$modify_time."'") or die (mysql_error());
				
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
					return intval($id);
				} else {
					return false;
				}
			} else {
				return $message;
			}
		}
		
		function uploadFile($array) {
			ini_set("memory_limit", "200000000");
						
			$image = $array["media_file"]["name"];
			$uploadedfile = $array['media_file']['tmp_name'];
			$msg = array();
			if ($array["media_file"]["error"] == 1) {
				$msg['title'] = "ERROR";
				$msg['desc'] = "The uploaded file exceeds the mazimum upload file limit";
			} else if ($array["media_file"]["error"] == 2 ) {
				$msg['title'] = "ERROR";
				$msg['desc'] = "The uploaded file exceeds the mazimum upload file limit";
			} else if ($array["media_file"]["error"] == 3) {
				$msg['title'] = "ERROR";
				$msg['desc'] = "The uploaded file was only partially uploaded, please re-upload file";
			} else if ($array["media_file"]["error"] == 4) {
				$msg['title'] = "ERROR";
				$msg['desc'] = "Missing file, please check the uploaded file and try again";
			} else if ($array["media_file"]["error"] == 6) {
				$msg['title'] = "ERROR";
				$msg['desc'] = "Missing a temporary folder, contact the website administrator";
			} else if ($array["media_file"]["error"] == 7) {
				$msg['title'] = "ERROR";
				$msg['desc'] = "Failed to write file to disk, contact the administrator";
			} else if ($array["media_file"]["error"] == 0) {
				$media_file = stripslashes($array['media_file']['name']);
				$uploadedfile = $array['media_file']['tmp_name']; 
				$extension = $this->getExtension($media_file);
				$extension = strtolower($extension);
				
				if ($extension == "pdf") {
					$size=filesize($array['media_file']['tmp_name']);
					
					$userDoc = "../library/caselaws/";
					$file = time().rand(100, 999).".".$extension;
					
					if(!is_dir($userDoc)) {
						mkdir($userDoc, 0777, true);
					}
					
					$newFile = $userDoc.$file;
					$move = move_uploaded_file($uploadedfile, $newFile);
					
					if ($move) {
						$msg['title'] = "OK";
						$msg['desc'] = $file;
					} else {
						$msg['title'] = "ERROR";
						$msg['desc'] = "an error occured";
					}
				} else {
					$msg['title'] = "ERROR";
					$msg['desc'] = "the file extension ".$extension." is not allowed";
				}
			}
			return $msg;
		}
		
		function remove($id) {
			$id = $this->mysql_prep($id);
			$modDate = time();
			
			$data = $this->getOne($id);
			
			@unlink("../library/caselaws/".$data['file']);
			$sql = mysql_query("DELETE FROM `caselaw` WHERE ref = '".$id."'") or die (mysql_error());
			
			if ($sql) {
			
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = $_SESSION['admin']['id'];
				$logArray['desc'] = "removed case law id #".$id;
				$logArray['create_date'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				return true;
			} else {
				return false;
			}
		}
		
		function removeFile($id) {
			$id = $this->mysql_prep($id);
			$data = $this->getOne($id);
			
			@unlink("../library/caselaws/".$data['file']);
			$this->modifyOne("file", "", $id);
			
			return true;
		}
		
		function modifyOne($tag, $value, $id) {
			$value = $this->mysql_prep($value);
			$id = $this->mysql_prep($id);
			$modDate = time();
			$sql = mysql_query("UPDATE `caselaw` SET `".$tag."` = '".$value."', `modify_time` = '".$modDate."' WHERE ref = '".$id."'") or die (mysql_error());
			
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
			$sql = mysql_query("SELECT * FROM `caselaw` ORDER BY `ref` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['title'] = $row['title'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['court'] = $row['court'];
					$result[$count]['areas'] = $row['areas'];
					$result[$count]['owner'] = $row['owner'];
					$result[$count]['year'] = $row['year'];
					$result[$count]['reporter'] = $row['reporter'];
					$result[$count]['file'] = $row['file'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function listAllHome($court=false, $filter="title") {
			$caselaw_area = new caselaw_area;
			if ($court != false) {
				$addition = "`court` = '".$court."' AND ";
			} else {
				$addition = "";
			}
			
			$listReg = $caselaw_area->sortAll("active", "status");
			$result = array();
			for ($i = 0; $i < count($listReg); $i++) {
				$tag = $listReg[$i]['title'];
				$sql = mysql_query("SELECT * FROM `caselaw` WHERE ".$addition."`status` = 'active' AND `areas` LIKE '%".$tag."%' ORDER BY `title` ASC") or die (mysql_error());
				
				if ($sql) {
					while ($row = mysql_fetch_array($sql)) {
						$count = count($result[$tag]);
						$result[$tag][$count]['ref'] = $row['ref'];
						$result[$tag][$count]['section_ref'] = 0;
						$result[$tag][$count]['title'] = $row['title'];
						$result[$tag][$count]['status'] = $row['status'];
						$result[$tag][$count]['court'] = $row['court'];
						$result[$tag][$count]['areas'] = $row['areas'];
						$result[$tag][$count]['owner'] = $row['owner'];
						$result[$tag][$count]['year'] = $row['year'];
						$result[$tag][$count]['reporter'] = $row['reporter'];
						$result[$tag][$count]['file'] = $row['file'];
						$result[$tag][$count]['create_time'] = $row['create_time'];
						$result[$tag][$count]['modify_time'] = $row['modify_time'];
					}
					ksort($result);
				}
			}
			return $this->out_prep($result);
		}
		
		function quickSearchSections($val, $court=false) {
			$val = $this->mysql_prep($val);
			if ($reporter != false) {
				$addition = "`caselaw`.`court` = '".$court."' AND ";
			} else {
				$addition = "";
			}
			$sql = mysql_query("SELECT `caselaw`.`areas`, `caselaw`.`title`, `caselaw`.`ref`, `caselaw_sections`.`section_content`, `caselaw_sections`.`tags`, `caselaw`.`reporter`, `caselaw_sections`.`ref` AS 'section_ID' FROM `caselaw`, `caselaw_sections` WHERE `caselaw`.`ref` = `caselaw_sections`.`caselaw` AND `caselaw`.`status` = 'active' AND `caselaw_sections`.`status` = 'active' AND ".$addition."(`caselaw_sections`.`citation` LIKE '%".$val."%' OR `caselaw_sections`.`areas` LIKE '%".$val."%' OR `caselaw`.`areas` LIKE '%".$val."%' OR `caselaw_sections`.`tags` LIKE '%".$val."%' OR `caselaw_sections`.`section_content` LIKE '%".$val."%' OR MATCH(`caselaw_sections`.`section_content`) AGAINST ('".$val."')) ORDER BY `title` ASC LIMIT 20") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['label'] = ucwords(strtolower($row['title']));
					$result[$count]['category'] = "Sections in ".$row['title']."s";
					$result[$count]['code'] = $row['ref'];
					$result[$count]['section'] = $row['section_ID'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function quickSearch($val, $reporter=false) {
			$val = $this->mysql_prep($val);
			if ($reporter != false) {
				$addition = "`court` = '".$reporter."' AND ";
			} else {
				$addition = "";
			}
			$sql = mysql_query("SELECT * FROM `caselaw` WHERE (`reporter` LIKE '%".$val."%' OR `year` LIKE '%".$val."%' OR `title` LIKE '%".$val."%' OR `areas` LIKE '%".$val."%') AND ".$addition."`status` = 'active' ORDER BY `title` ASC LIMIT 20") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['label'] = ucwords(strtolower($row['title']));
					$result[$count]['category'] = trim($row['title']);
					$result[$count]['code'] = $row['ref'];
					$result[$count]['section'] = 0;
					$count++;
				}
				return $this->out_prep($result);
			}
		}		
		
		function fullSearch($val, $court=false,$filter="title") {
			$caselaw_area = new caselaw_area;
			$val = $this->mysql_prep($val);
			if ($reporter != false) {
				$addition = "`caselaw`.`court` = '".$court."' AND ";
			} else {
				$addition = "";
			}
			$listReg = $caselaw_area->sortAll("active", "status");
			$result = array();
			for ($i = 0; $i < count($listReg); $i++) {
				$tag = $listReg[$i]['title'];
				$sql = mysql_query("SELECT `caselaw`.`areas`, `caselaw`.`title`, `caselaw`.`court`, `caselaw`.`file`,  `caselaw`.`year`, `caselaw`.`create_time`, `caselaw`.`modify_time`, `caselaw`.`ref`, `caselaw_sections`.`section_content`, `caselaw_sections`.`tags`, `caselaw_sections`.`ref` AS 'section_ID' FROM `caselaw`, `caselaw_sections` WHERE `caselaw`.`ref` = `caselaw_sections`.`caselaw` AND `caselaw`.`status` = 'active' AND `caselaw_sections`.`status` = 'active' AND ".$addition."(`caselaw`.`court` LIKE '%".$val."%' OR `caselaw`.`year` LIKE '%".$val."%' OR `caselaw`.`areas` LIKE '%".$val."%' OR `caselaw_sections`.`areas` LIKE '%".$val."%' OR `caselaw`.`title` LIKE '%".$val."%' OR `caselaw_sections`.`tags` LIKE '%".$val."%' OR `caselaw_sections`.`section_content` LIKE '%".$val."%' OR MATCH(`caselaw_sections`.`section_content`) AGAINST ('".$val."')) ORDER BY `caselaw`.`".$filter."` ASC") or die (mysql_error());
				
				if ($sql) {
					while ($row = mysql_fetch_array($sql)) {
						$count = count($result[$tag]);
						$result[$tag][$count]['ref'] = $row['ref'];
						$result[$tag][$count]['section_ref'] = intval($row['section_ID']);
						$result[$tag][$count]['title'] = $row['title'];
						$result[$tag][$count]['court'] = $row['court'];
						$result[$tag][$count]['owner'] = $row['owner'];
						$result[$tag][$count]['year'] = $row['year'];
						$result[$tag][$count]['areas'] = $row['areas'];
						$result[$tag][$count]['reporter'] = $row['reporter'];
						$result[$tag][$count]['file'] = $row['file'];
						$result[$tag][$count]['create_time'] = $row['create_time'];
						$result[$tag][$count]['modify_time'] = $row['modify_time'];
					}
					ksort($result);
				}
			}
			return $this->out_prep($result);
		}
		
		function indexSearch($val, $reporter=false, $filter="title") {
			$caselaw_area = new caselaw_area;
			$val = $this->mysql_prep($val);
			if ($reporter != false) {
				$addition = "`court` = '".$reporter."' AND ";
			} else {
				$addition = "";
			}
			$listReg = $caselaw_area->sortAll("active", "status");
			$result = array();
			//for ($i = 0; $i < count($listReg); $i++) {
				$tag = $listReg[$i]['title'];
				//$sql = mysql_query("SELECT * FROM `caselaw` WHERE `title` LIKE '".$val."%' AND ".$addition."`status` = 'active' AND `areas` LIKE '%".$tag."%' ORDER BY `title` ASC") or die (mysql_error());
				$sql = mysql_query("SELECT * FROM `caselaw` WHERE `areas` LIKE '".$val."%' AND ".$addition."`status` = 'active' ORDER BY `title` ASC") or die (mysql_error());
				
				if ($sql) {
					
					$count = 0;
					while ($row = mysql_fetch_array($sql)) {
						//$count = count($result[$tag]);
						$result[$row['areas']][$count]['ref'] = $row['ref'];
						$result[$row['areas']][$count]['section_ref'] = 0;
						$result[$row['areas']][$count]['title'] = $row['title'];
						$result[$row['areas']][$count]['status'] = $row['status'];
						$result[$row['areas']][$count]['court'] = $row['court'];
						$result[$row['areas']][$count]['owner'] = $row['owner'];
						$result[$row['areas']][$count]['year'] = $row['year'];
						$result[$row['areas']][$count]['areas'] = $row['areas'];
						$result[$row['areas']][$count]['reporter'] = $row['reporter'];
						$result[$row['areas']][$count]['file'] = $row['file'];
						$result[$row['areas']][$count]['create_time'] = $row['create_time'];
						$result[$row['areas']][$count]['modify_time'] = $row['modify_time'];
						$count++;
					}
					ksort($result);
				}
			//}
			return $this->out_prep($result);
		}
		
		function lisstMultiple($array) {
			$list = implode(",", $array);
			$sql = mysql_query("SELECT * FROM `caselaw` WHERE ref IN (".$list.") ORDER BY `section_no` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['title'] = $row['title'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['court'] = $row['court'];
					$result[$count]['owner'] = $row['owner'];
					$result[$count]['year'] = $row['year'];
					$result[$count]['areas'] = $row['areas'];
					$result[$count]['reporter'] = $row['reporter'];
					$result[$count]['file'] = $row['file'];
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
			
			$sql = mysql_query("SELECT * FROM `caselaw` WHERE `".$tag."` = '".$id."'".$sqlTag." ORDER BY `ref` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['title'] = $row['title'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['court'] = $row['court'];
					$result[$count]['year'] = $row['year'];
					$result[$count]['owner'] = $row['owner'];
					$result[$count]['areas'] = $row['areas'];
					$result[$count]['reporter'] = $row['reporter'];
					$result[$count]['file'] = $row['file'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function getOne($id, $tag='ref') {
			$id = $this->mysql_prep($id);
			$sql = mysql_query("SELECT * FROM `caselaw` WHERE `".$tag."` = '".$id."' ORDER BY `ref` DESC LIMIT 1") or die (mysql_error());
			if ($sql) {
				$result = array();
				
				if (mysql_num_rows($sql) == 1) {
					$row = mysql_fetch_array($sql);
					$result['ref'] = $row['ref'];
					$result['title'] = $row['title'];
					$result['status'] = $row['status'];
					$result['court'] = $row['court'];
					$result['areas'] = $row['areas'];
					$result['owner'] = $row['owner'];
					$result['year'] = $row['year'];
					$result['reporter'] = $row['reporter'];
					$result['file'] = $row['file'];
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
		
		function listCourt() {
			$sql = mysql_query("SELECT `court` FROM `caselaw` GROUP BY `court` ORDER BY `court`") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['title'] = ucwords(strtolower($row['court']));
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function counter($id, $from=false, $to=false) {
			
			if ($from != false) {
				$ad = " AND `date_time` BETWEEN ".$from." AND ".$to;
			}
			
			$sql = mysql_query("SELECT * FROM `counter_log` WHERE `type` = 'caseLaw' AND `id` IN (SELECT `ref` FROM `caselaw` WHERE `owner` = '".$id."')".$ad);
			
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