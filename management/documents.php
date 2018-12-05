<?php
	$redirect = "documents";
	include_once("../includes/functions.php");
	include_once("session.php");
	
	
	$counter = count($categories->listAll());
	
	if ($counter < 1) {
		header("location: categories?error=".urlencode('You must create at least on category before you can add a document'));
	}
	
	if ((isset($_POST['addAdmin'])) || (isset($_POST['editButton']))) {
		$add = $documents->add($_POST);
		if ($add) {
			if (isset($_POST['editButton'])) {
				header("location: ?done");
			} else {
				header("location: documents.sections?id=$add");
			}
		} else {
			header("location: ?error");
		}
	}
	
	if (isset($_GET['editAdmin'])) {
		$getData = $documents->getOne($common->get_prep($_GET['id']));
		$editAdmin = true;
			
	} else if (isset($_GET['deleteAdmin'])) {
		$edit = $documents->remove($common->get_prep($_GET['id']));
		if ($edit) {
			header("location: ?done");
		} else {
			header("location: ?error");
		}
	}
	
	$list = $documents->listAll();
	$catParent = $categories->sortAll(0, "parent_id", "status", "active");
	$clientList = $clients->listAll();
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
  <script src="../SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
  <link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
  <link href="../SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css">
  <link href="../SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
  </head>
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

      <?php $adminPages->topHeader();
	  $adminPages->sidebar("documents"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Documents
            <small>List All</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo URLAdmin; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo URLAdmin; ?>documents">Documents</a></li>
            <li class="active">Manage Documents</li>
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
            <?php if (isset($_GET['add'])) { ?>
              <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Add New Document</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form role="form" method="post" action="">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="title">Title <em>*</em>
                      <p class="help-block">Enter document title</p>
                      </label>
                      <input type="text" class="form-control" id="title" name="title" placeholder="Document Title" value="<?php echo $getData['title']; ?>" required></div>
                    <div class="form-group">
                      <label for="type">Type <em>*</em>
                      <p class="help-block">Select document type</p>
                      </label>
                      <select name="type" id="type" class="form-control select2" style="width: 100%;" onChange="toggleType()" required>
                      	<option value="">Select One</option>
                      	<option<?php if ($getData['type'] == "Books") { ?> selected<?php } ?> value="Books">Books</option>
                      	<option<?php if ($getData['type'] == "Law") { ?> selected<?php } ?> value="Law">Law</option>
                      	<option<?php if ($getData['type'] == "Law Reports") { ?> selected<?php } ?> value="Law Reports">Law Reports</option>
                      </select>
                      </div>
                  <div id="cat_div" class="form-group">
                    <label for="cat">Cartegory<em>*</em>
                    <p class="help-block">select document  category</p>
                    </label>
                    <input name="cat" id="cat" value="<?php echo $categories->getOneField($getData['cat']); ?>" type="text" class="form-control" onClick="window.open('selectCategory','Select Category','width=500,height=500,left=0,top=0,toolbar=0,location=0,statusbar=0,menubar=0,');" onFocus="window.open('selectCategory','Select Category','width=500,height=500,left=0,top=0,toolbar=0,location=0,statusbar=0,menubar=0,');" required readonly></div><!-- /.form-group -->
                  <?php if ($editAdmin === true) { ?>
                  <div class="form-group">
                    <label for="status">Display Status<em>*	</em>
                    <p class="help-block">Toggle display status on and off on the home screen</p></label>
                    <select name="status" id="status" class="form-control select2" required style="width: 100%;">
                        <option<?php if ($getData['status'] == "inactive") { ?> selected<?php } ?> value="inactive">Inactive</option>
                        <option<?php if ($getData['status'] == "active") { ?> selected<?php } ?> value="active">Active</option>
                      </select>
                  </div><!-- /.form-group -->
                  <?php } else { ?>
                  	<input type="hidden" id="status" name="status" value="inactive">
                  <?php } ?>
                    <div class="form-group">
                      <label for="owner">Owner<p class="help-block">Enrer document owner name</p>
                      </label>
                      
                    <select name="owner" id="owner" class="form-control select2" required style="width: 100%;">
                    	
                        <option<?php if ($getData['owner'] == "0") { ?> selected<?php } ?> value="0">Default</option>
                        <?php for ($i = 0; $i < count($clientList); $i++) { ?>
                        
                        <option<?php if ($getData['owner'] == $clientList[$i]['id']) { ?> selected<?php } ?> value="<?php echo $clientList[$i]['id']; ?>"><?php echo $clientList[$i]['company']." (".$clientList[$i]['name'].")"; ?></option>
                        <?php } ?>
                      </select></div>
                    <div class="form-group">
                      <label for="exampleInputFile">Year <em>*</em>
                      <p class="help-block">Select document creation or publication year</p>
                      </label>
                      <select name="year" id="year" class="form-control select2" style="width: 100%;" required>
                        <?php for ($i = date("Y"); $i > 1900; $i--) { ?>
                        <option value="<?php echo $i; ?>"<?php if ($getData['year'] == $i) { ?> selected<?php } ?>><?php echo $i; ?></option>
                        <?php } ?>
                      </select></div>
                    <div class="form-group">
                      <label for="tags">Tags and Keywords
                      <p class="help-block">Enter document keywords for search identification &quot;,&quot;</p>
                      </label>
                      <textarea name="tags" class="form-control" id="tags" required><?php echo $getData['tags']; ?></textarea>
                      <input type="hidden" name="cat_id" id="cat_id" value="">
                    </div>
                    <?php if ($editAdmin === true) { ?>
                    <div class="form-group">
                    <label> <a href="<?php echo URLAdmin; ?>documents.sections?id=<?php echo $getData['ref']; ?>">Click here to view/modify sections in this document</a></label>
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
            <?php } else { ?>
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Showing All Documents</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>&nbsp;
                          </td>
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
                        <td><a href="documents.view?id=<?php echo $list[$i]['ref']; ?>"><?php echo $list[$i]['title']; ?></a></td>
                        <td><?php echo $list[$i]['category_name']; ?></td>
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
                        <th>&nbsp;
                          </td>
                        <th><strong>Title</strong></th>
                        <th><strong>Parent Category</strong></th>
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
			var data = val.split("_");
			document.getElementById('cat').value = data[0];
			document.getElementById('cat_id').value = data[1];
		}
		
		function toggleType() {
			var type = document.getElementById('type').value;
			if (type == "Books") {
				document.getElementById('cat_div').style.display = "none";
			} else {
				document.getElementById('cat_div').style.display = "block";
			}
		}
    </script>
  </body>
</html>
