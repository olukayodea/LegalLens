<?php
	include_once("../functions.php");
	$id = $common->get_prep($_REQUEST['id']);
	$subject = $common->get_prep($_REQUEST['subject']);
	$message = $common->get_prep($_REQUEST['message']);
	$data = $orders->getOne($id);
	$userData = $users->listOne($data['order_owner'], "ref");
	
	$sub_data = $data['order_subscription'];

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $subject; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="<?php echo URLAdmin; ?>bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo URLAdmin; ?>dist/css/AdminLTE.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="wrapper">
      <!-- Main content -->
      <section class="invoice">
        <!-- title row -->
        <div class="row">
          <div class="col-xs-12">
            <h2 class="page-header">
              <i class="fa fa-globe"></i>LegalLens.
              <small class="pull-right">Date: <?php echo date("Y/m/d"); ?></small>
            </h2>
          </div><!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
          <div class="col-sm-4 invoice-col">
            From
            <address>
              <strong>LegalLens</strong><br>
              Phone: <?php echo phoneData; ?><br>
              Email: <?php echo emailData; ?>
            </address>
          </div><!-- /.col -->
          <div class="col-sm-4 invoice-col">
            To
            <address>
              <strong><?php echo $userData['last_name']." ".$userData['other_names']; ?></strong><br>
              Phone: <?php echo $userData['phone']; ?><br>
              Email: <?php echo $userData['email']; ?>
            </address>
          </div><!-- /.col -->
          <div class="col-sm-4 invoice-col">
          	<b>Order ID:</b> <?php echo $orders->orderID($id); ?><br>
            <b>Issue Date:</b> <?php echo date("Y/m/d"); ?>
          </div>
          <!-- /.col -->
        </div><!-- /.row -->

        <!-- Table row -->
        <div class="row">
          <div class="col-xs-12 table-responsive">
            <table class="table table-striped" width="100%">
              <thead>
                <tr>
                  <th>QTY</th>
                  <th>Subscription</th>
                  <th>Subscription Type</th>
                  <th>Validity</th>
                  <th>Subtotal</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><?php echo number_format($data['order_users']); ?></td>
                  <td><?php echo $subscriptions->getOneField($data['order_subscription']); ?></td>
                  <td><?php echo $data['payment_type']; ?></td>
                  <td><?php echo $subscriptions->getOneField($data['order_subscription'], "ref", "validity")." days(s)"; ?></td>
                  <td align="right"><?php echo NGN." ".number_format($subscriptions->getOneField($data['order_subscription'], "ref", "amount"), 2); ?></td>
                </tr>
              </tbody>
            </table>
          </div><!-- /.col -->
        </div><!-- /.row -->

        <div class="row">
          <!-- accepted payments column -->
          <div class="col-xs-6">
            <p class="lead"><?php echo $message; ?></p>
            <p class="lead">This invoice has not been paid. You will not be able to access your account if your subscription expires untill you settle this invoice.</p>
          </div><!-- /.col -->
          <div class="col-xs-6">
            <div class="table-responsive">
              <table class="table">
                <tr>
                  <th style="width:50%">Subtotal:</th>
                  <td align="right"><?php echo NGN." ".number_format($data['order_amount_gross'], 2); ?></td>
                </tr>
                <tr>
                  <th>Group Discount</th>
                  <td align="right"><?php echo NGN." ".number_format(($data['order_amount_gross']-$data['order_amount_net']), 2)." (".$data['order_amount_discount']."%)"; ?></td>
                </tr>
                <tr>
                  <th>Total:</th>
                  <td align="right"><?php echo NGN." ".number_format($data['order_amount_net'], 2); ?></td>
                </tr>
              </table>
            </div>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </section><!-- /.content -->
    </div><!-- ./wrapper -->

    <!-- AdminLTE App -->
    <script src="<?php echo URLAdmin; ?>dist/js/app.min.js"></script>
  </body>
</html>>