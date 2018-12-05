<?php
	$redirect = "managesubscription";
	include_once("includes/functions.php");
	include_once("includes/session.php");
	
	if (isset($_REQUEST['id'])) {
		$id = $common->mysql_prep($_REQUEST['id']);
	} else {
		header("location: managesubscription?error=".urlencode("There was an error processing your order, please try again"));
	}
	$transactionData = $transactions->getOne($id);
	$data = $orders->getOne($transactionData['order_id']);
	
	if (isset($_REQUEST['cancel'])) {
		$orders->reverseal($data['ref']);
		$orders->updateOne("order_status", "CANCELLED", $data['ref']);
		//$orders->updateOne("payment_status", "CANCELLED", $data['ref']);
		$transactions->updateOne("transaction_status", "CANCELLED", $transactionData['ref']);
		
		header("location: shoppingCart");
	} else if (isset($_REQUEST['others'])) {
		$orders->updateOne("order_payment", "cash", $data['ref']);
		$transactions->updateOne("transaction_channel", "cash", $transactionData['ref']);
		
		$array['ResponseCode'] = "00";
		$array['TransactionDate'] = date('l jS \of F Y h:i:s A', $data['create_time']);
		$response = json_encode($array);
		$token = base64_encode($response);
		$orders->orderNotification($data['ref']);
		header("location: confirmation?id=".$data['ref']."&token=".$token);
	}
	
	$last_name = trim($_SESSION['users']['last_name']);
	$other_names = trim($_SESSION['users']['other_names']);
	$phone = trim($_SESSION['users']['phone']);
	$email = trim($_SESSION['users']['email']);
?>
<!doctype html>
        <!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en-US"> <![endif]-->
        <!--[if IE 7]>    <html class="lt-ie9 lt-ie8" lang="en-US"> <![endif]-->
        <!--[if IE 8]>    <html class="lt-ie9" lang="en-US"> <![endif]-->
        <!--[if gt IE 8]><!--> <html lang="en-US"> <!--<![endif]-->
        

<head>
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

                <title>LegalLens | Manage Subscription </title>

        <?php $pages->head(); ?>

        <?php $pages->chatHeader(); ?>
        </head>

        <body>

                <!-- Start of Header -->
                <div class="header-wrapper">
					<?php $pages->headerFiles(); ?>
                </div>
                <!-- End of Header -->
                <!-- Start of Search Wrapper -->
                <div class="search-area-wrapper_two">
                </div>
                <!-- End of Search Wrapper -->
                <!-- Start of Page Container -->
                <div class="page-container">
                    <div class="container">
                        <div class="row">
                        <!-- start of page content -->
                        <div class="span8 page-content">
                        <article class="type-page hentry clearfix">
                            <h2 class="post-title">
                                    Manage Subscription
                            </h2>
                            <hr>
                            <?php if (isset($_GET['error'])) { ?>
                            <p class="error">Y<?php echo $_GET['error']; ?></p>
                            <?php } ?>
                          <p>Pre-Payment Confirmation</p>
                        </article>
                        <div style="margin-left:35px">
                        <form id="subscribe-form" class="row" action="createOrder" method="post">
                          <table width="100%" border="0">
                            <tr>
                              <td width="20%">Order ID</td>
                              <td><strong><?php echo $orders->orderID($data['ref']); ?></strong></td>
                            </tr>
                            <tr>
                              <td width="20%">Transaction ID</td>
                              <td><strong><?php echo $transactionData['transaction_id']; ?></strong></td>
                            </tr>
                            <tr>
                              <td width="20%">Amount</td>
                              <td><strong><?php echo NGN." ".number_format($transactionData['amount'], 2); ?></strong></td>
                            </tr>
                            <tr>
                              <td width="20%">Customer name</td>
                              <td><strong><?php echo $last_name." ".$other_names; ?></strong></td>
                            </tr>
                            <tr>
                              <td width="20%">Customer Email</td>
                              <td><strong><?php echo $email; ?></strong></td>
                            </tr>
                            <tr>
                              <td width="20%">Customer Phone</td>
                              <td><strong><?php echo $phone; ?></strong></td>
                            </tr>
                            <tr>
                              <td width="20%">Payment Channel</td>
                              <td><strong><?php echo $transactionData['transaction_channel']; ?></strong></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td><button type="button" onClick="window.location='redirect?id=<?php echo $id; ?>'" class="btn btn-cart">Confirm and Pay Online</button></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td><p><a style="color:#F00; text-decoration:underline" href="<?php echo URL; ?>preview?cancel&id=<?php echo $id; ?>" onClick="return confirm('do you really want to cancel this order?')">Cancel Transaction</a> | <a style="color:#060; text-decoration:underline" href="<?php echo URL; ?>preview?others&id=<?php echo $id; ?>" onClick="return confirm('You have opted to pay for this order via other channels, do you wish to continue?')">Other Payment Method</a></p></td>
                            </tr>
                          </table>
<input name="total" id="total" type="hidden" value="">
                              <input name="order_owner" id="order_owner" type="hidden" value="<?php echo $ref; ?>">
                        </form>
                          </div>
                    </div>
                    <!-- end of page content -->


                    <!-- start of sidebar -->
                    <aside class="span4 page-sidebar">
                        <section class="widget">
                        	<div class="login-widget">Welcome, <?php echo $last_name." ".$other_names; ?><br>Last logged in: <?php echo date('l jS \of F Y h:i:s A', $loginTime); ?><br><a href="<?php echo URL; ?>managesubscription">Manage Subscription</a><br><a href="<?php echo URL; ?>support">Help and Support</a><br><a href="<?php echo URL; ?>userprofile">View profile</a><br><a href="<?php echo URL; ?>managesavedpages">Manage saved pages</a><br><a href="<?php echo URL; ?>?logout">Logout</a></div>
                            
                            <section class="widget">
                                <div align="center">
                                    <a href="<?php echo URL; ?>helpAndSupport" ><img src="<?php echo URL; ?>/images/help.png" width="100" /></a><br />
                                    <h3 class="title">Need Help?</h3>
                                    <p class="intro">Click here to<br /><a id="mibew-agent-button" href="<?php echo URL; ?>helpAndSupport" >Contact Support</a></p>
                                </div>
                                <?php $pages->advert(); ?>
                            </section>
					</aside>
                            <!-- end of sidebar -->
                    </div>
            </div>
    </div>
                <!-- End of Page Container -->

                <!-- Start of Footer -->
                <footer id="footer-wrapper">
                        <?php $pages->footer(); ?>
                        <!-- end of #footer -->

                        <!-- Footer Bottom -->
                       <?php $pages->footerButtom(); ?>
                        <!-- End of Footer Bottom -->
                </footer>
                <!-- End of Footer -->

                <a href="#top" id="scroll-top"></a>

                <!-- script -->
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

        </body>


</html>

