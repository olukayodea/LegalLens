<?php
	class forum_categories extends common {
		function add($array) {
			$cat_name = $this->mysql_prep($array['cat_name']);
			$cat_description = $this->mysql_prep($array['cat_description']);
			$status = $this->mysql_prep($array['status']);
			$create_time = $modify_time = time();
			$cat_id = $this->mysql_prep($array['cat_id']);
			
			if ($cat_id != "") {
				$firstpart = "`cat_id`, ";
				$secondPArt = "'".$cat_id."', ";
				$log = "Modified object ".$cat_name;
			} else {
				$firstpart = "";
				$secondPArt = "";
				$log = "Created object ".$cat_name;
			}
			
			$sql = mysql_query("INSERT INTO `forum_categories` (".$firstpart."`cat_name`,`cat_description`, `status`, `create_time`, `modify_time`) VALUES (".$secondPArt."'".$cat_name."','".$cat_description."','".$status."', '".$create_time."', '".$modify_time."') ON DUPLICATE KEY UPDATE `cat_name` = '".$cat_name."', `cat_description` = '".$cat_description."', `status` = '".$status."', `modify_time` = '".$modify_time."'") or die (mysql_error());
			
			if ($sql) {
				$id = mysql_insert_id();
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
			
			$data = $this->getOne($id);
			
			$sql = mysql_query("DELETE FROM `forum_categories` WHERE cat_id = '".$id."'") or die (mysql_error());
			
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
			$modDate = time();
			$sql = mysql_query("UPDATE `forum_categories` SET `".$tag."` = '".$value."', `modify_time` = '".$modDate."' WHERE cat_id = '".$id."'") or die (mysql_error());
			
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
			$sql = mysql_query("SELECT * FROM `forum_categories` ORDER BY `cat_name` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['cat_id'] = $row['cat_id'];
					$result[$count]['cat_name'] = $row['cat_name'];
					$result[$count]['cat_description'] = $row['cat_description'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function search($val) {
			$val = $this->mysql_prep($val);
			$sql = mysql_query("SELECT `cat_id`,`cat_name`,`cat_description`,`status`,`create_time`,`modify_time`, MATCH (`cat_description`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match  FROM `forum_categories` WHERE `cat_name` LIKE '%".$val."%' OR `cat_description` LIKE '%".$val."%' OR MATCH(`cat_description`) AGAINST ('".$val."')  ORDER BY `cat_name` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['cat_id'] = $row['cat_id'];
					$result[$count]['cat_name'] = $row['cat_name'];
					$result[$count]['cat_description'] = $row['cat_description'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function sortAll($id, $tag, $tag2=false, $id2=false, $tag3=false, $id3=false) {
			$id = $this->mysql_prep($id);
			$id2 = $this->mysql_prep($id2);
			$id3 = $this->mysql_prep($id3);
			if ($tag2 != false) {
				$sqlTag = " AND `".$tag2."` = '".$id2."'";
			} else {
				$sqlTag = "";
			}
			if ($tag3 != false) {
				$sqlTag .= " AND `".$tag3."` = '".$id3."'";
			} else {
				$sqlTag .= "";
			}
			
			$sql = mysql_query("SELECT * FROM `forum_categories` WHERE `".$tag."` = '".$id."'".$sqlTag." ORDER BY `cat_id` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['cat_id'] = $row['cat_id'];
					$result[$count]['cat_name'] = $row['cat_name'];
					$result[$count]['cat_description'] = $row['cat_description'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function getOne($id, $tag='cat_id') {
			$id = $this->mysql_prep($id);
			$sql = mysql_query("SELECT * FROM `forum_categories` WHERE `".$tag."` = '".$id."' ORDER BY `cat_id` DESC LIMIT 1") or die (mysql_error());
			if ($sql) {
				$result = array();
				
				if (mysql_num_rows($sql) == 1) {
					$row = mysql_fetch_array($sql);
					$result['cat_id'] = $row['cat_id'];
					$result['cat_name'] = $row['cat_name'];
					$result['cat_description'] = $row['cat_description'];
					$result['status'] = $row['status'];
					$result['create_time'] = $row['create_time'];
					$result['modify_time'] = $row['modify_time'];
					return $this->out_prep($result);
				} else {
					return false;
				}
			}
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
			
			
			$sql = mysql_query("INSERT INTO `forum_posts` (".$firstpart."`post_content`,`post_date`, `post_by`, `post_topic`,`status`) VALUES (".$secondPArt."'".$post_content."','".$post_date."','".$post_by."', '".$post_topic."','".$status."') ON DUPLICATE KEY UPDATE `post_content` = '".$post_content."', `post_date` = '".$post_date."', `post_topic` = '".$post_topic."', `post_by` = '".$post_by."', `status` = '".$status."'") or die (mysql_error());
			
			if ($sql) {
				$id = mysql_insert_id();
				
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
			$id = $this->mysql_prep($id);
			$modDate = time();
			
			$data = $this->getOne($id);
			
			$sql = mysql_query("DELETE FROM `forum_posts` WHERE post_id = '".$id."'") or die (mysql_error());
			
			if ($sql) {
				return true;
			} else {
				return false;
			}
		}
		
		function modifyOne($tag, $value, $id) {
			$value = $this->mysql_prep($value);
			$id = $this->mysql_prep($id);
			$modDate = time();
			$sql = mysql_query("UPDATE `forum_posts` SET `".$tag."` = '".$value."' WHERE post_id = '".$id."'") or die (mysql_error());
			
			if ($sql) {
				return true;
			} else {
				return false;
			}
		}
		
		function listAll() {
			$sql = mysql_query("SELECT * FROM `forum_posts` ORDER BY `cat_name` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['post_id'] = $row['post_id'];
					$result[$count]['post_content'] = $row['post_content'];
					$result[$count]['post_date'] = $row['post_date'];
					$result[$count]['post_topic'] = $row['post_topic'];
					$result[$count]['post_by'] = $row['post_by'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['like'] = $row['like'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function search($val) {
			$val = $this->mysql_prep($val);
			$sql = mysql_query("SELECT `post_id`,`post_content`,`post_date`,`post_topic`,`status`,`post_by`,`like`, MATCH (`post_content`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match  FROM `forum_posts` WHERE `post_content` LIKE '%".$val."%' OR MATCH(`post_content`) AGAINST ('".$val."')  ORDER BY `post_id` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['post_id'] = $row['post_id'];
					$result[$count]['post_content'] = $row['post_content'];
					$result[$count]['post_date'] = $row['post_date'];
					$result[$count]['post_topic'] = $row['post_topic'];
					$result[$count]['post_by'] = $row['post_by'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['like'] = $row['like'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function sortAll($id, $tag, $tag2=false, $id2=false, $tag3=false, $id3=false, $order='post_id', $dir="DESC") {
			$id = $this->mysql_prep($id);
			$id2 = $this->mysql_prep($id2);
			$id3 = $this->mysql_prep($id3);
			if ($tag2 != false) {
				$sqlTag = " AND `".$tag2."` = '".$id2."'";
			} else {
				$sqlTag = "";
			}
			if ($tag3 != false) {
				$sqlTag .= " AND `".$tag3."` = '".$id3."'";
			} else {
				$sqlTag .= "";
			}
			
			
			$sql = mysql_query("SELECT * FROM `forum_posts` WHERE `".$tag."` = '".$id."'".$sqlTag." ORDER BY `".$order."` ".$dir) or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['post_id'] = $row['post_id'];
					$result[$count]['post_content'] = $row['post_content'];
					$result[$count]['post_date'] = $row['post_date'];
					$result[$count]['post_topic'] = $row['post_topic'];
					$result[$count]['post_by'] = $row['post_by'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['like'] = $row['like'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function getOne($id, $tag='post_id') {
			$id = $this->mysql_prep($id);
			$sql = mysql_query("SELECT * FROM `forum_posts` WHERE `".$tag."` = '".$id."' ORDER BY `post_id` DESC LIMIT 1") or die (mysql_error());
			if ($sql) {
				$result = array();
				
				if (mysql_num_rows($sql) == 1) {
					$row = mysql_fetch_array($sql);
					$result['post_id'] = $row['post_id'];
					$result['post_content'] = $row['post_content'];
					$result['post_date'] = $row['post_date'];
					$result['post_topic'] = $row['post_topic'];
					$result['post_by'] = $row['post_by'];
					$result['like'] = $row['like'];
					$result['status'] = $row['status'];
					return $this->out_prep($result);
				} else {
					return false;
				}
			}
		}
		
		function getOneField($id, $tag="post_id", $cat_id="post_content") {
			$data = $this->getOne($id, $tag);
			return $data[$cat_id];
		}
		
		function recent() {
			$sql = mysql_query("SELECT `forum_posts`.`post_id`, `forum_posts`.`post_date`, `forum_topics`.`topic_id`, `forum_topics`.`topic_subject` FROM `forum_posts`, `forum_topics` WHERE `forum_posts`.`post_topic` = `forum_topics`.`topic_id` AND `forum_topics`.`status` = 'active' AND `forum_posts`.`status` = 'active' GROUP BY `forum_topics`.`topic_id` ORDER BY `forum_posts`.`post_date` DESC LIMIT 5") or die (mysql_error());
			if ($sql) {
				
				while ($row = mysql_fetch_array($sql)) { ?>
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
			
			if ($topic_id != "") {
				$firstpart = "`topic_id`, ";
				$secondPArt = "'".$post_id."', ";
				$log = "Modified object ".$cat_name;
			} else {
				$firstpart = "";
				$secondPArt = "";
				$log = "Created object ".$cat_name;
			}
			$sql = mysql_query("INSERT INTO `forum_topics` (".$firstpart."`topic_subject`,`topic_date`, `topic_by`,`topic_cat`,`status`) VALUES (".$secondPArt."'".$topic_subject."','".$topic_date."','".$topic_by."', '".$topic_cat."','".$status."') ON DUPLICATE KEY UPDATE `topic_subject` = '".$topic_subject."', `topic_date` = '".$topic_date."', `topic_by` = '".$topic_by."',`status` = '".$status."', `topic_cat` = '".$topic_cat."'") or die (mysql_error());
			
			if ($sql) {
				$id = mysql_insert_id();
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
			$id = $this->mysql_prep($id);
			$modDate = time();
			
			$data = $this->getOne($id);
			
			$sql = mysql_query("DELETE FROM `forum_topics` WHERE topic_id = '".$id."'") or die (mysql_error());
			
			if ($sql) {
				return true;
			} else {
				return false;
			}
		}
		
		function modifyOne($tag, $value, $id) {
			$value = $this->mysql_prep($value);
			$id = $this->mysql_prep($id);
			$modDate = time();
			$sql = mysql_query("UPDATE `forum_topics` SET `".$tag."` = '".$value."', `modify_time` = '".$modDate."' WHERE post_id = '".$id."'") or die (mysql_error());
			
			if ($sql) {
				return true;
			} else {
				return false;
			}
		}
		
		function listAll() {
			$sql = mysql_query("SELECT * FROM `forum_topics` ORDER BY `topic_subject` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['topic_id'] = $row['topic_id'];
					$result[$count]['topic_subject'] = $row['topic_subject'];
					$result[$count]['topic_date'] = $row['topic_date'];
					$result[$count]['topic_cat'] = $row['topic_cat'];
					$result[$count]['topic_by'] = $row['topic_by'];
					$result[$count]['status'] = $row['status'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function search($val) {
			$sql = mysql_query("SELECT `topic_id`,`topic_subject`,`topic_date`,`topic_cat`,`topic_by`,`status`, MATCH (`topic_subject`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match  FROM `forum_topics` WHERE `topic_subject` LIKE '%".$val."%' OR MATCH(`topic_subject`) AGAINST ('".$val."') ORDER BY `topic_date` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['topic_id'] = $row['topic_id'];
					$result[$count]['topic_subject'] = $row['topic_subject'];
					$result[$count]['topic_date'] = $row['topic_date'];
					$result[$count]['topic_cat'] = $row['topic_cat'];
					$result[$count]['topic_by'] = $row['topic_by'];
					$result[$count]['status'] = $row['status'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function sortAll($id, $tag, $tag2=false, $id2=false, $tag3=false, $id3=false) {
			$id = $this->mysql_prep($id);
			$id2 = $this->mysql_prep($id2);
			$id3 = $this->mysql_prep($id3);
			if ($tag2 != false) {
				$sqlTag = " AND `".$tag2."` = '".$id2."'";
			} else {
				$sqlTag = "";
			}
			if ($tag3 != false) {
				$sqlTag .= " AND `".$tag3."` = '".$id3."'";
			} else {
				$sqlTag .= "";
			}
			
			$sql = mysql_query("SELECT * FROM `forum_topics` WHERE `".$tag."` = '".$id."'".$sqlTag." ORDER BY `topic_id` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['topic_id'] = $row['topic_id'];
					$result[$count]['topic_subject'] = $row['topic_subject'];
					$result[$count]['topic_date'] = $row['topic_date'];
					$result[$count]['topic_cat'] = $row['topic_cat'];
					$result[$count]['topic_by'] = $row['topic_by'];
					$result[$count]['status'] = $row['status'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function getOne($id, $tag='topic_id') {
			$id = $this->mysql_prep($id);
			$sql = mysql_query("SELECT * FROM `forum_topics` WHERE `".$tag."` = '".$id."' ORDER BY `topic_id` DESC LIMIT 1") or die (mysql_error());
			if ($sql) {
				$result = array();
				
				if (mysql_num_rows($sql) == 1) {
					$row = mysql_fetch_array($sql);
					$result['topic_id'] = $row['topic_id'];
					$result['topic_subject'] = $row['topic_subject'];
					$result['topic_date'] = $row['topic_date'];
					$result['topic_by'] = $row['topic_by'];
					$result['topic_cat'] = $row['topic_cat'];
					$result['status'] = $row['status'];
					return $this->out_prep($result);
				} else {
					return false;
				}
			}
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
			
			if ($post_id != "") {
				$firstpart = "`ref`, ";
				$secondPArt = "'".$ref."', ";
				$log = "Modified object ".$cat_name;
			} else {
				$firstpart = "";
				$secondPArt = "";
				$log = "Created object ".$cat_name;
			}
			$sql = mysql_query("INSERT INTO `forum_users` (".$firstpart."`username`,`timestamp`, `email`) VALUES (".$secondPArt."'".$username."','".$timestamp."','".$email."') ON DUPLICATE KEY UPDATE `username` = '".$username."', `timestamp` = '".$timestamp."', `email` = '".$email."'") or die (mysql_error());
			
			if ($sql) {
				$id = mysql_insert_id();
				return $id;
			} else {
				return false;
			}
		}
		
		function remove($id) {
			$id = $this->mysql_prep($id);
			$modDate = time();
			
			$data = $this->getOne($id);
			
			$sql = mysql_query("DELETE FROM `forum_users` WHERE ref = '".$id."'") or die (mysql_error());
			
			if ($sql) {
				return true;
			} else {
				return false;
			}
		}
		
		function modifyOne($tag, $value, $id) {
			$value = $this->mysql_prep($value);
			$id = $this->mysql_prep($id);
			$modDate = time();
			$sql = mysql_query("UPDATE `forum_users` SET `".$tag."` = '".$value."', `timestampc` = '".$modDate."' WHERE post_id = '".$id."'") or die (mysql_error());
			
			if ($sql) {
				return true;
			} else {
				return false;
			}
		}
		
		function listAll() {
			$sql = mysql_query("SELECT * FROM `forum_users` ORDER BY `username` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['username'] = $row['username'];
					$result[$count]['timestamp'] = $row['timestamp'];
					$result[$count]['email'] = $row['email'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function sortAll($id, $tag, $tag2=false, $id2=false, $tag3=false, $id3=false) {
			$id = $this->mysql_prep($id);
			$id2 = $this->mysql_prep($id2);
			$id3 = $this->mysql_prep($id3);
			if ($tag2 != false) {
				$sqlTag = " AND `".$tag2."` = '".$id2."'";
			} else {
				$sqlTag = "";
			}
			if ($tag3 != false) {
				$sqlTag .= " AND `".$tag3."` = '".$id3."'";
			} else {
				$sqlTag .= "";
			}
			
			$sql = mysql_query("SELECT * FROM `forum_users` WHERE `".$tag."` = '".$id."'".$sqlTag." ORDER BY `ref` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['username'] = $row['username'];
					$result[$count]['timestamp'] = $row['timestamp'];
					$result[$count]['email'] = $row['email'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function getOne($id, $tag='ref') {
			$id = $this->mysql_prep($id);
			$sql = mysql_query("SELECT * FROM `forum_users` WHERE `".$tag."` = '".$id."' ORDER BY `ref` DESC LIMIT 1") or die (mysql_error());
			if ($sql) {
				$result = array();
				
				if (mysql_num_rows($sql) == 1) {
					$row = mysql_fetch_array($sql);
					$result['ref'] = $row['ref'];
					$result['username'] = $row['username'];
					$result['timestamp'] = $row['timestamp'];
					$result['email'] = $row['email'];
					return $this->out_prep($result);
				} else {
					return false;
				}
			}
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
			$sql = mysql_query("SELECT * FROM forum_users WHERE `username` = '".$key."'") or die (mysql_error()."sch");
			if (mysql_num_rows($sql) == 0) {
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