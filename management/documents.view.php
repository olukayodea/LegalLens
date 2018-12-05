<?php
	$redirect = "documents.view";
	include_once("../includes/functions.php");
	include_once("session.php");
	
	if (isset($_REQUEST['id'])) {
		$id = $common->get_prep($_GET['id']);
	} else {
		header("location: documents?error=".urlencode('You must create a document first'));
	}
	
	$data = $documents->getOne($id);
	
	
	
	if ((isset($_POST['addAdmin'])) || (isset($_POST['addAdmin'])) || (isset($_POST['editButton_2']))) {
		$add = $sections->add($_POST);
		if ($add) {
			if (isset($_POST['editButton_2'])) {
				header("location: documents?id=".$id);
			} else {
				header("location: ?done&id=".$id);
			}
		} else {
			header("location: ?done&id=".$id);
		}
	}
	
	if (isset($_GET['editAdmin'])) {
		$getData = $sections->getOne($common->get_prep($_GET['editRef']));
		$editAdmin = true;
			
	} else if (isset($_GET['deleteAdmin'])) {
		$edit = $sections->remove($common->get_prep($_GET['del']));
		if ($edit) {
			header("location: ?done&id=".$id);
		} else {
			header("location: ?done&id=".$id);
		}
	}
	
	$list = $sections->sortAll($id,"document");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>LegalLens | Document Manager</title>
    <?php $adminPages->headerFiles(); ?>
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
            <?php echo $data['title']; ?>
            <small>View Document</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo URLAdmin; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo URLAdmin; ?>documents">Documents</a></li>
            <li class="active">View Documents</li>
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
                  <h3 class="box-title">Document Properties</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
              <div class="form-group">
                  <label for="status" class="form-label">
                  Document Title
                <p class="help-block"><?php echo $data['title']; ?></p>
                <?php if ($data['type'] != "Books") { ?>
                  Document Category
                <p class="help-block"><?php echo $data['category_name']; ?></p>
                <?php } ?>
                  Document Owner
                <p class="help-block"><?php echo $data['owner']; ?></p>
                  Document Year
                <p class="help-block"><?php echo $data['year']; ?></p>
                Document Tags and Keywords
                <p class="help-block"><?php echo $data['tags']; ?></p>
                  Status
                <p class="help-block"><?php echo $data['status']; ?></p>
                  Created
                  <p class="help-block"><?php echo $common->get_time_stamp($data['create_time']); ?></p>
                  Last Modified
                  <p class="help-block"><?php echo $common->get_time_stamp($data['modify_time']); ?></p>
                  </label>
                  <br>
                  <br>
                <a href="documents?add&editAdmin&id=<?php echo $data['ref']; ?>">Edit</a> </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <button class="btn btn-primary" name="button2" id="button" type="button" onClick="location='documents'" data-icon-primary="ui-icon-circle-check">Go Back</button>
                    
                </div>
              </form>
            </div><!-- /.box -->
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Document Sections</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table class="table table-bordered table-striped">
                    <tbody>
                      <?php for ($i = 0; $i < count($list); $i++) {
						$sn++; ?>
                      <tr>
                        <td><?php echo $list[$i]['section_no']; ?></td>
                        <td colspan="5"><?php echo $list[$i]['section_content']; ?></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>Created <?php echo $common->get_time_stamp($list[$i]['create_time']); ?></td>
                        <td>Last Modified <?php echo $common->get_time_stamp($list[$i]['modify_time']); ?></td>
                        <td colspan="2"><?php echo $list[$i]['tags']; ?>.</td>
                        <td><a href="documents.sections?editAdmin&id=<?php echo $id; ?>&editRef=<?php echo $list[$i]['ref']; ?>&redirect=<?php echo $redirect; ?>">edit</a> | <a href="documents.sections?deleteAdmin&id=<?php echo $id; ?>&del=<?php echo $list[$i]['ref']; ?>&redirect=<?php echo $redirect; ?>" onClick="return confirm('this action will remove this section. are you sure you want to continue ?')">delete</a></td>
                      </tr>
                      <?php }
						unset($i); 
						unset($sn); ?>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
              <!-- Form Element sizes --><!-- /.box --><!-- /.box -->

              <!-- Input addon --><!-- /.box -->

            </div>
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
    </script>
</body>
</html>
