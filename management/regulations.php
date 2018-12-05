<?php
	$redirect = "regulations";
	include_once("../includes/functions.php");
	include_once("session.php");
	
	
	$listReg = $regulators->sortAll("active", "status");
	
	if (count($listReg) < 1) {
		header("location: regulations.create?error=".urlencode('You must create at least one regulator before you can add a circular or regulation'));
	}
	
	if ((isset($_POST['addAdmin'])) || (isset($_POST['editButton']))) {
		$add = $regulations->add($_POST);
		if ($add) {
			if (isset($_POST['editButton'])) {
				header("location: ?done");
			} else {
				header("location: regulations.sections?id=$add");
			}
		} else {
			header("location: ?error");
		}
	}
	
	if (isset($_GET['editAdmin'])) {
		$getData = $regulations->getOne($common->get_prep($_GET['id']));
		$editAdmin = true;
			
	} else if (isset($_GET['deleteAdmin'])) {
		$edit = $regulations->remove($common->get_prep($_GET['id']));
		if ($edit) {
			header("location: ?done");
		} else {
			header("location: ?error");
		}
	}
	
	$list = $regulations->listAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>LegalLens | Circulars and Regulations</title>
    <?php $adminPages->headerFiles(); ?>
  <script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
  <script src="../SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
  <script src="../SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
  <link href="../SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css">
  <link href="../SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

      <?php $adminPages->topHeader();
	  $adminPages->sidebar("list"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1> Circulars and Regulations <small>Manage Circulars and Regulations</small> </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo URLAdmin; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Create</li>
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
                  <h3 class="box-title">Add New Circular/Regulation</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form role="form" method="post" action="">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="title">Title <em>*</em>
                      <p class="help-block">Enter document title</p>
                      </label>
                      <span id="sprytextfield3">
                      <input type="text" class="form-control" id="title" name="title" placeholder="Document Title" value="<?php echo $getData['title']; ?>" required>
                      <span class="textfieldRequiredMsg">A value is required.</span></span></div><!-- /.form-group -->
                  <?php if ($editAdmin === true) { ?>
                  <div class="form-group">
                    <label for="status">Display Status<em>*</em><p class="help-block">Toggle display status on and off on the home screen</p></label>
                    <span id="spryselect2">
                    <select name="status" id="status" class="form-control select2" required style="width: 100%;">
                      <option<?php if ($getData['status'] == "inactive") { ?> selected<?php } ?> value="inactive">Inactive</option>
                      <option<?php if ($getData['status'] == "active") { ?> selected<?php } ?> value="active">Active</option>
                    </select>
                    <span class="selectRequiredMsg">Please select an item.</span></span></div><!-- /.form-group -->
                  <?php } else { ?>
                  	<input type="hidden" id="status" name="status" value="inactive">
                  <?php } ?>
                  <div class="form-group">
                    <label for="regulator">
                    Regulator <em>*</em>
                    <p class="help-block">Select regulator</p>
                    </label>
                    <span id="spryselect3">
                    <select name="regulator" id="regulator" class="form-control select2" style="width: 100%;" required>
                      <option value="">Select One</option>
                      <?php for ($i = 0; $i < count($listReg); $i++) { ?>
                      <option value="<?php echo $listReg[$i]['title']; ?>"<?php if ($getData['regulator'] == $listReg[$i]['title']) { ?> selected<?php } ?>><?php echo $listReg[$i]['title']; ?></option>
                      <?php } ?>
                    </select>
                    <span class="selectRequiredMsg">Please select an item.</span></span></div>
                  <div class="form-group">
                    <label for="exampleInputFile">
                    Year <em>*</em>
                    <p class="help-block">Select document creation or publication year</p>
                    </label>
                    <span id="year"><span id="spryselect5">
                    <select name="year" id="year" class="form-control select2" style="width: 100%;" required>
                      <?php for ($i = date("Y"); $i > 1900; $i--) { ?>
                      <option value="<?php echo $i; ?>"<?php if ($getData['year'] == $i) { ?> selected<?php } ?>><?php echo $i; ?></option>
                      <?php } ?>
                    </select>
                    <span class="selectRequiredMsg">Please select an item.</span></span></span></div>
                  <div class="form-group">
                    <label for="type">
                    Type <em>*</em>
                    <p class="help-block">Select  type</p>
                    </label>
                    <span id="spryselect4">
                    <select name="type" id="type" class="form-control select2" style="width: 100%;" required>
                      <option value="">Select One</option>
                      <option<?php if ($getData['type'] == "Circular") { ?> selected<?php } ?> value="Circular">Circular</option>
                      <option<?php if ($getData['type'] == "Regulations") { ?> selected<?php } ?> value="Regulations">Regulations</option>
                    </select>
                    <span class="selectRequiredMsg">Please select an item.</span></span></div>
                  <div class="form-group">
                      <label for="tags">Tags and Keywords
                    <p class="help-block">Enter document keywords for search identification &quot;,&quot;</p>
                      </label>
                      <span id="sprytextarea1">
                      <textarea name="tags" class="form-control" id="tags" required><?php echo $getData['tags']; ?></textarea>
                      <span class="textareaRequiredMsg">A value is required.</span></span></div>
                    <?php if ($editAdmin === true) { ?>
                    <div class="form-group">
                    <label> <a href="<?php echo URLAdmin; ?>regulations.sections?id=<?php echo $getData['ref']; ?>">Click here to view/modify sections in this document</a></label>
                    </div>
                    <?php } ?>
                  </div><!-- /.box-body -->

                  <div class="box-footer">               
                    <?php if ($editAdmin === true) { ?>
					<?php if (!isset($_GET['view'])) { ?>
                    <input type="hidden" name="ref" value="<?php echo $getData['ref']; ?>">
                    <button class="btn btn-primary" name="editButton" id="editButton" type="submit"  data-icon-primary="ui-icon-circle-check">Save Changes</button>
                    <?php } ?>
                    <button class="btn btn-primary" name="button2" id="button" type="button" onClick="location='<?php echo $redirect; ?>'" data-icon-primary="ui-icon-circle-check">Cancel</button>
                    <?php } else { ?>
                    <button class="btn btn-primary" name="addAdmin" id="addAdmin" type="submit" data-icon-primary="ui-icon-circle-check">Add Sections and Save</button>
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
                  <h3 class="box-title">Showing All Circular/Regulation</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>&nbsp;</td>
                        <th><strong>Title</strong></th>
                        <th><strong>Regulator</strong></th>
                        <th><strong>Date</strong></th>
                        <th><strong>Type</strong></th>
                        <th><strong>Status</strong></th>
                        <th><strong>Creation Date</strong></th>
                        <th><strong>Modified Last</strong></th>
                        <th>&nbsp;</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php for ($i = 0; $i < count($list); $i++) {
						$sn++; ?>
                      <tr>
                        <td><?php echo $sn; ?></td>
                        <td><?php echo $list[$i]['title']; ?></td>
                        <td><?php echo $list[$i]['regulator']; ?></td>
                        <td><?php echo $list[$i]['year']; ?></td>
                        <td><?php echo $list[$i]['type']; ?></td>
                        <td><?php echo $list[$i]['status']; ?></td>
                        <td><?php echo $common->get_time_stamp($list[$i]['create_time']); ?></td>
                        <td><?php echo $common->get_time_stamp($list[$i]['modify_time']); ?></td>
                        <td><a href="?add&view&editAdmin&id=<?php echo $list[$i]['ref']; ?>">view</a> | <a href="?add&editAdmin&id=<?php echo $list[$i]['ref']; ?>">edit</a> | <a href="?deleteAdmin&id=<?php echo $list[$i]['ref']; ?>" onClick="return confirm('this action will remove this document. are you sure you want to continue ?')">delete</a></td>
                      </tr>
                      <?php }
						unset($i); 
						unset($sn); ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>&nbsp;</td>
                        <th><strong>Title</strong></th>
                        <th><strong>Regulator</strong></th>
                        <th><strong>Type</strong></th>
                        <th><strong>Status</strong></th>
                        <th><strong>Creation Date</strong></th>
                        <th><strong>Modified Last</strong></th>
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
	  
		function pasteValue(val) {
			document.getElementById('cat').value = val;
		}
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
var spryselect2 = new Spry.Widget.ValidationSelect("spryselect2");
var spryselect3 = new Spry.Widget.ValidationSelect("spryselect3");
var spryselect4 = new Spry.Widget.ValidationSelect("spryselect4");
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1");
var spryselect5 = new Spry.Widget.ValidationSelect("spryselect5");
    </script>
</body>
</html>
