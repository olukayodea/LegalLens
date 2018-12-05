<?php
	class system_log extends common {
		function create($array) {
			$object = $this->mysql_prep($array['object']);
			$object_id = $this->mysql_prep($array['object_id']);
			$owner = $this->mysql_prep($array['owner']);
			$owner_id = $this->mysql_prep($array['owner_id']);
			$desc = $this->mysql_prep($array['desc']);
			$create_time = time();
			
			$sql = mysql_query("INSERT INTO `system_log` (`object`, `object_id`, `owner`, `owner_id`, `desc`, `create_time`) VALUES ('".$object."', '".$object_id."', '".$owner."', '".$owner_id."', '".$desc."', '".$create_time."')") or (mysql_error());
			
			if ($sql) {
				$id = mysql_insert_id();
				return $id;
			} else {
				return false;
			}
		}
		
		function countAl() {
			$sql = mysql_query("SELECT COUNT(*) FROM system_log") or die (mysql_error());
			if ($sql) {				
				$row = mysql_fetch_array($sql);
				return $row[0];
			}
		}
		
		function purge() {
			$time = time()-(60*60*24*180);
			$sql = mysql_query("DELETE FROM `system_log` WHERE `create_time` < '".$time."'") or die (mysql_error());
		}
		
		function listAll() {
			$sql = mysql_query("SELECT * FROM `system_log` ORDER BY `ref` DESC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['object'] = $row['object'];
					$result[$count]['object_id'] = $row['object_id'];
					$result[$count]['owner'] = $row['owner'];
					$result[$count]['owner_id'] = $row['owner_id'];
					$result[$count]['desc'] = $row['desc'];
					$result[$count]['create_time'] = $row['create_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function sortAll($tag,$id, $tag2=false, $id2=false) {
			$id = $this->mysql_prep($id);
			$id2 = $this->mysql_prep($id2);
			if ($tag2 != false) {
				$sqlTag = " AND `".$tag2."` = '".$id2."'";
			} else {
				$sqlTag = "";
			}
			
			$sql = mysql_query("SELECT * FROM `system_log` WHERE `".$tag."` = '".$id."'".$sqlTag." ORDER BY `ref` DESC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['object'] = $row['object'];
					$result[$count]['object_id'] = $row['object_id'];
					$result[$count]['owner'] = $row['owner'];
					$result[$count]['owner_id'] = $row['owner_id'];
					$result[$count]['desc'] = $row['desc'];
					$result[$count]['create_time'] = $row['create_time'];
					$count++;
				}
				return $result;
			}
		}
		
		
		function getOne($id, $tag='ref') {
			$id = $this->mysql_prep($id);
			$sql = mysql_query("SELECT * FROM `system_log` WHERE `".$tag."` = '".$id."'") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				
				$row = mysql_fetch_array($sql);
				$result['ref'] = $row['ref'];
				$result['object'] = $row['object'];
				$result['object_id'] = $row['object_id'];
				$result['owner'] = $row['owner'];
				$result['owner_id'] = $row['owner_id'];
				$result['desc'] = $row['desc'];
				$result['create_time'] = $row['create_time'];
				
				return $this->out_prep($result);
			}
		}
		function getOneField($id, $tag="ref", $ref="title") {
			$data = $this->getOne($id, $tag);
			return $data[$ref];
		}
	}
?>