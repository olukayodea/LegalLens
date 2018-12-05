<?php
	$redirect = "transactions.view";
	include_once("../includes/functions.php");
	include_once("session.php");
	
	if (isset($_REQUEST['ref'])) {
		$id = $_REQUEST['ref'];
	} else {
		header("location: transactions?error=".urlencode("Select a transaction"));
	}
	
	$data = $transactions->getOne($id);
	
	if (($data['transaction_channel'] == "Online")) {
		$mackey = "68466204A33A75724CC43810F794550093969EE824A02F959411D6601051E26D3DBD15C361DB827D4925883F926A455408ACC8E0DFDBAECAF6D4EF3363D4B3BC";
		$product_id = 6699;
		
		$txnref = $data['transaction_id'];
		
		$submittedamt = $data['amount']*100;
		
		$nhash = $product_id.$txnref.$mackey;
		$thash = hash('sha512',$nhash);
		
		$valuesforurl = array(
		"productid"=>$product_id,
		"transactionreference"=>$txnref,
		"amount"=>$submittedamt
		);
		$outvalue = http_build_query($valuesforurl) . "\n";
			
		$url = "https://webpay.interswitchng.com/paydirect/api/v1/gettransaction.json?$outvalue "; // json
		
		$headers = array("GET /HTTP/1.1","User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.1) Gecko/2008070208 Firefox/3.0.1","Accept-Language: en-us,en;q=0.5","Keep-Alive: 300","Connection: keep-alive","Hash: $thash " ); // computed hash now added to header of my request
		
		$ch = curl_init(); // initiate the request
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt( $ch, CURLOPT_POST, false );
		$response = curl_exec($ch);
		curl_close($ch);
		
		$rawData = json_decode($response, true);
		
		if ($rawData['ResponseCode'] == "00") {			
			$orders->updateOne("order_status", "COMPLETE", $data['order_id']);
			$transactions->updateOne("transaction_status", "PAID", $data['ref']);
			$orders->orderNotification($data['order_id'], "reciept", $code);
			$orders->updateSubscrption($data['order_id']);
		}
	}
	$data = $transactions->getOne($id);
	$list = $transactions->sortAll("user_id", $data['user_id']);
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>LegalLens | Transaction #<?php echo $data['transaction_id']; ?></title>
    <?php $adminPages->headerFiles(); ?>
  </head>
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

      <?php $adminPages->topHeader();
	  $adminPages->sidebar("report"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Transaction
            <small>#<?php echo $data['transaction_id']; ?></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo URLAdmin; ?>transactions">Transactions</a></li>
            <li class="active">#<?php echo $data['transaction_id']; ?></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="invoice">
          <!-- title row -->
          <div class="row">
            <div class="col-xs-12">
              <h2 class="page-header">
                Transaction #<?php echo $data['transaction_id']; ?>.
                <small class="pull-right">Last Modified: <?php echo date("Y/m/d", $data['modify_time']); ?></small>
              </h2>
            </div><!-- /.col -->
          </div>
          <!-- info row -->
          <div class="row invoice-info"><!-- /.col --><!-- /.col -->
            
                <div class="box-body">
              <table class="table table-striped">
                <tr style="background:#CCCCCC">
                  <td width="20%"><strong>Transaction ID</strong></td>
                  <td width="80%"><strong>#<a href="<?php echo URLAdmin; ?>order.view?ref=<?php echo $data['order_id']; ?>" title="View Order"><b><?php echo $data['transaction_id']; ?></b></a></strong></td>
                </tr>
                <tr>
                  <td><strong>Order Owner</strong></td>
                  <td><?php echo $users->getOneField($data['user_id'])." ".$users->getOneField($data['user_id'], "ref", "other_names"); ?></td>
                </tr>
                <tr style="background:#CCCCCC">
                  <td width="20%"><strong>Order ID</strong></td>
                  <td width="80%"><strong>#<a href="<?php echo URLAdmin; ?>order.view?ref=<?php echo $data['order_id']; ?>" title="View Order"><?php echo $orders->orderID($data['order_id']); ?></a></strong></td>
                </tr>
                <tr>
                  <td><strong>Amount</strong></td>
                  <td><?php echo NGN.number_format($data['amount'], 2); ?></td>
                </tr>
                <tr style="background:#CCCCCC">
                  <td><strong>Channel</strong></td>
                  <td><?php echo $data['transaction_channel']; ?></td>
                </tr>
                <tr>
                  <td><strong>Status</strong></td>
                  <td><?php echo $data['transaction_status']; ?></td>
                </tr>
                <?php if ($data['transaction_channel'] == "Online") { ?>
                <tr style="background:#CCCCCC">
                  <td><strong>Card Number</strong></td>
                  <td>**** **** ****<?php echo $rawData['CardNumber']; ?></td>
                </tr>
                <tr>
                  <td><strong>Gateway Reference</strong></td>
                  <td><?php echo $rawData['PaymentReference']; ?></td>
                </tr>
                <tr style="background:#CCCCCC">
                  <td><strong>Gateway Traxn. Date</strong></td>
                  <td><?php echo $rawData['TransactionDate']; ?></td>
                </tr>
                <tr>
                  <td><strong>Gateway Response</strong></td>
                  <td><?php echo $rawData['ResponseCode']; ?></td>
                </tr>
                <tr style="background:#CCCCCC">
                  <td><strong>Gateway Description</strong></td>
                  <td><?php echo $rawData['ResponseDescription']; ?></td>
                </tr>
                <tr>
                  <td><strong>Approved Amount</strong></td>
                  <td><?php echo NGN.number_format(($rawData['Amount']/100), 2); ?></td>
                </tr>
                <?php } ?>
                <tr style="background:#CCCCCC">
                  <td><strong>Created</strong></td>
                  <td><strong><?php echo date('l jS \of F Y h:i:s A', $data['create_time']); ?></strong></td>
                </tr>
                <tr>
                  <td><strong>Last Modified</strong></td>
                  <td><strong><?php echo date('l jS \of F Y h:i:s A', $data['modify_time']); ?></strong></td>
                </tr>
              </table>
            </div>
            <!-- /.col -->
          </div><!-- /.row -->
          <div class="row">
            <div class="col-xs-12">
              <h2 class="page-header">
              Similar Transactions by <?php echo $users->getOneField($data['user_id'])." ".$users->getOneField($data['user_id'], "ref", "other_names"); ?></h2>
            </div><!-- /.col -->
          </div>
          <!-- Table row -->
          <div class="row">
            <div class="col-xs-12 table-responsive">
              <table class="table table-striped"e id="example1">
                <thead>
                  <tr>
                    <th>&nbsp;</td>
                    <th><strong>Txn ID</strong></th>
                    <th><strong>Order ID</strong></th>
                    <th><strong>Amount</strong></th>
                    <th><strong>Channel</strong></th>
                    <th><strong>Status</strong></th>
                    <th><strong>Creationd</strong></th>
                    <th><strong>Modified</strong></th>
                  </tr>
                </thead>
                <tbody>
				  <?php for ($i = 0; $i < count($list); $i++) {
                                $sn++; ?>
                  <?php if ($list[$i]['ref'] != $data['ref']) { ?>
                  <tr>
                    <td><?php echo $sn; ?></td>
                    <td><a href="<?php echo URLAdmin; ?>transactions.view?ref=<?php echo $list[$i]['ref']; ?>" title="View Transaction"><?php echo $list[$i]['transaction_id']; ?></a></td>
                    <td><a href="<?php echo URLAdmin; ?>order.view?ref=<?php echo $list[$i]['order_id']; ?>" title="View Order"><?php echo $orders->orderID($list[$i]['order_id']); ?></a></td>
                    <td><?php echo NGN.number_format($list[$i]['amount']); ?></td>
                    <td><?php echo $list[$i]['transaction_channel']; ?></td>
                    <td><?php echo $list[$i]['transaction_status']; ?></td>
                    <td><?php echo $common->get_time_stamp($list[$i]['create_time']); ?></td>
                    <td><?php echo $common->get_time_stamp($list[$i]['modify_time']); ?></td>
                  </tr>
                  <?php }
				  }?>
                </tbody>
              </table>
            </div><!-- /.col -->
          </div><!-- /.row --><!-- /.row -->

        <!-- this row will not appear when printing --></section><!-- /.content -->
        <div class="clearfix"></div>
      </div><!-- /.content-wrapper -->
      
      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 1.0.0
        </div>
        <strong>Copyright &copy; <?php echo date("Y"); ?> <a href="<?php echo URL; ?>">LegalLens</a>.</strong> All rights reserved.
      </footer>
    </div><!-- ./wrapper -->

    <!-- jQuery 2.1.4 -->
    <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <!-- DataTables -->
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/app.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <!-- page script -->
    <script>
      $(function () {
        $("#example1").DataTable();
        $('#example2').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": false,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });
      });
	  </script>
  </body>
</html>
