<?php
	class search extends common {
		function create($array) {
			$val = $this->mysql_prep($array['s']);
			$categories = new categories;
			$cat = "";
			if (is_array($array['parameter'])) {
				for ($i = 0; $i < count($array['parameter']); $i++) {
					$cat .= ",".$categories->linkListListDef($array['parameter'][$i]);
				}
			
				$cat = ltrim($cat, ",");
				$result['doc'] = $this->searchCategory($val, $cat);
			}
			
			if ($array['case_law'] == 1) {
				$result['case_law'] = $this->searchCase($val);
			}
			if ($array['reg_circular'] == 1) {
				$result['reg'] = $this->searchregulation($val);
			}
			if ($array['dic'] == 1) {
				$result['dic'] = $this->searchDictionar($val);
			}
			if ($array['forum'] == 1) {
				$result['forum']['cat'] = $this->searchForumCat($val);
				$result['forum']['title'] = $this->searchForumPost($val);
				$result['forum']['post'] = $this->searchForumTitle($val);
			}
			return $result;
		}
		
		function searchCategory($val, $cat) {
			$categories = new categories;

			global $db;
			try {
				$sql = $db->query("SELECT `documents`.`title`, `documents`.`ref`, `sections`.`ref` AS section_ref, `documents`.`cat`, `documents`.`type`, `documents`.`year`, `documents`.`category_name`, `documents`.`owner`, `sections`.`section_no`, `sections`.`section_content`, `documents`.`create_time`, `documents`.`modify_time`,`categories`.`priority`,MATCH (`sections`.`section_content`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match FROM `documents`, `sections`,`categories` WHERE `documents`.`cat` = `categories`.`ref` AND`documents`.`ref` = `sections`.`document` AND `documents`.`status` = 'active' AND `sections`.`status` = 'active' AND `cat` IN (".$cat.") AND (`documents`.`title` LIKE '%".$val."%' OR `documents`.`year` LIKE '%".$val."%' OR `documents`.`category_name` LIKE '%".$val."%' OR `documents`.`owner` LIKE '%".$val."%' OR `documents`.`tags` LIKE '%".$val."%' OR `sections`.`tags` LIKE '%".$val."%' OR `sections`.`section_content` LIKE '%".$val."%' OR MATCH(`sections`.`section_content`) AGAINST ('".$val."') OR MATCH(`documents`.`title`) AGAINST ('".$val."')) GROUP BY `sections`.`section_content` ORDER BY `name_match` DESC, `documents`.`tags`,`title`,`documents`.`category_name` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				$result = array();
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
			/*$sql = mysql_query("SELECT `documents`.`title`, `documents`.`ref`, `sections`.`ref` AS section_ref, `documents`.`cat`, `documents`.`type`, `documents`.`year`, `documents`.`category_name`, `documents`.`owner`, `sections`.`section_no`, `sections`.`section_content`, `documents`.`create_time`, `documents`.`modify_time`,`categories`.`priority`,MATCH (`sections`.`section_content`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match, MATCH (`sections`.`tags`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match2, MATCH (`documents`.`tags`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match3 FROM `documents`, `sections`,`categories` WHERE `documents`.`cat` = `categories`.`ref` AND`documents`.`ref` = `sections`.`document` AND `documents`.`status` = 'active' AND `sections`.`status` = 'active' AND `cat` IN (".$cat.") AND (`documents`.`title` LIKE '%".$val."%' OR `documents`.`year` LIKE '%".$val."%' OR `documents`.`category_name` LIKE '%".$val."%' OR `documents`.`owner` LIKE '%".$val."%' OR `documents`.`tags` LIKE '%".$val."%' OR `sections`.`tags` LIKE '%".$val."%' OR `sections`.`section_content` LIKE '%".$val."%' OR MATCH(`sections`.`section_content`) AGAINST ('".$val."') OR MATCH(`documents`.`tags`) AGAINST ('".$val."') OR MATCH(`sections`.`tags`) AGAINST ('".$val."')) GROUP BY `sections`.`section_content` ORDER BY `name_match2`,`name_match3`,`name_match` DESC, `documents`.`tags`,`priority`,`title`,`documents`.`category_name` ASC") or die (mysql_error());*/
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['section_no'] = $row['section_no'];
					$result[$count]['section_ref'] = $row['section_ref'];
					$result[$count]['title'] = $row['title'];
					$result[$count]['cat'] = $row['cat'];
					$result[$count]['type'] = $row['type'];
					$result[$count]['owner'] = $row['owner'];
					$result[$count]['year'] = $row['year'];
					$result[$count]['section_content'] = $row['section_content'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function searchCase($val) {

			global $db;
			try {
				$sql = $db->query("SELECT `caselaw`.`title`, `reporter`, `caselaw`.`court`, `caselaw`.`file`,  `caselaw`.`year`, `caselaw`.`create_time`, `caselaw`.`modify_time`, `caselaw`.`ref`, `caselaw_sections`.`section_content`, `caselaw_sections`.`tags`, `caselaw_sections`.`ref` AS 'section_ID', MATCH (`caselaw_sections`.`section_content`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match, citation FROM `caselaw`, `caselaw_sections` WHERE `caselaw`.`ref` = `caselaw_sections`.`caselaw` AND `caselaw`.`status` = 'active' AND `caselaw_sections`.`status` = 'active' AND (`caselaw`.`court` LIKE '%".$val."%' OR `caselaw`.`year` LIKE '%".$val."%' OR `caselaw`.`title` LIKE '%".$val."%' OR `caselaw_sections`.`citation` LIKE '%".$val."%' OR `caselaw_sections`.`tags` LIKE '%".$val."%' OR `caselaw_sections`.`section_content` LIKE '%".$val."%' OR MATCH(`caselaw_sections`.`section_content`) AGAINST ('".$val."') OR (MATCH(`caselaw_sections`.`section_content`) AGAINST ('".$val."'))) GROUP BY `caselaw_sections`.`section_content` ORDER BY  `name_match` DESC,`caselaw_sections`.`tags`, `title`,`court` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				$result = array();
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
			/*$sql = mysql_query("SELECT `caselaw`.`title`, `reporter`, `caselaw`.`court`, `caselaw`.`file`,  `caselaw`.`year`, `caselaw`.`create_time`, `caselaw`.`modify_time`, `caselaw`.`ref`, `caselaw_sections`.`section_content`, `caselaw_sections`.`tags`, `caselaw_sections`.`ref` AS 'section_ID', MATCH (`caselaw_sections`.`section_content`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match, MATCH (`caselaw_sections`.`tags`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match2, citation FROM `caselaw`, `caselaw_sections` WHERE `caselaw`.`ref` = `caselaw_sections`.`caselaw` AND `caselaw`.`status` = 'active' AND `caselaw_sections`.`status` = 'active' AND (`caselaw`.`court` LIKE '%".$val."%' OR `caselaw`.`year` LIKE '%".$val."%' OR `caselaw`.`title` LIKE '%".$val."%' OR `caselaw_sections`.`citation` LIKE '%".$val."%' OR `caselaw_sections`.`tags` LIKE '%".$val."%' OR `caselaw_sections`.`section_content` LIKE '%".$val."%' OR (MATCH(`caselaw_sections`.`section_content`) AGAINST ('".$val."') OR MATCH(`caselaw_sections`.`tags`) AGAINST ('".$val."'))) GROUP BY `caselaw_sections`.`section_content` ORDER BY `name_match2`, `name_match` DESC,`caselaw_sections`.`tags`, `title`,`court` ASC") or die (mysql_error());*/
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['title'] = $row['title'];
					$result[$count]['type'] = "Case Law";
					$result[$count]['court'] = $row['court'];
					$result[$count]['reporter'] = $row['reporter'];
					$result[$count]['year'] = $row['year'];
					$result[$count]['file'] = $row['file'];
					$result[$count]['citation'] = $row['citation'];
					$result[$count]['section_ID'] = $row['section_ID'];
					$result[$count]['section_content'] = $row['section_content'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function searchregulation($val) {
			global $db;
			try {
				$sql = $db->query("SELECT `regulations`.`title`, `regulations`.`regulator`, `regulations`.`create_time`, `regulations`.`modify_time`, `regulations`.`ref`,`regulations_sections`.`ref` AS `section_ref`, `regulations_sections`.`section_content`, `regulations_sections`.`section_no`, MATCH (`regulations`.`tags`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match, MATCH (`regulations_sections`.`section_content`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match2, MATCH (`regulations_sections`.`tags`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match3  FROM `regulations`, `regulations_sections` WHERE `regulations`.`ref` = `regulations_sections`.`regulations` AND `regulations`.`status` = 'active' AND `regulations_sections`.`status` = 'active' AND (`regulations`.`year` LIKE '%".$val."%' OR `regulations`.`title` LIKE '%".$val."%' OR `regulations`.`tags` LIKE '%".$val."%' OR `regulations_sections`.`tags` LIKE '%".$val."%' OR `regulations_sections`.`section_no` LIKE '%".$val."%' OR `regulations_sections`.`section_content` LIKE '%".$val."%' OR (MATCH(`regulations_sections`.`tags`) AGAINST ('".$val."') OR MATCH(`regulations`.`tags`) AGAINST ('".$val."') OR MATCH(`regulations`.`title`) AGAINST ('".$val."') OR MATCH(`regulations_sections`.`section_content`) AGAINST ('".$val."'))) GROUP BY `regulations_sections`.`section_content` ORDER BY `name_match3`,`name_match2`,`name_match` DESC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				$result = array();
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {			
			//$sql = mysql_query("SELECT `regulations`.`title`, `regulations`.`regulator`, `regulations`.`create_time`, `regulations`.`modify_time`, `regulations`.`ref`,`regulations_sections`.`ref` AS `section_ref`, `regulations_sections`.`section_content`, `regulations_sections`.`section_no`, MATCH (`regulations_sections`.`section_content`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match, MATCH (`regulations_sections`.`tags`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match3, MATCH (`regulations`.`tags`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match FROM `regulations`, `regulations_sections` WHERE `regulations`.`ref` = `regulations_sections`.`regulations` AND `regulations`.`status` = 'active' AND `regulations_sections`.`status` = 'active' AND (`regulations`.`year` LIKE '%".$val."%' OR `regulations`.`title` LIKE '%".$val."%' OR `regulations`.`tags` LIKE '%".$val."%' OR `regulations_sections`.`tags` LIKE '%".$val."%' OR `regulations_sections`.`section_no` LIKE '%".$val."%' OR `regulations_sections`.`section_content` LIKE '%".$val."%') GROUP BY `regulations_sections`.`section_content` ORDER BY `name_match3` DESC") or die (mysql_error());
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['section_ref'] = $row['section_ref'];
					$result[$count]['title'] = $row['title'];
					$result[$count]['type'] = $row['type'];
					$result[$count]['regulator'] = $row['regulator'];
					$result[$count]['year'] = $row['year'];
					$result[$count]['section_no'] = $row['section_no'];
					$result[$count]['section_content'] = $row['section_content'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function searchDictionar($val) {

			global $db;
			try {
				$sql = $db->query("SELECT `ref`, `title`, `details`, MATCH (`details`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match  FROM `list_library` WHERE `list_library`.`status` = 'active' AND (`list_library`.`title` LIKE '%".$val."%' OR `list_library`.`details` LIKE '%".$val."%' OR MATCH(`details`) AGAINST ('".$val."')) GROUP BY `details` ORDER BY `name_match` DESC, `title` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				$result = array();
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['title'] = $row['title'];
					$result[$count]['type'] = "Dictionary";
					$result[$count]['details'] = $row['details'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function searchForumCat($val) {
			global $db;
			try {
				$sql = $db->query("SELECT `cat_id`, `cat_name`, `cat_description`, MATCH(`cat_description`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match FROM `forum_categories` WHERE  `forum_categories`.`status` = 'active' AND (`cat_name` LIKE '%".$val."%' OR `cat_description` LIKE '%".$val."%' OR MATCH(`cat_description`) AGAINST ('".$val."')) ORDER BY `cat_name` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				$result = array();
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result[$count]['cat_id'] = $row['cat_id'];
					$result[$count]['cat_name'] = $row['cat_name'];
					$result[$count]['cat_description'] = $row['cat_description'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function searchForumTitle($val) {
			global $db;
			try {
				$sql = $db->query("SELECT `topic_id`, `topic_subject`, MATCH(`topic_subject`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match FROM `forum_topics` WHERE  `forum_topics`.`status` = 'active' AND (`topic_subject` LIKE '%".$val."%' OR MATCH(`topic_subject`) AGAINST ('".$val."')) ORDER BY `topic_id` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				$result = array();
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result[$count]['topic_id'] = $row['topic_id'];
					$result[$count]['topic_subject'] = $row['topic_subject'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function searchForumPost($val) {
			global $db;
			try {
				$sql = $db->query("SELECT `post_id`, `post_content`, MATCH(`post_content`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match FROM `forum_posts` WHERE  `forum_posts`.`status` = 'active' AND (`post_content` LIKE '%".$val."%' OR MATCH(`post_content`) AGAINST ('".$val."')) ORDER BY `post_id` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				$result = array();
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result[$count]['post_id'] = $row['post_id'];
					$result[$count]['post_content'] = $row['post_content'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
	}
?>