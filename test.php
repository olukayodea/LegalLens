<?php
	$product_key = "29ED0C4C59E8E7315";
	$product_id = 6224;
	$key = $product_key+$product_id;
	$hash = hash("sha256", $key);
	//$u = "http://127.0.0.1/legallens/";
	$u = "http://legallens.com.ng/";
	
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
	
	
	/*//post reward
	//JSON Example
	$array['product_key'] = $product_key;
	$array['product_id'] = $product_id;
	$array['user_id'] = "2";
	$array['amount'] = "100";
	$array['number'] = "08182441752";
	$array['productCode'] = "ETST";
	$array['type'] = "payout";
	$xml_data = json_encode($array);
	
	//XML example
	$xml_data = '<?xml version="1.0" encoding="utf-8"?>
	<skrinAd>
	<product_key>'.$product_key.'</product_key>
	<product_id>'.$product_id.'</product_id>
	<user_id>2</user_id>
	<amount>50</amount>
	<number>08182441752</number>
	<productCode>ETST</productCode>
	<type>payout</type>
	</skrinAd>';

	$URL = $u."api/".$hash."/xml/reward/postrequest";*/
	
	
	/*//update
	//JSON Example
	$array['product_key'] = $product_key;
	$array['product_id'] = $product_id;
	$array['advert'][0]['id'] = 1;
	$array['advert'][0]['user_id'] = 1;
	$array['advert'][0]['impression'] = 1;
	$array['advert'][0]['click'] = 1;
	$array['advert'][0]['impression_time'] = time();
	$array['advert'][0]['click'] = 1;
	$array['advert'][0]['click_time'] = time();
	$array['advert'][1]['id'] = 1;
	$array['advert'][1]['user_id'] = 1;
	$array['advert'][1]['impression'] = 1;
	$array['advert'][1]['impression_time'] = time();
	$array['advert'][1]['click'] = 1;
	$array['advert'][1]['click_time'] = time();
	$array['advert'][2]['id'] = 1;
	$array['advert'][2]['user_id'] = 1;
	$array['advert'][2]['impression'] = 1;
	$array['advert'][2]['impression_time'] = time();
	$array['advert'][2]['click'] = 0;
	$xml_data = json_encode($array);
	
	//XML example
	$xml_data = '<?xml version="1.0" encoding="utf-8"?>
	<skrinAd>
	<product_key>'.$product_key.'</product_key>
	<product_id>'.$product_id.'</product_id>
	<advert>
		<id>3</id>
		<user_id>1</user_id>
		<impression>1</impression>
		<impression_time>'.time().'</impression_time>>
		<click>1</click>>
		<click_time>'.time().'</click_time>>
	</advert>
	<advert>
		<id>3</id>
		<user_id>1</user_id>
		<impression>1</impression>
		<impression_time>'.time().'</impression_time>>
		<click>1</click>>
		<click_time>'.time().'</click_time>>
	</advert>
	<advert>
		<id>4</id>
		<user_id>1</user_id>
		<impression>1</impression>
		<impression_time>'.time().'</impression_time>>
		<click>0</click>>
	</advert>
	<advert>
		<id>3</id>
		<user_id>1</user_id>
		<impression>1</impression>
		<impression_time>'.time().'</impression_time>>
		<click>1</click>>
		<click_time>'.time().'</click_time>>
	</advert>
	<advert>
		<id>4</id>
		<user_id>1</user_id>
		<impression>1</impression>
		<impression_time>'.time().'</impression_time>>
		<click>0</click>>
	</advert>
	<advert>
		<id>3</id>
		<user_id>1</user_id>
		<impression>1</impression>
		<impression_time>'.time().'</impression_time>>
		<click>1</click>>
		<click_time>'.time().'</click_time>>
	</advert>
	<advert>
		<id>4</id>
		<user_id>1</user_id>
		<impression>1</impression>
		<impression_time>'.time().'</impression_time>>
		<click>0</click>>
	</advert>
	<advert>
		<id>4</id>
		<user_id>1</user_id>
		<impression>1</impression>
		<impression_time>'.time().'</impression_time>>
		<click>0</click>>
	</advert>
	<advert>
		<id>3</id>
		<user_id>1</user_id>
		<impression>1</impression>
		<impression_time>'.time().'</impression_time>>
		<click>0</click>>
	</advert>
	<advert>
		<id>4</id>
		<user_id>1</user_id>
		<impression>1</impression>
		<impression_time>'.time().'</impression_time>>
		<click>0</click>>
	</advert>
	</skrinAd>';

	$URL = $u."api/".$hash."/xml/advert/update";*/
	
	//JSON Example
	/*$array['product_key'] = $product_key;
	$array['product_id'] = $product_id;
	$xml_data = json_encode($array);*/
	
	/*//XML example
	$xml_data = '<?xml version="1.0" encoding="utf-8"?>
	<skrinAd>
	<product_key>'.$product_key.'</product_key>
	<product_id>'.$product_id.'</product_id>
	</skrinAd>';*/
	
	//$URL = $u."api/".$hash."/xml/list/category";
	//$URL = $u."api/".$hash."/xml/list/ageRange";
	//$URL = $u."api/".$hash."/xml/list/gender";
	//$URL = $u."api/".$hash."/xml/list/state";
	//$URL = $u."api/".$hash."/xml/list/banks";
	//$URL = $u."api/".$hash."/json/list/category";
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