<?php
	$redirect = "transactions";
	include_once("../includes/functions.php");
	include_once("session.php");
		
	$list = $transactions->listAll();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>LegalLens | Transactions</title>
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
            Transactions
            <small>View all transaction attempts</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo URLAdmin; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Transactions</li>
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
                  <h3 class="box-title">Showing All Transactions</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                <div align="right">
                  <form name="form1" method="post" action="transactions.excel">
                    <input type="date" name="from" id="from" required max="<?php echo date("Y-m-d", mktime(0, 0, 0, date('m'), date('d')-1, date('Y'))); ?>">
                    <input type="date" name="to" id="to" required max="<?php echo date("Y-m-d"); ?>">
                    <input type="submit" name="button" id="button" value="Download">
                  </form>
                </div>
                  <table id="example1" class="table table-bordered table-striped">
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
                        <th>&nbsp;</td>
                      </tr>
                    </thead>
                    <tbody>
					  <?php for ($i = 0; $i < count($list); $i++) {
                                    $sn++; ?>
                      <tr>
                        <td><?php echo $sn; ?></td>
                        <td><a href="<?php echo URLAdmin; ?>transactions.view?ref=<?php echo $list[$i]['ref']; ?>" title="View Transaction"><?php echo $list[$i]['transaction_id']; ?></a></td>
                        <td><a href="<?php echo URLAdmin; ?>order.view?ref=<?php echo $list[$i]['order_id']; ?>" title="View Order"><?php echo $orders->orderID($list[$i]['order_id']); ?></a></td>
                        <td><?php echo NGN.number_format($list[$i]['amount']); ?></td>
                        <td><?php echo $list[$i]['transaction_channel']; ?></td>
                        <td><?php echo $list[$i]['transaction_status']; ?></td>
                        <td><?php echo $common->get_time_stamp($list[$i]['create_time']); ?></td>
                        <td><?php echo $common->get_time_stamp($list[$i]['modify_time']); ?></td>
                        <th>
                        <?php if (($list[$i]['transaction_channel'] == "Online") && ($list[$i]['transaction_status'] != "PAID")) { ?><a href="verify?data=<?php echo $link; ?>&id=<?php echo $list[$i]['ref']; ?>">verify transaction</a><?php } ?></td>
                      </tr>
                      <?php }
                                    unset($i); 
                          unset($sn); ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>&nbsp;</td>
                        <th><strong>Txn ID</strong></th>
                        <th><strong>Order ID</strong></th>
                        <th><strong>Amount</strong></th>
                        <th><strong>Channel</strong></th>
                        <th><strong>Status</strong></th>
                        <th><strong>Creationd</strong></th>
                        <th><strong>Modified</strong></th>
                        <th>&nbsp;</td>
                      </tr>
                    </tfoot>
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
