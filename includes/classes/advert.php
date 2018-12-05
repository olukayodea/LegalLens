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
				$sql = mysql_query("INSERT INTO `advert` (`title`, `status`, `duration`, `url`, `media_file`, `create_time`, `modify_time`) VALUES ('".$title."','".$status."','".$duration."','".$url."','".$media_file."', '".$create_time."', '".$modify_time."') ON DUPLICATE KEY UPDATE `title` = '".$title."', `status` = '".$status."', `duration` = '".$duration."', `url` = '".$url."', `modify_time` = '".$modify_time."'") or die (mysql_error());
			
				if ($sql) {
					$id = mysql_insert_id();
					
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
			$sql = mysql_query("DELETE FROM `advert` WHERE ref = '".$id."'") or die (mysql_error());
				
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
			$modDate = time();
			$sql = mysql_query("UPDATE `advert` SET `".$tag."` = '".$value."', `modify_time` = '".$modDate."' WHERE ref = '".$id."'") or die (mysql_error());
			
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
			$sql = mysql_query("SELECT * FROM `advert` ORDER BY `ref` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
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
						
			$sql = mysql_query("SELECT * FROM `advert` WHERE `".$tag."` = '".$id."'".$sqlTag." ORDER BY ".$order.$limitTag) or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
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
			$sql = mysql_query("SELECT * FROM `advert` WHERE `".$tag."` = '".$id."' ORDER BY `ref` DESC LIMIT 1") or die (mysql_error());
			if ($sql) {
				$result = array();
				
				if (mysql_num_rows($sql) == 1) {
					$row = mysql_fetch_array($sql);
					$result['ref'] = $row['ref'];
					$result['title'] = ucfirst(strtolower($row['title']));
					$result['duration'] = $row['duration'];
					$result['status'] = $row['status'];
					$result['url'] = $row['url'];
					$result['media_file'] = $row['media_file'];
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
		
		function showAd($limit) {
			$type = $this->mysql_prep($type);
			$sql = mysql_query("SELECT * FROM `advert` WHERE `status` = 'active' AND `duration` > '".time()."' ORDER BY RAND() LIMIT ".$limit) or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
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
				$sql = mysql_query("INSERT INTO `slider` (`status`, `url`, `media_file`, `create_time`, `modify_time`) VALUES ('".$status."','".$url."','".$media_file."', '".$create_time."', '".$modify_time."') ON DUPLICATE KEY UPDATE `status` = '".$status."', `url` = '".$url."', `modify_time` = '".$modify_time."'") or die (mysql_error());
			
				if ($sql) {
					$id = mysql_insert_id();
					
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
			$sql = mysql_query("DELETE FROM `slider` WHERE ref = '".$id."'") or die (mysql_error());
				
			if ($sql) {
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = $_SESSION['admin']['id'];
				$logArray['desc'] = "removed slider Item with Ref ".$id;
				$logArray['create_date'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				return true;
			} else {
				return false;
			}
		}
		
		function listAll_s() {
			$sql = mysql_query("SELECT * FROM `slider` ORDER BY `ref` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
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
		
		function sortAll_s($id, $tag, $tag2=false, $id2=false, $tag3=false, $id3=false, $orderby = "ref", $dir="ASC", $limit=false) {
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
						
			$sql = mysql_query("SELECT * FROM `slider` WHERE `".$tag."` = '".$id."'".$sqlTag." ORDER BY ".$order.$limitTag) or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
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
		
		function getOne_s($id, $tag='ref') {
			$id = $this->mysql_prep($id);
			$sql = mysql_query("SELECT * FROM `slider` WHERE `".$tag."` = '".$id."' ORDER BY `ref` DESC LIMIT 1") or die (mysql_error());
			if ($sql) {
				$result = array();
				
				if (mysql_num_rows($sql) == 1) {
					$row = mysql_fetch_array($sql);
					$result['ref'] = $row['ref'];
					$result['status'] = $row['status'];
					$result['url'] = $row['url'];
					$result['media_file'] = $row['media_file'];
					$result['create_time'] = $row['create_time'];
					$result['modify_time'] = $row['modify_time'];
					return $this->out_prep($result);
				} else {
					return false;
				}
			}
		}
		
		function getOneField_s($id, $tag="ref", $ref="title") {
			$data = $this->getOne_s($id, $tag);
			return $data[$ref];
		}
		
		function showAd_s($limit) {
			$type = $this->mysql_prep($type);
			$sql = mysql_query("SELECT * FROM `slider` WHERE `status` = 'active' ORDER BY RAND() LIMIT ".$limit) or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
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
?>