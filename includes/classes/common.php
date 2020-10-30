<?php
	class common {
		protected $exclude = array(
			"HenryTem",
			"Michaelbem",
			"Jameslob"
		);
		function curl_file_get_contents($url) {
			if(strstr($url, "https") == 0) {
				return $this->curl_file_get_contents_https($url);
			}
			else {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$data = curl_exec($ch);
				curl_close($ch);
				return $data;
			}
		}
		
		function curl_file_get_contents_https($url) {
			$res = curl_init();
			curl_setopt($res, CURLOPT_URL, $url);
			curl_setopt($res,CURLOPT_RETURNTRANSFER,true);
			curl_setopt($res, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($res, CURLOPT_SSL_VERIFYPEER, false);
			$out = curl_exec($res);
			curl_close($res);
			return $out;
		}
				
		function get_prep($value) {
			$value = urldecode(htmlentities(strip_tags($value)));
			
			return $value;
		}
		
		function get_prep2(&$item) {
			$item = htmlentities($item);
		}
		
		function out_prep($array) {
			if (is_array($array)) {
				if (count($array) > 0) {
					array_walk_recursive($array, array($this, 'get_prep2'));
				}
			}
			return $array;
		}
		
		function mysql_prep($value) {
			return $value;
		}
		
		function createRandomPassword($len = 7) { 
			$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"; 
			srand((double)microtime()*1000000); 
			$i = 0; 
			$pass = '' ; 
			$count = strlen($chars);
			while ($i <= $len) { 
				$num = (rand() % $count); 
				$tmp = substr($chars, $num, 1); 
				$pass = $pass . $tmp; 
				$i++; 
			} 
			return $pass; 
		}
		
		function send_mail($from,$to,$subject,$body) {
			$headers = '';
			$headers .= "From: $from\r\n";
			$headers .= "Reply-to: ".replyMail."\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "Date: " . date('r', time()) . "\r\n";
		
			if (@mail($to,$subject,$body,$headers)) {
				return true;
			} else {
				return false;
			}
		}
		
		function truncate($text, $chars = 100) {
			$text = $text." ";
			$text = substr($text,0,$chars);
			$text = substr($text,0,strrpos($text,' '));
			$text = $text."...";
			return $text;
		}
		
		function truncateLine($data) {
			$split = explode(".", $data);
			$text = $split[0];
			$text = $text."... ";
			return $text;
		}
		
		function getLine($text) {
			$word = explode(".", $text);
			return $word[0];
		}
		
		function get_time_stamp($post_time) {
			if (($post_time == "") || ($post_time <1)) {
				return false;
			} else {
				$difference = time() - $post_time;
				$periods = array("sec", "min", "hour", "day", "week",
				"month", "years", "decade");
				$lengths = array("60","60","24","7","4.35","12","10");
				
				if ($difference >= 0) { // this was in the past
					$ending = "ago";
				} else { // this was in the future
					$difference = -$difference;
					$ending = "time";
				}
				
				for($j = 0; $difference >= $lengths[$j]; $j++)
				$difference /= $lengths[$j];
				$difference = round($difference);
				
				if($difference != 1) $periods[$j].= "s";
				$text = "$difference $periods[$j] $ending";
				return $text;
			}
		}
		
		function getExtension($str) {
			$i = strrpos($str,".");
			if (!$i) { return ""; } 
			$l = strlen($str) - $i;
			$ext = substr($str,$i+1,$l);
			return $ext;
		}
		
		function getParam($url) {
			$urlData = explode("?", $url);
			$param = explode("&", $urlData[1]);
			$tag = "";
			for ($i = 1; $i < count($param); $i++) {
				if ($param[$i] != "") {
					$tag .= "&".$param[$i];
				}
			}
			return $tag;
		}
		
		function get_tiny_url($url)  {  
			$ch = curl_init();  
			$timeout = 5;  
			curl_setopt($ch,CURLOPT_URL,'http://tinyurl.com/api-create.php?url='.$url);  
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);  
			curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);  
			$data = curl_exec($ch);  
			curl_close($ch);  
			return $data;  
		}
		
		
		function seo($id, $type="item") {
			if ($type == "help") {
				$knowledge_base = new knowledge_base;
				$row = $knowledge_base->getOne($id);
				$id = $row['ref'];
				$name = trim(strtoupper($row['title']));
				
				$urlLink = explode(" ", $name);
				$link = implode("-", $urlLink);
				
				$result = URL."help/".$id."/".$link."/";
			} else if ($type == "category") {
				$forum_categories = new forum_categories;
				$row = $forum_categories->getOne($id);
				$id = $row['cat_id'];
				$name = trim(strtoupper($row['cat_name']));
				
				$urlLink = explode(" ", $name);
				$link = implode("-", $urlLink);
				
				$result = URL."category/".$id."/".$link."/";
			} else if ($type == "topic") {
				$forum_topics = new forum_topics;
				$row = $forum_topics->getOne($id);
				$id = $row['topic_id'];
				$name = trim(strtoupper($row['topic_subject']));
				
				$urlLink = explode(" ", $name);
				$link = implode("-", $urlLink);
				
				$result = URL."topics/".$id."/".$link."/";
			} else if ($type == "news") {
				$news = new news;
				$row = $news->getOne($id);
				$id = $row['ref'];
				$name = trim(strtoupper($row['title']));
				
				$urlLink = explode(" ", $name);
				$link = implode("-", $urlLink);
				
				$result = URL."news/".$id."/".$link."/";
			} else {
			}
//			$result = URL."item?id=".$id;
			
			return $result;
		}
		
		function hashPass($string) {
			$count = strlen($string);
			$start = $count/2;
			$list = "";
			for ($i = 0; $i < $start; $i++) {
				$list .= "*";
			}
			$hasPass = substr_replace($string, $list, $start);
			
			return $hasPass;
		}
		
		function initials($string) {
			$string = trim($string);
			$words = explode(" ", $string);
			$words = array_filter($words);
			$letters = "";
			foreach ($words as $value) {
				$letters .= strtoupper(substr($value, 0, 1)).". ";
			}
			$letters = trim(trim($letters), ".");
			
			return $letters;
		}
		
		function africaProblem() {
			if ($_SESSION['location_data']['loc_city'] != "") {
				return trim($_SESSION['location_data']['loc_city']);
			} else {
				return trim($_SESSION['location_data']['loc_country']);
			}
		}
		
		function unwrap($data) {
			$result = base64_decode($data);
			$result = json_decode($result, true);
			
			return $result;
		}
		
		function numberPrintFormat($value) {
			if ($value > 999 && $value <= 999999) {
				$result = floor($value / 1000) . ' K';
			} elseif ($value > 999999 && $value < 999999999) {
				$result = floor($value / 1000000) . ' M';
			} elseif ($value > 999999999) {
				$result = floor($value / 1000000000) . ' B';
			} else {
				$result = $value;
			}
			
			return $result;
		}
		
		function stateList() {
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `areas` ORDER BY `state` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			if ($sql) {
				$result = array();
				$count = 0;
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result[$count]['state'] = ucfirst(strtolower($row['state']));
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function searchStte($val) {
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `areas` WHERE `state` LIKE '%".$val."%' ORDER BY `state` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}

			if ($sql) {
				$result = array();
				$count = 0;
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result[$count]['state'] = ucfirst(strtolower($row['state']));
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function convert_clickable_links($message) {
			$parsedMessage = preg_replace(array('/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))/', '/(^|[^a-z0-9_])@([a-z0-9_]+)/i', '/(^|[^a-z0-9_])([a-z0-9_]+)/i'), array('<a href="$1" target="_blank">$1</a>', '$1<a href="'.URL.'regulations?s=$2">@$2</a>', '$1<a href="'.URL.'regulations?s=$2">#$2</a>'), $message);
		
			return $parsedMessage;
		}
		
		function updateCounter($id, $section, $type, $mobile = false) {
			$id = $this->mysql_prep($id);
			$type = $this->mysql_prep($type);
			$section = $this->mysql_prep($section);

			if ($mobile == false) {
				$mobile = $_SESSION['users']['ref'];
			}
			global $db;
			try {
				$sql = $db->prepare("INSERT INTO `counter_log` (`id`, `type`,`section`,`user_id`, `date_time`) VALUES (:id, :type, :section, :user_id, :date_time)");
				$sql->execute(
					array(
					':id' => $id,
					':type' => $type,
					':section' => $section,
					':user_id' => intval($mobile),
					':date_time' => time())
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
		}
		
		function addS($val) {
			if ($val > 1) {
				return "s";
			}
		}
		
		function highlight($word, $status) {
			if ($status == 0) {
				return '<strong>'.$word.'</strong>';
			} else {
				return $word;
			}
		}
		
		function sendContact($array) {
			$name = $this->get_prep($array['name']);
			$reason = $this->get_prep($array['reason']);
			$email = $this->get_prep($array['email']);
			$message = $this->get_prep($array['message']);

			if (in_array($name, $this->exclude)) {
				return false;
			}
			
			$messageToClient = "<p>You got the following message from<br />";
			$messageToClient .= "Name: <strong>".$name."</strong><br>";
			$messageToClient .= "Email: <strong>".$email."</strong><br>";
			$messageToClient .= "Time: <strong>".date('l jS \of F Y h:i:s A')."</strong><br>";
			$messageToClient .= "Subject: <strong>".$reason."</strong><br>";
			$messageToClient .= "Message: <strong>".$message."</strong>";
			$messageToClient .= "</p>";
			
			$subjectToClient = "[Contact Form] RE: ".$reason;
			$contact = $name." <".replyMail.">";
			
			
			$mail['from'] = $contact;
			$mail['subject'] = $subjectToClient;
			$mail['body'] = $messageToClient;
			

			$alerts = new alerts;
			$mail['to'] = "meet abims <meetabims@yahoo.com>";
			$alerts->sendEmail($mail);
			$mail['to'] = "Babatope Davies <babatopedavies@gmail.com>";
			$alerts->sendEmail($mail);
			$mail['to'] = "Babatope Davies <babatope.davies@legallens.com.ng>";
			$alerts->sendEmail($mail);
			$mail['to'] = "Babatope Davies <olukayode.adebiyi@linnkstec.com>";
			$alerts->sendEmail($mail);
			$mail['to'] = "info@legallens.com.ng";
			$alerts->sendEmail($mail);
		}
		
		function http2https() {
			//If the HTTPS is not found to be "on"
			if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on") {
				//Tell the browser to redirect to the HTTPS URL.
				header("HTTP/1.1 301 Moved Permanently"); 
				header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
				//Prevent the rest of the script from executing.
				exit;
			}
		}

		function getKey($seckey){
			$hashedkey = md5($seckey);
			$hashedkeylast12 = substr($hashedkey, -12);

			$seckeyadjusted = str_replace("FLWSECK-", "", $seckey);
			$seckeyadjustedfirst12 = substr($seckeyadjusted, 0, 12);

			$encryptionkey = $seckeyadjustedfirst12.$hashedkeylast12;
			return $encryptionkey;
		}

		function encrypt3Des($data, $key) {
			$encData = openssl_encrypt($data, 'DES-EDE3', $key, OPENSSL_RAW_DATA);
			return base64_encode($encData);
		}
	}
?>