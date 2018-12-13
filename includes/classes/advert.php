<?php
	class advert extends common {
		function add($array, $file) {
			$title = $this->mysql_prep($array['title']);
			$status = $this->mysql_prep($array['status']);
			$duration= strtotime($this->mysql_prep($array['duration']));
			$url = $this->mysql_prep($array['url']);
			$upload = $this->upload($file);
			$create_time = $modify_time = time();
			
			if ($upload['info'] == "Done") {
				$media_file = $upload['msg'];

				global $db;
				$value_array = array(
								':title' => $title, 
								':status' => $status,
								':duration' => $duration,
								':url' => $url,
								':media_file' => $media_file,
								':create_time' => $create_time,
								':modify_time' => $modify_time
								);		
				
				try {
					$sql = $db->prepare("INSERT INTO `advert` (`title`, `status`, `duration`, `url`, `media_file`, `create_time`, `modify_time`)
					VALUES (:title, :status, :duration, :url, :media_file, :create_time, :modify_time)
						ON DUPLICATE KEY UPDATE 
							`status` = :status,
							`duration` = :duration,
							`url` = :url,
							`media_file` = :media_file,
							`create_time` = :create_time,
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
					$logArray['owner_id'] = $_SESSION['admin']['id'];
					$logArray['desc'] = "created advert ".$title;
					$logArray['create_date'] = time();
					$system_log = new system_log;
					$system_log->create($logArray);
					$result['info'] = "Done";
					return $result;
				} else {
					return false;
				}
			} else {
				return $upload;
			}
		}
		
		function upload($files, $width_pix=400, $height_pix=400) {
			$valid_formats = array("jpg", "png", "gif", "bmp","jpeg");
			define ("MAX_SIZE","9000"); 
			$uploaddir = "../advert/"; //a directory inside
			$typeAray[] = $width_pix;
			$typeAray[] = $height_pix;
			$img_width = trim($typeAray[0]);
			$img_height = trim($typeAray[1]);
	
			$filename = stripslashes($files['name']);
			$size=filesize($files['tmp_name']);
			//get the extension of the file in a lower case format
			$ext = $this->getExtension($filename);
			$ext = strtolower($ext);
			
			if(in_array($ext,$valid_formats)) {
				if ($size < (MAX_SIZE*1024)) {
					$im_info = getimagesize($files["tmp_name"]);
					$width = $im_info[0];
					$height = $im_info[1];
					
					$check_width = $this->between($width, ($img_width-20), ($img_width+20));
					$check_height = $this->between($height, ($img_height-20), ($img_height+20));
					if (($check_height == true) && ($check_width == true)) {
						$image_name=time().rand(10000,99999).".".$ext; 
						$newname=$uploaddir.$image_name; 
						//Moving file to uploads folder
						if (move_uploaded_file($files['tmp_name'], $newname))  { 
							$time=time(); 
							$result['info'] = "Done";
							$result['msg'] = $image_name;
						} else { 
							$result['info'] = "Error";
							$result['msg'] = "There was an error";
						} 
					} else {
						$result['info'] = "Error";
						$result['msg'] = "You must make sure that the image you have uploaded is between ".$img_width." X ".$img_height;
					}
				} else {
					$result['info'] = "Error";
					$result['msg'] = "You have exceeded the size limit";
				}
			} else {
				$result['info'] = "Error";
				$result['msg'] = "Unknown extension";
			}
			
			return $result;
		}
		
		function between($val, $min, $max){
		   if($val >= $min && $val <= $max) {
			   return true;
		   } else {
			return false;
		   }
		}
		
		function remove($id) {
			$id = $this->mysql_prep($id);
			$data = $this->getOne($id);
			$media_url = $data['media_url'];

			@unlink("../advert/".$data['file']);
			global $db;
			try {
				$sql = $db->prepare("DELETE FROM `advert` WHERE `ref` =:id");
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
				$logArray['owner_id'] = $_SESSION['admin']['id'];
				$logArray['desc'] = "removed advert Item with Ref ".$id;
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
				$sql = $db->prepare("UPDATE `advert` SET  `".$tag."` = :value, `modify_time` = :modifyTime WHERE `ref`=:id");
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
				$logArray['owner_id'] = $_SESSION['admin']['id'];
				$logArray['desc'] = "modified advert ".$tag." to ".$value." for Ref ".$id;
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
				$sql = $db->query("SELECT * FROM `advert` ORDER BY `ref` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['title'] = ucfirst(strtolower($row['title']));
					$result[$count]['duration'] = $row['duration'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['url'] = $row['url'];
					$result[$count]['media_file'] = $row['media_file'];
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
				$sql = $db->prepare("SELECT * FROM `advert` WHERE `".$tag."` = :id".$sqlTag." ORDER BY ".$order.$limitTag);
								
				$sql->execute($token);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['title'] = ucfirst(strtolower($row['title']));
					$result[$count]['duration'] = $row['duration'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['url'] = $row['url'];
					$result[$count]['media_file'] = $row['media_file'];
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
				$sql = $db->prepare("SELECT * FROM advert WHERE `".$tag."` = :id ORDER BY `ref` DESC LIMIT 1");
				$sql->execute(
					array(
					':id' => $id)
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			if ($sql) {
				$result = array();
				
				$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				$result['ref'] = $row['ref'];
				$result['title'] = ucfirst(strtolower($row['title']));
				$result['duration'] = $row['duration'];
				$result['status'] = $row['status'];
				$result['url'] = $row['url'];
				$result['media_file'] = $row['media_file'];
				$result['create_time'] = $row['create_time'];
				$result['modify_time'] = $row['modify_time'];
				$count++;
				
				return $this->out_prep($result);
			}
		}
		
		function getOneField($id, $tag="ref", $ref="title") {
			$data = $this->getOne($id, $tag);
			return $data[$ref];
		}
		
		function showAd($limit) {
			$type = $this->mysql_prep($type);
			
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `advert` WHERE `status` = 'active' AND `duration` > '".time()."' ORDER BY RAND() LIMIT ".$limit);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['title'] = ucfirst(strtolower($row['title']));
					$result[$count]['duration'] = $row['duration'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['url'] = $row['url'];
					$result[$count]['media_file'] = $row['media_file'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
	}
	
	class slider extends advert {
		function add_s($array, $file) {
			$status = $this->mysql_prep($array['status']);
			$url = $this->mysql_prep($array['url']);
			$upload = $this->upload($file, 1300, 620);
			$create_time = $modify_time = time();
			
			if ($upload['info'] == "Done") {
				$media_file = $upload['msg'];
				global $db;
				$value_array = array(
								':status' => $status,
								':url' => $url,
								':media_file' => $media_file,
								':create_time' => $create_time,
								':modify_time' => $modify_time
								);		
				
				try {
					$sql = $db->prepare("INSERT INTO `slider` (`status`, `url`, `media_file`, `create_time`, `modify_time`)
					VALUES (:status, :url, :media_file, :create_time, :modify_time)
						ON DUPLICATE KEY UPDATE 
							`status` = :status,
							`url` = :url,
							`media_file` = :media_file,
							`create_time` = :create_time,
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
					$logArray['owner_id'] = $_SESSION['admin']['id'];
					$logArray['desc'] = "created slider ".$id;
					$logArray['create_date'] = time();
					$system_log = new system_log;
					$system_log->create($logArray);
					$result['info'] = "Done";
					return $result;
				} else {
					return false;
				}
			} else {
				return $upload;
			}
		}
		
		function remove_s($id) {
			$id = $this->mysql_prep($id);
			$data = $this->getOne($id);
			$media_url = $data['media_url'];

			@unlink("../advert/".$data['file']);
			global $db;
			try {
				$sql = $db->prepare("DELETE FROM `slider` WHERE `ref` =:id");
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
				$logArray['owner_id'] = $_SESSION['admin']['id'];
				$logArray['desc'] = "removed advert Item with Ref ".$id;
				$logArray['create_date'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				return true;
			} else {
				return false;
			}
		}
		
		function listAll_s() {
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `slider` ORDER BY `ref` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		function sortAll_s($id, $tag, $tag2=false, $id2=false, $tag3=false, $id3=false, $orderby = "ref", $dir="ASC", $limit=false) {
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
				$sql = $db->prepare("SELECT * FROM `slider` WHERE `".$tag."` = :id".$sqlTag." ORDER BY ".$order.$limitTag);
								
				$sql->execute($token);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		function getOne_s($id, $tag='ref') {
			$id = $this->mysql_prep($id);
			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM slider WHERE `".$tag."` = :id ORDER BY `ref` DESC LIMIT 1");
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
		
		function getOneField_s($id, $tag="ref", $ref="title") {
			$data = $this->getOne_s($id, $tag);
			return $data[$ref];
		}
		
		function showAd_s($limit) {
			$type = $this->mysql_prep($type);
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `slider` WHERE `status` = 'active' ORDER BY RAND() LIMIT ".$limit);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
	}
?>