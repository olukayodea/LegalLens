<?php
	$redirect = "knowledgeBase.categories";
	include_once("../includes/functions.php");
	include_once("session.php");
	//administrator
	
	if ((isset($_POST['addAdmin'])) || (isset($_POST['editButton']))) {
		$add = $knowledge_base_category->add($_POST);
		if ($add) {
			header("location: ?done");
		} else {
			header("location: ?error");
		}
	}
	
	if (isset($_GET['editAdmin'])) {
		$getData = $knowledge_base_category->getOne($common->get_prep($_GET['id']));
		
		$editID = $getData['id'];
		$editAdmin = true;
			
	} else if (isset($_GET['deleteAdmin'])) {
		$edit = $knowledge_base_category->remove($common->get_prep($_GET['id']));
		if ($edit) {
			header("location: ?done");
		} else {
			header("location: ?error");
		}
	}
	

	$listAdmin = $knowledge_base_category->listAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>LegalLens | Manage Knowledge Base</title>
    <?php $adminPages->headerFiles(); ?>
  <script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
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
            <small>Manage KnowledgeBase Category</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo URLAdmin; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">KnowledgeBase</li>
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
                  <h3 class="box-title">Add New Knowledge Base Category</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form role="form" action="" method="post">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="title">Title <em>*</em>
                      <p class="help-block">Enter Knowledge Base category Title</p></label>
                      <span id="sprytextfield1">
                      <input type="text" class="form-control" name="title"<?php if (isset($_REQUEST['view'])) { ?> disabled<?php } ?> id="title" value="<?php echo $getData['title']; ?>" required>
                      <span class="textfieldRequiredMsg">A value is required.</span></span></div>
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
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Showing All Categories</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>&nbsp;</td>
                        <th><strong>Title</strong></th>
                        <th>&nbsp;</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php for ($i = 0; $i < count($listAdmin); $i++) {
                                    $sn++; ?>
                      <tr>
                        <td><?php echo $sn; ?></td>
                        <td><?php echo $listAdmin[$i]['title']; ?></td>
                        <td><a href="?view&editAdmin&id=<?php echo $listAdmin[$i]['ref']; ?>">view</a> | <a href="?editAdmin&id=<?php echo $listAdmin[$i]['ref']; ?>">edit</a> | <a href="?deleteAdmin&id=<?php echo $listAdmin[$i]['ref']; ?>">Delete</a></td>
                      </tr>
                      <?php }
							unset($i); 
						unset($sn); ?>
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
		function setSelectedIndex(s, valsearch) {
			// Loop through all the items in drop down list
			for (i = 0; i< s.options.length; i++) { 
				if (s.options[i].value == valsearch) {
				// Item is found. Set its property and exit
				s.options[i].selected = true;
				break;
				}
			}
			return;
		}
		
		function setMultiSelectedIndex(s, data) {
			var main = data.split(",");
			// Loop through all the items in drop down list
			for (var j = 0; j < main.length; j++) {
				var opt = main[j];
				for (i = 0; i< s.options.length; i++) { 
					if (s.options[i].value == opt) {
					// Item is found. Set its property and exit
					s.options[i].selected = true;
					break;
					}
				}
			}
			return;
		}
		$(document).ready(function() {
			setSelectedIndex(document.getElementById("mainPage"),"<?php echo $editMain; ?>");
			setSelectedIndex(document.getElementById("level"),"<?php echo $editLevel; ?>");
			setMultiSelectedIndex(document.getElementById("pages"),"<?php echo $getData['pages']; ?>");
		});
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
    </script>
</body>
</html>
