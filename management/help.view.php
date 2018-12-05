<?php
	$redirect = "help.view";
	include_once("../includes/functions.php");
	include_once("session.php");
	
	if (isset($_REQUEST['ref'])) {
		$id = $common->get_prep($_REQUEST['ref']);
	} else {
		header("location: help?error=".urlencode("Select an items"));
	}
	
	if (isset($_REQUEST['end'])) {
		$help->modifyOne("admin_id", $ref, $id);
		$help->close($id);
		$help->statusMail($id);
		header("location: ?done&ref=".$id);
	}
	
	if (isset($_POST['editButton'])) {
		$_FILES["media_file"]["error"] = 4;
		$add = $help->add($_POST, $_FILES, true);
		
		if ($add) {
			$help->modifyOne("admin_id", $ref, $id);
			header("location: ?done&ref=".$id);
		} else {
			header("location: ".$redirect."?error=".urlencode("An error occured&ref=".$id));
		}
	}
	
	$data = $help->getOne($id);
	if ($data['parent_id'] != 0) {
		header("location: ".$redirect."?ref=".$data['parent_id']);
	}
	
	$list = $help->sortAll($data['ref'], "parent_id");
	if ($data['status'] == 0) {
		$help->modifyOne("status", "1", $id);
		$help->statusMail($id);
	}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>LegalLens | Help and Support</title>
  <?php $adminPages->headerFiles(); ?>
<script src="../SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
  <link href="../SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
        <?php $pages->chatHeader(); ?>
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
                  <form name="form1" method="post" action="">
                    <table width="100%" border="1" cellpadding="1">
                      <tr bordercolor="#CCCCCC">
                        <td width="25%"><?php echo $users->getOneField($data['user_id'], "ref", "last_name")." ".$users->getOneField($data['user_id'], "ref", "other_names"); ?></td>
                        <td width="25%"><?php echo $common->get_time_stamp($data['create_time']); ?></td>
                        <td width="25%"><?php echo $knowledge_base_category->catToTex($data['category']); ?></td>
                        <td width="25%"><?php if ($data['file'] != "") { ?>
                          <a href="<?php echo URL; ?>library/helpfiles/<?php echo $data['file']; ?>" target="_blank">View File</a>
                          <?php } ?></td>
                      </tr>
                      <tr>
                        <td colspan="4"><?php echo $data['content']; ?></td>
                      </tr>
                      <tr>
                        <td colspan="4" align="right"><em>Status: <?php echo $help->status($data['status']); ?><br>
                          Last modified on <?php echo date('l jS \of F Y h:i:s A', $data['modify_time']); ?><br>
                          Last Modified By <?php echo $admin->getOneField($data['admin_id']); ?></em></td>
                      </tr>
                      <tr>
                        <td colspan="4"><hr></td>
                      </tr>
                      <?php for ($i = 0; $i < count($list); $i++) { ?>
                      <tr bordercolor="#CCCCCC">
                        <td width="25%"><?php echo $users->getOneField($list[$i]['user_id'], "ref", "last_name")." ".$users->getOneField($list[$i]['user_id'], "ref", "other_names"); ?></td>
                        <td width="25%"><?php echo $common->get_time_stamp($list[$i]['create_time']); ?></td>
                        <td width="25%"><?php echo $knowledge_base_category->catToTex($list[$i]['category']); ?></td>
                        <td width="25%"><?php if ($list[$i]['file'] != "") { ?>
                          <a href="<?php echo URL; ?>library/helpfiles/<?php echo $list[$i]['file']; ?>" target="_blank">View File</a>
                          <?php } ?></td>
                      </tr>
                      <tr>
                        <td colspan="4"><?php echo $list[$i]['content']; ?></td>
                      </tr>
                      <tr>
                        <td colspan="4" align="right"><em>Status: <?php echo $help->status($list[$i]['status']); ?><br>
                          Last modified on <?php echo date('l jS \of F Y h:i:s A', $list[$i]['modify_time']); ?><br>
                          Last Modified By <?php echo $admin->getOneField($list[$i]['admin_id']); ?></em></td>
                      <tr>
                        <td colspan="4"><hr></td>
                      </tr>
                        <?php } ?>
                        <?php if ($data['status'] != 2) { ?>
                      <tr>
                        <td colspan="4"><h2>Reply</h2></td>
                      </tr>
                      <tr>
                        <td colspan="4"><span id="sprytextarea1">
                          <textarea name="content" id="content" class="form-control"></textarea>
                        <span class="textareaRequiredMsg">A value is required.</span></span>
                          <input type="hidden" name="category" id="category" value="<?php echo $data['category']; ?>">
                          <input type="hidden" name="parent_id" id="parent_id" value="<?php echo $data['ref']; ?>">
                          <input type="hidden" name="user_id" id="user_id" value="<?php echo $data['user_id']; ?>">
                          <input type="hidden" name="admin_id" id="admin_id" value="<?php echo $ref; ?>">
                          <input type="hidden" name="response_id" id="response_id" value="<?php echo $list[$i]['ref']; ?>">
                          <input type="hidden" name="ref" id="ref" value="<?php echo $id; ?>"></td>
                      </tr>
                      <tr>
                        <td colspan="3" align="left"><button class="btn btn-primary" name="editButton" id="editButton" type="submit"  data-icon-primary="ui-icon-circle-check">Post Reply</button>
                        </td>
                        <td align="right"><button class="btn btn-primary" name="editButton" id="editButton2" type="button" data-icon-primary="ui-icon-circle-check" onclick="confirmDel()">Close Thread</button></td>
                      </tr>
                      <?php } ?>
                    </table>
                  </form>
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
      });
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1");

function confirmDel() {
	var result = confirm("Are you sure you want to end this Thread?");
	if (result) {
		window.location='?end&ref=<?php echo $id; ?>';
	}
}
    </script>
  </body>
</html>
