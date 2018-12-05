<?php
	$redirect = "list";
	include_once("../includes/functions.php");
	include_once("session.php");
	
	if (isset($_REQUEST['courts'])) {
		$courts = true;
		$judges = false;
		$sans = false;
		$tag = "Court";
		$link = "courts";
	} else if (isset($_REQUEST['judges'])) {
		$courts = false;
		$judges = true;
		$sans = false;
		$tag = "Judge";
		$link = "judges";
	} else if (isset($_REQUEST['sans'])) {
		$courts = false;
		$judges = false;
		$sans = true;
		$tag = "SAN";
		$link = "sans";
	} else {
		header("location: ".$redirect."?courts");
	}
	
	if ((isset($_POST['addAdmin'])) || (isset($_POST['editButton']))) {
		$add = $listItem->add($_POST);
		if ($add) {
			header("location: ?".$link."&done");
		} else {
			header("location: ?".$link."&error");
		}
	}
	
	if (isset($_GET['editAdmin'])) {
		$getData = $listItem->getOne($common->get_prep($_GET['id']));
		$editAdmin = true;
			
	} else if (isset($_GET['deleteAdmin'])) {
		$edit = $listItem->remove($common->get_prep($_GET['id']));
		if ($edit) {
			header("location: ?".$link."&done");
		} else {
			header("location: ?".$link."&error");
		}
	}
	
	$list = $listItem->sortAll($tag, "type");
	$stateList = $common->stateList();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>LegalLens | Listings</title>
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
          <h1>
            Listings
            <small><?php echo $tag; ?></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo URLAdmin; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo URLAdmin; ?>list">Listings</a></li>
            <li class="active"><?php echo $tag; ?></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row"><div class="col-md-12">
          		<?php if (isset($_GET['error'])) { ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                    An error occured, please try again. <?php echo $common->get_prep($_GET['error']); ?>
            </div>
                  <?php } else if (isset($_REQUEST['done'])) { ?>
                  <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4>	<i class="icon fa fa-check"></i> Message!</h4>
                    Actions performed successfully
                  </div>
                  <?php } ?>
              <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Add New <?php echo $tag; ?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form role="form" action="" method="post">
                  <div class="box-body">
                   <?php if ($courts == false ) { ?>
                    <div class="form-group">
                      <label for="title" class="form-label">Title <em>*</em>
                      <p class="help-block">Enter <?php echo $tag; ?> name prefix</p></label>
                      <span id="spryselect4">
                      <select name="pref" id="pref" class="form-control select2" required style="width: 100%;">
                        <option<?php if ($getData['pref'] == "Bar") { ?> selected<?php } ?> value="Bar">Bar</option>
                        <option<?php if ($getData['pref'] == "Chief") { ?> selected<?php } ?> value="Chief">Chief</option>
                        <option<?php if ($getData['pref'] == "Dr") { ?> selected<?php } ?> value="Dr">Dr</option>
                        <option<?php if ($getData['pref'] == "Hon") { ?> selected<?php } ?> value="Hon">Hon</option>
                        <option<?php if ($getData['pref'] == "Hon Justice") { ?> selected<?php } ?> value="Hon Justice">Hon Justice</option>
                        <option<?php if ($getData['pref'] == "Mr") { ?> selected<?php } ?> value="Mr">Mr</option>
                        <option<?php if ($getData['pref'] == "Mrs") { ?> selected<?php } ?> value="Mrs">Mrs</option>
                        <option<?php if ($getData['pref'] == "Ms") { ?> selected<?php } ?> value="Ms">Ms</option>
                        <option<?php if ($getData['pref'] == "Right Hon") { ?> selected<?php } ?> value="Right Hon">Right Hon</option>
                        <option<?php if ($getData['pref'] == "Prof") { ?> selected<?php } ?> value="Prof">Prof</option>
                      </select>
                      <span class="selectRequiredMsg">Please select an item.</span></span></div>
                   <?php } ?>
                  <div class="form-group">
                      <label for="title" class="form-label">Name <em>*</em>
                      <p class="help-block">Enter <?php echo $tag; ?> name</p></label>
                      <span id="sprytextfield1">
                      <input type="text" name="title" id="title" class="form-control" value="<?php echo $getData['title']; ?>" required>
                      <span class="textfieldRequiredMsg">A value is required.</span></span></div>
                    <?php if ($sans == false ) { ?>
                    <div class="form-group">
                      <label for="court" class="form-label">
                      Associated Court<em>*</em>
                      <p class="help-block">Select the court type associated with this entry</p>
                      </label>
                      <span id="spryselect2">
                      <select name="court" id="court" class="form-control select2" required style="width: 100%;">
                        <option value="">Select Associated Court</option>
                        <option<?php if ($getData['court'] == "Supreme Court") { ?> selected<?php } ?> value="Supreme Court">Supreme Court</option>
                        <option<?php if ($getData['court'] == "Court of Appeal") { ?> selected<?php } ?> value="Court of Appeal">Court of Appeal</option>
                        <option<?php if ($getData['court'] == "Federal High Court") { ?> selected<?php } ?> value="Federal High Court">Federal High Court</option>
                        <option<?php if ($getData['court'] == "State High Court") { ?> selected<?php } ?> value="State High Court">State High Court</option>
                        <option<?php if ($getData['court'] == "Magistrate Court") { ?> selected<?php } ?> value="Magistrate Court">Magistrate Court</option>
                        <option<?php if ($getData['court'] == "Customary Court") { ?> selected<?php } ?> value="Customary Court">Customary Court</option>
                        <option<?php if ($getData['court'] == "Sharia Court") { ?> selected<?php } ?> value="Sharia Court">Sharia Court</option>
                      </select>
                      <span class="selectRequiredMsg">Please select an item.</span></span></div>
                    <?php }
					if ($courts == true) { ?>
                  <div class="form-group">
                      <label for="state" class="form-label">
                      State <em>*</em>
                      <p class="help-block">Select location</p>
                      </label>
                      <span id="spryselect3">
                      <select name="state" id="state" class="form-control select2" required style="width: 100%;">
                        <option value="">Select State</option>
                        <?php for ($i = 0; $i < count($stateList); $i++) { ?>
                        <option<?php if ($getData['state'] == $stateList[$i]['state']) { ?> selected<?php } ?> value="<?php echo $stateList[$i]['state']; ?>"><?php echo $stateList[$i]['state']; ?></option>
                        <?php } ?>
                      </select>
                      <span class="selectRequiredMsg">Please select an item.</span></span></div>
                    <?php }
					if ($courts != true) { ?>
                    <div class="form-group">
                    <label for="exampleInputFile">
                    Year <em>*</em>
                    <p class="help-block">Select associated year for this entry</p>
                    </label>
                    <span id="year"><span id="spryselect5">
                    <select name="year" id="year" class="form-control select2" style="width: 100%;" required>
                      <?php for ($i = date("Y"); $i > 1900; $i--) { ?>
                      <option value="<?php echo $i; ?>"<?php if ($getData['year'] == $i) { ?> selected<?php } ?>><?php echo $i; ?></option>
                      <?php } ?>
                    </select>
                    <span class="selectRequiredMsg">Please select an item.</span></span><span class="selectRequiredMsg">Please select an item.</span></span></div>
                    <?php } ?>
                  <div class="form-group">
                      <label for="details" class="form-label">
                      Details <em>*</em>
                      <p class="help-block">Enter all the details associated with this entry</p>
                      </label>
                      <textarea name="details" class="form-control" rows="10" id="details" required><?php echo $getData['details']; ?></textarea></div>
                  <div class="form-group">
                      <label for="status" class="form-label">Display Status<em>*</em>
                      <p class="help-block">Toggle display status on and off on the home screen</p>
                      </label>
                      <span id="spryselect1">
                      <select name="status" id="status" class="form-control select2" required style="width: 100%;">
                        <option<?php if ($getData['status'] == "active") { ?> selected<?php } ?> value="active">Active</option>
                        <option<?php if ($getData['status'] == "inactive") { ?> selected<?php } ?> value="inactive">Inactive</option>
                      </select>
                      <span class="selectRequiredMsg">Please select an item.</span></span></div>
                    <!-- /.form-group -->

                  <div class="box-footer">   
                    <input type="hidden" name="type" value="<?php echo $tag; ?>">
                    <?php if ($editAdmin === true) { ?>
					<?php if (!isset($_GET['view'])) { ?>
                    <input type="hidden" name="ref" value="<?php echo $getData['ref']; ?>">
                    <button class="btn btn-primary" name="editButton" id="editButton" type="submit"  data-icon-primary="ui-icon-circle-check">Save Changes</button>
                    <?php } ?>
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
                  <h3 class="box-title">Showing <?php echo $tag; ?> Listings</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>&nbsp;</td>
                        <th><strong>Name</strong></th>
                        <?php if ($sans == false) { ?>
                        <th><strong>Court Type</strong></th>
                        <?php } ?>
                        <?php if ($courts == true) { ?>
                        <th><strong>Location</strong></th>
                        <?php } ?>
                        <?php if ($courts != true) { ?>
                        <th><strong>Date</strong></th>
                        <?php } ?>
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
                        <?php if ($sans == false) { ?>
                            <td><?php echo $list[$i]['court']; ?></td>
                        <?php } ?>
                        <?php if ($courts == true) { ?>
                            <td><?php echo $list[$i]['state']; ?></td>
                        <?php } ?>
                        <?php if ($courts != true) { ?>
                            <td><?php echo $list[$i]['year']; ?></td>
                        <?php } ?>
                            <td><?php echo $list[$i]['status']; ?></td>
                            <td><?php echo $common->get_time_stamp($list[$i]['create_time']); ?></td>
                            <td><?php echo $common->get_time_stamp($list[$i]['modify_time']); ?></td>
                            <td><a href="?<?php echo $link; ?>&add&view&editAdmin&id=<?php echo $list[$i]['ref']; ?>">view</a> | <a href="?<?php echo $link; ?>&editAdmin&id=<?php echo $list[$i]['ref']; ?>">edit</a> | <a href="?<?php echo $link; ?>&deleteAdmin&id=<?php echo $list[$i]['ref']; ?>" onClick="return confirm('this action will remove this listing, are you sure you want to continue ?')">delete</a></td>
						</tr>
						<?php }
						unset($i); 
						unset($sn); ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>&nbsp;</td>
                        <th><strong>Name</strong></th>
                        <?php if ($sans == false) { ?>
                        <th><strong>Court Type</strong></th>
                        <?php } ?>
                        <?php if ($courts == true) { ?>
                        <th><strong>Location</strong></th>
                        <?php } ?>
                        <th><strong>Status</strong></th>
                        <th><strong>Creation Date</strong></th>
                        <th><strong>Modified Last</strong></th>
                        <th>&nbsp;</th>
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
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1");
var spryselect2 = new Spry.Widget.ValidationSelect("spryselect2");
var spryselect3 = new Spry.Widget.ValidationSelect("spryselect3");
var spryselect4 = new Spry.Widget.ValidationSelect("spryselect4");
var spryselect5 = new Spry.Widget.ValidationSelect("spryselect5");
    </script>
  </body>
</html>
