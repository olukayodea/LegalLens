<?php
	$redirect = "help";
	include_once("../includes/functions.php");
	include_once("session.php");
	
	if (isset($_REQUEST['open'])) {
		$list = $help->sortAll(0, "parent_id", "status", 1);
		$tag = "Showing Opened Support Tickets";
	} else if (isset($_REQUEST['new'])) {
		$list = $help->sortAll(0, "parent_id", "status", 0);
		$tag = "Showing Opened Support Tickets";
	} else if (isset($_REQUEST['closed'])) {
		$list = $help->sortAll(0, "parent_id", "status", 2);
		$tag = "Showing Closed Support Tickets";
	} else {
		$list = $help->sortAll(0, "parent_id");
		$tag = "Showing all Support Tickets";
	}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>LegalLens | Help and Support</title>
    <?php $adminPages->headerFiles(); ?>
  </head>
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

      <?php $adminPages->topHeader();
	  $adminPages->sidebar("help"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Help
            <small>Support Ticket</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo URLAdmin; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Support Ticket</li>
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
                  <h3 class="box-title"><?php echo $tag; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>&nbsp;</th>
                        <th>Category</th>
                        <th>Customer Name</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Updated</th>
                        <th>Last Modified By</th>
                        <th>&nbsp;</th>
                      </tr>
                    </thead>
                    <tbody>
						<?php 
                            $amount = 0; 
                            for ($i = 0; $i < count($list); $i++) {
                            $sn++;?>
                          <tr>
                            <td><?php echo $sn; ?></td>
                            <td><?php echo $common->highlight($knowledge_base_category->getOneField($list[$i]['category']), $list[$i]['status']); ?></td>
                            <td><?php echo $common->highlight($users->getOneField($list[$i]['user_id'], "ref", "last_name")." ".$users->getOneField($list[$i]['user_id'], "ref", "other_names"), $list[$i]['status']); ?></td>
                            <td><?php echo $common->highlight($help->status($list[$i]['status']), $list[$i]['status']); ?></td>
                            <td><?php echo $common->highlight(date('l jS \of F Y h:i:s A', $list[$i]['create_time']), $list[$i]['status']); ?></td>
                            <td><?php echo $common->highlight(date('l jS \of F Y h:i:s A', $list[$i]['modify_time']), $list[$i]['status']); ?></td>
                            <td><?php echo $common->highlight($admin->getOneField($list[$i]['admin_id']), $list[$i]['status']); ?></td>
                            <td><a href="help.view?open&ref=<?php echo $list[$i]['ref']; ?>">
							<?php echo $common->highlight("Open", $list[$i]['status']); ?>
                            </a></td>
                          </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>&nbsp;</th>
                        <th>Category</th>
                        <th>Customer Name</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Updated</th>
                        <th>Last Modified By</th>
                        <th>&nbsp;</th>s
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
