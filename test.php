<?php
	$product_key = "29ED0C4C59E8E7315";
	$product_id = 6224;
	$key = $product_key+$product_id;
	$hash = hash("sha256", $key);
	$u = "http://127.0.0.1/legallens/";
	//$u = "http://legallens.com.ng/";
	
	/*//Login
	//JSON Example
	$array['product_key'] = $product_key;
	$array['product_id'] = $product_id;
	$array['app_id'] = "1234567890";
	$array['user']['email'] = "olukayode.adebiyi@linnkstec.com";
	$array['user']['password'] = "lolade";
	$array['user']['mobile'] = "12345678909999999999";
	$xml_data = json_encode($array);
	$type = "text/json";

	$URL = $u."api/".$hash."/users/login";*/

	/*//register
	//JSON Example
	$array['product_key'] = $product_key;
	$array['product_id'] = $product_id;
	$array['app_id'] = "1234567890";
	$array['user']['email'] = "olukayode.adebiyi@linnkstec.com";
	$array['user']['password'] = "lolade";
	$array['user']['last_name'] = "Adebiyi";
	$array['user']['other_names'] = "Olukayode";
	$array['user']['phone'] = "08182441752";
	$xml_data = json_encode($array);
	$type = "text/json";

	$URL = $u."api/".$hash."/users/register";*/
	
/*	//Change Password
	//JSON Example
	$array['product_key'] = $product_key;
	$array['product_id'] = $product_id;
	$array['user']['password'] = "lolade";
	$array['user']['ref'] = "1";
	$xml_data = json_encode($array);
	$type = "text/json";

	$URL = $u."api/".$hash."/users/changePassword";  */

/*	//Reset Password
	//JSON Example
	$array['product_key'] = $product_key;
	$array['product_id'] = $product_id;
	$xml_data = json_encode($array);
	$type = "text/json";

	$URL = $u."api/".$hash."/users/passwordReset/olukayode.adebiyi@gmail.com";  */

	//logout
	//JSON Example
	$array['product_key'] = $product_key;
	$array['product_id'] = $product_id;
	$xml_data = json_encode($array);
	$type = "text/json";

	$URL = $u."api/".$hash."/category"; 
	
/*	//update
	//JSON Example
	$array['product_key'] = $product_key;
	$array['product_id'] = $product_id;
	$array['mobile'] = "1234567890";
	$array['user']['ref'] = 1;
	$array['user']['last_name'] = "Adebiyi";
	$array['user']['other_names'] = "Olukayode";
	$array['user']['email'] = "olukayode.adebiyi@hotmail.co.uk";
	$array['user']['password'] = "lolade";
	$array['user']['phone'] = "08182441752";
	$array['user']['address'] = "some address";
	$xml_data = json_encode($array);

	$URL = $u."api/".$hash."/users/updatedetails";*/
	
	
	/*//get details
	//JSON Example
	$array['product_key'] = $product_key;
	$array['product_id'] = $product_id;
	$array['mobile'] = "1234567890";
	$array['user'] = 1;
	$xml_data = json_encode($array);

	$URL = $u."api/".$hash."/users/getDetails";*/
	
		//quick find
	/*//JSON Example
	$array['product_key'] = $product_key;
	$array['product_id'] = $product_id;
	$array['mobile'] = "1234567890";
	$xml_data = json_encode($array);

	$URL = $u."api/".$hash."/quickfind/getParameters";*/
	
	/*//quick find
	//JSON Example
	$array['product_key'] = $product_key;
	$array['product_id'] = $product_id;
	$array['mobile'] = "1234567890";
	$array['query'] = "law";
	$array['data']['parameter'][] = 1;
	$array['data']['parameter'][] = 2;
	$array['data']['parameter'][] = 11;
	$array['data']['case_law'] = 1;
	$array['data']['reg_circular'] = 1;
	$array['data']['dic'] = 1;
	$array['data']['court_rules'] = 1;
	$array['data']['forum'] = 1;
	$xml_data = json_encode($array);

	$URL = $u."api/".$hash."/quickfind/search";*/
	
	
	$ch = curl_init($URL);
	curl_setopt($ch, CURLOPT_VERBOSE, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$output = curl_exec($ch);
	curl_close($ch);
	
	echo $output;
?>