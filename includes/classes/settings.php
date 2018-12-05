<?php
	class settings extends common {
		function add($array) {
			$page_view = $this->mysql_prep($array['page_view']);
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
			
			$sql = mysql_query("UPDATE `settings` SET `value` = '".$page_view."' WHERE `title` = 'page_view'") or die (mysql_error());
			$sql = mysql_query("UPDATE `settings` SET `value` = '".$instagram."' WHERE `title` = 'instagram'") or die (mysql_error());
			$sql = mysql_query("UPDATE `settings` SET `value` = '".$facebook."' WHERE `title` = 'facebook'") or die (mysql_error());
			$sql = mysql_query("UPDATE `settings` SET `value` = '".$flickr."' WHERE `title` = 'flickr'") or die (mysql_error());
			$sql = mysql_query("UPDATE `settings` SET `value` = '".$google."' WHERE `title` = 'google'") or die (mysql_error());
			$sql = mysql_query("UPDATE `settings` SET `value` = '".$linkedin."' WHERE `title` = 'linkedin'") or die (mysql_error());
			$sql = mysql_query("UPDATE `settings` SET `value` = '".$rss."' WHERE `title` = 'rss'") or die (mysql_error());
			$sql = mysql_query("UPDATE `settings` SET `value` = '".$skype."' WHERE `title` = 'skype'") or die (mysql_error());
			$sql = mysql_query("UPDATE `settings` SET `value` = '".$twitter."' WHERE `title` = 'twitter'") or die (mysql_error());
			$sql = mysql_query("UPDATE `settings` SET `value` = '".$email."' WHERE `title` = 'email'") or die (mysql_error());
			$sql = mysql_query("UPDATE `settings` SET `value` = '".$phone."' WHERE `title` = 'phone'") or die (mysql_error());
			$sql = mysql_query("UPDATE `settings` SET `value` = '".$address."' WHERE `title` = 'address'") or die (mysql_error());
			$sql = mysql_query("UPDATE `settings` SET `value` = '".$city."' WHERE `title` = 'city'") or die (mysql_error());
			$sql = mysql_query("UPDATE `settings` SET `value` = '".$state."' WHERE `title` = 'state'") or die (mysql_error());
			$sql = mysql_query("UPDATE `settings` SET `value` = '".$country."' WHERE `title` = 'country'") or die (mysql_error());
			
			//add to log
			$logArray['object'] = get_class($this);
			$logArray['object_id'] = $id;
			$logArray['owner'] = "admin";
			$logArray['owner_id'] = $_SESSION['admin']['id'];
			$logArray['desc'] = "Updated system settings";
			$logArray['create_date'] = time();
			$system_log = new system_log;
			$system_log->create($logArray);
			return true;
		}
		
		function listAll() {
			$sql = mysql_query("SELECT * FROM `settings` ORDER BY `title` DESC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['title'] = $row['title'];
					$result[$count]['value'] = $row['value'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function getOne($id) {
			$id = $this->mysql_prep($id);
			$sql = mysql_query("SELECT `value` FROM `settings` WHERE `title` = '".$id."'") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				
				if (mysql_num_rows($sql) == 1) {
					$row = mysql_fetch_array($sql);
					$result = $row[0];
					
					return $result;
				} else {
					return false;
				}
			}
		}
	}
?>