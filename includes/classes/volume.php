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
			$sql = mysql_query("
			INSERT INTO `volume` (`ref`, `low_band`, `high_band`,`discount`, `status`)
			VALUES (".$ref.", ".$low_band.", ".$high_band.", ".$discount.", '".$status."'), (".$ref2.", ".$low_band2.", ".$high_band2.", ".$discount2.", '".$status2."'), (".$ref3.", ".$low_band3.", ".$high_band3.", ".$discount3.", '".$status3."'), (".$ref4.", ".$low_band4.", ".$high_band4.", ".$discount4.", '".$status4."'), (".$ref5.", ".$low_band5.", ".$high_band5.", ".$discount5.", '".$status5."') ON DUPLICATE KEY UPDATE `ref` = VALUES(`ref`), `low_band` = VALUES(`low_band`), `high_band` = VALUES(`high_band`), `discount` = VALUES(`discount`), `status` = VALUES(`status`)") or die (mysql_error());
			
			if ($sql) {
				$id = mysql_insert_id();
				
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = $_SESSION['admin']['id'];
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
			$sql = mysql_query("SELECT * FROM `volume` ORDER BY `ref` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['low_band'] = $row['low_band'];
					$result[$count]['high_band'] = $row['high_band'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['discount'] = $row['discount'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function getRange($val) {
			$val = $this->mysql_prep($val);
			$sql = mysql_query("SELECT `discount` FROM `volume` WHERE (".$val." BETWEEN `low_band` AND `high_band`) AND `status` = 'active'") or die (mysql_error());
			$row = mysql_fetch_array($sql);
			return $row[0];
		}
	}
?>