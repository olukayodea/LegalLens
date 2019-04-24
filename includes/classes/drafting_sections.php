<?php
	class drafting_sections extends common {
		function add($array) {
			$drafting = $this->mysql_prep($array['drafting']);
			$section_content = htmlentities($this->mysql_prep($array['section_content']));
			$tags = $this->mysql_prep($array['tags']);
			$status = $this->mysql_prep($array['status']);
			$create_time = $modify_time = time();
			$ref = $this->mysql_prep($array['ref']);


			global $db;
			$value_array = array(
							':section_content' => $section_content, 
							':status' => $status, 
							':drafting' => $drafting,
							':tags' => $tags,
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
				$sql = $db->prepare("INSERT INTO `drafting_sections` (".$firstpart."`drafting`, `section_content`, `status`, `tags`, `create_time`, `modify_time`) 
				VALUES (".$secondPArt.":drafting, :section_content, :status, :tags, :create_time, :modify_time)
					ON DUPLICATE KEY UPDATE 
						`section_content` = :section_content,
						`status` = :status,
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
					$doc = new articles;
					$doc->modifyOne("status", "active", $drafting);
				}
				//mysql_query("ALTER TABLE drafting_sections ADD FULLTEXT (section_content);") or die (mysql_error());
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
			
			$data = $this->getOne($id);
			
			@unlink("../library/agreement/".$data['file']);
			
			global $db;
			try {
				$sql = $db->prepare("DELETE FROM `drafting_sections` WHERE `ref` =:id");
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
			global $db;
			try {
				$sql = $db->prepare("UPDATE `drafting_sections` SET  `".$tag."` = :value, `modify_time` = :modifyTime WHERE `ref`=:id");
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
				$sql = $db->query("SELECT * FROM `drafting_sections` ORDER BY `ref` ASC");
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
				$sql = $db->query("SELECT * FROM `drafting_sections` WHERE ref IN (".$list.") ORDER BY `section_no` ASC");
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
				$sqlTag .= " AND `".$tag3."` = :id3";
				$token[':id3'] = $id3;
			} else {
				$sqlTag .= "";
			}
			
			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM `drafting_sections` WHERE `".$tag."` = :id".$sqlTag." ORDER BY `".$order."` ASC");
								
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
				$sql = $db->prepare("SELECT * FROM drafting_sections WHERE `".$tag."` = :id ORDER BY `ref` DESC LIMIT 1");
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