<?php
	$redirect = "forum.post";
	include_once("../includes/functions.php");
	include_once("session.php");
	$listReg = $forum_topics->sortAll("active", "status");
	
	if (count($listReg) < 1) {
		header("location: forum.topic?error=".urlencode('You must create at least one topic before you can view post'));
	}
	
	if (isset($_GET['id'])) {
		$id = $common->get_prep($_GET['id']);
		$data = $forum_topics->getOne($id);
		$tag = "in ".$data['topic_subject'];
	} else {
		$id = 0;
	}
	if (isset($_GET['deleteAdmin'])) {
		$edit = $forum_posts->remove($common->get_prep($_GET['del']));
		if ($edit) {
			header("location: ?done&id=".$id);
		} else {
			header("location: ?error&id=".$id);
		}
	} else if (isset($_GET['approve'])) {
		$edit = $forum_posts->modifyOne("status", "active", $common->get_prep($_GET['del']));
		if ($edit) {
			header("location: ?done&id=".$id);
		} else {
			header("location: ?error&id=".$id);
		}
	}
	
	
	$list = $forum_posts->sortAll($id,"post_topic");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>LegalLens | Forum</title>
    <?php $adminPages->headerFiles(); ?>
<script type="text/javascript">
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
    </script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

      <?php $adminPages->topHeader();
	  $adminPages->sidebar("forum"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Forum
            <small>Manage Posts</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo URLAdmin; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo URLAdmin; ?>forum.topic"><i class="fa fa-dashboard"></i>Forums</a></li>
            <li class="active">Manage Post <?php echo $tag; ?></li>
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
                  <h3 class="box-title">Select Topic</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
              <form role="form" method="post" action="">
                  <div class="box-body">
                <!-- /.form-group -->
                <div class="form-group">
                    <label for="id" class="form-label">
                    Forum Topics<em>*</em>
                  <p class="help-block">Select a topic to list all posts under it</p>
                    </label>
                    <select name="id" id="id" class="form-control select2" required style="width: 100%;" onChange="MM_jumpMenu('parent',this,0)">
                    <option value="#">Select One</option>
    <?php for ($i = 0; $i < count($listReg); $i++) { ?>
    <option value="?id=<?php echo $listReg[$i]['topic_id']; ?>"<?php if ($id == $listReg[$i]['topic_id']) { ?> selected<?php } ?>><?php echo $listReg[$i]['topic_subject']; ?></option>
    <?php } ?>
                  </select>
                </div>
                <!-- /.box-body -->
              </form>
            </div><!-- /.box -->
            </div>

            <?php if (isset($_REQUEST['editRef'])) { ?>
              <div class="box">
                  <div class="box-header with-border">
                    <h3 class="box-title">Approve Post in <?php echo $tag; ?></h3>
                  </div><!-- /.box-header -->
                  <div class="box-body">
                    <?php echo html_entity_decode($forum_posts->getOneField($_REQUEST['editRef'])); ?>
                </div><!-- /.box -->
                  <div class="box-footer">
                  <a href="?approve&id=<?php echo $id; ?>&del=<?php echo $_REQUEST['editRef']; ?>" onClick="return confirm('this action will mke this post available to other users?')">Approve</a> | <a href="?deleteAdmin&id=<?php echo $id; ?>&del=<?php echo $_REQUEST['editRef']; ?>" onClick="return confirm('this action will remove this section. are you sure you want to continue ?')">delete</a>
                </div><!-- /.box -->
              </div>
            <?php } ?>
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Showing All Post <?php echo $tag; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>&nbsp;</td>
                        <th><strong>Title</strong></th>
                        <th><strong>Topic</strong></th>
                        <th><strong>Posted By</strong></th>
                        <th><strong>Status</strong></th>
                        <th><strong>Creation Date</strong></th>
                        <th>&nbsp;</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php for ($i = 0; $i < count($list); $i++) {
						$sn++; ?>
                      <tr>
                        <td><?php echo $sn; ?></td>
                        <td><?php echo html_entity_decode($list[$i]['post_content']); ?></td>
                        <td><?php echo $forum_topics->getOneField($list[$i]['post_topic']); ?></td>
                        <td><?php echo trim($users->getOneField($list[$i]['post_by'], "ref", "last_name")." ".$users->getOneField($list[$i]['post_by'], "ref", "other_names")); ?></td>
                        <td><?php echo $list[$i]['status']; ?></td>
                        <td><?php echo $common->get_time_stamp($list[$i]['post_date']); ?></td>
                        <td>
                        <?php if ($list[$i]['status'] == "inactive") { ?>
                        <a href="?approve&id=<?php echo $id; ?>&del=<?php echo $list[$i]['post_id']; ?>" onClick="return confirm('this action will mke this post available to other users?')">Approve</a> | <?php } ?><a href="?deleteAdmin&id=<?php echo $id; ?>&del=<?php echo $list[$i]['post_id']; ?>" onClick="return confirm('this action will remove this section. are you sure you want to continue ?')">delete</a></td>
                      </tr>
                      <?php }
						unset($i); 
						unset($sn); ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>&nbsp;</td>
                        <th><strong>Title</strong></th>
                        <th><strong>Topic</strong></th>
                        <th><strong>Posted By</strong></th>
                        <th><strong>Status</strong></th>
                        <th><strong>Creation Date</strong></th>
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
</script>
</body>
</html>
