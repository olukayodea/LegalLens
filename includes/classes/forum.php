<?php
	class forum_categories extends common {
		function add($array) {
			$cat_name = $this->mysql_prep($array['cat_name']);
			$cat_description = $this->mysql_prep($array['cat_description']);
			$status = $this->mysql_prep($array['status']);
			$create_time = $modify_time = time();
			$cat_id = $this->mysql_prep($array['cat_id']);
			global $db;
			$value_array = array(
							':cat_name' => $cat_name, 
							':cat_description' => $cat_description,
							':status' => $status,
							':create_time' => $create_time,
							':modify_time' => $modify_time
							);
			if ($cat_id != "") {
				$firstpart = "`cat_id`, ";
				$secondPArt = ":cat_id, ";
				$value_array[':cat_id'] = $cat_id;
				$log = "Modified object ".$title;
			} else {
				$firstpart = "";
				$secondPArt = "";
				$log = "Created object ".$title;
			}			
			
			try {
				$sql = $db->prepare("INSERT INTO `forum_categories` (".$firstpart."`cat_name`, `cat_description`, `status`, `create_time`, `modify_time`)
				VALUES (".$secondPArt.":cat_name, :cat_description, :status, :create_time, :modify_time)
					ON DUPLICATE KEY UPDATE 
						`cat_name` = :cat_name,
						`cat_description` = :cat_description,
						`status` = :status,
						`modify_time` = :modify_time
					");
				$sql->execute($value_array);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
						
			if ($sql) {
				$id = $db->lastInsertId();
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = $_SESSION['admin']['id'];
				$logArray['desc'] = $tag;
				$logArray['create_date'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				return $id;
			} else {
				return false;
			}
		}
		
		function remove($id) {
			$id = $this->mysql_prep($id);
			$modDate = time();			

			global $db;
			try {
				$sql = $db->prepare("DELETE FROM `forum_categories` WHERE `cat_id` =:id");
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
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = $_SESSION['admin']['id'];
				$logArray['desc'] = "removed regulator id #".$id;
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
				$sql = $db->prepare("UPDATE `forum_categories` SET  `".$tag."` = :value, `modify_time` = :modifyTime WHERE `cat_id`=:id");
				$sql->execute(
					array(
					':value' => $value,
					':modifyTime' => time(),
					':id' => $id)
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = $_SESSION['admin']['id'];
				$logArray['desc'] = "Modified ".$tag." with ".$value;
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
				$sql = $db->query("SELECT * FROM `forum_categories` ORDER BY `cat_name` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		function search($val) {
			global $db;
			try {
				$sql = $db->query("SELECT `cat_id`,`cat_name`,`cat_description`,`status`,`create_time`,`modify_time`, MATCH (`cat_description`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match  FROM `forum_categories` WHERE `cat_name` LIKE '%".$val."%' OR `cat_description` LIKE '%".$val."%' OR MATCH(`cat_description`) AGAINST ('".$val."')  ORDER BY `cat_name` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		function sortAll($id, $tag, $tag2=false, $id2=false, $tag3=false, $id3=false, $order="cat_id") {
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
					$sql = $db->prepare("SELECT * FROM `forum_categories` WHERE `".$tag."` = :id".$sqlTag." ORDER BY `".$order."` ASC");
									
					$sql->execute($token);
				} catch(PDOException $ex) {
					echo "An Error occured! ".$ex->getMessage(); 
				}
				
				$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				return $this->out_prep($row);
		}
		
		function getOne($id, $tag='cat_id') {
			$id = $this->mysql_prep($id);
			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM forum_categories WHERE `".$tag."` = :id ORDER BY `cat_id` DESC LIMIT 1");
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
		
		function getOneField($id, $tag="cat_id", $cat_id="cat_name") {
			$data = $this->getOne($id, $tag);
			return $data[$cat_id];
		}
	}
	
	class forum_posts extends common {
		function add($array) {
			$post_content = $this->mysql_prep($array['post_content']);
			$post_date = time();
			$post_by = $this->mysql_prep($array['post_by']);
			$post_topic = $this->mysql_prep($array['post_topic']);
			$status = "inactive";
						
			global $db;
			$value_array = array(
							':post_content' => $post_content, 
							':post_date' => $post_date,
							':status' => $status,
							':post_by' => $post_by,
							':post_topic' => $post_topic
							);
			
			try {
				$sql = $db->prepare("INSERT INTO `forum_posts` (".$firstpart."`post_content`, `post_date`, `status`, `post_by`, `post_topic`)
				VALUES (".$secondPArt.":post_content, :post_date, :status, :post_by, :post_topic)
					ON DUPLICATE KEY UPDATE 
						`post_content` = :post_content,
						`post_date` = :post_date,
						`status` = :status,
						`post_by` = :post_by,
						`post_topic` = :post_topic
					");
				$sql->execute($value_array);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
						
			if ($sql) {
				$id = $db->lastInsertId();
				$notification = new notification;
				$notification_array['type'] = "forum";
				$notification_array['type_id'] = $id;
				$notification_array['desc'] = "New forum post";
				$notification->create($notification_array);
				return $id;
			} else {
				return false;
			}
		}
		
		function remove($id) {
			global $db;
			try {
				$sql = $db->prepare("DELETE FROM `forum_posts` WHERE `post_id` =:id");
				$sql->execute(
					array(
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
		
		function modifyOne($tag, $value, $id) {
			$value = $this->mysql_prep($value);
			$id = $this->mysql_prep($id);
			
			global $db;
			try {
				$sql = $db->prepare("UPDATE `forum_posts` SET  `".$tag."` = :value WHERE `post_id`=:id");
				$sql->execute(
					array(
					':value' => $value,
					':modifyTime' => time(),
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
				$sql = $db->query("SELECT * FROM `forum_posts` ORDER BY `cat_name` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		function search($val) {
			global $db;
			try {
				$sql = $db->query("SELECT `post_id`,`post_content`,`post_date`,`post_topic`,`status`,`post_by`,`like`, MATCH (`post_content`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match  FROM `forum_posts` WHERE `post_content` LIKE '%".$val."%' OR MATCH(`post_content`) AGAINST ('".$val."')  ORDER BY `post_id` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		function sortAll($id, $tag, $tag2=false, $id2=false, $tag3=false, $id3=false, $order='post_id', $dir="DESC") {
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
					$sql = $db->prepare("SELECT * FROM `forum_posts` WHERE `".$tag."` = :id".$sqlTag." ORDER BY `".$order."` ".$dir);
									
					$sql->execute($token);
				} catch(PDOException $ex) {
					echo "An Error occured! ".$ex->getMessage(); 
				}
				
				$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				return $this->out_prep($row);
		}
		
		function getOne($id, $tag='post_id') {
			$id = $this->mysql_prep($id);
			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM forum_posts WHERE `".$tag."` = :id ORDER BY `post_id` DESC LIMIT 1");
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
		
		function getOneField($id, $tag="post_id", $cat_id="post_content") {
			$data = $this->getOne($id, $tag);
			return $data[$cat_id];
		}
		
		function recent() {
			global $db;
			try {
				$sql = $db->query("SELECT `forum_posts`.`post_id`, `forum_posts`.`post_date`, `forum_topics`.`topic_id`, `forum_topics`.`topic_subject` FROM `forum_posts`, `forum_topics` WHERE `forum_posts`.`post_topic` = `forum_topics`.`topic_id` AND `forum_topics`.`status` = 'active' AND `forum_posts`.`status` = 'active' GROUP BY `forum_topics`.`topic_id` ORDER BY `forum_posts`.`post_date` DESC LIMIT 5");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			if ($sql) {
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) { ?>
					<li class="article-entry standard">
					<h4><a href="<?php echo URL."Forum.post?id=".$row['topic_id']."#".$row['post_id']; ?>"><?php echo $row['topic_subject']; ?></a></h4>
					<span class="article-meta"><?php echo date("j M, Y", $row['post_date']); ?></span>
					</li>
				<?php }
			}
		}
	}
		
	class forum_topics extends common {
		function add($array) {
			$topic_subject = $this->mysql_prep($array['topic_subject']);
			$topic_date = time();
			$topic_by = $this->mysql_prep($array['topic_by']);
			$topic_cat = $this->mysql_prep($array['topic_cat']);
			$status = $this->mysql_prep($array['status']);
			$topic_id = $this->mysql_prep($array['topic_id']);
			$is_user = $this->mysql_prep($array['is_user']);
				
			global $db;
			$value_array = array(
							':topic_subject' => $topic_subject, 
							':topic_date' => $topic_date,
							':status' => $status,
							':topic_by' => $topic_by,
							':topic_cat' => $topic_cat
							);
			if ($cat_id != "") {
				$firstpart = "`topic_id`, ";
				$secondPArt = ":topic_id, ";
				$value_array[':topic_id'] = $topic_id;
				$log = "Modified object ".$title;
			} else {
				$firstpart = "";
				$secondPArt = "";
				$log = "Created object ".$title;
			}			
			
			try {
				$sql = $db->prepare("INSERT INTO `forum_topics` (".$firstpart."`topic_subject`, `topic_date`, `status`, `topic_by`, `topic_cat`)
				VALUES (".$secondPArt.":topic_subject, :topic_date, :status, :topic_by, :topic_cat)
					ON DUPLICATE KEY UPDATE 
						`topic_subject` = :topic_subject,
						`topic_date` = :topic_date,
						`status` = :status,
						`topic_by` = :topic_by,
						`topic_cat` = :topic_cat
					");
				$sql->execute($value_array);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
						
			if ($sql) {
				$id = $db->lastInsertId();
				if ($is_user == "yes") {					
					$notification = new notification;
					$notification_array['type'] = "forum_topic";
					$notification_array['type_id'] = $id;
					$notification_array['desc'] = "New Topic Created";
					$notification->create($notification_array);
				}
				return $id;
			} else {
				return false;
			}
		}
		
		function remove($id) {
			global $db;
			try {
				$sql = $db->prepare("DELETE FROM `forum_topics` WHERE `topic_id` =:id");
				$sql->execute(
					array(
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
		
		function modifyOne($tag, $value, $id) {
			$value = $this->mysql_prep($value);
			$id = $this->mysql_prep($id);
			global $db;
			try {
				$sql = $db->prepare("UPDATE `forum_topics` SET  `".$tag."` = :value, `modify_time` = :modifyTime WHERE `post_id`=:id");
				$sql->execute(
					array(
					':value' => $value,
					':modifyTime' => time(),
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
				$sql = $db->query("SELECT * FROM `forum_topics` ORDER BY `topic_subject` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		function search($val) {
			global $db;
			try {
				$sql = $db->query("SELECT `topic_id`,`topic_subject`,`topic_date`,`topic_cat`,`topic_by`,`status`, MATCH (`topic_subject`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match  FROM `forum_topics` WHERE `topic_subject` LIKE '%".$val."%' OR MATCH(`topic_subject`) AGAINST ('".$val."') ORDER BY `topic_date` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		function sortAll($id, $tag, $tag2=false, $id2=false, $tag3=false, $id3=false, $order="topic_id") {
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
					$sql = $db->prepare("SELECT * FROM `forum_topics` WHERE `".$tag."` = :id".$sqlTag." ORDER BY `".$order."` ASC");
									
					$sql->execute($token);
				} catch(PDOException $ex) {
					echo "An Error occured! ".$ex->getMessage(); 
				}
				
				$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				return $this->out_prep($row);
		}
		
		function getOne($id, $tag='topic_id') {
			$id = $this->mysql_prep($id);
			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM forum_topics WHERE `".$tag."` = :id ORDER BY `topic_id` DESC LIMIT 1");
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
		
		function getOneField($id, $tag="topic_id", $cat_id="topic_subject") {
			$data = $this->getOne($id, $tag);
			return $data[$cat_id];
		}
	}
		
	class forum_users extends common {
		function add($array) {
			$username = $this->mysql_prep($array['username']);
			$timestamp = time();
			$email = $this->mysql_prep($array['email']);
			$ref = $this->mysql_prep($array['ref']);
			
			global $db;
			$value_array = array(
							':username' => $username, 
							':timestamp' => $timestamp,
							':email' => $email
							);
			if ($cat_id != "") {
				$firstpart = "`topic_id`, ";
				$secondPArt = ":topic_id, ";
				$value_array[':topic_id'] = $topic_id;
				$log = "Modified object ".$title;
			} else {
				$firstpart = "";
				$secondPArt = "";
				$log = "Created object ".$title;
			}			
			
			try {
				$sql = $db->prepare("INSERT INTO `forum_users` (".$firstpart."`username`, `timestamp`, `email`)
				VALUES (".$secondPArt.":username, :timestamp, :email)
					ON DUPLICATE KEY UPDATE 
						`username` = :username,
						`timestamp` = :timestamp,
						`email` = :email
					");
				$sql->execute($value_array);
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
		
		function remove($id) {
			global $db;
			try {
				$sql = $db->prepare("DELETE FROM `forum_users` WHERE `ref` =:id");
				$sql->execute(
					array(
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
		
		function modifyOne($tag, $value, $id) {
			$value = $this->mysql_prep($value);
			$id = $this->mysql_prep($id);
			global $db;
			try {
				$sql = $db->prepare("UPDATE `forum_users` SET  `".$tag."` = :value, `timestampc` = :modifyTime WHERE `post_id`=:id");
				$sql->execute(
					array(
					':value' => $value,
					':modifyTime' => time(),
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
				$sql = $db->query("SELECT * FROM `forum_users` ORDER BY `username` ASC");
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
				$sql = $db->prepare("SELECT * FROM `forum_users` WHERE `".$tag."` = :id".$sqlTag." ORDER BY `".$order."` ASC");
								
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
				$sql = $db->prepare("SELECT * FROM forum_users WHERE `".$tag."` = :id ORDER BY `ref` DESC LIMIT 1");
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
		
		function getOneField($id, $tag="ref", $cat_id="username") {
			$data = $this->getOne($id, $tag);
			return $data[$cat_id];
		}
		
		function createUnique($username) {
			$num = $username.rand(1, 999);
			return $num;
		}
		
		function confirmUnique($key) {
			$key = $this->mysql_prep($key);

			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM forum_users WHERE `username`= :key");
				$sql->execute(
					array(
					':key' => $key)
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql->rowCount() == 0) {	
				return $key;
			} else {
				return $this->confirmUnique($this->createUnique($key));
			}
		}
	}	
	
	class forum_login extends forum_users {
		function checkLogin() {
			if (isset($_SESSION['users']['ref'])) {
				$data = $this->getOne($_SESSION['users']['email'], "email");
				if ($data) {
					$_SESSION['forum']['username'] = $data['username'];
					$_SESSION['forum']['email'] = $data['email'];
					$_SESSION['forum']['ref'] = $data['ref'];
					return $data['ref'];
				} else {
					$name = $_SESSION['users']['last_name']." ".$_SESSION['users']['other_names'];
					$username_temp = str_replace(" ","", $name);
					$username_temp = str_replace("-","", $username_temp);
					$username_temp = str_replace(",","", $username_temp);
					$username_temp = strtolower($username_temp);
					$username = $this->confirmUnique($username_temp);
					$array['username'] = $_SESSION['forum']['username'] = $username;
					$array['email'] = $_SESSION['forum']['email'] = $_SESSION['users']['email'];
					$add = $this->add($array);
					$_SESSION['forum']['ref'] = $add;
					return $add;
				}
			} else if (isset($_SESSION['forum']['ref'])) {
				$data = $this->getOne($_SESSION['forum']['ref']);
				if ($data) {
					$_SESSION['forum']['username'] = $data['username'];
					$_SESSION['forum']['email'] = $data['email'];
					$_SESSION['forum']['ref'] = $data['ref'];
					return $data['ref'];
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
		
		function login($array) {
			$email = $this->mysql_prep($array['email']);
			$name = $this->mysql_prep($array['full_name']);
			
			
			$username_temp = str_replace(" ","", $name);
			$username_temp = str_replace("-","", $username_temp);
			$username_temp = str_replace(",","", $username_temp);
			$username_temp = strtolower($username_temp);
			$username = $this->confirmUnique($username_temp);
			$array['username'] = $username;
			$array['email'] = $email;
			$add = $this->add($array);
			
			$_SESSION['forum']['username'] = $username;
			$_SESSION['forum']['email'] = $email;
			$_SESSION['forum']['ref'] = $add;
		}
	}
?>