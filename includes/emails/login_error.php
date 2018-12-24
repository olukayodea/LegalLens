<?php
	include_once("../functions.php");
	$id = $common->get_prep($_REQUEST['id']);
	$data = $users->listOne($id);
	$userData = $usersControl->listUser("user", $id);
?>

<html>
<head>
    <meta charset="utf-8">
<style type="text/css">
<!--
.title {
	font-family: Arial, Helvetica, sans-serif;
	padding: 5px;
	font-weight:bold;
	color: #FFFFFF;
	font-size: 16px;
}

.header {
    background: none repeat scroll 0% 0% #0E3F97;
    border-bottom: 3px solid #F0C237;
}

.title2 {
	font-family: Arial, Helvetica, sans-serif;
	padding: 5px;
	font-weight:bold;
	color: #000000;
	font-size: 14px;
}
.messege {
	font-family: Arial, Helvetica, sans-serif;
	padding: 5px;
	font-weight:bold;
	color: #000000;
	font-size: 12px;
}
.logoThumb{
	float:left;
	padding: 2px;
	margin: 3px;
	/*border: 1px solid #F0F0F0;*/
	text-align: center;
	vertical-align: middle;
}
.logoThumb img{border:0px}
body,td,th {
	font-family: tahoma;
	font-size: 11px;
	color: #000000;
}
.text {
	font-family: tahoma;
	font-size: 11px;
	color: #000000;
	padding: 5px;
}
-->
</style>
<title><?php echo $common->get_prep($_REQUEST['subject']); ?></title>
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td>
    <div class="logoThumb"><img src="<?php echo URL; ?>images/logo.png" width="250" alt=""></div>
      
     </td>
  </tr>

  <tr>
    <td><p class="text"><span class="title2">New Device Login Notification</span></p>

<p class="text">Hello <?php echo $data['last_name']; ?></p>

<p class="text">Our record shows that you are currently logged in to up to 3 devices. Please review the list of all your logged in devices. You will not be able to log inn to a 4th device until you log out of one of these devices</p>
<table width="100%" border="0">
<?php for ($i = 0; $i < count($userData); $i++) { ?>
  <tr>
    <td width="20%">Device</td>
    <td><?php echo $userData[$i]['os']." ".$userData[$i]['os_number'];; ?></td>
  </tr>
  <tr>
    <td width="20%">Browser</td>
    <td><?php echo $userData[$i]['browser_name']." ".$userData[$i]['browser_number']; ?></td>
  </tr>
<!--  <tr>
    <td>&nbsp;</td>
    <td><a href="<?php echo URL; ?>logout?ref=<?php echo $userData[$i]['ref']; ?>">Logout from device</a></td>
  </tr>-->
<?php } ?>
</table>

<p class="text">If this wasn't you, please click here to logout from this device and don't forget to change your password</p>
<p class="text">Thanks<br>
LegalLens Team</p>
<p class="text">This email is intended for <?php echo $common->get_prep($data['last_name']); ?> <?php echo $common->get_prep($data['other_names']); ?>, please do not reply directly to this email. This email was sent from a notification-only address that cannot accept incoming email. If you have questions or need assistance,please use the contact information above</p>
<p class="text"><strong>Protect Your Password</strong><br>
Be alert to emails that request account information or urgent action.  Be cautious of websites with irregular addresses or those that offer   unofficial payments to LegalLens Administrator or other privates accounts.<br>
</p></td>
  </tr>
  <tr>
    <td bgcolor="#009999">&copy; <?php echo date("Y"); ?> LegalLens, All Rights Reserved</td>
  </tr>
</table>

<div class="header">
</div>
</body>
</html>