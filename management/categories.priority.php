<?php
	$redirect = "categories.priority";
	include_once("../includes/functions.php");
	include_once("session.php");
	$counter = count($categories->listAll());
	
	if ($counter < 1) {
		header("location: categories?error=".urlencode('You must create at least on category before you can add a document'));
	}
	
	if (isset($_REQUEST['id'])) {
		$id = $common->get_prep($_GET['id']);
		$tag = $categories->getOneField($id);
		if ($tag == "") {
			$id = 0;
			$tag = "Main Category";
		}
	}
	
	if (isset($_POST['editButton'])) {
		$add = $categories->setPriority($_POST['priority'], $id);
		
		if ($add) {
			header("location: ?done&id=".$id);
		} else {
			header("location: ?error&id=".$id);
		}
		
	}
	$catList = $categories->listLink($id);
	$list = $categories->sortAll($id, "parent_id");
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
	  $adminPages->sidebar("categories"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Manage <?php echo $tag; ?>
            <small>Set search priority for <?php echo $tag; ?> Categoory</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo URLAdmin; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo URLAdmin; ?>categories">Categories</a></li>
            <li class="active">Set Priority </li>
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
            </div>
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Showing All Categories in <?php echo $tag; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                <form name="form1" method="post" action="">
                <?php if ($id > 0) { ?>
                  <button class="btn btn-primary" name="resetButton" id="resetButton" type="button" data-icon-primary="ui-icon-circle-check" onClick="window.location.href='<?php echo URLAdmin.$redirect."?id=".$catList[1]['ref']; ?>'">Go Back</button>
                  <?php } ?>
                  <?php if (count($list) > 0) { ?>
                  <button class="btn btn-primary" name="resetButton" id="resetButton" type="button" data-icon-primary="ui-icon-circle-check" onClick="window.location.href=''">Reset Changes</button>
                  <button class="btn btn-primary" name="editButton" id="editButton" type="submit" data-icon-primary="ui-icon-circle-check">Save Changes</button>
                  <input type="hidden" name="temp" id="temp">
                  <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
                  <?php } ?>
                  <br>
                  <br>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>&nbsp;</td>
                        <th><strong>Title</strong></th>
                        <th>Priority Index</th>
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
                            <td><input type="text" name="priority[<?php echo $list[$i]['ref']; ?>]" id="priority_<?php echo $sn; ?>" onChange="setPriority(this.value, '<?php echo $sn; ?>', <?php echo count($list); ?>)" value="<?php echo $list[$i]['priority_code']; ?>" onFocus="grapFocus(this.value, <?php echo count($list); ?>)"></td>
                            <td><?php echo $common->get_time_stamp($list[$i]['create_time']); ?></td>
                            <td><?php echo $common->get_time_stamp($list[$i]['modify_time']); ?></td>
                            <td>&nbsp;</td>
						</tr>
						<?php }
						unset($i); 
						unset($sn); ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>&nbsp;</td>
                        <th><strong>Title</strong></th>
                        <th>Priority Index</th>
                        <th><strong>Creation Date</strong></th>
                        <th><strong>Modified Last</strong></th>
                        <th>&nbsp;</th>
                      </tr>
                    </tfoot>
                  </table>
                <?php if ($id > 0) { ?>
                  <button class="btn btn-primary" name="resetButton" id="resetButton" type="button" data-icon-primary="ui-icon-circle-check" onClick="window.location.href='<?php echo URLAdmin.$redirect."?id=".$catList[1]['ref']; ?>'">Go Back</button>
                  <?php } ?>
                  <?php if (count($list) > 0) { ?>
                  <button class="btn btn-primary" name="resetButton" id="resetButton" type="button" data-icon-primary="ui-icon-circle-check" onClick="window.location.href=''">Reset Changes</button>
                  <button class="btn btn-primary" name="editButton" id="editButton" type="submit" data-icon-primary="ui-icon-circle-check">Save Changes</button>
                  <input type="hidden" name="temp" id="temp">
                  <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
                  <?php } ?>
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
	  
	  function setPriority(val, loc, total) {
		  var sumTotal = gettotal(total);
		  var tempV = document.getElementById('temp').value;
		  if (isNaN(val) == true) {
			  document.getElementById('priority_'+loc).focus();
			  document.getElementById('priority_'+loc).value = tempV;
			  document.getElementById('temp').value = tempV;
			  alert("The priority value entered is not a number");
		  } else if ((val > total) || (val <= 0)) {
			  document.getElementById('priority_'+loc).focus();
			  document.getElementById('temp').value = tempV;
			  document.getElementById('priority_'+loc).value = tempV;
			  alert("The priority value entered is out of range. Please enter a value from 1 to "+total);
		  } else if (tempV == val) {
			  return false;
		  } else {
			  var oldVal = document.getElementById('temp').value;
			  
			  for (var i = 1; i <= total; i++) {
				  var check = document.getElementById('priority_'+i).value;
				  if (check == val) {
					  document.getElementById('priority_'+i).value = oldVal;
				  }
			  }
			  
			  document.getElementById('priority_'+loc).value = val;
		  }
	  }
	  
	  function grapFocus(val, total) {
		  if ((parseInt(val) > parseInt(total)) || (parseInt(val) <= 0)) {
			  return false;
		  } else {
		  	document.getElementById('temp').value = vsal;
			return true;
		  }
	  }
	  
	  function gettotal(val) {
		  var total = 0;
		  for (var i = 1; i <= val; i++) {
			  total = total+i;
		  }
		  
		  return total;
	  }
	  
    </script>
  </body>
</html>
