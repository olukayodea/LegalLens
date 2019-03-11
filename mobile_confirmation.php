<?php
	$redirect = "mobile_confirmation";
	include_once("includes/functions.php");
	if (isset($_REQUEST['id'])) {
		$id = $common->mysql_prep($_REQUEST['id']);
    $token = $common->mysql_prep($_REQUEST['token']);
    
	} else {
		header("location: mobilehome?error=".urlencode("There was an error processing your order, please try again"));
	}
	
	$data = $orders->getOne($id);
  $userData = $users->listOne($data['order_owner']);

  $subscription = trim($userData['subscription']);
  $subscription_type = trim($userData['subscription_type']);
  $subscription_group = trim($userData['subscription_group']);
  if ($userData['payment_frequency'] == "Single") {
    $sub_Renw = "No Auto-renew";
    $sub_type = "Not Applicable";
  } else {
    $expiryDate = $subscription-(60*60*24*3);
    $sub_Renw = "Automatic Renewal";
    $sub_type = "Auto renew on ".date('l jS \of F Y h:i:s A', $expiryDate);
  }
  $subscription_group_onwer = trim($userData['subscription_group_onwer']);

	$transaction_data = $transactions->getOne($id, "order_id");
    $urlData = json_decode(base64_decode($token), true);
	$sub_list = $users->sortAll($ref, "subscription_group_onwer", "subscription_group", 1);
?>

<!doctype html>
        <!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en-US"> <![endif]-->
        <!--[if IE 7]>    <html class="lt-ie9 lt-ie8" lang="en-US"> <![endif]-->
        <!--[if IE 8]>    <html class="lt-ie9" lang="en-US"> <![endif]-->
        <!--[if gt IE 8]><!--> <html lang="en-US"> <!--<![endif]-->
<base href="<?php echo URL; ?>" />

<head>
    <meta charset="utf-8">
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-4142286148495329",
    enable_page_level_ads: true
  });
</script>
    <!-- META TAGS -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $data['title']; ?></title>
<?php $pages->head(); ?>
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="js/html5.js"></script>
    <![endif]-->
			<style>
            /* Cosmetic only */
            .easyPaginateNav a {padding:5px;}
            .easyPaginateNav a.current {font-weight:bold;text-decoration:underline;}
            </style>

        <?php $pages->chatHeader(); ?>
</head>

<body>

<!-- Start of Page Container -->
<div>
<div>
<article class="type-page hentry clearfix">
    <h2 class="post-title">
            Manage Subscription
    </h2>
    <hr>
    <?php if (isset($_GET['error'])) { ?>
    <p class="error">Y<?php echo $_GET['error']; ?></p>
    <?php } ?>
    <p>Hi <?php echo $last_name." ".$other_names; ?>, please review your current subscription data below</p>
    <?php if (isset($_GET['renew'])) { ?>
    <p class="error">You were redirected here because either your subscription has expired or you are yet to purchase a valid subscription</p>
    <?php } ?>
        
</article>
<div>
   <div style="border:1px solid #ccc; padding:10px">
     <div style="margin-top:30px">
     <?php if ($urlData['ResponseCode'] == "00") { ?>
    <p>Thank you for your order. If you have any questions about your order please contact us at <a href="mailto:<?php echo emailData; ?>"><?php echo emailData; ?></a> or call us at <?php echo phoneData; ?> 24 Hours, 7 Days a week.      
    <p>Your order confirmation is shown below.<br>
    Your Order #<?php echo $orders->orderID($id); ?> (placed on <?php echo date('l jS \of F Y h:i:s A', $data['create_time']); ?>) </p>
    <?php } else { ?>
    <p style="color:#F00"><strong>Thank you for order, unfortunately we were not able to process your order at this time. Please see below for detailed description concerning this order</strong></p>
    <p>Transaction Reference: <strong><?php echo $transaction_data['transaction_id']; ?></strong><br>
    Transaction Time: <strong><?php echo $urlData['TransactionDate']; ?></strong><br />
    Transaction Amount: <strong><?php echo NGN.number_format($transaction_data['amount'], 2); ?></strong><br>
    Transaction Status Description: <strong><?php echo $urlData['ResponseDescription']; ?></strong></p>
        <p><a href="<?php echo URL; ?>mobile_preview?others&id=<?php echo $transaction_data['ref']; ?>" onClick="return confirm('You have opted to pay for this order via other channels, do you wish to continue?')">Cick here to select other payment method</a></p>
    <p><a href="<?php echo URL; ?>redirect?mobile&retry&id=<?php echo $transaction_data['ref']; ?>">Cick here to retry online payment</a></p>
    <?php } ?>
    <?php if (($data['payment_type'] != "Online") && ($urlData['Amount'] > 0)) { ?>
    <p><strong><p>To make payments online through your card, please choose online payments.</p>
                    <p>Alternatively, you can complete your subscription by making payment to our account provided below and contacting Legallens officials and send the name of payer and transaction referenced to us through our live chat to activate your subscription.</p>
                    <p>Account details:<br>
                    Name:&nbsp;&nbsp;<strong>Pearlhouse Legal Lens Limited</strong><br>
                    Bank:&nbsp;&nbsp;<strong>United Bank For Africa</strong><br>
                Number:&nbsp;&nbsp;<strong>1019666896</strong></p></strong></p>
    <?php } ?>
    
    <?php if (($data['order_status'] == "COMPLETE") && ($urlData['Amount'] > 0) ) { ?>
    <p><strong>Payments has been confirmed for this order. <?php if ($urlData['isRenew'] == true) { ?>This transaction will automatically renew on <?php echo date('l jS \of F Y h:i:s A', $userData['subscription']); } ?> </strong></p>
    <p>Transaction Time: <strong><?php echo $urlData['TransactionDate']; ?></strong><br>
    Amount Charged: <strong><?php echo NGN.number_format($data['order_amount_net'], 2); ?></strong><br />
    <?php if ($urlData['isRenew'] == true) { ?>
    Amount Processed at Gateway: <strong><?php echo NGN.number_format($urlData['Amount'], 2); ?></strong><br />
    <?php } else { ?>
    Amount Processed at Gateway: <strong><?php echo NGN.number_format($urlData['Amount']/100, 2); ?></strong><br />
    <?php } ?>
    Transaction Reference: <strong><?php echo $urlData['MerchantReference']; ?></strong><br />
    Payment Gateway Reference: <strong><?php echo $urlData['PaymentReference']; ?></strong><br />
    Card Number: <strong>**** **** **** <?php echo $urlData['CardNumber']; ?></strong><br />
    Payment Status: <strong><?php echo $data['payment_status']; ?></strong><br />
    Transaction Status Description: <strong><?php echo $urlData['ResponseDescription']; ?></strong></p>
    <?php } else if ($urlData['Amount'] < 1) { ?>
        <p><strong>Pyment is not needed for this order</strong></p>
    <?php } else { ?>
    <p><strong>Pyment has not been confirmed for this order</strong></p>
    <?php } ?>
        <table width=300 class="table table-hover" align=center>
            <thead>
                <tr>
            <th colspan=3>Personal Details</th>
            
                </tr>
            </thead>
            <tbody>
                <tr>
            <td width="25%">Surname</td>
            <td><?php echo $last_name; ?></td>
            
                </tr>
                <tr>
            <td width="25%">Firstname</td>
            <td><?php echo $other_names; ?></td>
            
                </tr>
                <tr>
            <td width="25%">Email address</td>
            <td><?php echo $email; ?></td>
            
                </tr>
                <tr>
            <td width="25%">Mobile number</td>
            <td><?php echo $phone; ?></td>
            
                </tr>
            </tbody>
            </table>				
        <table width=300 class="table table-hover" align=center>
            <thead>
                <tr>
            <th colspan=3>Subscription</th>
            
                </tr>
            </thead>
            <tbody>
                <tr>
            <td width="25%">Current Subscription</td>
            <td><?php echo $subscriptions->getOneField($subscription_type)." (".$sub_Renw.")"; ?></td>
            
                </tr>
                <tr>
            <td width="25%">Subscription Type</td>
            <td><?php echo $subscriptions->getOneField($subscription_type, "ref", "type"); ?></td>
            
                </tr>
                <tr>
            <td width="25%">Expiry Date</td>
            <td><?php echo date('l jS \of F Y h:i:s A', $subscription)." (".$sub_type.")"; ?></td>
            
                </tr>
            <?php if ($userData['payment_frequency'] == "Renew") { ?>
                <tr>
            <td width="25%">Auto Rewew</td>
            <td><?php echo $sub_type; ?>
            <br>
            <a href="mobile_subscription?cancel" onClick="return confirm('Your subscription will not <?php echo $sub_type; ?> after this action. are you sure you want to continue ?')"> cancel auto renew</a></td>
            
                </tr>
            <?php } ?>
                <?php if (($subscription_group_onwer == $ref) && ($subscription_group == 1)) { ?>
                <tr>
            <td width="25%">Subscription Users</td>
            <td><?php echo number_format(count($sub_list)); ?> [ <a href="<?php echo URL; ?>mobile_subscription_users">Manage Users</a> ]</td>
            
                </tr>
                <?php } ?>
            </tbody>
            </table>
        </div>
	 </div>

   </div>
</div>
                  </div> <!--end row -->      
			</div><!-- end container-->
                </div>
                <!-- End of Page Container -->

                <a href="#top" id="scroll-top"></a>
                <!-- script -->
               <!-- <script type='text/javascript' src='js/jquery-1.8.3.min.js'></script> -->
			   	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
                <script type='text/javascript' src='js/jquery.easing.1.34e44.js?ver=1.3'></script>
                <script type='text/javascript' src='js/prettyphoto/jquery.prettyPhotoaeb9.js?ver=3.1.4'></script>
                <script type='text/javascript' src='js/jquery.liveSearchd5f7.js?ver=2.0'></script>
				<script type='text/javascript' src='js/jflickrfeed.js'></script>
                <script type='text/javascript' src='js/jquery.formd471.js?ver=3.18'></script>
                <script type='text/javascript' src='js/jquery.validate.minfc6b.js?ver=1.10.0'></script>
                <script type='text/javascript' src="js/jquery-twitterFetcher.js"></script>
                <script type='text/javascript' src='js/custom5152.js?ver=1.0'></script>
                <script type='text/javascript' src='js/frontEnd.js'></script>
				<script type='text/javascript' src="js/navAccordion.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="js/pagination.js"></script>
                
                
    <script>
        $(function() {
			$('#easyPaginate').easyPaginate({
				paginateElement: 'p',
				elementsPerPage: 20,
				effect: 'climb'
			});
        });
    </script>

        </body>


</html>