<?php
	class photos extends common {
		function removeOne($id, $index) {
			unlink("products-images/".$_SESSION['tempData'][$id]['media'][$index]);
		}
		
		function add($array) {
			$inventory = $this->mysql_prep($array['inventory']);
			$photos = $this->mysql_prep($array['photos']);
			$ref = $this->mysql_prep($array['ref']);
			$create_time = $modify_time = time();
			
			$sql = mysql_query("INSERT INTO `photos` (`inventory`, `photos`) VALUES ('".$inventory."','".$photos."')") or die (mysql_error());
			
			if ($sql) {
				$id = mysql_insert_id();
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = $_SESSION['admin']['id'];
				$logArray['desc'] = $log;
				$logArray['create_time'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				return $id;
			} else {
				return false;
			}
			
		}
		
		function deleteAll($id) {
			$id = $this->mysql_prep($id);
			
			$data = $this->sortAll("inventory", $id);
			for ($i = 0; $i < count($data); $i++) {
				@unlink("products-images/".$data[$i]['photos']);
			}
			$sql = mysql_query("DELETE FROM photos WHERE inventory = '".$id."'") or die (mysql_error());
			
			if ($sql) {
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = $_SESSION['admin']['id'];
				$logArray['desc'] = "Deleted Album '".$id."' created on ".date('l jS \of F Y h:i:s A', $data['create_time']);
				$logArray['create_time'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				return true;
			} else {
				return false;
			}
		}
		
		function delete($id) {
			$id = $this->mysql_prep($id);
			
			$data = $this->getOne($id);
			
			@unlink("products-images/".$data['photos']);
			$sql = mysql_query("DELETE FROM photos WHERE ref = '".$id."'") or die (mysql_error());
			
			if ($sql) {
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = $_SESSION['admin']['id'];
				$logArray['desc'] = "Deleted entry '".$data['inventory']."' created on ".date('l jS \of F Y h:i:s A', $data['create_time']);
				$logArray['create_time'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				return true;
			} else {
				return false;
			}
		}
		
		function updateOne($tag, $value, $id) {
			$id = $this->mysql_prep($id);
			$value = $this->mysql_prep($value);
			
			$sql = mysql_query("UPDATE photos SET `".$tag."` = '".$value."', `modify_time` = '".time()."' WHERE ref = '".$id."'") or die (mysql_error());
			
			if ($sql) {
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = $_SESSION['admin']['id'];
				$logArray['desc'] = "modified field ".$tag." as ".$value." for object";
				$logArray['create_time'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				return true;
			} else {
				return false;
			}
		}
		
		function listAll() {
			$sql = mysql_query("SELECT * FROM `photos` ORDER BY `inventory` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['photos'] = $row['photos'];
					$result[$count]['inventory'] = $row['inventory'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function sortAll($tag, $id) {
			$id = $this->mysql_prep($id);
			$sql = mysql_query("SELECT * FROM `photos` WHERE `".$tag."`  = '".$id."' ORDER BY `inventory` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['photos'] = $row['photos'];
					$result[$count]['inventory'] = $row['inventory'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function getOne($id, $tag='ref') {
			$id = $this->mysql_prep($id);
			$sql = mysql_query("SELECT * FROM `photos` WHERE `".$tag."` = '".$id."'") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				
				$row = mysql_fetch_array($sql);
				if ($row > 0) {
					$result['ref'] = $row['ref'];
					$result['photos'] = $row['photos'];
					$result['inventory'] = $row['inventory'];
				}
				
				return $this->out_prep($result);
			}
		}
                
		function getOneField($id, $tag="ref", $ref="inventory") {
			$data = $this->getOne($id, $tag);
			return $data[$ref];
		}
	}
?>