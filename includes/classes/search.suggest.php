<?php
	class search_result extends common {
		function add($array) {
			$title = ucfirst(strtolower($this->mysql_prep($array['title'])));
			$users = $this->mysql_prep($array['users']);
			$data = $array['data'];
			$create_time = time();
			
			global $db;
			try {
				$sql = $db->prepare("INSERT IGNORE INTO `search_result` (`title`,`users`,`data`,`create_time`) 
				VALUES (:title,:users,:data,:create_time)");
				$sql->execute(array(
							':title' => $title, 
							':users' => $users, 
							':data' => $data,
							':create_time' => $create_time));
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			if ($sql) {
				return true;
			} else {
				return false;
			}
		}
		
		function remove($id) {
			$id = $this->mysql_prep($id);
			$data = $this->getOne($id);
			global $db;
			try {
				$sql = $db->prepare("DELETE FROM `search_result` WHERE `ref` =:id");
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
			
			global $db;
			try {
				$sql = $db->prepare("UPDATE `search_result` SET  `".$tag."` = :value WHERE `ref`=:id");
				$sql->execute(
					array(
					':value' => $value,
					':id' => $id)
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				return true;
			} else {
				return false;
			}
		}
		
		function listAll() {
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `search_result` ORDER BY `ref` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				$result = array();
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['title'] = ucwords(strtolower($row['title']));
					$result[$count]['users'] = $row['users'];
					$result[$count]['data'] = $row['data'];
					$result[$count]['create_time'] = $row['create_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
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
				$sql = $db->prepare("SELECT * FROM `search_result` WHERE `".$tag."` = :id".$sqlTag." ORDER BY `".$order."` ASC");
								
				$sql->execute($token);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
						
			if ($sql) {
				$result = array();
				$count = 0;
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['title'] = ucwords(strtolower($row['title']));
					$result[$count]['users'] = $row['users'];
					$result[$count]['data'] = $row['data'];
					$result[$count]['create_time'] = $row['create_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function getOne($id, $tag='ref') {
			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM search_result WHERE `".$tag."` = :id ORDER BY `ref` DESC LIMIT 1");
				$sql->execute(
					array(
					':id' => $id)
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}

			if ($sql) {
				$result = array();
				if ($sql->rowCount() == 1) {
					$row = $sql->fetch(PDO::FETCH_ASSOC);
					$row = mysql_fetch_array($sql);
					$result['ref'] = $row['ref'];
					$result['title'] = ucwords(strtolower($row['title']));
					$result['users'] = $row['users'];
					$result['data'] = $row['data'];
					$result['create_time'] = $row['create_time'];
					return $this->out_prep($result);
				}
			}
		}
		
		function getOneField($id, $tag="ref", $ref="title") {
			$data = $this->getOne($id, $tag);
			return $data[$ref];
		}
	}
?>