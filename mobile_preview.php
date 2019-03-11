<?php
	$redirect = "mobile_subscription";
	include_once("includes/functions.php");
	
	if (isset($_REQUEST['id'])) {
		echo $id = $common->mysql_prep($_REQUEST['id']);
	} else {
		header("location: mobile_subscription?error=".urlencode("There was an error processing your order, please try again"));
	}
  $transactionData = $transactions->getOne($id);
	$data = $orders->getOne($transactionData['order_id']);
	
	if (isset($_REQUEST['cancel'])) {
		$orders->updateOne("order_status", "CANCELLED", $data['ref']);
		$transactions->updateOne("transaction_status", "CANCELLED", $transactionData['ref']);
		
		header("location: mobilehome");
	} else if (isset($_REQUEST['others'])) {
		$orders->updateOne("payment_type", "cash", $data['ref']);
		$transactions->updateOne("transaction_channel", "cash", $transactionData['ref']);
		
		$array['ResponseCode'] = "00";
		$array['Amount'] = $transactionData['amount'];
		$array['TransactionDate'] = date('l jS \of F Y h:i:s A', $data['create_time']);
		$response = json_encode($array);
		$token = base64_encode($response);
		$orders->orderNotification($data['ref']);
		header("location: mobile_confirmation?id=".$data['ref']."&token=".$token);
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
    <p>Pre-Payment Confirmation</p>
</article>
<div>
   <div style="border:1px solid #ccc; padding:10px">
     <div style="margin-top:30px">
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
              <td><button type="button" onClick="window.location='redirect?mobile&id=<?php echo $id; ?>'" class="btn btn-cart">Confirm and Pay Online</button></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><p><a style="color:#F00; text-decoration:underline" href="<?php echo URL; ?>mobile_preview?cancel&id=<?php echo $id; ?>" onClick="return confirm('do you really want to cancel this order?')">Cancel Transaction</a> | <a style="color:#060; text-decoration:underline" href="<?php echo URL; ?>mobile_preview?others&id=<?php echo $id; ?>" onClick="return confirm('You have opted to pay for this order via other channels, do you wish to continue?')">Other Payment Method</a></p></td>
            </tr>
          </table>
              <input name="total" id="total" type="hidden" value="">
              <input name="order_owner" id="order_owner" type="hidden" value="<?php echo $ref; ?>">
        </form>
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