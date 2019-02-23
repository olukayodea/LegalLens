<?php
	class settings extends common {
		function add($array) {
			$page_view = $this->mysql_prep($array['page_view']);
			$page_list = $this->mysql_prep($array['page_list']);
			$instagram = $this->mysql_prep($array['instagram']);
			$facebook = $this->mysql_prep($array['facebook']);
			$flickr = $this->mysql_prep($array['flickr']);
			$google = $this->mysql_prep($array['google']);
			$linkedin = $this->mysql_prep($array['linkedin']);
			$rss = $this->mysql_prep($array['rss']);
			$skype = $this->mysql_prep($array['skype']);
			$twitter = $this->mysql_prep($array['twitter']);
			$email = $this->mysql_prep($array['email']);
			$phone = $this->mysql_prep($array['phone']);
			$address = $this->mysql_prep($array['address']);
			$city = $this->mysql_prep($array['city']);
			$state = $this->mysql_prep($array['state']);
			$country = $this->mysql_prep($array['country']);

			$this->modify("page_view", $page_view);
			$this->modify("page_list", $page_list);
			$this->modify("instagram", $instagram);
			$this->modify("facebook", $facebook);
			$this->modify("flickr", $flickr);
			$this->modify("google", $google);
			$this->modify("linkedin", $linkedin);
			$this->modify("rss", $rss);
			$this->modify("skype", $skype);
			$this->modify("twitter", $twitter);
			$this->modify("email", $email);
			$this->modify("phone", $phone);
			$this->modify("address", $address);
			$this->modify("city", $city);
			$this->modify("state", $state);
			$this->modify("country", $country);
			
			//add to log
			return true;
		}


		function modify($title, $value) {
			global $db;
			
			try {
				$sql = $db->prepare("INSERT INTO `settings` (
						`title`,
						`value`) 
					VALUES (
						:title,
						:value
					)
					ON DUPLICATE KEY UPDATE 
						`value` = :value
					");
				$sql->execute(
					array(	':title' => $title, 
							':value' => $value)
						);
						
						
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage()." at ".$title;
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
				$sql = $db->query("SELECT * FROM `settings` ORDER BY `title` DESC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$result = array();
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		function getOne($id) {
			$id = $this->mysql_prep($id);
			global $db;
			try {
				$sql = $db->prepare("SELECT `value` FROM `settings` WHERE `title` =:id");
				$sql->execute(
						array(':id' => $id)
					);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}

			$row = $sql->fetchColumn();
			return $this->out_prep($row);
		}
	}
?>