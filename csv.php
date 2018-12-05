<?php
	include("includes/functions.php");
	$tag = time();
	unset($_SESSION['error']);
	if(isset($_POST['submit2'])) {
		$sub_total = $common->mysql_prep($_POST['sub_total']);
		$sub_list = $common->mysql_prep($_POST['sub_list']);
		$subscription_type = $common->mysql_prep($_POST['subscription_type']);
		$subscription_group = $common->mysql_prep($_POST['subscription_group']);
		$subscription_group_onwer = $common->mysql_prep($_POST['subscription_group_onwer']);
		$subscription = $common->mysql_prep($_POST['subscription']);
		$remaining = $sub_total-$sub_list;
		if ($_FILES["filename"]["error"] > 0) {
			if ($_FILES["filename"]["error"] == 1) {
			} else if (($_FILES["filename"]["error"] == 1) || ($_FILES["filename"]["error"] == 2)) {
				$uploadError = "The uploaded file exceeds the mazimum upload file limit";
			} else if ($_FILES["filename"]["error"] == 3) {
				$uploadError = "The uploaded file was only partially uploaded, please re-upload file";
			} else if ($_FILES["filename"]["error"] == 4) {
				$uploadError = "No file was uploaded";
			} else if ($_FILES["filename"]["error"] == 6) {
				$uploadError = "Missing a temporary folder, contact the website administrator";
			} else if ($_FILES["filename"]["error"] == 7) {
				$uploadError = "Failed to write file to disk, contact the administrator";
			}
			$link = "managesubscriptionusers?error=".$uploadError;
			header("location: $link");
		} else {
			$filename = stripslashes($_FILES['filename']['name']);
			$uploadedfile = $_FILES['filename']['tmp_name'];
			$extension = $common->getExtension($filename);
			$extension = strtolower($extension);
			if ($extension != "csv") {
				$er = "File not in CSV format. Please use the format you downloaded from this page";
				$link = "managesubscriptionusers?error=".$er;
				header("location: $link");
			} else {
				$handle = fopen($uploadedfile, "r"); 
				$c = 1;
				$j = 0;
				$errorList = array();
				$error = 0;
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)  {
					if ($c > 1) {
						if (($data[1] == "") && ($data[2] == "") && ($data[3] == "")) {
							$errorList[] = "Error on line ".$c.": This line is empty";
							$error = $error + 1;
						}
						if ($data[1] == "") {
							$errorList[] = "Error on line ".$c.": The last name field is empty";
							$error = $error + 1;
						}
						if ($data[2] == "") {
							$errorList[] = "Error on line ".$c.": The other names field is empty";
							$error = $error + 1;
						}
						if ($data[3] == "") {
							$errorList[] = "Error on line ".$c.": The email field is empty";
							$error = $error + 1;
						}
						if (!filter_var($data[3], FILTER_VALIDATE_EMAIL)) {
							$errorList[] = "Error on line ".$c.": The email address is invalid";
							$error = $error + 1;
						}
						if ($users->checkAccount($data[3]) != 0) {
							$errorList[] = "Error on line ".$c.": This user is already registered on the system";
							$error = $error + 1;
						}
					}
					$c++; 
				}
				if (($c-2) > $remaining) {
					$er = "The file contains more enteries than allowed. You can only upload ".$remaining." users";
					$link = "managesubscriptionusers?error=".$er;
					header("location: $link");
				} else if ($c == 2) {
					$er = "The file is empty";
					$link = "managesubscriptionusers?error=".$er;
					header("location: $link");
				} else if ($error > 0) {
					fclose($handle); 
					$_SESSION['error'] = urlencode(serialize($errorList));
					header("location: managesubscriptionusers?error_upload");
				} else {
					$handle = fopen("$uploadedfile", "r"); 
					$count = 1;
					$rem = 0;
					$array = array();
					while (($input = fgetcsv($handle, 1000, ",")) !== FALSE)  {
						if (($count > 1) && ($count <= 1000)) {
							
							$array['last_name'] = $input[1];
							$array['other_names'] = $input[2];
							$array['email'] = $input[3];
							$array['subscription_type'] = $subscription_type;
							$array['subscription_group'] = $subscription_group;
							$array['subscription_group_onwer'] = $subscription_group_onwer;
							$array['subscription'] = $subscription;
							$array['sub_total'] = $sub_total;
							$array['sub_list'] = $sub_list;
							$users->createGroup($array);
							$rem++;
						}
						$count ++;
					}
					fclose($handle); 
					
					header("location: managesubscriptionusers?done");
				}
			}
		}
	}
?>  