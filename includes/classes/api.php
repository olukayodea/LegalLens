<?php
	class api extends common {		
		function prep($array, $raw_data) {
			$usersControl = new usersControl;
			// get all api url variables
			$data = explode("/", $array);
			$key = $data[0];
			$mode = strtolower($data[1]);
			$action = strtolower($data[2]);
			$string = strtolower($data[3]);
			
			//get additional data			
			$return = false;
			
			$app_data = json_decode($raw_data, true);
			$product_key = $app_data['product_key'];
			$product_id = $app_data['product_id'];
			$app_id = $app_data['mobile'];
			
			//authenticate user
				
			if ($this->authenticate($key, $product_key, $product_id)) {
				switch ($mode) {
					case "users":
						$users = new users;
						switch ($action) {
							case "login":
								$array_data = $app_data['user'];
								$login = $users->loginMobile($array_data);
								if ($login == 0) {
									$return['header']['status'] = "ERROR";
									$return['header']['code'] = "107";
									$return['header']['error'] = "Invalid login";
									//invalid login
								} else if ($login == 1) {
									$return['header']['status'] = "ERROR";
									$return['header']['code'] = "108";
									$return['header']['error'] = "login on three or more devices";
									//login on three or more devices
								} else {
									$return['header']['status'] = 'DONE';
									$return['header']['code'] = "200";
									$return['header']['completedTime'] = date('l jS \of F Y h:i:s A');
									$login['current_time'] = time();
									$return['body'] = $login;
								}
								break;
							case "register";
								$array_data = $app_data['user'];
								$register = $users->create($array_data);
								if ($register) {
									$return['header']['status'] = 'DONE';
									$return['header']['code'] = "200";
									$return['header']['completedTime'] = date('l jS \of F Y h:i:s A');
									$return['body'] = $register;
								} else {
									$return['header']['status'] = "ERROR";
									$return['header']['code'] = "107";
									$return['header']['error'] = "Invalid registration";
									//invalid registration
								}
								break;
							case "changepassword";
								$array_data = $app_data['user'];
								$register = $users->activate($array_data['password'], $array_data['ref']);
								if ($register) {
									$return['header']['status'] = 'DONE';
									$return['header']['code'] = "200";
									$return['header']['completedTime'] = date('l jS \of F Y h:i:s A');
								} else {
									$return['header']['status'] = "ERROR";
									$return['header']['code'] = "107";
									$return['header']['error'] = "Invalid activation";
									//invalid activation
								}
								break;
							case "passwordreset";
								$pass = $users->passwordReset($this->mysql_prep($string));
								if ($pass) {
									$return['header']['status'] = 'DONE';
									$return['header']['code'] = "200";
									$return['header']['completedTime'] = date('l jS \of F Y h:i:s A');
									$return['header']['message'] = "Please check ".$string." for details on how to create a new password";
								} else {
									$return['header']['status'] = "ERROR";
									$return['header']['code'] = "107";
									$return['header']['error'] = "we can not verify this email address, or the user with this email address does not exist on our system. If you believe this email address is correct, please contact the administrator";
									//invalid registration
								}
								break;
							case "logout":
								$array_data = $app_data['mobile'];
								$login = $users->logoutApp($array_data);
								$return['header']['status'] = 'DONE';
								$return['header']['code'] = "200";
								$return['header']['completedTime'] = date('l jS \of F Y h:i:s A');
								break;
							case "getdetails":
								if ($usersControl->checkLogin($app_id) == true) {
									$subscriptions = new subscriptions;
									$array_data['ref'] = $app_data['user'];
									$getDetails = $users->listOne($array_data['ref']);
									if ($getDetails) {
										$return['header']['status'] = 'DONE';
										$return['header']['code'] = "200";
										$return['header']['completedTime'] = date('l jS \of F Y h:i:s A');
										unset($getDetails['subscription_group']);
										unset($getDetails['subscription_group_onwer']);
										unset($getDetails['subscription_order']);
										unset($getDetails['subscription_type_name']);
										$getDetails['subscription_type_name'] = $subscriptions->getOneField($getDetails['subscription_type'], "ref", "title");
										$getDetails['subscription_url'] = URL."mobile_subscription?id=".$array_data['ref'];

										$getDetails['current_time'] = time();
										$return['body']['userData'] = $getDetails;
									} else {
										$return['header']['status'] = "ERROR";
										$return['header']['code'] = "116";
										$return['header']['error'] = "cannot get details";
									}
								} else {
									$return['header']['status'] = "ERROR";
									$return['header']['code'] = "100";
									$return['header']['error'] = "user not logged in";
								}
								break;
							case "updatedetails":
								if ($usersControl->checkLogin($app_id) == true) {
									$array_data = $app_data['user'];
									
									$register = $users->update($array_data);
									if ($register) {
										$return['header']['status'] = 'DONE';
										$return['header']['code'] = "200";
										$return['header']['completedTime'] = date('l jS \of F Y h:i:s A');
										$array_data['current_time'] = time();
										$return['body']['id'] = $array_data['ref'];
									} else {
										$return['header']['status'] = "ERROR";
										$return['header']['code'] = "110";
										$return['header']['error'] = "update not complete";
									}
								} else {
									$return['header']['status'] = "ERROR";
									$return['header']['code'] = "100";
									$return['header']['error'] = "user not logged in";
								}
								break;
						}
						break;
					case "subscription":
						break;
					case "quickfind":
						if ($usersControl->checkLogin($app_id) == true) {
							$categories = new categories;
							$list = $categories->sortAll(0, "parent_id", "status", "active");
							$array_data = $app_data['search'];
							switch ($action) {
								case "getparameters":
									for ($i = 0; $i < count($list); $i++) {
										$result["parameter"][$i]['id'] = $list[$i]['ref'];
										$result["parameter"][$i]['title'] = $list[$i]['title'];
									}
									$result['other_parameters'][] = "case_law";
									$result['other_parameters'][] = "reg_circular";
									$result['other_parameters'][] = "court_rules";
									$result['other_parameters'][] = "dic";
									$result['other_parameters'][] = "forum";
									
									$return['header']['status'] = 'DONE';
									$return['header']['code'] = "200";
									$return['header']['completedTime'] = date('l jS \of F Y h:i:s A');
									$return['body']['data'] = $result;
									break;
								case "search":
									$search = new search;
									$array_data = $app_data['data'];
									$result = $search->create($array_data, false);
									
									$return['header']['status'] = 'DONE';
									$return['header']['code'] = "200";
									$return['header']['completedTime'] = date('l jS \of F Y h:i:s A');
									$return['body']['data'] = $result;
									break;
							}
							break;
				
						} else {
							$return['header']['status'] = "ERROR";
							$return['header']['code'] = "100";
							$return['header']['error'] = "user not logged in";
						}
						break;
					case "category":
						$categories = new categories;
						$list_array = array();
						$list_array['parent'] = 0;
						$list_array['title'] = "Quick Find";
						$list_array['url'] = URL."mobilehome";
						$list_array['child'] = "";
						$result[] = $list_array;
						$list = $categories->sortAll("0", "parent_id", "status", "active");
						for ($i = 0; $i < count($list); $i++) {
							$list_array['parent'] = 0;
							$list_array['title'] = $list[$i]['title'];
							$list_array['url'] = URL."mobile_document_home?sort=".$list[$i]['ref'];

							$subList = $categories->sortAll($list[$i]['ref'], "parent_id", "status", "active");
							for ($j = 0; $j < count($subList); $j++) {
								$list_array['child'][$j]['parent'] = $subList[$j]['ref'];
								$list_array['child'][$j]['title'] = $subList[$j]['title'];
								$list_array['child'][$j]['url'] = URL."mobile_document?sort=".$subList[$j]['ref'];
							
							}
							
							$result[] = $list_array;
						}
						$list_array['parent'] = 0;
						$list_array['title'] = "Case law";
						$list_array['url'] = URL."mobile_caseLaw";
						$list_array['child'] = "";
						$result[] = $list_array;
						$list_array['parent'] = 0;
						$list_array['title'] = "Regulations /Circular";
						$list_array['url'] = URL."mobile_regulations";
						$list_array['child'] = "";
						$result[] = $list_array;
						$list_array['parent'] = 0;
						$list_array['title'] = "Clauses";
						$list_array['url'] = URL."mobile_clause";
						$list_array['child'] = "";
						$result[] = $list_array;
						$list_array['parent'] = 0;
						$list_array['title'] = "Agreements";
						$list_array['url'] = URL."mobile_agreements";
						$list_array['child'] = "";
						$result[] = $list_array;
						$list_array['parent'] = 0;
						$list_array['title'] = "Forms";
						$list_array['url'] = URL."mobile_forms";
						$list_array['child'] = "";
						$result[] = $list_array;
						$list_array['parent'] = 0;
						$list_array['title'] = "Dictionary";
						$list_array['url'] = URL."mobile_dictionary";
						$list_array['child'] = "";
						$result[] = $list_array;
						
						$return['header']['status'] = 'DONE';
						$return['header']['code'] = "200";
						$return['header']['completedTime'] = date('l jS \of F Y h:i:s A');
						$return['body']['data'] = $result;
					case "report":
					case "list":
				}
			} else {
				$return['header']['status'] = "ERROR";
				$return['header']['code'] = "101";
			}
			
			return $this->convert_to_json($return);
		}
		
		function authenticate($key, $hash, $product_id) {
			$keyHash = $hash+$product_id;
			$hash_key = hash("sha256", $keyHash);
			if ($hash_key == $key) {
				return true;
			} else {
				return false;
			}
		}
		
		function convert_to_json($data) {
			header('Content-type: application/json');
			echo json_encode($data);
		}
	}
?>