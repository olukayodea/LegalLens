<?php
	class drafting_sections extends common {
		function add($array) {
			$drafting = $this->mysql_prep($array['drafting']);
			$section_content = $this->mysql_prep($array['section_content']);
			$tags = $this->mysql_prep($array['tags']);
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
			
			$sql = mysql_query("INSERT INTO `drafting_sections` (".$firstpart."`drafting`, `section_content`,`tags`, `status`, `create_time`, `modify_time`) VALUES (".$secondPArt."'".$drafting."','".$section_content."','".$tags."','".$status."', '".$create_time."', '".$modify_time."') ON DUPLICATE KEY UPDATE `section_content` = '".$section_content."', `status` = '".$status."', `tags` = '".$tags."', `modify_time` = '".$modify_time."'") or die (mysql_error());
			
			if ($sql) {
				$id = mysql_insert_id();
				if ($ref == "") {
					$doc = new drafting;
					$doc->modifyOne("status", "active", $drafting);
				}
				//mysql_query("ALTER TABLE drafting_sections ADD FULLTEXT (section_content);") or die (mysql_error());
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
			
			@unlink("../library/agreement/".$data['file']);
			$sql = mysql_query("DELETE FROM `drafting_sections` WHERE ref = '".$id."'") or die (mysql_error());
			
			if ($sql) {
			
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = $_SESSION['admin']['id'];
				$logArray['desc'] = "removed drafting section id #".$id;
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
			$sql = mysql_query("UPDATE `drafting_sections` SET `".$tag."` = '".$value."', `modify_time` = '".$modDate."' WHERE ref = '".$id."'") or die (mysql_error());
			
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
			$sql = mysql_query("SELECT * FROM `drafting_sections` ORDER BY `ref` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['drafting'] = $row['drafting'];
					$result[$count]['section_content'] = $row['section_content'];
					$result[$count]['tags'] = $row['tags'];
					$result[$count]['file'] = $row['file'];
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
			$sql = mysql_query("SELECT * FROM `drafting_sections` WHERE ref IN (".$list.") ORDER BY `section_no` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['drafting'] = $row['drafting'];
					$result[$count]['section_content'] = $row['section_content'];
					$result[$count]['tags'] = $row['tags'];
					$result[$count]['file'] = $row['file'];
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
			$sql = mysql_query("SELECT * FROM `drafting_sections` WHERE `".$tag."` = '".$id."'".$sqlTag." ORDER BY `ref` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['drafting'] = $row['drafting'];
					$result[$count]['section_content'] = $row['section_content'];
					$result[$count]['tags'] = $row['tags'];
					$result[$count]['file'] = $row['file'];
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
			$sql = mysql_query("SELECT * FROM `drafting_sections` WHERE `".$tag."` = '".$id."' ORDER BY `ref` DESC LIMIT 1") or die (mysql_error());
			if ($sql) {
				$result = array();
				
				if (mysql_num_rows($sql) == 1) {
					$row = mysql_fetch_array($sql);
					$result['ref'] = $row['ref'];
					$result['drafting'] = $row['drafting'];
					$result['section_content'] = $row['section_content'];
					$result['tags'] = $row['tags'];
					$result['file'] = $row['file'];
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
				
				if (($extension == "doc") || ($extension == "docx") || ($extension == "pdf")) {
					$size=filesize($array['media_file']['tmp_name']);
					
					$userDoc = "../library/agreement/";
					$file = time().rand(100, 999).".".$extension;
					
					if(!is_dir($userDoc)) {
						mkdir($userDoc, 0777, true);
					}
					
					$newFile = $userDoc.$file;
					move_uploaded_file($uploadedfile, $newFile);
					
					$msg['title'] = "OK";
					$msg['desc'] = $file;
					$msg['fileName'] = $image;
					$msg['size'] = $size;
					$msg['type'] = $extension;
				} else {
					$msg['title'] = "ERROR";
					$msg['desc'] = "the file extension ".$extension." is not allowed";
				}
			}
			return $msg;
		}
	}
?>