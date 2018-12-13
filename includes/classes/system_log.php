<?php
	class system_log extends common {
		function create($array) {
			$object = $this->mysql_prep($array['object']);
			$object_id = $this->mysql_prep($array['object_id']);
			$owner = $this->mysql_prep($array['owner']);
			$owner_id = $this->mysql_prep($array['owner_id']);
			$desc = $this->mysql_prep($array['desc']);
			$create_time = time();

			global $db;
			try {
				$sql = $db->prepare("INSERT INTO `system_log` (`object`,`object_id`,`owner`,`owner_id`,`desc`,`create_time`) VALUES (:object,:object_id,:owner,:owner_id,:desc,:create_time)");
				$sql->execute(array(
							':object' => $object, 
							':object_id' => $object_id, 
							':owner' => $owner,
							':owner_id' => $owner_id,
							':desc' => $desc,
							':create_time' => $create_time));
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				$id = $db->lastInsertId();
				return $id;
			} else {
				return false;
			}
		}
		
		function countAl() {
			global $db;
			try {
				$sql = $db->query("SELECT COUNT(*) FROM `system_log`");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}

			if ($sql) {				
				return $sql->fetchColumn;
			}
		}
		
		function purge() {
			$time = time()-(60*60*24*180);

			global $db;
			try {
				$sql = $db->query("DELETE FROM `system_log` WHERE `create_time` < '".$time."'");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
		}
		
		function listAll() {
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `system_log` ORDER BY `ref` ASC");
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
				$sqlTag = " AND `".$tag3."` = :id3";
				$token[':id3'] = $id3;
			} else {
				$sqlTag .= "";
			}
			
			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM `system_log` WHERE `".$tag."` = :id".$sqlTag." ORDER BY `".$order."` ASC");
								
				$sql->execute($token);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
			return $this->out_prep($row);
		}
		
		function getOne($id, $tag='ref') {
			$id = $this->mysql_prep($id);
			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM system_log WHERE `".$tag."` = :id ORDER BY `ref` DESC LIMIT 1");
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

		function getOneField($id, $tag="ref", $ref="title") {
			$data = $this->getOne($id, $tag);
			return $data[$ref];
		}
	}
?>