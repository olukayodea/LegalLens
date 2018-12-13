<?php
	class alerts extends common {
		function sendEmail($array) {
			$from = $array['from'];
			$to = $array['to'];
			$subject = $array['subject'];
			$body = $array['body'];
			
			$send = $this->send_mail($from,$to,$subject,$body);
		
			if ($send) {
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
			

			global $db;
			try {
				$sql = $db->prepare("INSERT INTO `email_spool` (`subject`, `body`, `user`,`from`,`to`, `create_time`, `reminder`) VALUES (:subject, :body, :user,:from,:to, :create_time, :reminder)");
				$sql->execute(
					array(	':subject' => $subject,
							':body' => $body,
							':user' => $user,
							':from' => $from,
							':to' => $to,
							':create_time' => $create_time,
							':reminder' => $reminder)
						);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			$id = $db->lastInsertId();

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
			
			global $db;
			try {
				$sql = $db->prepare("UPDATE `email_spool` SET  `".$tag."` = :value WHERE `ref`=:id");
				$sql->execute(
					array(
					':value' => $value,
					':id' => $id)
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
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

			global $db;
			try {
				$sql = $db->query("SELECT * FROM `email_spool` WHERE `status` = 'NEW' LIMIT ".$limit);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		function listAll() {
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `email_spool` ORDER BY `ref` DESC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		function getOne($id, $tag='ref') {
			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM email_spool WHERE `".$tag."` = :id");
				$sql->execute(
					array(
					':id' => $id)
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetch(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		//get one field from the dtails of one application
		function getOneField($id, $tag='ref', $ref='body') {
			$data = $this->getOne($id, $tag);
			return $data[$ref];
		}
	}
?>