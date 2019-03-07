<?php
    include_once("../functions.php");
    include_once("..//scripts/qrcode.php");
    $id = $common->get_prep($_REQUEST['ref']);
    $last_name = $common->get_prep($_REQUEST['last_name']);
    $other_names = $common->get_prep($_REQUEST['other_names']);
    $email = $common->get_prep($_REQUEST['email']);
    $userData = $users->listOne($id);

    $subscription = trim($userData['subscription']);
    $subscription_type = trim($userData['subscription_type']);
    $subscription_group = trim($userData['subscription_group']);
?>
<html>
<head>
    <meta charset="utf-8">
<style type="text/css">
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
</style>
<title><?php echo $common->get_prep($_REQUEST['subject']); ?></title>
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="5">

  <tr>
    <td>
    <p class="title2">Hello <?php echo $last_name; ?>, </p>
    <p class="text">The following modification has occured on your account</p>
    <p class="text">Your auto renewal instruction for the subscription listed below has been revoked, this subscription will now expire on <?php echo date('l jS \of F Y h:i:s A', $subscription); ?></p>
    <p class="text">
    Current Subscription
    <strong><?php echo $subscriptions->getOneField($subscription_type); ?></strong>
    Subscription Type
    <strong><?php echo $subscriptions->getOneField($subscription_type, "ref", "type"); ?></strong></p>\
    <p class="text">All saved authorizartion details has been deleted and you will need to provide your credit card details when next you wnat to activate the auto renew function. If you did not authorize this change, please contact support immediately</p>
    <p>
      <span class="text">Regards,</span><br>
  <span class="text">LegalLens.com.ng</span><span class="text"></span></p>
      <p class="text">This email is intended for <?php echo $last_name." ".$other_names; ?>, please do not reply directly to this email. This email was sent from a notification-only address that cannot accept incoming email.</p>
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