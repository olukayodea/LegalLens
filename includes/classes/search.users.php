<?php
	class searchUsers extends common {
		function add($array) {
			$title = ucfirst(strtolower($this->mysql_prep($array['title'])));
			$users = $this->mysql_prep($array['users']);
			$create_time = time();
			
			$sql = mysql_query("INSERT IGNORE INTO `search_history` (".$firstpart."`title`, `users`, `create_time`) VALUES ('".$title."','".$users."', '".$create_time."')") or die (mysql_error());
			
			if ($sql) {
				return true;
			} else {
				return false;
			}
		}
		
		function remove($id) {
			$id = $this->mysql_prep($id);
			$data = $this->getOne($id);
			$media_url = $data['media_url'];
			$sql = mysql_query("DELETE FROM `search_history` WHERE ref = '".$id."'") or die (mysql_error());
				
			if ($sql) {
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "users";
				$logArray['owner_id'] = $_SESSION['users']['ref'];
				$logArray['desc'] = "cleared search history";
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
			$sql = mysql_query("UPDATE `search_history` SET `".$tag."` = '".$value."', `modify_time` = '".$modDate."' WHERE ref = '".$id."'") or die (mysql_error());
			
			if ($sql) {
				return true;
			} else {
				return false;
			}
		}
		
		function listAll() {
			$sql = mysql_query("SELECT * FROM `search_history` ORDER BY `ref` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['title'] = ucwords(strtolower($row['title']));
					$result[$count]['users'] = $row['users'];
					$result[$count]['create_time'] = $row['create_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function searchAll($val) {
			$val = $this->mysql_prep($val);
			$sql = mysql_query("SELECT * FROM `search_history` WHERE `title` LIKE '%".$val."%' ORDER BY `ref` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['title'] = ucwords(strtolower($row['title']));
					$result[$count]['users'] = $row['users'];
					$result[$count]['create_time'] = $row['create_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function sortAll($id, $tag, $tag2=false, $id2=false, $tag3=false, $id3=false, $orderby = "ref", $dir="DESC", $limit=false) {
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
						
			$sql = mysql_query("SELECT * FROM `search_history` WHERE `".$tag."` = '".$id."'".$sqlTag." ORDER BY ".$order.$limitTag) or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['title'] = ucwords(strtolower($row['title']));
					$result[$count]['users'] = $row['users'];
					$result[$count]['create_time'] = $row['create_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function getOne($id, $tag='ref') {
			$id = $this->mysql_prep($id);
			$sql = mysql_query("SELECT * FROM `search_history` WHERE `".$tag."` = '".$id."' ORDER BY `ref` DESC LIMIT 1") or die (mysql_error());
			if ($sql) {
				$result = array();
				
				if (mysql_num_rows($sql) == 1) {
					$row = mysql_fetch_array($sql);
					$result['ref'] = $row['ref'];
					$result['title'] = ucwords(strtolower($row['title']));
					$result['users'] = $row['users'];
					$result['create_time'] = $row['create_time'];
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
	}
?>