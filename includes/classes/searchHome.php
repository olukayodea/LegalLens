<?php
	class searchHome extends common {
		function create($array, $page_array) {
			$val = $this->mysql_prep($array['s']);
			$cat = "";
			$count = 0;
			
            $result['doc'][$array['parameter']] = $this->searchCategory($val, $page_array['law']);
            $result['doc_count'][$array['parameter']] = $c = $this->searchCategoryCount($val, $cat);

            $result['case_law'] = $this->searchCase($val, $page_array['case_law']);
            $result['case_law_count'] = $c = $this->searchCaseCount($val);
            $count = $count+$c;

            $result['reg'] = $this->searchregulation($val, $page_array['reg']);
            $result['reg_count'] = $c = $this->searchregulationCount($val);
            $count = $count+$c;

            $result['dic'] = $this->searchDictionar($val, $page_array['dic']);
            $result['dic_count'] = $c = $this->searchDictionarCount($val);
            $count = $count+$c;

			$result['count'] = $count;
			return $result;
		}
		
		function searchCategory($val, $page_count) {
			global $db;

			$limit = intval(page_list*$page_count);

			try {
				$sql = $db->query("SELECT `documents`.`title`, `documents`.`ref`, `sections`.`ref` AS section_ref, `documents`.`cat`, `documents`.`type`, `documents`.`year`, `documents`.`category_name`, `documents`.`owner`, `sections`.`section_no`, `sections`.`section_content`, `documents`.`create_time`, `documents`.`modify_time`,`categories`.`priority`,MATCH (`sections`.`section_content`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match FROM `documents`, `sections` WHERE `documents`.`ref` = `sections`.`document` AND `documents`.`status` = 'active' AND `sections`.`status` = 'active' AND (`documents`.`title` LIKE '%".$val."%' OR `documents`.`year` LIKE '%".$val."%' OR `documents`.`category_name` LIKE '%".$val."%' OR `documents`.`owner` LIKE '%".$val."%' OR `documents`.`tags` LIKE '%".$val."%' OR `sections`.`tags` LIKE '%".$val."%' OR `sections`.`section_content` LIKE '%".$val."%' OR MATCH(`sections`.`section_content`) AGAINST ('".$val."') OR MATCH(`documents`.`title`) AGAINST ('".$val."')) GROUP BY `sections`.`section_content` ORDER BY `name_match` DESC, `documents`.`tags`,`title`,`documents`.`category_name` ASC LIMIT ".$limit.",".page_list);
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

		function searchCategoryCount($val) {
			global $db;
			try {
				$sql = $db->query("SELECT `documents`.`title`, `documents`.`ref`, `sections`.`ref` AS section_ref, `documents`.`cat`, `documents`.`type`, `documents`.`year`, `documents`.`category_name`, `documents`.`owner`, `sections`.`section_no`, `sections`.`section_content`, `documents`.`create_time`, `documents`.`modify_time`,`categories`.`priority`,MATCH (`sections`.`section_content`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match FROM `documents`, `sections` WHERE `documents`.`ref` = `sections`.`document` AND `documents`.`status` = 'active' AND `sections`.`status` = 'active' AND (`documents`.`title` LIKE '%".$val."%' OR `documents`.`year` LIKE '%".$val."%' OR `documents`.`category_name` LIKE '%".$val."%' OR `documents`.`owner` LIKE '%".$val."%' OR `documents`.`tags` LIKE '%".$val."%' OR `sections`.`tags` LIKE '%".$val."%' OR `sections`.`section_content` LIKE '%".$val."%' OR MATCH(`sections`.`section_content`) AGAINST ('".$val."') OR MATCH(`documents`.`title`) AGAINST ('".$val."')) GROUP BY `sections`.`section_content`");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
						
			return $sql->rowCount();
		}
		
		function listCategory($page_count) {
			global $db;

			$limit = intval(page_list*$page_count);

			try {
				$sql = $db->query("SELECT `documents`.`title`, `documents`.`ref`, `sections`.`ref` AS section_ref, `documents`.`cat`, `documents`.`type`, `documents`.`year`, `documents`.`category_name`, `documents`.`owner`, `sections`.`section_no`, `sections`.`section_content`, `documents`.`create_time`, `documents`.`modify_time` FROM `documents`, `sections` WHERE `documents`.`ref` = `sections`.`document` AND `documents`.`status` = 'active' AND `sections`.`status` = 'active'  GROUP BY `sections`.`section_content` ORDER BY `documents`.`tags`,`title`,`documents`.`category_name` ASC LIMIT ".$limit.",".page_list);
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

		function listCategoryCount() {
			global $db;
			try {
				$sql = $db->query("SELECT `documents`.`title`, `documents`.`ref`, `sections`.`ref` AS section_ref, `documents`.`cat`, `documents`.`type`, `documents`.`year`, `documents`.`category_name`, `documents`.`owner`, `sections`.`section_no`, `sections`.`section_content`, `documents`.`create_time`, `documents`.`modify_time` FROM `documents`, `sections` WHERE `documents`.`ref` = `sections`.`document` AND `documents`.`status` = 'active' AND `sections`.`status` = 'active'  GROUP BY `sections`.`section_content` ORDER BY `documents`.`tags`,`title`,`documents`.`category_name` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
						
			return $sql->rowCount();
		}
		
		function searchCase($val, $page_count) {
			global $db;

			$limit = intval(page_list*$page_count);
			try {
				$sql = $db->query("SELECT `caselaw`.`title`, `reporter`, `caselaw`.`court`, `caselaw`.`file`,  `caselaw`.`year`, `caselaw`.`create_time`, `caselaw`.`modify_time`, `caselaw`.`ref`, `caselaw_sections`.`section_content`, `caselaw_sections`.`tags`, `caselaw_sections`.`ref` AS 'section_ID', MATCH (`caselaw_sections`.`section_content`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match, citation FROM `caselaw`, `caselaw_sections` WHERE `caselaw`.`ref` = `caselaw_sections`.`caselaw` AND `caselaw`.`status` = 'active' AND `caselaw_sections`.`status` = 'active' AND (`caselaw`.`court` LIKE '%".$val."%' OR `caselaw`.`year` LIKE '%".$val."%' OR `caselaw`.`title` LIKE '%".$val."%' OR `caselaw_sections`.`citation` LIKE '%".$val."%' OR `caselaw_sections`.`tags` LIKE '%".$val."%' OR `caselaw_sections`.`section_content` LIKE '%".$val."%' OR MATCH(`caselaw_sections`.`section_content`) AGAINST ('".$val."') OR (MATCH(`caselaw_sections`.`section_content`) AGAINST ('".$val."'))) GROUP BY `caselaw_sections`.`section_content` ORDER BY  `name_match` DESC,`caselaw_sections`.`tags`, `title`,`court` ASC LIMIT ".$limit.",".page_list);
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
				$sql = $db->query("SELECT `caselaw`.`title`, `reporter`, `caselaw`.`court`, `caselaw`.`file`,  `caselaw`.`year`, `caselaw`.`create_time`, `caselaw`.`modify_time`, `caselaw`.`ref`, `caselaw_sections`.`section_content`, `caselaw_sections`.`tags`, `caselaw_sections`.`ref` AS 'section_ID', MATCH (`caselaw_sections`.`section_content`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match, citation FROM `caselaw`, `caselaw_sections` WHERE `caselaw`.`ref` = `caselaw_sections`.`caselaw` AND `caselaw`.`status` = 'active' AND `caselaw_sections`.`status` = 'active' AND (`caselaw`.`court` LIKE '%".$val."%' OR `caselaw`.`year` LIKE '%".$val."%' OR `caselaw`.`title` LIKE '%".$val."%' OR `caselaw_sections`.`citation` LIKE '%".$val."%' OR `caselaw_sections`.`tags` LIKE '%".$val."%' OR `caselaw_sections`.`section_content` LIKE '%".$val."%' OR MATCH(`caselaw_sections`.`section_content`) AGAINST ('".$val."') OR (MATCH(`caselaw_sections`.`section_content`) AGAINST ('".$val."'))) GROUP BY `caselaw_sections`.`section_content`");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
						
			return $sql->rowCount();
		}
		
		function listCase($page_count) {
			global $db;

			$limit = intval(page_list*$page_count);
			try {
				$sql = $db->query("SELECT `caselaw`.`title`, `reporter`, `caselaw`.`court`, `caselaw`.`file`,  `caselaw`.`year`, `caselaw`.`create_time`, `caselaw`.`modify_time`, `caselaw`.`ref`, `caselaw_sections`.`section_content`, `caselaw_sections`.`tags`, `caselaw_sections`.`ref` AS 'section_ID', citation FROM `caselaw`, `caselaw_sections` WHERE `caselaw`.`ref` = `caselaw_sections`.`caselaw` AND `caselaw`.`status` = 'active' AND `caselaw_sections`.`status` = 'active' GROUP BY `caselaw_sections`.`section_content` ORDER BY `caselaw_sections`.`tags`, `title`,`court` ASC LIMIT ".$limit.",".page_list);
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

		function listCaseCount() {
			global $db;
			try {
				$sql = $db->query("SELECT `caselaw`.`title`, `reporter`, `caselaw`.`court`, `caselaw`.`file`,  `caselaw`.`year`, `caselaw`.`create_time`, `caselaw`.`modify_time`, `caselaw`.`ref`, `caselaw_sections`.`section_content`, `caselaw_sections`.`tags`, `caselaw_sections`.`ref` AS 'section_ID', `citation` FROM `caselaw`, `caselaw_sections` WHERE `caselaw`.`ref` = `caselaw_sections`.`caselaw` AND `caselaw`.`status` = 'active' AND `caselaw_sections`.`status` = 'active' GROUP BY `caselaw_sections`.`section_content`");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
						
			return $sql->rowCount();
		}
		
		function searchregulation($val, $page_count) {
			global $db;

			$limit = intval(page_list*$page_count);
			try {
				$sql = $db->query("SELECT `regulations`.`title`, `regulations`.`regulator`, `regulations`.`create_time`, `regulations`.`modify_time`, `regulations`.`ref`,`regulations_sections`.`ref` AS `section_ref`, `regulations_sections`.`section_content`, `regulations_sections`.`section_no`, MATCH (`regulations`.`tags`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match, MATCH (`regulations_sections`.`section_content`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match2, MATCH (`regulations_sections`.`tags`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match3  FROM `regulations`, `regulations_sections` WHERE `regulations`.`ref` = `regulations_sections`.`regulations` AND `regulations`.`status` = 'active' AND `regulations_sections`.`status` = 'active' AND (`regulations`.`year` LIKE '%".$val."%' OR `regulations`.`title` LIKE '%".$val."%' OR `regulations`.`tags` LIKE '%".$val."%' OR `regulations_sections`.`tags` LIKE '%".$val."%' OR `regulations_sections`.`section_no` LIKE '%".$val."%' OR `regulations_sections`.`section_content` LIKE '%".$val."%' OR (MATCH(`regulations_sections`.`tags`) AGAINST ('".$val."') OR MATCH(`regulations`.`tags`) AGAINST ('".$val."') OR MATCH(`regulations`.`title`) AGAINST ('".$val."') OR MATCH(`regulations_sections`.`section_content`) AGAINST ('".$val."'))) GROUP BY `regulations_sections`.`section_content` ORDER BY `name_match3`,`name_match2`,`name_match` ASC LIMIT ".$limit.",".page_list);
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
				$sql = $db->query("SELECT `regulations`.`title`, `regulations`.`regulator`, `regulations`.`create_time`, `regulations`.`modify_time`, `regulations`.`ref`,`regulations_sections`.`ref` AS `section_ref`, `regulations_sections`.`section_content`, `regulations_sections`.`section_no`, MATCH (`regulations`.`tags`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match, MATCH (`regulations_sections`.`section_content`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match2, MATCH (`regulations_sections`.`tags`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match3  FROM `regulations`, `regulations_sections` WHERE `regulations`.`ref` = `regulations_sections`.`regulations` AND `regulations`.`status` = 'active' AND `regulations_sections`.`status` = 'active' AND (`regulations`.`year` LIKE '%".$val."%' OR `regulations`.`title` LIKE '%".$val."%' OR `regulations`.`tags` LIKE '%".$val."%' OR `regulations_sections`.`tags` LIKE '%".$val."%' OR `regulations_sections`.`section_no` LIKE '%".$val."%' OR `regulations_sections`.`section_content` LIKE '%".$val."%' OR (MATCH(`regulations_sections`.`tags`) AGAINST ('".$val."') OR MATCH(`regulations`.`tags`) AGAINST ('".$val."') OR MATCH(`regulations`.`title`) AGAINST ('".$val."') OR MATCH(`regulations_sections`.`section_content`) AGAINST ('".$val."'))) GROUP BY `regulations_sections`.`section_content`");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
						
			return $sql->rowCount();
        }
        
		function listRegulation($page_count) {
			global $db;

			$limit = intval(page_list*$page_count);
			try {
				$sql = $db->query("SELECT `regulations`.`title`, `regulations`.`regulator`, `regulations`.`create_time`, `regulations`.`modify_time`, `regulations`.`ref`,`regulations_sections`.`ref` AS `section_ref`, `regulations_sections`.`section_content`, `regulations_sections`.`section_no` FROM `regulations`, `regulations_sections` WHERE `regulations`.`ref` = `regulations_sections`.`regulations` AND `regulations`.`status` = 'active' AND `regulations_sections`.`status` = 'active' GROUP BY `regulations_sections`.`section_content` ORDER BY  `title` ASC LIMIT ".$limit.",".page_list);
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

		function listRegulationCount() {
			global $db;
			try {
				$sql = $db->query("SELECT `regulations`.`title`, `regulations`.`regulator`, `regulations`.`create_time`, `regulations`.`modify_time`, `regulations`.`ref`,`regulations_sections`.`ref` AS `section_ref`, `regulations_sections`.`section_content`, `regulations_sections`.`section_no` FROM `regulations`, `regulations_sections` WHERE `regulations`.`ref` = `regulations_sections`.`regulations` AND `regulations`.`status` = 'active' AND `regulations_sections`.`status` = 'active' GROUP BY `regulations_sections`.`section_content`");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
						
			return $sql->rowCount();
		}
		
		function searchDictionar($val, $page_count) {
			global $db;

			$limit = intval(page_list*$page_count);
			try {
				$sql = $db->query("SELECT `ref`, `title`, `details`, MATCH (`details`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match  FROM `list_library` WHERE `list_library`.`status` = 'active' AND (`list_library`.`title` LIKE '%".$val."%' OR `list_library`.`details` LIKE '%".$val."%' OR MATCH(`details`) AGAINST ('".$val."')) GROUP BY `details` ORDER BY `name_match` DESC, `title` ASC LIMIT ".$limit.",".page_list);
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
				$sql = $db->query("SELECT `ref`, `title`, `details`, MATCH (`details`) AGAINST ('".$val."' IN BOOLEAN MODE) AS name_match  FROM `list_library` WHERE `list_library`.`status` = 'active' AND (`list_library`.`title` LIKE '%".$val."%' OR `list_library`.`details` LIKE '%".$val."%' OR MATCH(`details`) AGAINST ('".$val."')) GROUP BY `details`");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
						
			return $sql->rowCount();
		}
		function listDictionar($page_count) {
			global $db;

			$limit = intval(page_list*$page_count);
			try {
				$sql = $db->query("SELECT `ref`, `title`, `details`  FROM `list_library` WHERE `list_library`.`status` = 'active' GROUP BY `details` ORDER BY`title` ASC LIMIT ".$limit.",".page_list);
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

		function listDictionarCount() {
			global $db;
			try {
				$sql = $db->query("SELECT `ref`, `title`, `details` FROM `list_library` WHERE `list_library`.`status` = 'active' GROUP BY `details`");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
						
			return $sql->rowCount();
		}

		function listClause($page_count) {
			global $db;

			$limit = intval(page_list*$page_count);
			try {
				$sql = $db->query("SELECT * FROM `drafting` WHERE `type` = 'clause' AND `status` = 'active' ORDER BY `title` ASC LIMIT ".$limit.",".page_list);
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

		function listClauseCount() {
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `drafting` WHERE `type` = 'clause' AND `status` = 'active' ORDER BY `title` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
						
			return $sql->rowCount();
		}

		function listAgreement($page_count) {
			global $db;

			$limit = intval(page_list*$page_count);
			try {
				$sql = $db->query("SELECT * FROM `drafting` WHERE `type` = 'Agreement' AND `status` = 'active' ORDER BY `title` ASC LIMIT ".$limit.",".page_list);
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

		function listAgreementCount() {
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `drafting` WHERE `type` = 'Agreement' AND `status` = 'active' ORDER BY `title` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
						
			return $sql->rowCount();
		}

		function listForm($page_count) {
			global $db;

			$limit = intval(page_list*$page_count);
			try {
				$sql = $db->query("SELECT * FROM `drafting` WHERE `type` = 'Forms' AND `status` = 'active' ORDER BY `title` ASC LIMIT ".$limit.",".page_list);
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

		function listFormCount() {
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `drafting` WHERE `type` = 'Forms' AND `status` = 'active' ORDER BY `title` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
						
			return $sql->rowCount();
		}
	}
?>