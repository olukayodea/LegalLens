<?php
	$redirect = "clients";
	include_once("../includes/functions.php");
	include_once("session.php");
	
	
	//administrator
	if (isset($_POST['addAdmin'])) {
		$add = $clients->create($_POST);
		if ($add) {
			header("location: ?done");
		} else {
			header("location: ?error");
		}
	} else if (isset($_POST['editButton'])) {
		$edit = $clients->update($_POST);
		if ($edit) {
			header("location: ?done");
		} else {
			header("location: ?error");
		}
	}
	
	if (isset($_GET['editAdmin'])) {
		$getData = $clients->listOne($common->get_prep($_GET['id']));
		$editAdmin = true;
				
	} else if (isset($_GET['deactivateAdmin'])) {
		$edit = $clients->deactivate($common->get_prep($_GET['id']));
		if ($edit) {
			header("location: ?done");
		} else {
			header("location: ?error");
		} 
	} else if (isset($_GET['deleteAdmin'])) {
		$edit = $clients->delete($common->get_prep($_GET['id']));
		if ($edit) {
			header("location: ?done");
		} else {
			header("location: ?error");
		}
	}
	
	$listAdmin = $clients->listAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>LegalLens | Administrator Manager</title>
    <?php $adminPages->headerFiles(); ?>
  <script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

      <?php $adminPages->topHeader();
	  $adminPages->sidebar("settings"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Administrators
            <small>Manage new and existing system Document Owners</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo URLAdmin; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <?php if (isset($_GET['new'])) { ?>
            <li><a href="<?php echo URLAdmin; ?>administrators">Administrators</a></li>
            <li class="active">Manage Administrators</li>
            <?php } else { ?>
            <li class="active">Document Owners</li>
            <?php } ?>
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
            <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Add New Document Owner</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form role="form" action="" method="post">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="name">Name <em>*</em>
                      <p class="help-block">Enter Full names</p></label>
                      <span id="sprytextfield1">
                      <input type="text" name="name" id="name" value="<?php echo $getData['name']; ?>" required class="form-control">
                      <span class="textfieldRequiredMsg">A value is required.</span></span></div>
                    <div class="form-group">
                      <label for="email">
                      Company <em>*</em>
                      <p  class="help-block">Enter company name for this owner, this name will always be shown where reference to this person is made</p>
                      </label>
                      <span id="sprytextfield2">
                        <input type="text" name="company" id="company" value="<?php echo $getData['company']; ?>" required class="form-control">
                        <span class="textfieldRequiredMsg">A value is required.</span></span></div>
                    <div class="form-group">
                      <label for="email">
                      Email <em>*</em>
                      <p  class="help-block">Enter email of administrator, the username and password will be sent to this address</p>
                      </label>
                      <span id="sprytextfield4">
                        <input type="email" name="email" id="email" value="<?php echo $getData['email']; ?>" required class="form-control">
                        <span class="textfieldRequiredMsg">A value is required.</span></span></div>
                    <div class="form-group">
                      <label for="phone">Phone <em>*</em><p  class="help-block">Administrator phone number</p></label>
                      <span id="sprytextfield3">
                      <input name="phone" type="number" id="phone" required value="<?php echo $getData['phone']; ?>" class="form-control">
                      <span class="textfieldRequiredMsg">A value is required.</span></span></div>
                  </div><!-- /.box-body -->

                  <div class="box-footer">
					<?php if ($editAdmin === true) { ?>
                    <input type="hidden" name="id" value="<?php echo $editID; ?>">
                    <button class="btn btn-primary" name="editButton" id="editButton" type="submit" data-icon-primary="ui-icon-circle-check">Save Changes</button>
                    <button class="btn btn-primary" name="button2" id="button" type="button" onClick="location='<?php echo $redirect; ?>'" data-icon-primary="ui-icon-circle-check">Cancel</button>
                    <?php } else { ?>
                    <button class="btn btn-primary" name="addAdmin" id="addAdmin" type="submit" data-icon-primary="ui-icon-circle-check">Add</button>
                    <?php } ?>
                  </div>
                </form>
            </div><!-- /.box -->

              <!-- Form Element sizes --><!-- /.box --><!-- /.box -->

              <!-- Input addon --><!-- /.box -->

            </div>
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Showing All Document Owners</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>&nbsp;</th>
                        <th>Company</th>
                        <th>Full Names</th>
                        <th>E-Mail</th>
                        <th>Creation Date</th>
                        <th>Modified Last</th>
                        <th>Status</th>
                        <th>&nbsp;</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php for ($i = 0; $i < count($listAdmin); $i++) {
$sn++; ?>
                          <tr>
                            <td><?php echo $sn; ?></td>
                            <td><?php echo $listAdmin[$i]['company']; ?></td>
                            <td><?php echo $listAdmin[$i]['name']; ?></td>
                            <td><?php echo $listAdmin[$i]['email']; ?></td>
                            <td><?php echo $common->get_time_stamp($listAdmin[$i]['create_time']); ?></td>
                            <td><?php echo $common->get_time_stamp($listAdmin[$i]['modify_time']); ?></td>
                            <td><?php echo $listAdmin[$i]['status']; ?></td>
                            <td><a href="?new&add&editAdmin&id=<?php echo $listAdmin[$i]['id']; ?>&tab=<?php echo $Tab4; ?>">edit</a>
                              <?php if ($listAdmin[$i]['id'] != $ref) { ?>
                              | <a href="?deactivateAdmin&id=<?php echo $listAdmin[$i]['id']; ?>">de-activate</a> | <a href="?deleteAdmin&id=<?php echo $listAdmin[$i]['id']; ?>" onClick="return confirm('this action will remove this user. are you sure you want to continue ?')">delete</a>
                              <?php } ?></td>
                          </tr>
                          <?php }
                unset($i); 
                unset($sn); ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>&nbsp;</th>
                        <th>Company</th>
                        <th>Full Names</th>
                        <th>E-Mail</th>
                        <th>Creation Date</th>
                        <th>Modified Last</th>
                        <th>Status</th>
                        <th>&nbsp;</th>
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
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
    </script>
</body>
</html>
