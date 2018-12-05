<?php
	include_once("../functions.php");
	$name = $common->get_prep($_REQUEST['name']);		
	$username = $common->get_prep($_REQUEST['username']);
	$password = $common->get_prep($_REQUEST['password']);
	$store_id = $common->get_prep($_REQUEST['store_id']);
	$store_name = $common->get_prep($_REQUEST['store_name']);
?>
<html>
<head>
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
	color: #FFFFFF;
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
    <td bgcolor="#009999" > 
    <div class="logoThumb"><img src="<?php echo URL; ?>images/logo.png" width="75">
   	  </div>
      
     </td>
  </tr>

  <tr>
    <td>
    <p class="title2">Hello <?php echo $name; ?>, </p>
    <p class="text">Your store <strong><?php echo $store_name; ?></strong> was recently created on the <strong>LegalLens.com.ng</strong></p>
    <p class="text">Username: <?php echo $username; ?><br>
    Password: <?php echo $password; ?><br>
    Administrator URL: <?php echo URLClients; ?><br>
    Client Name: <?php echo $store_name; ?><br>
    Client ID: <?php echo $store_id; ?></p>
      <p>
  <span class="text">Regards,</span><br>
  <span class="text">LegalLens.com.ng</span><span class="text"></span></p>
      <p class="text">This email is intended for <?php echo $store_name; ?>, please do not reply directly to this email. This email was sent from a notification-only address that cannot accept incoming email.</p>
<p class="text"><strong>Protect Your Password</strong><br>
Be alert to emails that request account information or urgent action.  Be cautious of websites with irregular addresses or those that offer   unofficial payments to LegalLens Administrator or other privates accounts.<br>
</p></td>
  </tr>
  <tr>
    <td bgcolor="#009999">&copy; <?php echo date("Y"); ?> LegalLens.com.ng All Rights Reserved</td>
  </tr>
</table>

<div class="header">
</div>
</body>
</html>