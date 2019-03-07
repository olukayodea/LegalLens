<?php
	class search extends common {
		function create($array, $page_array) {
			$val = $this->mysql_prep($array['s']);
			global $categories;
			$cat = "";
			$count = 0;
			
			if (is_array($array['parameter'])) {
				for ($i = 0; $i < count($array['parameter']); $i++) {
					$cat = ",".$categories->linkListListDef($array['parameter'][$i]);
					$cat = ltrim($cat, ",");
					$result['doc'][$array['parameter'][$i]] = $this->searchCategory($val, $cat, $page_array['law']);
					$result['doc_count'][$array['parameter'][$i]] = $c = $this->searchCategoryCount($val, $cat);
					$count = $count+$c;
				}
			}
			if ($array['case_law'] == 1) {
				$result['case_law'] = $this->searchCase($val, $page_array['case_law']);
				$result['case_law_count'] = $c = $this->searchCaseCount($val);
				$count = $count+$c;
			}
			if ($array['reg_circular'] == 1) {
				$result['reg'] = $this->searchregulation($val, $page_array['reg']);
				$result['reg_count'] = $c = $this->searchregulationCount($val);
				$count = $count+$c;
			}
			if ($array['dic'] == 1) {
				$result['dic'] = $this->searchDictionar($val, $page_array['dic']);
				$result['dic_count'] = $c = $this->searchDictionarCount($val);
				$count = $count+$c;
			}
			$result['count'] = $count;
			return $result;
		}
		
		function searchCategory($val, $cat, $page_count) {
			global $db;

			$limit = intval(page_list*$page_count);

			try {
				$sql = $db->prepare("SELECT `documents`.`title`, `documents`.`ref`, `sections`.`ref` AS section_ref, `documents`.`cat`, `documents`.`type`, `documents`.`year`, `documents`.`category_name`, `documents`.`owner`, `sections`.`section_no`, `sections`.`section_content`, `documents`.`create_time`, `documents`.`modify_time`,`categories`.`priority`,MATCH (`sections`.`section_content`) AGAINST ('?' IN BOOLEAN MODE) AS name_match FROM `documents`, `sections`,`categories` WHERE `documents`.`cat` = `categories`.`ref` AND`documents`.`ref` = `sections`.`document` AND `documents`.`status` = 'active' AND `sections`.`status` = 'active' AND `cat` IN (".$cat.") AND (`documents`.`title` LIKE :keyword OR `documents`.`year` LIKE :keyword OR `documents`.`category_name` LIKE :keyword OR `documents`.`owner` LIKE :keyword OR `documents`.`tags` LIKE :keyword OR `sections`.`tags` LIKE :keyword OR `sections`.`section_content` LIKE :keyword OR MATCH(`sections`.`section_content`) AGAINST ('?') OR MATCH(`documents`.`title`) AGAINST ('?')) GROUP BY `sections`.`section_content` ORDER BY `name_match` DESC, `documents`.`tags`,`title`,`documents`.`category_name` ASC LIMIT ".$limit.",".page_list);
				$sql->bindValue(':keyword','%'.$val.'%');
				$sql->execute();
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				$result = array();
				$count = 0;
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
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

		function searchCategoryCount($val, $cat) {
			global $db;
			try {
				$sql = $db->prepare("SELECT `documents`.`title`, `documents`.`ref`, `sections`.`ref` AS section_ref, `documents`.`cat`, `documents`.`type`, `documents`.`year`, `documents`.`category_name`, `documents`.`owner`, `sections`.`section_no`, `sections`.`section_content`, `documents`.`create_time`, `documents`.`modify_time`,`categories`.`priority`,MATCH (`sections`.`section_content`) AGAINST ('?' IN BOOLEAN MODE) AS name_match FROM `documents`, `sections`,`categories` WHERE `documents`.`cat` = `categories`.`ref` AND`documents`.`ref` = `sections`.`document` AND `documents`.`status` = 'active' AND `sections`.`status` = 'active' AND `cat` IN (".$cat.") AND (`documents`.`title` LIKE :keyword OR `documents`.`year` LIKE :keyword OR `documents`.`category_name` LIKE :keyword OR `documents`.`owner` LIKE :keyword OR `documents`.`tags` LIKE :keyword OR `sections`.`tags` LIKE :keyword OR `sections`.`section_content` LIKE :keyword OR MATCH(`sections`.`section_content`) AGAINST ('?') OR MATCH(`documents`.`title`) AGAINST ('?')) GROUP BY `sections`.`section_content`");
				$sql->bindValue(':keyword','%'.$val.'%');
				$sql->execute();
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
						
			return $sql->rowCount();
		}
		
		function searchCase($val, $page_count) {
			global $db;

			$limit = intval(page_list*$page_count);
			try {
				$sql = $db->prepare("SELECT `caselaw`.`title`, `reporter`, `caselaw`.`court`, `caselaw`.`file`,  `caselaw`.`year`, `caselaw`.`create_time`, `caselaw`.`modify_time`, `caselaw`.`ref`, `caselaw_sections`.`section_content`, `caselaw_sections`.`tags`, `caselaw_sections`.`ref` AS 'section_ID', MATCH (`caselaw_sections`.`section_content`) AGAINST ('?' IN BOOLEAN MODE) AS name_match, citation FROM `caselaw`, `caselaw_sections` WHERE `caselaw`.`ref` = `caselaw_sections`.`caselaw` AND `caselaw`.`status` = 'active' AND `caselaw_sections`.`status` = 'active' AND (`caselaw`.`court` LIKE :keyword OR `caselaw`.`year` LIKE :keyword OR `caselaw`.`title` LIKE :keyword OR `caselaw_sections`.`citation` LIKE :keyword OR `caselaw_sections`.`tags` LIKE :keyword OR `caselaw_sections`.`section_content` LIKE :keyword OR MATCH(`caselaw_sections`.`section_content`) AGAINST ('?') OR (MATCH(`caselaw_sections`.`section_content`) AGAINST ('?'))) GROUP BY `caselaw_sections`.`section_content` ORDER BY  `name_match` DESC,`caselaw_sections`.`tags`, `title`,`court` ASC LIMIT ".$limit.",".page_list);
				$sql->bindValue(':keyword','%'.$val.'%');
				$sql->execute();
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				$result = array();
				$count = 0;
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
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

		function searchCaseCount($val) {
			global $db;
			try {
				$sql = $db->prepare("SELECT `caselaw`.`title`, `reporter`, `caselaw`.`court`, `caselaw`.`file`,  `caselaw`.`year`, `caselaw`.`create_time`, `caselaw`.`modify_time`, `caselaw`.`ref`, `caselaw_sections`.`section_content`, `caselaw_sections`.`tags`, `caselaw_sections`.`ref` AS 'section_ID', MATCH (`caselaw_sections`.`section_content`) AGAINST ('?' IN BOOLEAN MODE) AS name_match, citation FROM `caselaw`, `caselaw_sections` WHERE `caselaw`.`ref` = `caselaw_sections`.`caselaw` AND `caselaw`.`status` = 'active' AND `caselaw_sections`.`status` = 'active' AND (`caselaw`.`court` LIKE :keyword OR `caselaw`.`year` LIKE :keyword OR `caselaw`.`title` LIKE :keyword OR `caselaw_sections`.`citation` LIKE :keyword OR `caselaw_sections`.`tags` LIKE :keyword OR `caselaw_sections`.`section_content` LIKE :keyword OR MATCH(`caselaw_sections`.`section_content`) AGAINST ('?') OR (MATCH(`caselaw_sections`.`section_content`) AGAINST ('?'))) GROUP BY `caselaw_sections`.`section_content`");
				$sql->bindValue(':keyword','%'.$val.'%');
				$sql->execute();
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
						
			return $sql->rowCount();
		}
		
		function searchregulation($val, $page_count) {
			global $db;

			$limit = intval(page_list*$page_count);
			try {
				$sql = $db->prepare("SELECT `regulations`.`title`, `regulations`.`regulator`, `regulations`.`create_time`, `regulations`.`modify_time`, `regulations`.`ref`,`regulations_sections`.`ref` AS `section_ref`, `regulations_sections`.`section_content`, `regulations_sections`.`section_no`, MATCH (`regulations`.`tags`) AGAINST ('?' IN BOOLEAN MODE) AS name_match, MATCH (`regulations_sections`.`section_content`) AGAINST ('?' IN BOOLEAN MODE) AS name_match2, MATCH (`regulations_sections`.`tags`) AGAINST ('?' IN BOOLEAN MODE) AS name_match3  FROM `regulations`, `regulations_sections` WHERE `regulations`.`ref` = `regulations_sections`.`regulations` AND `regulations`.`status` = 'active' AND `regulations_sections`.`status` = 'active' AND (`regulations`.`year` LIKE :keyword OR `regulations`.`title` LIKE :keyword OR `regulations`.`tags` LIKE :keyword OR `regulations_sections`.`tags` LIKE :keyword OR `regulations_sections`.`section_no` LIKE :keyword OR `regulations_sections`.`section_content` LIKE :keyword OR (MATCH(`regulations_sections`.`tags`) AGAINST ('?') OR MATCH(`regulations`.`tags`) AGAINST ('?') OR MATCH(`regulations`.`title`) AGAINST ('?') OR MATCH(`regulations_sections`.`section_content`) AGAINST ('?'))) GROUP BY `regulations_sections`.`section_content` ORDER BY `name_match3`,`name_match2`,`name_match` ASC LIMIT ".$limit.",".page_list);
				$sql->bindValue(':keyword','%'.$val.'%');
				$sql->execute();
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				$result = array();
				$count = 0;
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {			
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

		function searchregulationCount($val) {
			global $db;
			try {
				$sql = $db->prepare("SELECT `regulations`.`title`, `regulations`.`regulator`, `regulations`.`create_time`, `regulations`.`modify_time`, `regulations`.`ref`,`regulations_sections`.`ref` AS `section_ref`, `regulations_sections`.`section_content`, `regulations_sections`.`section_no`, MATCH (`regulations`.`tags`) AGAINST ('?' IN BOOLEAN MODE) AS name_match, MATCH (`regulations_sections`.`section_content`) AGAINST ('?' IN BOOLEAN MODE) AS name_match2, MATCH (`regulations_sections`.`tags`) AGAINST ('?' IN BOOLEAN MODE) AS name_match3  FROM `regulations`, `regulations_sections` WHERE `regulations`.`ref` = `regulations_sections`.`regulations` AND `regulations`.`status` = 'active' AND `regulations_sections`.`status` = 'active' AND (`regulations`.`year` LIKE :keyword OR `regulations`.`title` LIKE :keyword OR `regulations`.`tags` LIKE :keyword OR `regulations_sections`.`tags` LIKE :keyword OR `regulations_sections`.`section_no` LIKE :keyword OR `regulations_sections`.`section_content` LIKE :keyword OR (MATCH(`regulations_sections`.`tags`) AGAINST ('?') OR MATCH(`regulations`.`tags`) AGAINST ('?') OR MATCH(`regulations`.`title`) AGAINST ('?') OR MATCH(`regulations_sections`.`section_content`) AGAINST ('?'))) GROUP BY `regulations_sections`.`section_content`");
				$sql->bindValue(':keyword','%'.$val.'%');
				$sql->execute();
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
						
			return $sql->rowCount();
		}
		
		function searchDictionar($val, $page_count) {
			global $db;

			$limit = intval(page_list*$page_count);
			try {
				$sql = $db->prepare("SELECT `ref`, `title`, `details`, MATCH (`details`) AGAINST ('?' IN BOOLEAN MODE) AS name_match  FROM `list_library` WHERE `list_library`.`status` = 'active' AND (`list_library`.`title` LIKE :keyword OR `list_library`.`details` LIKE :keyword OR MATCH(`details`) AGAINST ('?')) GROUP BY `details` ORDER BY `name_match` DESC, `title` ASC LIMIT ".$limit.",".page_list);
				$sql->bindValue(':keyword','%'.$val.'%');
				$sql->execute();
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				$result = array();
				$count = 0;
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

		function searchDictionarCount($val) {
			global $db;
			try {
				$sql = $db->prepare("SELECT `ref`, `title`, `details`, MATCH (`details`) AGAINST ('?' IN BOOLEAN MODE) AS name_match  FROM `list_library` WHERE `list_library`.`status` = 'active' AND (`list_library`.`title` LIKE :keyword OR `list_library`.`details` LIKE :keyword OR MATCH(`details`) AGAINST ('?')) GROUP BY `details`");
				$sql->bindValue(':keyword','%'.$val.'%');
				$sql->execute();
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
						
			return $sql->rowCount();
		}
	}
?>