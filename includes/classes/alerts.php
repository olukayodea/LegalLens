<?php
	class alerts extends common {
		function sendEmail($array) {
			$from = $array['from'];
			$to = $array['to'];
			$subject = $array['subject'];
			$body = $array['body'];
			
			$send = $this->send_mail($from,$to,$subject,$body);
			
			if ($send) {
				
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "system";
				$logArray['owner_id'] = 0;
				$logArray['desc'] = "Sent new Email to ".$to." from ".$from;
				$logArray['create_time'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				return true;
			} else {
				return false;
			}
		}
		
		function addToMailSpool($array) {
			$to = $this->mysql_prep($array['to']);
			$from = $this->mysql_prep($array['from']);
			$subject = $this->mysql_prep($array['subject']);
			$body = $this->mysql_prep($array['body']);
			$user = $this->mysql_prep($array['user']);
			$reminder = $this->mysql_prep($array['reminder']);
			$create_time = $modify_time = time();
			
			$sql = mysql_query("INSERT INTO `email_spool` (`to`,`from`,`subject`,`body`,`user`,`reminder`,`create_time`) VALUES ( '".$to."',  '".$from."',  '".$subject."', '".$body."','".$user."','".$reminder."', '".$create_time."')") or die (mysql_error());
			$id = mysql_insert_id();
			//add to log
			$logArray['object'] = get_class($this);
			$logArray['object_id'] = $id;
			$logArray['owner'] = "users";
			$logArray['owner_id'] = $user;
			$logArray['desc'] = "Queued Email to ".$to." from ".$from." to spool";
			$logArray['create_time'] = time();
			$system_log = new system_log;
			$system_log->create($logArray);
			return true;
		}
		
		function updateOneSpool($tag, $value, $id) {
			$id = $this->mysql_prep($id);
			$value = $this->mysql_prep($value);
			
			$sql = mysql_query("UPDATE email_spool SET `".$tag."` = '".$value."' WHERE ref = '".$id."'") or die (mysql_error());
		}
		
		function sendBulkSystem() {
			$data = $this->spoolBatch(10);
			
			for ($i = 0; $i < count($data); $i++) {
				
				$array['from'] = $data[$i]['from'];
				$array['to'] = $data[$i]['to'];
				$array['subject'] = $data[$i]['subject'];
				$array['body'] = $data[$i]['body'];
				$send = $this->sendEmail($array);
				
				if ($send) {
					$this->updateOneSpool("status", "SENT", $data[$i]['ref']);
				}
			}
			
			return true;
		}
		
		function spoolBatch($limit) {
			$sql = mysql_query("SELECT * FROM `email_spool` WHERE `status` = 'NEW' LIMIT ".$limit) or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['user'] = $row['user'];
					$result[$count]['reminder'] = $row['reminder'];
					$result[$count]['to'] = $row['to'];
					$result[$count]['from'] = $row['from'];
					$result[$count]['subject'] = $row['subject'];
					$result[$count]['body'] = $row['body'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['sent_time'] = $row['sent_time'];
					$count++;
				}
				return $result;
			}
		}
		
		function listAll() {
			$sql = mysql_query("SELECT * FROM `email_spool` ORDER BY `ref` DESC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['user'] = $row['user'];
					$result[$count]['reminder'] = $row['reminder'];
					$result[$count]['to'] = $row['to'];
					$result[$count]['from'] = $row['from'];
					$result[$count]['subject'] = $row['subject'];
					$result[$count]['body'] = $row['body'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['sent_time'] = $row['sent_time'];
					$count++;
				}
				return $result;
			}
		}
		
		function getOne($id, $tag='ref') {
			$id = $this->mysql_prep($id);
			$sql = mysql_query("SELECT * FROM `email_spool` WHERE `".$tag."` = '".$id."'") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				
				$row = mysql_fetch_array($sql);
				$result['ref'] = $row['ref'];
				$result['user'] = $row['user'];
				$result['reminder'] = $row['reminder'];
				$result['to'] = $row['to'];
				$result['from'] = $row['from'];
				$result['subject'] = $row['subject'];
				$result['body'] = $row['body'];
				$result['status'] = $row['status'];
				$result['create_time'] = $row['create_time'];
				$result['sent_time'] = $row['sent_time'];
				
				return $result;
			}
		}
		
		//get one field from the dtails of one application
		function getOneField($id, $tag='ref', $ref='body') {
			$data = $this->getOne($id, $tag);
			return $data[$ref];
		}
	}
?>