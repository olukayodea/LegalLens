<?php
	$redirect = "order.view";
	include_once("../includes/functions.php");
	include_once("session.php");
	if (isset($_REQUEST['ref'])) {
		$id = $common->get_prep($_REQUEST['ref']);
	} else {
		header("location: order?error=".urlencode("Please select an order"));
	}
	
	if (isset($_REQUEST['paymentStatus'])) {
		$orderStatus = $common->get_prep($_REQUEST['paymentStatus']);
		if ($_REQUEST['paymentStatus'] == "CANCELLED") {      
      $orders->updateOne("order_status", "CANCELLED", $id);
      $orders->updateOne("payment_status", "CANCELLED", $id);
			$transData = $transactions->getOne($id, "order_id");
			$transactions->updateOne("transaction_status", "CANCELLED", $transData['ref']);
		} else if ($_REQUEST['paymentStatus'] == "PAID") {
			$orders->updateOne("order_status", "COMPLETE", $id);
			$transData = $transactions->getOne($id, "order_id");
			$transactions->updateOne("transaction_status", "PAID", $transData['ref']);
			$orders->orderNotification($id, "reciept", $code);
			$orders->updateSubscrption($id);
		}
		$orders->updateOne("order_status", $orderStatus, $id);
		$orders->updateOne("last_modified_by", $ref, $id);
		
		$orders->orderNotification($id, "notification");
		
		header("location: ?done&ref=".$id);
	}
	
	$data = $orders->getOne($id);
	$userData = $users->listOne($data['order_owner'], "ref");
	
	$items = $orders->unWrap($data['order_item']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>LegalLens | Transactions</title>
  <?php $adminPages->headerFiles(); ?>
<script type="text/javascript">
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
    </script>
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
            Orders
            <small>View Order details for #<?php echo $orders->orderID($id); ?></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo URLAdmin; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="order.php">Orders</a></li>
            <li class="active">#<?php echo $orders->orderID($id); ?></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row"><div class="col-md-12">
          
			<?php if (isset($_REQUEST['done'])) { ?><div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4>	<i class="icon fa fa-check"></i> Alert!</h4>
                Actions performed successfully
              </div>
            <?php } ?>
            <?php if (isset($_GET['error'])) { ?>
                <div class="alert alert-warning alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                An error occured, please try again. <?php echo $common->get_prep($_GET['error']); ?>
                </div>
            <?php } ?>
              <!-- general form elements -->
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title"><strong>#<?php echo $orders->orderID($id); ?></strong></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table width="100%" border="0">
                    <tr style="background:#CCCCCC">
                      <td width="20%">Order ID</td>
                      <td width="80%"><strong>#<?php echo $orders->orderID($id); ?></strong></td>
                    </tr>
                    <tr>
                      <td>Customer Name</td>
                      <td><strong><?php echo $users->getOneField($data['order_owner'], "ref", "last_name"); ?> <?php echo $users->getOneField($data['order_owner'], "ref", "other_names"); ?></strong></td>
                    </tr>
                    <tr style="background:#CCCCCC">
                      <td>Customer E-Mail</td>
                      <td><strong><?php echo $users->getOneField($data['order_owner'], "ref", "email"); ?></strong></td>
                    </tr>
                    <tr>
                      <td>Customer Phone</td>
                      <td><strong><?php echo $users->getOneField($data['order_owner'], "ref", "phone"); ?></strong></td>
                    </tr>
                    <tr style="background:#CCCCCC">
                      <td>Order Status</td>
                      <td><?php if (($data['order_status'] == "COMPLETE") || ($data['order_status'] == "CANCELLED")) {
				  echo $data['order_status'];
			  } else { ?>
                        <select name="jumpMenu2" id="jumpMenu2" onChange="MM_jumpMenu('parent',this,0)">
                          <option value="Javascript:void(0);"<?php if ($data['order_status'] == "NEW") { ?> selected<?php } ?>>NEW</option>
                          <option value="?ref=<?php echo $id; ?>&paymentStatus=PAID"<?php if ($data['order_status'] == "PAID") { ?> selected<?php } ?>>PAID</option>
                          <option value="?ref=<?php echo $id; ?>&paymentStatus=FAILED"<?php if ($data['order_status'] == "FAILED") { ?> selected<?php } ?>>FAILED</option>
                          <option value="?ref=<?php echo $id; ?>&paymentStatus=CANCELLED"<?php if ($data['order_status'] == "CANCELLED") { ?> selected<?php } ?>>CANCELLED</option>
                        </select>
                        <?php } ?></td>
                    </tr>
                    <tr style="background:#CCCCCC">
                      <td>Created</td>
                      <td><strong><?php echo date('l jS \of F Y h:i:s A', $data['create_time']); ?></strong></td>
                    </tr>
                    <tr>
                      <td>Last Modified</td>
                      <td><strong><?php echo date('l jS \of F Y h:i:s A', $data['modify_time']); ?></strong></td>
                    </tr>
                    <tr style="background:#CCCCCC">
                      <td>Last Modified By</td>
                      <td><strong><?php echo $admin->getOneField($data['last_modified_by']); ?></strong></td>
                    </tr>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
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
    <!-- SlimScroll -->
    <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/app.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <!-- Select2 -->
    <script src="plugins/select2/select2.full.min.js"></script>
    <!-- InputMask -->
    <script src="plugins/input-mask/jquery.inputmask.js"></script>
    <script src="plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <!-- date-range-picker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap color picker -->
    <script src="plugins/colorpicker/bootstrap-colorpicker.min.js"></script>
    <!-- bootstrap time picker -->
    <script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>
    <!-- iCheck 1.0.1 -->
    <script src="plugins/iCheck/icheck.min.js"></script>
    <!-- page script -->
    <script>
      $(function () {
        //Initialize Select2 Elements
        $(".select2").select2();
		
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
