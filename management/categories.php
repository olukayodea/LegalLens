<?php
	$redirect = "categories";
	include_once("../includes/functions.php");
	include_once("session.php");
		
	if (isset($_REQUEST['id'])) {
		$id = $common->get_prep($_GET['id']);
		$tag = $categories->getOneField($id);
		if ($tag == "") {
			$id = 0;
			$tag = "Main Category";
		}
	} else {
		$id = 0;
		$tag = "Main Category";
	}
	
	if ((isset($_POST['addAdmin'])) || (isset($_POST['editButton']))) {
		$add = $categories->add($_POST);
		if ($add) {
			header("location: ?done");
		} else {
			header("location: ?error");
		}
	}
	
	if (isset($_GET['editAdmin'])) {
		$getData = $categories->getOne($common->get_prep($_GET['ref']));
		$editAdmin = true;
			
	} else if (isset($_GET['deleteAdmin'])) {
		$edit = $categories->remove($common->get_prep($_GET['ref']));
		if ($edit) {
			header("location: ?done");
		} else {
			header("location: ?error");
		}
	}
	
	$list = $categories->sortAll($id, "parent_id");
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>LegalLens | Document Manager</title>
    <?php $adminPages->headerFiles(); ?>
  <script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
  <script src="../SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
  <link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
  <link href="../SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css">
  </head>
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

      <?php $adminPages->topHeader();
	  $adminPages->sidebar("categories"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Categories
            <small>List All</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo URLAdmin; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo URLAdmin; ?>categories">Categories</a></li>
            <li class="active">Manage Categories</li>
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
            <?php if (isset($_GET['add'])) { ?>
              <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Add New Category</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form role="form" action="" method="post">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="title" class="form-label">Name <em>*</em><p class="help-block">Enter Category name</p></label>
                      <span id="sprytextfield1">
                      <input type="text" name="title" id="title" class="form-control" value="<?php echo $getData['title']; ?>" required>
                      <span class="textfieldRequiredMsg">A value is required.</span></span></div>
                  <div class="form-group">
                    <label for="status" class="form-label">Display Status<em>*</em><p class="help-block">Toggle display status on and off on the home screen</p></label>
                    <span id="spryselect1">
                    <select name="status" id="status" class="form-control select2" required style="width: 100%;">
                      <option<?php if ($getData['status'] == "active") { ?> selected<?php } ?> value="active">Active</option>
                      <option<?php if ($getData['status'] == "inactive") { ?> selected<?php } ?> value="inactive">Inactive</option>
                    </select>
                    <span class="selectRequiredMsg">Please select an item.</span></span></div><!-- /.form-group -->
                    <div class="form-group">
                      <label for="cat" class="form-label">Parent Category <em>*</em><p class="help-block">Select the parent category of this category or leave this option blank if this is a parent category, popups must be allowed for this function to work</p></label>
                      <span id="sprytextfield2">
                      <input name="cat" id="cat" value="<?php echo $categories->getOneField($getData['parent_id']); ?>" type="text" class="form-control" onClick="window.open('selectCategory','Select Category','width=500,height=500,left=0,top=0,toolbar=0,location=0,statusbar=0,menubar=0,');" onFocus="window.open('selectCategory','Select Category','width=500,height=500,left=0,top=0,toolbar=0,location=0,statusbar=0,menubar=0,');" readonly>
                      <span class="textfieldRequiredMsg">A value is required.</span></span>
                      <input type="hidden" name="cat_id" id="cat_id" value="">
                    </div>

                  <div class="box-footer">                    
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
            <?php } else { ?>
            <?php if ($read == 1) { ?>
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Showing <?php echo $tag; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                
                <?php if ($id > 0) { ?>
                
                  <button class="btn btn-primary" name="resetButton" id="resetButton" type="button" data-icon-primary="ui-icon-circle-check" onClick="window.location.href='<?php echo URLAdmin.$redirect."?id=".$catList[1]['ref']; ?>'">Go Back</button>
                  <?php } ?>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>&nbsp;</td>
                        <th><strong>Title</strong></th>
                        <th><strong>Parent Category</strong></th>
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
                            <td><a href="<?php echo URLAdmin.$redirect."?id=".$list[$i]['ref']; ?>"><?php echo $list[$i]['title']; ?></a></td>
                            <td><?php echo $categories->getOneField($list[$i]['parent_id']); ?></td>
                            <td><?php echo $common->get_time_stamp($list[$i]['create_time']); ?></td>
                            <td><?php echo $common->get_time_stamp($list[$i]['modify_time']); ?></td>
                            <td><a href="?id=<?php echo $id; ?>&add&view&editAdmin&ref=<?php echo $list[$i]['ref']; ?>">view</a><?php if ($modify == 1) { ?> | <a href="?id=<?php echo $id; ?>&add&editAdmin&ref=<?php echo $list[$i]['ref']; ?>">edit</a> | <a href="?id=<?php echo $id; ?>&deleteAdmin&ref=<?php echo $list[$i]['ref']; ?>" onClick="return confirm('this action will remove this category, its corresponding documents and document sections. are you sure you want to continue ?')">delete</a><?php } ?></td>
						</tr>
						<?php }
						unset($i); 
						unset($sn); ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>&nbsp;</td>
                        <th><strong>Title</strong></th>
                        <th><strong>Parent Category</strong></th>
                        <th><strong>Creation Date</strong></th>
                        <th><strong>Modified Last</strong></th>
                        <th>&nbsp;</th>
                      </tr>
                    </tfoot>
                  </table>
                
                <?php if ($id > 0) { ?>
                  <button class="btn btn-primary" name="resetButton" id="resetButton" type="button" data-icon-primary="ui-icon-circle-check" onClick="window.location.href='<?php echo URLAdmin.$redirect."?id=".$catList[1]['ref']; ?>'">Go Back</button>
                  <?php } ?>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
            <?php } ?>
            
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
			var data = val.split("_");
			document.getElementById('cat').value = data[0];
			document.getElementById('cat_id').value = data[1];
		}
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
    </script>
  </body>
</html>
