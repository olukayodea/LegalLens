<?php
	$redirect = "mobile_subscription";
    include_once("includes/functions.php");
    include_once("includes/mobile_session.php");

  $userData = $users->listOne($ref);
  if (isset($_REQUEST['cancel'])) {
    if ($userData['payment_frequency'] == "Renew") {
      if ($users->modifyOne("payment_frequency", "Single", $ref)) {
            $client = $last_name." ".$other_names." <".$email.">";
            $subjectToClient = "Modification to your LegalLens Account";
            
            $contact = "LegalLens <".replyMail.">";
                
            $fields = 'subject='.urlencode($subjectToClient).
                '&ref='.urlencode($ref).
                '&last_name='.urlencode($last_name).
                '&other_names='.urlencode($other_names).
                '&email='.urlencode($email);
            $mailUrl = URL."includes/emails/account_subscription.php?".$fields;
            $messageToClient = $common->curl_file_get_contents($mailUrl);
            
            $mail['from'] = $contact;
            $mail['to'] = $client;
            $mail['subject'] = $subjectToClient;
            $mail['body'] = $messageToClient;
            
            $alerts = new alerts;
          $alerts->sendEmail($mail);
          $users->modifyOne("card_token", "", $ref);

          header("location: ?done");
      } else {
        header("location: ?error=".urlencode("an error occured"));
      }
    }
    header("location: ?done");
	}

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
    <?php if (isset($_GET['done'])) { ?>
    <p class="success">Action Completed Successfully</p>
    <?php } if (isset($_GET['error'])) { ?>
    <p class="error"><?php echo $_GET['error']; ?></p>
    <?php } ?>
    <p>Hi <?php echo $last_name." ".$other_names; ?>, please review your current subscription data below</p>
    <?php if (isset($_GET['renew'])) { ?>
    <p class="error">You were redirected here because either your subscription has expired or you are yet to purchase a valid subscription</p>
    <?php } ?>
    <?php if ((time() > $subscription) && (!isset($_GET['renew']))) { ?>	
    <p class="error">Your subscription expired <?php echo $common->get_time_stamp($subscription); ?>. Please renew your subscription now</p>
    <?php } else if (((time()+(60*60*24*3)) > $subscription) && (!isset($_GET['renew']))) { ?>	
    <p class="error">Your subscription expires in <?php echo $common->get_time_stamp($subscription); ?>. Please renew your subscription now</p>
    <?php } ?>
        
</article>
<div>
   <div style="border:1px solid #ccc; padding:10px">
     <div style="margin-top:30px">
     <form id="subscribe-form" class="row" action="createOrder?mobile" method="post">				
        <table width=300 class="table table-hover" align=center>
            <thead>
                <tr>
            <th colspan=2>Personal Details</th>
            
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
        <table width=300 class="table table-hover">
            <thead>
                <tr>
            <th colspan=2>Subscription</th>
            
                </tr>
            </thead>
            <tbody>
                <tr>
                <td colspan="2"><p>To make payments online through your card, please choose online payments.</p>
                    <p>Alternatively, you can complete your subscription by making payment to our account provided below and contacting Legallens officials and send the name of payer and transaction referenced to us through our live chat to activate your subscription.</p>
                    <p>Account details:<br>
                    Name:&nbsp;&nbsp;<strong>Pearlhouse Legal Lens Limited</strong><br>
                    Bank:&nbsp;&nbsp;<strong>United Bank For Africa</strong><br>
                Number:&nbsp;&nbsp;<strong>1019666896</strong></p></td>
                </tr>
                <tr>
            <td width="25%">Current Subscription</td>
            <td><?php echo $subscriptions->getOneField($subscription_type)." (".$sub_Renw.")"; ?></td>
            
                </tr>
            <?php if (($subscription == "") || ($subscription <  time()) || (isset($_GET['renew']))) { ?>
                <tr>
            <td width="25%">User Type</td>
            <td><select name="type" class="form-control" id="type" required onChange="fetchList(this.value), toggleStatus(this.value)">
                <option value="">Select User Type</option>
                <option value="single">Single User</option>
                <option value="group">Multiple User</option>
                </select></td>
            
                </tr>
                <tr>
            <td width="25%">Subscription Package</td>
            <td><select name="package" class="form-control" id="package" required onChange="selectPackage(this.value)">
                <option value="">Select User Type First</option>
                </select></td>
                </tr>
                <tr>
            <td width="25%">Number of Users</td>
            <td><input type="number" name="num_user" id="num_user" onChange="enterUsers(this.value)" readonly required></td>
            
                </tr>
                <tr>
            <td width="25%">Payment Channel</td>
            <td><select name="payment_type" id="payment_type" class="form-control" required onChange="getPayment(this.value)">
                <option value="">Select One</option>
                <option value="Online">Online Payment</option>
                <option value="Others">Other Channels</option>
            </select></td>
                </tr>
                <tr>
            <td width="25%">Payment Frequency</td>
            <td><select name="payment_frequency" id="payment_frequency" class="form-control" required onChange="openCard(this.value)" disabled>
                <option value="Single">Single Payment</option>
                <option value="Renew">Auto Renew</option>
            </select></td>
                </tr>
            </tbody>
            </table>		
            <div id="showCard" style="display:none;">
            <table width=300 class="table table-hover">
            <thead>
                <tr>
            <th colspan=2>Payment Details</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <td width="25%">Card Number</td>
                <td><input type="text" name="cardno" id="cardno" placeholder="XXXX XXXX XXXX XXXX" onKeyUp="displayCardType(this.value)"><span id="cardLogo"></span></td>
                </tr>
                <tr>
                <td width="25%">Card Expiry (MM/YY)</td>
                <td><input type="number" maxlength="2" size="2" name="mm" id="mm" placeholder="MM" pattern="[0-9.]+" onKeyUp="monthCheck()" max="12"> <input type="number" maxlength="2" size="2" name="yy" id="yy" placeholder="YY" pattern="[0-9.]+" onKeyUp="yearCheck()" max="99"></td>
                </tr>
                <tr>
                <td width="25%">CVV</td>
                <td><input type="number" maxlength="3" size="3" name="cvv" id="cvv" placeholder="CVV" max="999"> </td>
                </tr>
                <tr>
                <td width="25%">Billing Address</td>
                <td><input type="text" name="billingaddress" id="billingaddress" placeholder="123 Steet Address"></td>
                </tr>
                <tr>
                <td width="25%">Billing City</td>
                <td><input type="text" name="billingcity" id="billingcity" placeholder="City"></td>
                </tr>
                <tr>
                <td width="25%">Billing ZIP/Post Code</td>
                <td><input type="text" name="billingzip" id="billingzip" placeholder="ZIP/Post Code"></td>
                </tr>
                <tr>
                <td width="25%">Billing State</td>
                <td><input type="text" name="billingstate" id="billingstate" placeholder="State"></td>
                </tr>
                <tr>
                <td width="25%">Billing State</td>
                <td><input type="text" name="billingcountry" id="billingcountry" placeholder="Country"></td>
                </tr>
                <tr>
                <td colspan="2" align="right">Powered by Rave&TRADE; by <a href='https://flutterwave.com/int/online-payments-products/rave/'>Flutterwave</a></td>
                </tr>
            </tbody>
            </table>		
            </div>

            <table width=300 class="table table-hover">
            <thead>
                <tr>
            <th colspan=2>Order Summary</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <td width="25%">Subscription Fees</td>
                <td align="right"><span id="s_fee"><?php echo NGN; ?> 0.00</span></td>
                </tr>
                <tr>
                <td width="25%">Gross Total</td>
                <td align="right"><span id="g_total"><?php echo NGN; ?> 0.00</span></td>
                </tr>
                <tr>
                <td width="25%">Group Discount</td>
                <td align="right"><span id="d_disc">0%</span></td>
                </tr>
                <tr>
                <td width="25%">Net Total</td>
                <td align="right"><span id="n_total"><?php echo NGN; ?> 0.00</span></td>
                </tr>
                <tr>
                <td>&nbsp;</td>
                <td><input type="submit" name="submit" value="Subscribe" class="btn btn-inverse"></td>
                </tr>
                
            <?php } else if ((time()+(60*60*24*3)) > $subscription) { ?>
                <tr>
            <td width="25%">&nbsp;</td>
            <td><a href="<?php echo URL; ?>mobile_subscription?id=<?php echo $ref; ?>&renew">Renew Subscription</a></td>
            
                </tr>
            <?php } else { ?>
                <tr>
            <td width="25%">Subscription Type</td>
            <td><?php echo $subscriptions->getOneField($subscription_type, "ref", "type"); ?></td>
            
                </tr>
                <tr>
            <td width="25%">Renewal Option</td>
            <td><?php echo $users->getOneField($ref, "ref", "payment_frequency"); ?></td>
            
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
            <a href="mobile_subscription?id=<?php echo $ref; ?>&cancel" onClick="return confirm('Your subscription will not <?php echo $sub_type; ?> after this action. are you sure you want to continue ?')"> cancel auto renew</a></td>
            
                </tr>
            <?php } ?>
                <?php if (($subscription_group_onwer == $ref) && ($subscription_group == 1)) { ?>
                <tr>
            <td width="25%">Subscription Users</td>
            <td><?php echo number_format(count($sub_list)); ?> [ <a href="<?php echo URL; ?>mobile_subscription_users?id=<?php echo $ref; ?>">Manage Users</a> ]</td>
            
                </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
            </table>
            <input name="total" id="total" type="hidden" value="0">
            <input name="order_owner" id="order_owner" type="hidden" value="<?php echo $ref; ?>">
    </form>
	 </div>

   </div>
</div>

<ins class="adsbygoogle"
style="display:inline-block;width:250px;height:250px"
          data-ad-client="ca-pub-4142286148495329"
          data-ad-slot="9218590698"></ins>
    <script>
    (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
                  </div> <!--end row -->      
			</div><!-- end container-->
                </div>
                <!-- End of Page Container -->

                <a href="#top" id="scroll-top"></a>
                <!-- script -->
               <!-- <script type='text/javascript' src='js/jquery-1.8.3.min.js'></script> -->
			   	<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
                <script type='text/javascript' src='<?php echo URL; ?>js/jquery.easing.1.34e44.js?ver=1.3'></script>
                <script type='text/javascript' src='<?php echo URL; ?>js/prettyphoto/jquery.prettyPhotoaeb9.js?ver=3.1.4'></script>
                <script type='text/javascript' src='<?php echo URL; ?>js/jquery.liveSearchd5f7.js?ver=2.0'></script>
				<script type='text/javascript' src='<?php echo URL; ?>js/jflickrfeed.js'></script>
                <script type='text/javascript' src='<?php echo URL; ?>js/jquery.formd471.js?ver=3.18'></script>
                <script type='text/javascript' src='<?php echo URL; ?>/js/jquery.validate.minfc6b.js?ver=1.10.0'></script>
                <script type='text/javascript' src="<?php echo URL; ?>/js/jquery-twitterFetcher.js"></script>
                <script type='text/javascript' src='<?php echo URL; ?>/js/custom5152.js?ver=1.0'></script>
                <script type='text/javascript' src='<?php echo URL; ?>/js/frontEnd.js'></script>
				<script type='text/javascript' src="<?php echo URL; ?>/js/navAccordion.min.js"></script>
    <script src="<?php echo URL; ?>//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="<?php echo URL; ?>js/pagination.js"></script>
                
                
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

