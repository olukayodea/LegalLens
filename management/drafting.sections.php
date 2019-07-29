<?php
	$redirect = "drafting.sections";
	include_once("../includes/functions.php");
	include_once("session.php");
	
	if (isset($_REQUEST['id'])) {
		$id = $common->get_prep($_GET['id']);
	} else {
		header("location: drafting?error=".urlencode('You must create a draft first'));
	}
	
	if (isset($_REQUEST['redirect'])) {
		$redirect = $common->get_prep($_REQUEST['redirect']);
	}
	
	$data = $drafting->getOne($id);
	
	if (strtolower($data['type']) == "clause") {
		$title = "Legal Drafting: Clause";
		$sub_title = "Clause";
	} else if (strtolower($data['type']) == "forms") {
		$title = "Legal Drafting: Forms";
		$sub_title = "Form";
	} else if (strtolower($data['type']) == "agreement") {
		$title = "Legal Drafting: Agreements";
		$sub_title = "Agreement";
	} else {
		$title = "Legal Drafting: Clause";
		$sub_title = "Clause";
	}
	
	if ((isset($_POST['editButton'])) || (isset($_POST['addAdmin'])) || (isset($_POST['editButton_2']))) {
		$add = $drafting_sections->add($_POST);
		if ($add) {
			if (((strtolower($data['type']) == 'forms')  || (strtolower($data['type']) == 'agreement')) && (isset($_FILES))) {
				$add_file = $drafting_sections->uploadFile($_FILES);
				if ($add_file['title'] == "ERROR") {
					header("location: ".$redirect."?editAdmin&error=".$add_file['desc']."&id=".$id."&editRef=".$add);
				} else {
					$drafting_sections->modifyOne("file", $add_file['desc'], $add);
				}
			}
			
			if (isset($_POST['editButton_2'])) {
				header("location: drafting?done&id=".$id);
			} else {
				header("location: ".$redirect."?done&id=".$id);
			}
		} else {
			header("location: ?error&id=".$id."&redirect=".$redirect);
		}
	}
	
	if (isset($_GET['editAdmin'])) {
		$getData = $drafting_sections->getOne($common->get_prep($_GET['editRef']));
		$editAdmin = true;
			
	} else if (isset($_GET['deleteAdmin'])) {
		$edit = $drafting_sections->remove($common->get_prep($_GET['del']));
		if ($edit) {
			header("location: ".$redirect."?done&id=".$id);
		} else {
			header("location: ".$redirect."?error&id=".$id);
		}
	}
	
	$list = $drafting_sections->sortAll($id,"drafting");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>LegalLens | Document Manager</title>
    <?php $adminPages->headerFiles(); ?>
  <script src="../SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
  <script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

      <?php $adminPages->topHeader();
	  $adminPages->sidebar("drafting"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            <?php echo $data['title']; ?>
            <small>Manage <?php echo $sub_title; ?></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo URLAdmin; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo URLAdmin; ?>drafting">Legal Draft</a></li>
            <li class="active">Manage <?php echo $title." ".$sub_title; ?></li>
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
                  <h3 class="box-title">Add/Modify <?php echo $sub_title; ?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
              <form action="" method="post" enctype="multipart/form-data" role="form">
                <div class="box-body">
                  <div class="form-group">
                    <label for="section_content"><?php echo $sub_title; ?> Content<em>*</em>
                    </label>
                    <span id="sprytextarea1">
                    <textarea name="section_content" class="form-control" id="section_content" placeholder="Section Content"><?php echo $getData['section_content']; ?></textarea>
                    <span class="textareaRequiredMsg">A value is required.</span></span></div>
                  <?php if (strtolower($data['type']) != "clause") { ?>
                  <div class="form-group">
                    <label for="section_content2"><?php echo $sub_title; ?> File<em>*</em> </label>
                  <?php if (($editAdmin === true) && ($getData['file'] != "")) { ?>
                  File: <?php echo $getData['file']; ?>
                  <?php } else { ?>
                    <span id="sprytextfield1">
                    <input type="file" name="media_file" id="media_file" class="form-control" accept="application/msword, .docx, .pdf">
                    <span class="textfieldRequiredMsg">A value is required.</span></span>
                    <?php } ?>
                    </div>
                    <?php } ?>
                  <!-- /.form-group -->
                    <div class="form-group">
                      <label for="tags">Tags and Keywords
                      <input type="hidden" name="drafting" id="drafting" value="<?php echo $id; ?>">
                      <input type="hidden" name="redirect" id="redirect" value="<?php echo $redirect; ?>">
<p class="help-block">Enter document keywords for search identification &quot;,&quot;</p>
                      </label>
                      <span id="sprytextarea2">
                      <textarea name="tags" class="form-control" id="tags" placeholder="Tags &amp; Keywords"><?php echo $getData['tags']; ?></textarea>
                      <span class="textareaRequiredMsg">A value is required.</span></span></div>
                  </div>
                  <div class="form-group">
                    <label for="status" class="form-label">
                    Display Status<em>*</em>
                    <p class="help-block">Toggle display status on and off on the home screen</p>
                    </label>
                      <select name="status" id="status" class="form-control select2" required style="width: 100%;">
                        <option<?php if ($getData['status'] == "active") { ?> selected<?php } ?> value="active">Active</option>
                        <option<?php if ($getData['status'] == "inactive") { ?> selected<?php } ?> value="inactive">Inactive</option>
                      </select>
                </div>
                <!-- /.box-body -->

                <div class="box-footer">               
                    <?php if ($editAdmin === true) { ?>
					<?php if (!isset($_GET['view'])) { ?>
                    <input type="hidden" name="ref" value="<?php echo $getData['ref']; ?>">
                    <button class="btn btn-primary" name="editButton" id="editButton" type="submit"  data-icon-primary="ui-icon-circle-check">Save Changes</button>
                    <?php } ?>
                    <button class="btn btn-primary" name="button2" id="button" type="button" onClick="location='<?php echo $redirect; ?>'" data-icon-primary="ui-icon-circle-check">Cancel</button>
                    <?php } else { ?>
                    <?php if (strtolower($data['type']) != "clause") {
						if (count($list) == 0) { ?>
                    	<button class="btn btn-primary" name="editButton_2" id="editButton_2" type="submit" data-icon-primary="ui-icon-circle-check">Save</button>
                    <?php } else { ?>
                    	<p style="color:#F00"><strong>You can only add one section to a template at a time</strong></p>
					<?php } 
					} else { ?>
                        <button class="btn btn-primary" name="addAdmin" id="addAdmin" type="submit" data-icon-primary="ui-icon-circle-check">Save</button>
                        <button class="btn btn-primary" name="editButton_2" id="editButton_2" type="submit" data-icon-primary="ui-icon-circle-check">Save & Exit</button>
                    <?php } ?>
                  <?php } ?>
                </div>
              </form>
            </div><!-- /.box -->
              
              <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Showing All <?php echo $sub_title; ?>(s) in <?php echo htmlspecialchars_decode($data['title']); ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>&nbsp;</td>
                        <th><strong>Content</strong></th>
                        <th><strong>File</strong></th>
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
                        <td><?php echo $common->truncate($list[$i]['section_content'], 40); ?>...</td>
                        <td><?php echo $list[$i]['file']; ?></td>
                        <td><?php echo $common->get_time_stamp($list[$i]['create_time']); ?></td>
                        <td><?php echo $common->get_time_stamp($list[$i]['modify_time']); ?></td>
                        <td><a href="?view&id=<?php echo $id; ?>&editAdmin&editRef=<?php echo $list[$i]['ref']; ?>">view</a> | <a href="?editAdmin&id=<?php echo $id; ?>&editRef=<?php echo $list[$i]['ref']; ?>">edit</a> | <a href="?deleteAdmin&id=<?php echo $id; ?>&del=<?php echo $list[$i]['ref']; ?>" onClick="return confirm('this action will remove this section. are you sure you want to continue ?')">delete</a></td>
                      </tr>
                      <?php }
						unset($i); 
						unset($sn); ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>&nbsp;</td>
                        <th><strong>Content</strong></th>
                        <th><strong>File</strong></th>
                        <th><strong>Creation Date</strong></th>
                        <th><strong>Modified Last</strong></th>
                        <th>&nbsp;</th>
                      </tr>
                    </tfoot>
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
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1");
var sprytextarea2 = new Spry.Widget.ValidationTextarea("sprytextarea2");
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
    </script>
</body>
</html>
