<?php
	include_once("includes/functions.php");
	if ((isset($_REQUEST['redirect'])) && ($_REQUEST['redirect'] != "")) {
		$redirect = $_REQUEST['redirect'];
	} else {
		$redirect = "home";
	}
		
	$id = $common->get_prep($_GET['id']);
		
	if (isset($_REQUEST['anonymous'])) {
		$array['full_name'] = "Anonymous".time();
		$array['email'] = time()."@legalLens.com.ng";
		$add = $forum_login->login($array);
		
		$close = true;
	} else if (isset($_POST['submit'])) {
		$add = $forum_login->login($_POST);
		
		$close = true;
	} else {
		$close = false;
	}
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>LegalLens Login Helper</title>
<script language="javascript">
function closeWin() {
	opener.location.href = '<?php echo URL; ?>?redirect=<?php echo $redirect; ?>&id=<?php echo $id; ?>';
	close();
}
function closeWin2() {
	opener.location.href = '<?php echo URL.$redirect; ?>?id=<?php echo $id; ?>';
	close();
}
</script>
</head>
<?php if ($close == true) { ?>
<body onLoad="closeWin2()">
<?php } else { ?>
<body>
<?php } ?>
<p><a href="Javascript:void(0)" onClick="closeWin()">Click Here to login with LegalLens ID</a></p>
<p>Login to Forum</p>
<form name="form1" method="post" action="">
  <table width="100%" border="0">
    <tr>
      <td width="20%">Nickname</td>
      <td><input type="text" name="full_name" id="full_name"></td>
    </tr>
    <tr>
      <td width="20%">Email</td>
      <td><input type="text" name="email" id="email"></td>
    </tr>
    <tr>
      <td width="20%">&nbsp;</td>
      <td><button type="submit" name="submit">Login As User</button>
      <button type="button" onClick="location='?anonymous&redirect=<?php echo $redirect; ?>&id=<?php echo $id; ?>'" name="submit_anonymus">Login As Anonymous</button>
      <button type="button" onClick="window.close()">Loss Window</button></td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
</body>
</html>