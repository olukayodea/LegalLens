<?php
	class visitorData extends common {
		function addStat($ip, $ref) {
			$ip = $this->mysql_prep($ip);
			$ref = $this->mysql_prep($ref);
			if (isset($_COOKIE['newVisit'])) {
				$unique_id = $_COOKIE['newVisit'];
			} else {
				$unique_id = time().rand(1000, 9999);
				setcookie("newVisit", $unique_id, time()+(60*60*5), "/");
			}
			
			if (isset($_SESSION['location_data'])) {
				$loc_city = $_SESSION['location_data']['loc_city'];
				$loc_region = $_SESSION['location_data']['loc_region'];
				$loc_country = $_SESSION['location_data']['loc_country'];
				$loc_continent = $_SESSION['location_data']['loc_continent'];
				$loc_lat = $_SESSION['location_data']['loc_lat'];
				$loc_long = $_SESSION['location_data']['loc_long'];
			} else {
				$remote_data = @unserialize(@file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ip));
				$loc_city = $_SESSION['location_data']["loc_city"] = $remote_data['geoplugin_city'];
				$loc_region = $_SESSION['location_data']["loc_region"] = $remote_data['geoplugin_region'];
				$loc_country = $_SESSION['location_data']["loc_country"] = $remote_data['geoplugin_countryName'];
				$loc_continent = $_SESSION['location_data']["loc_continent"] = $remote_data['geoplugin_continentCode'];
				$loc_lat = $_SESSION['location_data']["loc_lat"] = $remote_data['geoplugin_latitude'];
				$loc_long = $_SESSION['location_data']["loc_long"] = $remote_data['geoplugin_longitude'];
			}
			
			if ($_SESSION['location_data']["last_refresh"] < time()) {
				$weather_data = @file_get_contents("http://api.openweathermap.org/data/2.5/weather?lat=".$loc_lat."&lon=".$loc_long."&units=metric&appid=99758ae36cda29a45af3c4a34bdc0f0f");
				$weatherData = json_decode($weather_data, true);
				
				$_SESSION['location_data']["temp"] = $weatherData['main']['temp'];
				$_SESSION['location_data']["last_refresh"] = time()+(60*60);
			}
			
			
			
			$pageUR1  = preg_replace("/(.+)/", "", $ref); 
			$curdomain  = str_replace("www.", "", $pageUR1);
			
			if (!isset($_COOKIE['visit'])) {
				global $db;
				try {
					$sql = $db->query("INSERT INTO `visitors` (`address`, `referer`, `unique_id`, `loc_city`, `loc_region`, `loc_country`, `loc_continent`, `loc_lat`, `loc_long`, `time_stamp`) VALUES ('".$ip."','".$curdomain."','".$unique_id."','".$loc_city."','".$loc_region."','".$loc_country."','".$loc_continent."','".$loc_lat."','".$loc_long."','".time()."')");
				} catch(PDOException $ex) {
					echo "An Error occured! ".$ex->getMessage(); 
				}
				
				setcookie("visit", true, time()+(60*60), "/");
			}
			
			return true;
		}
		
		function countAl() {
			global $db;
			try {
				$sql = $db->query("SELECT COUNT(*) FROM visitors GROUP BY `unique_id`");
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
				$sql = $db->query("DELETE FROM `visitors` WHERE `time_stamp` < '".$time."'");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
		}
		
		function getUnigue($from, $to) {
			$from = $this->mysql_prep($from);
			$to = $this->mysql_prep($to);

			global $db;
			try {
				$sql = $db->query("SELECT * FROM `visitors` WHERE `time_stamp` BETWEEN '".$from."' AND '".$to."' GROUP BY `unique_id` ORDER BY `time_stamp` DESC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		function getVisit($from, $to) {
			$from = $this->mysql_prep($from);
			$to = $this->mysql_prep($to);
			
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `visitors` WHERE `time_stamp` BETWEEN '".$from."' AND '".$to."'");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
	}
?>