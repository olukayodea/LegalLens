<?php
	$redirect = "CaseLaw";
	include_once("../includes/functions.php");
	include_once("session.php");
	
	$listReg = $caselaw_area->sortAll("active", "status");
	
	if (count($listReg) < 1) {
		header("location: CaseLaw.subject?error=".urlencode('You must create at least on area of law before you can add a case law'));
	}
	
	if ((isset($_POST['addAdmin'])) || (isset($_POST['editButton']))) {
		$add = $caselaw->add($_POST, $_FILES);
		if ($add) {
			if ((is_numeric($add)) && ($add > 0)) {
				if (isset($_POST['editButton'])) {
					header("location: ?done");
				} else {
					header("location: CaseLaw.sections?id=$add");
				}
			}
		} else {
			header("location: ?error=".urlencode($add));
		}
	}
	
	if (isset($_GET['editAdmin'])) {
		$getData = $caselaw->getOne($common->get_prep($_GET['id']));
		$editAdmin = true;
			
	} else if (isset($_GET['deleteAdmin'])) {
		$edit = $caselaw->remove($common->get_prep($_GET['id']));
		if ($edit) {
			header("location: ?done");
		} else {
			header("location: ?error");
		}
	} else if (isset($_GET['deleteFile'])) {
		$edit = $caselaw->removeFile($common->get_prep($_GET['id']));
		if ($edit) {
			header("location: ?done");
		} else {
			header("location: ?error");
		}
	}
	
	$list = $caselaw->listAll();
	$clientList = $clients->listAll();
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
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
  <link href="../SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

      <?php $adminPages->topHeader();
	  $adminPages->sidebar("list"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>Case Law <small>Manage Cases</small> </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo URLAdmin; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Case Law</li>
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
            <?php if (($write == 1) || ($modify ==1)) { ?>
            <div class="box box-primary">
              <div class="box-header with-border">
                  <h3 class="box-title">Add New Case Law</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form action="" method="post" enctype="multipart/form-data" role="form">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="title">Title <em>*</em>
                      <p class="help-block">Enter case title</p>
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
                    <label for="court">
                    Court <em>*</em>
                    <p class="help-block">Select applicable court</p>
                    </label>
                    <span id="spryselect3">
                    <select name="court" id="court" class="form-control select2" style="width: 100%;" required>
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
                  <div class="form-group">
                    <label for="exampleInputFile">
                    Year <em>*</em>
  <p class="help-block">Select year</p>
  </label>
  <span id="spryselect5">
  <select name="year" id="year" class="form-control select2" style="width: 100%;" required>
    <?php for ($i = date("Y"); $i > 1900; $i--) { ?>
    <option value="<?php echo $i; ?>"<?php if ($getData['year'] == $i) { ?> selected<?php } ?>><?php echo $i; ?></option>
    <?php } ?>
  </select>
  <span class="selectRequiredMsg">Please select an item.</span></span></div>
                  <div class="form-group">
                    <label for="exampleInputFile">
                    Area of Law<em>*</em>
                    <p class="help-block">Select corresponding area of law</p>
                  </label>
                  <span id="spryselect">
                  <select name="area[]" id="area" class="form-control select2" style="width: 100%;" required multiple>
                      <?php for ($i = 0; $i < count($listReg); $i++) { ?>
                      <option value="<?php echo $listReg[$i]['title']; ?>"<?php if ($getData['areas'] == $listReg[$i]['title']) { ?> selected<?php } ?>><?php echo $listReg[$i]['title']; ?></option>
                      <?php } ?>
  </select>
  <span class="selectRequiredMsg">Please select an item.</span></span></div>
                  <div class="form-group">
                    <label for="owner">
                    Owner
                    <p class="help-block">Enrer document owner name</p>
                    </label>
                    <select name="owner" id="owner" class="form-control select2" required style="width: 100%;">
                      <option<?php if ($getData['owner'] == "0") { ?> selected<?php } ?> value="0">Default</option>
                      <?php for ($i = 0; $i < count($clientList); $i++) { ?>
                      <option<?php if ($getData['owner'] == $clientList[$i]['id']) { ?> selected<?php } ?> value="<?php echo $clientList[$i]['id']; ?>"><?php echo $clientList[$i]['company']." (".$clientList[$i]['name	'].")"; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="reporter">
                    Reporter <em>*</em>
                    <p class="help-block">the case as reported by</p>
                    </label>
                    <span id="sprytextfield"><span id="sprytextfield1">
                    <input type="text" class="form-control" id="reporter" name="reporter" placeholder="Reporter" value="<?php echo $getData['reporter']; ?>" required>
                    <span class="textfieldRequiredMsg">A value is required.</span></span><span class="textfieldRequiredMsg">A value is required.</span></span></div>
                  <?php if ($getData['file'] == "") { ?>
                  <div class="form-group">
                    <label for="media_file">
                    Case File<em>*</em>
                    <p class="help-block">Upload case file</p>
                    </label>
                    <span id="sprytextfield2">
                    <input type="file" name="media_file" id="media_file" class="form-control">
                    <span class="textfieldRequiredMsg">A value is required.</span></span>
                    <input name="file" id="file" type="hidden" value="0">
                  </div>
                  <?php } else { ?>
                  <div class="form-group">
                  <input name="file" id="file" type="hidden" value="1">
                  <a href="<?php echo URL; ?>library/caselaws/<?php echo $getData['file']; ?>" target="_blank">download file</a> | <a href="?deleteFile&id=<?php echo $getData['ref']; ?>" onClick="return confirm('this action will remove this document. are you sure you want to continue ?')">remove file</a></div>
                  </div>
                  <?php } ?>
<?php if ($editAdmin === true) { ?>
                    <div class="form-group">
                    <label> <a href="<?php echo URLAdmin; ?>CaseLaw.sections?id=<?php echo $getData['ref']; ?>">Click here to view/modify ratios in this case</a></label>
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
                    <button class="btn btn-primary" name="addAdmin" id="addAdmin" type="submit" data-icon-primary="ui-icon-circle-check">Add Ratios and Save</button>
                    <?php } ?>
                  </div>
                </form>
            </div><!-- /.box -->

              <!-- Form Element sizes --><!-- /.box --><!-- /.box -->

              <!-- Input addon --><!-- /.box -->

            <?php } ?>
            <?php if ($read == 1) { ?>
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Showing All Case Laws</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>&nbsp;</td>
                        <th><strong>Title</strong></th>
                        <th><strong>Court</strong></th>
                        <th><strong>Year</strong></th>
                        <th><strong>Reporter</strong></th>
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
                        <td><?php echo $list[$i]['court']; ?></td>
                        <td><?php echo $list[$i]['year']; ?></td>
                        <td><?php echo $list[$i]['reporter']; ?></td>
                        <td><?php echo $list[$i]['status']; ?></td>
                        <td><?php echo $common->get_time_stamp($list[$i]['create_time']); ?></td>
                        <td><?php echo $common->get_time_stamp($list[$i]['modify_time']); ?></td>
                        <td><?php if ($modify == 1) { ?><a href="?add&view&editAdmin&id=<?php echo $list[$i]['ref']; ?>">view</a> | <a href="?add&editAdmin&id=<?php echo $list[$i]['ref']; ?>">edit</a> | <a href="?deleteAdmin&id=<?php echo $list[$i]['ref']; ?>" onClick="return confirm('this action will remove this document. are you sure you want to continue ?')">delete</a><?php } ?></td>
                      </tr>
                      <?php }
						unset($i); 
						unset($sn); ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>&nbsp;</td>
                        <th><strong>Title</strong></th>
                        <th><strong>Court</strong></th>
                        <th><strong>Year</strong></th>
                        <th><strong>Reporter</strong></th>
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
              <?php } ?>
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
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
    </script>
</body>
</html>
