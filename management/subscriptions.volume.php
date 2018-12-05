<?php
	$redirect = "subscriptions";
	include_once("../includes/functions.php");
	include_once("session.php");
	//administrator
	
	if (isset($_POST['editButton'])) {
		$add = $volume->add($_POST);
		if ($add) {
			header("location: ?done");
		} else {
			header("location: ?error");
		}
	}
	
	$list = $volume->listAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>LegalLens | Manage Subscriptions</title>
  <?php $adminPages->headerFiles(); ?>
<script src="../SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

      <?php $adminPages->topHeader();
	  $adminPages->sidebar("settings"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Subscriptions
            <small>Manage Volume Subacription Licence Discount</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo URLAdmin; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Subscriptions</li>
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
                  <h3 class="box-title">Manage Dicount</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form role="form" action="" method="post">
                  <div class="box-body">
                    <div class="form-group">
                    <table width="100%" border="0" cellpadding="2" cellspacing="2">
                        <thead>
                          <tr>
                            <td>Lower Band</td>
                            <td>Upper Band</td>
                            <td>Percentage Discount</td>
                            <td>Status</td>
                          </tr>
                        </thead>
                        <tfoot>
                          <tr>
                            <td>Lower Band</td>
                            <td>Upper Band</td>
                            <td>Percentage Discount</td>
                            <td>Status</td>
                          </tr>
                        </tfoot>
                        <tbody>
                          <tr style="background:#CCC">
                            <td>1
                            <input type="hidden" name="ref" id="ref" value="1">
                            <input type="hidden" name="low_band" id="low_band" value="1">
                            <input type="hidden" name="high_band" id="high_band" value="10"></td>
                            <td>10</td>
                            <td><input type="number" name="discount" id="discount" value="<?php echo $list[0]['discount']; ?>" required></td>
                            <td><span id="spryselect1">
                              <select name="status" id="status">
                                <option>Select</option>
                                <option<?php if ($list[0]['status'] == 'active') { ?> selected<?php } ?> value="active">Active</option>
                                <option<?php if ($list[0]['status'] == 'inactive') { ?> selected<?php } ?> value="inactive">In-Active</option>
                              </select>
                            <span class="selectRequiredMsg">Please select an item.</span></span></td>
                          </tr>
                          <tr>
                            <td>11
                            <input type="hidden" name="ref2" id="ref2" value="2">
                            <input type="hidden" name="low_band2" id="low_band2" value="11">
                            <input type="hidden" name="high_band2" id="high_band2" value="50"></td>
                            <td>50</td>
                            <td><input type="number" name="discount2" id="discount2" value="<?php echo $list[1]['discount']; ?>" required></td>
                            <td><span id="spryselect2">
                            <select name="status2" id="status2">
                              <option>Select</option>
                              <option<?php if ($list[1]['status'] == 'active') { ?> selected<?php } ?> value="active">Active</option>
                              <option<?php if ($list[1]['status'] == 'inactive') { ?> selected<?php } ?> value="inactive">In-Active</option>
                            </select>
                            <span class="selectRequiredMsg">Please select an item.</span></span></td>
                          </tr>
                          <tr style="background:#CCC">
                            <td>51
                            <input type="hidden" name="ref3" id="ref3" value="3">
                            <input type="hidden" name="low_band3" id="low_band3" value="51">
                            <input type="hidden" name="high_band3" id="high_band3" value="100"></td>
                            <td>100</td>
                            <td><input type="number" name="discount3" id="discount3" value="<?php echo $list[2]['discount']; ?>" required></td>
                            <td><span id="spryselect3">
                            <select name="status3" id="status3">
                              <option>Select</option>
                              <option<?php if ($list[2]['status'] == 'active') { ?> selected<?php } ?> value="active">Active</option>
                              <option<?php if ($list[2]['status'] == 'inactive') { ?> selected<?php } ?> value="inactive">In-Active</option>
                            </select>
                            <span class="selectRequiredMsg">Please select an item.</span></span></td>
                          </tr>
                          <tr>
                            <td>101
                            <input type="hidden" name="ref4" id="ref4" value="4">
                            <input type="hidden" name="low_band4" id="low_band4" value="101">
                            <input type="hidden" name="high_band4" id="high_band4" value="1000"></td>
                            <td>1000</td>
                            <td><input type="number" name="discount4" id="discount4" value="<?php echo $list[3]['discount']; ?>" required></td>
                            <td><span id="spryselect4">
                            <select name="status4" id="status4">
                              <option>Select</option>
                              <option<?php if ($list[3]['status'] == 'active') { ?> selected<?php } ?> value="active">Active</option>
                              <option<?php if ($list[3]['status'] == 'inactive') { ?> selected<?php } ?> value="inactive">In-Active</option>
                            </select>
                            <span class="selectRequiredMsg">Please select an item.</span></span></td>
                          </tr>
                          <tr style="background:#CCC">
                            <td>1001
                            <input type="hidden" name="ref5" id="ref5" value="5">
                            <input type="hidden" name="low_band5" id="low_band5" value="1001">
                            <input type="hidden" name="high_band5" id="high_band5" value="1000000000"></td>
                            <td>&nbsp;</td>
                            <td><input type="number" name="discount5" id="discount5" value="<?php echo $list[4]['discount']; ?>" required></td>
                            <td><span id="spryselect5">
                            <select name="status5" id="status5">
                              <option>Select</option>
                              <option<?php if ($list[4]['status'] == 'active') { ?> selected<?php } ?> value="active">Active</option>
                              <option<?php if ($list[4]['status'] == 'inactive') { ?> selected<?php } ?> value="inactive">In-Active</option>
                            </select>
                            <span class="selectRequiredMsg">Please select an item.</span></span></td>
                          </tr>
                        </tbody>
                    </table>
                    </div>
                    <div class="box-footer">
                        <button class="btn btn-primary" name="editButton" id="editButton" type="submit"  data-icon-primary="ui-icon-circle-check">Save Changes</button>
                    </div>
              </div><!-- /.box -->
                </form>

              <!-- Form Element sizes --><!-- /.box --><!-- /.box -->

              <!-- Input addon --><!-- /.box -->

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
var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1");
var spryselect2 = new Spry.Widget.ValidationSelect("spryselect2");
var spryselect3 = new Spry.Widget.ValidationSelect("spryselect3");
var spryselect4 = new Spry.Widget.ValidationSelect("spryselect4");
var spryselect5 = new Spry.Widget.ValidationSelect("spryselect5");
    </script>
  </body>
</html>
