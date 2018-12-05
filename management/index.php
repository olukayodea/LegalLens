<?php
	$redirect = "index";
	include_once("../includes/functions.php");
	include_once("session.php");
	
	$registered_users = $users->listAll();
	$new_users = $users->sortAll("NEW", "status");
	$active_subscription = $users->listAllActive();
	$all_document = $documents->listAll();
	$order_list = $orders->listAll(10);
	
	$allTickets =  count($help->sortAll(0, "parent_id"));
	
	$newTicket = count($help->sortAll(0, "parent_id", "status", 0));
	$p_newTicket = ($newTicket/$allTickets)*100;
	$openedTicket = count($help->sortAll(0, "parent_id", "status", 1));
	$p_openedTicket = ($openedTicket/$allTickets)*100;
	$closedTickets = count($help->sortAll(0, "parent_id", "status", 2));
	$p_closedTickets = ($closedTickets/$allTickets)*100;
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>LegalLens | Dashboard</title>
    <?php $adminPages->headerFiles(); ?>
  </head>
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

      <?php $adminPages->topHeader();
	  $adminPages->sidebar("home"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
          Dashboard</h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <!-- Info boxes -->
          <div class="row">
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-balance-scale"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Documents</span>
                  <span class="info-box-number"><?php echo $common->numberPrintFormat(count($all_document)); ?></span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>

            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-green"><i class="ion ion-ios-cart-outline"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Active Subscription</span><span class="info-box-number"><?php echo $common->numberPrintFormat(count($active_subscription)); ?></span></div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col --><div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="ion ion-ios-people-outline"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Registered Users</span><span class="info-box-number"><?php echo $common->numberPrintFormat(count($all_document)); ?></span></div>
                <!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">New Users</span><span class="info-box-number"><?php echo $common->numberPrintFormat(count($new_users)); ?></span></div>
                <!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->
          </div><!-- /.row --><!-- /.row -->

          <!-- Main row -->
          <div class="row">
            <!-- Left col -->
            <div class="col-md-8">
              <!-- MAP & BOX PANE --><!-- /.box -->

              <!-- TABLE: LATEST ORDERS -->
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Latest Orders</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div class="table-responsive">
                    <table class="table no-margin">
                      <thead>
                        <tr>
                          <th>Order ID</th>
                          <th>Subscription</th>
                          <th>Status</th>
                          <th>Time</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php for ($i = 0; $i < count($order_list); $i++) { ?>
                        <tr>
                          <td><a href="<?php echo URLAdmin; ?>order.view?ref=<?php echo $order_list[$i]['ref']; ?>" title="View Order"><?php echo $orders->orderID($order_list[$i]['ref']); ?></a></td>
                          <td><?php echo $subscriptions->getOneField($order_list[$i]['order_subscription'])." (".$order_list[$i]['order_subscription_type'].")"; ?></td>
                          <td>
                          <?php if ($order_list[$i]['order_status'] == "NEW") { ?>
						  <span class="label label-warning">
                          <?php } else if ($order_list[$i]['order_status'] == "PAID") { ?>
						  <span class="label label-success">
                          <?php } else if ($order_list[$i]['order_status'] == "FAILED") { ?>
						  <span class="label label-danger">
                          <?php } else if ($order_list[$i]['order_status'] == "CANCELLED") { ?>
						  <span class="label label-danger">
                          <?php } else if ($order_list[$i]['order_status'] == "COMPLETE") { ?>
						  <span class="label label-info">
                          <?php } ?>
						  <?php echo $order_list[$i]['order_status']; ?>
                          </span>
                          </td>
                          <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $common->get_time_stamp($order_list[$i]['modify_time']); ?></div></td>
                        </tr>
                      <?php } ?>
                      </tbody>
                    </table>
                  </div><!-- /.table-responsive -->
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                  <a href="order" class="btn btn-sm btn-info btn-flat pull-left">View All Order</a>
                </div><!-- /.box-footer -->
              </div><!-- /.box -->
            </div><!-- /.col -->

            <div class="col-md-4">
              <!-- Info Boxes Style 2 -->
              <div class="info-box bg-yellow">
                <span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">New Tickeets</span>
                  <span class="info-box-number"><?php echo $common->numberPrintFormat($newTicket); ?></span>
                  <div class="progress">
                    <div class="progress-bar" style="width: <?php echo ceil($p_newTicket); ?>%"></div>
                  </div>
                  <span class="progress-description">
                    <?php echo ceil($p_newTicket); ?>% of <?php echo $allTickets; ?>
                  </span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
              <div class="info-box bg-green">
                <span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Open Tickets</span>
                  <span class="info-box-number"><?php echo $common->numberPrintFormat($openedTicket); ?></span>
                  <div class="progress">
                    <div class="progress-bar" style="width: <?php echo ceil($p_openedTicket); ?>%"></div>
                  </div>
                  <span class="progress-description">
                    <?php echo ceil($p_openedTicket); ?>% of <?php echo $allTickets; ?>
                  </span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
              <div class="info-box bg-red">
                <span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Closed Tickets</span>
                  <span class="info-box-number"><?php echo $common->numberPrintFormat($closedTickets); ?></span>
                  <div class="progress">
                    <div class="progress-bar" style="width: <?php echo ceil($p_closedTickets); ?>%"></div>
                  </div>
                  <span class="progress-description">
                    <?php echo ceil($p_closedTickets); ?>% of <?php echo $allTickets; ?>
                  </span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
              <div class="info-box bg-aqua">
                <span class="info-box-icon"><i class="ion-ios-chatbubble-outline"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Site Visitors</span>
                  <span class="info-box-number">163,921</span>
                  <div class="progress">
                    <div class="progress-bar" style="width: 40%"></div>
                  </div>
                  <span class="progress-description">
                    40% Increase in 30 Days
                  </span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box --><!-- /.box -->

              <!-- PRODUCT LIST --><!-- /.box -->
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
    <!-- FastClick -->
    <script src="plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/app.min.js"></script>
    <!-- Sparkline -->
    <script src="plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- jvectormap -->
    <script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- SlimScroll 1.3.0 -->
    <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- ChartJS 1.0.1 -->
    <script src="plugins/chartjs/Chart.min.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="dist/js/pages/dashboard2.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
  </body>
</html>
