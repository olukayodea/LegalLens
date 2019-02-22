<?php
	include_once("includes/functions.php");
	
	if ((isset($_REQUEST['redirect'])) && ($_REQUEST['redirect'] != "")) {
		$redirect = $_REQUEST['redirect'];
	} else {
		$redirect = "index";
	}
		
	$urlParam = $common->getParam($_SERVER['REQUEST_URI']);
	
	if (isset($_REQUEST['msg'])) {
		$er = $_REQUEST['msg'];
	}
	
	$tagLink = $redirect."?".$urlParam;
	if (isset($_GET['logout'])) {
		$users->logout();
		header("location: ./");
	}
	if (isset($_POST['submit'])) {
		$login = $users->login($_POST);
		
		if ($login == 0) {
			$er = "Incorect username and password combination";
		} else if ($login == 1) {
			header("location: login.php?confirm"."&".$urlParam);
		} else if ($login == 2) {
			header("location: ".$tagLink);
		} else if ($login == 3) {
		} else if ($login == 10) {
			$er = "you are signed in on up to 3 devices, please logout from one device to continue";
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
<meta charset="UTF-8">
<title>Untitled Document</title>
</head>
<body>
<p style="color:#F00"><?php echo $er; ?></p>
<form id="form1" name="form1" method="post" action="">
  <table width="100%" border="0">
    <tr>
      <td width="20%">Email</td>
      <td><input type="text" name="email" id="email"></td>
    </tr>
    <tr>
      <td width="20%">Password</td>
      <td><input type="password" name="password" id="password"></td>
    </tr>
    <tr>
      <td width="20%">&nbsp;</td>
      <td><input type="submit" name="submit" id="submit" value="Submit"></td>
    </tr>
  </table>
  
</form>
<?php if (isset($_SESSION['users']['ref'])) { ?>
<a href="login.php?logout">logout</a>
<?php } ?>
</body>
</html>