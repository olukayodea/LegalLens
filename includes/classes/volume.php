<?php
	class volume extends common {
		function add($array) {
			$ref = $this->mysql_prep($array['ref']);
			$low_band = $this->mysql_prep($array['low_band']);
			$high_band = $this->mysql_prep($array['high_band']);
			$status = $this->mysql_prep($array['status']);
			$discount = $this->mysql_prep($array['discount']);
			$ref2 = $this->mysql_prep($array['ref2']);
			$low_band2 = $this->mysql_prep($array['low_band2']);
			$high_band2 = $this->mysql_prep($array['high_band2']);
			$status2 = $this->mysql_prep($array['status2']);
			$discount2 = $this->mysql_prep($array['discount2']);
			$ref3 = $this->mysql_prep($array['ref3']);
			$low_band3 = $this->mysql_prep($array['low_band3']);
			$high_band3 = $this->mysql_prep($array['high_band3']);
			$status3 = $this->mysql_prep($array['status3']);
			$discount3 = $this->mysql_prep($array['discount3']);
			$ref4 = $this->mysql_prep($array['ref4']);
			$low_band4 = $this->mysql_prep($array['low_band4']);
			$high_band4 = $this->mysql_prep($array['high_band4']);
			$status4 = $this->mysql_prep($array['status4']);
			$discount4 = $this->mysql_prep($array['discount4']);
			$ref5 = $this->mysql_prep($array['ref5']);
			$low_band5 = $this->mysql_prep($array['low_band5']);
			$high_band5 = $this->mysql_prep($array['high_band5']);
			$status5 = $this->mysql_prep($array['status5']);
			$discount5 = $this->mysql_prep($array['discount5']);
			global $db;
			try {
				$sql = $db->query("
				INSERT INTO `volume` (`ref`, `low_band`, `high_band`,`discount`, `status`)
				VALUES (".$ref.", ".$low_band.", ".$high_band.", ".$discount.", '".$status."'), 
				(".$ref2.", ".$low_band2.", ".$high_band2.", ".$discount2.", '".$status2."'), 
				(".$ref3.", ".$low_band3.", ".$high_band3.", ".$discount3.", '".$status3."'), 
				(".$ref4.", ".$low_band4.", ".$high_band4.", ".$discount4.", '".$status4."'), 
				(".$ref5.", ".$low_band5.", ".$high_band5.", ".$discount5.", '".$status5."') 
				ON DUPLICATE KEY UPDATE `ref` = VALUES(`ref`), `low_band` = VALUES(`low_band`), `high_band` = VALUES(`high_band`), `discount` = VALUES(`discount`), `status` = VALUES(`status`)");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}

			if ($sql) {
				$id = $db->lastInsertId();
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = intval($_SESSION['admin']['id']);
				$logArray['desc'] = "updated subscriptions discount";
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
				$sql = $db->query("SELECT * FROM `volume` ORDER BY `ref` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		function getRange($val) {
			$val = $this->mysql_prep($val);
			global $db;
			try {
				$sql = $db->query("SELECT `discount` FROM `volume` WHERE (".$val." BETWEEN `low_band` AND `high_band`) AND `status` = 'active'");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			return $sql->fetchColumn;
		}
	}
?>