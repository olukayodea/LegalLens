<?php
	$redirect = "advert";
	include_once("../includes/functions.php");
	include_once("session.php");
	
	
	if ((isset($_POST['addAdmin'])) || (isset($_POST['editButton']))) {
		$add = $advert->add($_POST, $_FILES['media_file']);
		if ($add) {
			if ($add['info'] == "Done") {
				header("location: ?done");
			} else {
				header("location: ?error=".urlencode($add['msg']));
			}
		} else {
			header("location: ?error=".urlencode("An error occured"));
		}
	}
	
	if (isset($_GET['editAdmin'])) {
		$getData = $advert->getOne($common->get_prep($_GET['id']));
		$linkTag = "?edit";
		$cat = explode(",", $getData['cat']);
		for ($i = 0; $i < count($cat); $i++) {
			$result[$i]['id'] = $result[$i]['name'] = ucfirst(strtolower($cat[$i]));
		}
		
		$catData = json_encode($result);
		
		if (isset($_GET['view'])) {
			$linkTag .= "&view";
		}
		
		$propertyList = $advert->getOneField($common->get_prep($_GET['id']), "ref", "properties");
		$propertyArray = explode(",", $propertyList);
		$_SESSION['tempData']['property'] = $propertyArray;
		
		$getPhotoList = $photos->sortAll("advert", $common->get_prep($_GET['id']));
		unset($_SESSION['tempData']['media']);
		for ($i = 0; $i < count($getPhotoList); $i++) {
			$_SESSION['tempData']['media'][] = $getPhotoList[$i]['photos'];
		}
		$editAdmin = true;
	} else if (isset($_GET['deleteAdmin'])) {
		$edit = $advert->remove($common->get_prep($_GET['id']));
		if ($edit) {
			header("location: ?done");
		} else {
			header("location: ?error");
		}
	} else {
		$resul = "";
		
		$catData = json_encode($result);
	}
		
	$list = $advert->listAll();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>LegalLens | Advertisement</title>
    <?php $adminPages->headerFiles(); ?>
  <script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
  <script src="../SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
  <link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
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
            Advertisement
            <small>Manage new and existing adverts</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo URLAdmin; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Manage Adverts</li>
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
                An error occurred, please try again. <?php echo $common->get_prep($_GET['error']); ?>
                </div>
            <?php } ?>
              <!-- general form elements -->
            <?php if (($write == 1) || ($modify ==1)) { ?>
            <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Add New Advert</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form role="form" action="" method="post" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="title">Title <em>*</em><p class="help-block">Enter advert campaign name</p></label>
                      <span id="sprytextfield1">
                      <input type="text" class="form-control" name="title" id="title" value="<?php echo $getData['title']; ?>" required>
                      <span class="textfieldRequiredMsg">A value is required.</span></span></div>
                      
                  <div class="form-group">
                    <label for="status">Display Status<em>*</em><p class="help-block">Toggle display status on and off on the home screen</p></label>
                    <span id="spryselect2">
                    <select name="status" id="status" class="form-control select2" required style="width: 100%;">
                      <option<?php if ($getData['status'] == "inactive") { ?> selected<?php } ?> value="inactive">Inactive</option>
                      <option<?php if ($getData['status'] == "active") { ?> selected<?php } ?> value="active">Active</option>
                    </select>
                    <span class="selectRequiredMsg">Please select an item.</span></span></div><!-- /.form-group -->
                    
                    <div class="form-group">
                      <label for="duration">Duration <em>*</em><p class="help-block">Select advert run time</p></label>
                      <input type="date" class="form-control" name="duration" id="duration" value="<?php echo $getData['duration']; ?>" required min="<?php echo date("Y-m-d"); ?>" >
                      </div>
                    <div class="form-group">
                      <label for="media_file">Advert Image <em>*</em><p class="help-block">upload the advert banner to fit in the advert type</p></label>
                      <input type="file" name="media_file" class="form-control" id="media_file" accept="image/*" required>
                      </div>
                    <div class="form-group">
                      <label for="url">Advert URL <em>*</em>
                      <p class="help-block">Enter URL of advertisement</p></label>
                      <input type="text" class="form-control" name="url" id="url" value="<?php echo $getData['url']; ?>" required>
                      </div>

                  <div class="box-footer">
                    <?php if ($editAdmin === true) { ?>
						<?php if (!isset($_GET['view'])) { ?>
                            <input type="hidden" name="id" value="<?php echo $editID; ?>">
                            <button class="btn btn-primary" name="editButton" id="editButton" type="submit" data-icon-primary="ui-icon-circle-check">Save Changes</button>
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
            <?php } ?>
            <?php if ($read == 1) { ?>
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Showing All Adverts</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>&nbsp;</td>
                        <th>&nbsp;</th>
                        <th><strong>Display Duration</strong></th>
                        <th><strong>URL</strong></th>
                        <th><strong>Display Status</strong></th>
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
                        <td><img name="" src="../advert/<?php echo $list[$i]['media_file']; ?>" width="40" height="40" alt=""></td>
                        <td><?php echo $common->get_time_stamp($list[$i]['duration']); ?></td>
                        <td><?php echo $list[$i]['url']; ?></td>
                        <td><?php echo $list[$i]['status']; ?></td>
                        <td><?php echo $common->get_time_stamp($list[$i]['create_time']); ?></td>
                        <td><?php echo $common->get_time_stamp($list[$i]['modify_time']); ?></td>
                        <td><?php if ($modify == 1) { ?><a href="?deleteAdmin&id=<?php echo $list[$i]['ref']; ?>" onClick="return confirm('do you really want to remove this advert?')">delete</a><?php } ?></td>
                      </tr>
                      <?php }
                                    unset($i); 
                          unset($sn); ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>&nbsp;</td>
                        <th>&nbsp;</th>
                        <th><strong>Display Duration</strong></th>
                        <th><strong>URL</strong></th>
                        <th><strong>Display Status</strong></th>
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
