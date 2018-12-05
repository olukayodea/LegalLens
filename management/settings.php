<?php
	$redirect = "settings";
	include_once("../includes/functions.php");
	include_once("session.php");
	//administrator
	
	if ((isset($_POST['addAdmin'])) || (isset($_POST['editButton']))) {
		$add = $settings->add($_POST);
		if ($add) {
			header("location: ?done");
		} else {
			header("location: ?error");
		}
	}
	
	$page_view = $settings->getOne("page_view");
	$facebook = $settings->getOne("facebook");
	$flickr = $settings->getOne("flickr");
	$google = $settings->getOne("google");
	$linkedin = $settings->getOne("linkedin");
	$rss = $settings->getOne("rss");
	$skype = $settings->getOne("skype");
	$twitter = $settings->getOne("twitter");
	$instagram = $settings->getOne("instagram");
	$email = $settings->getOne("email");
	$phone = $settings->getOne("phone");
	$address = $settings->getOne("address");
	$city = $settings->getOne("city");
	$state = $settings->getOne("state");
	$country = $settings->getOne("country");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>LegalLens | Manage Settings</title>
  <?php $adminPages->headerFiles(); ?>
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
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
            Settings
            <small>Manage System Settings</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo URLAdmin; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Settings</li>
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
                  <h3 class="box-title">Manage Other Settings</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form role="form" action="" method="post">
                  <div class="box-body">
                    <div class="form-group">
                    <table width="100%" border="0" cellpadding="2" cellspacing="2">
                        <tbody>
                          <tr>
                            <td colspan="2">Documents</td>
                          </tr>
                          <tr style="background:#CCC">
                            <td width="25%">Unit Page View</td>
                            <td><span id="sprytextfield1">
                            <input type="text" name="page_view" id="page_view" value="<?php echo $page_view; ?>">
                            <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
                          </tr>
                          <tr>
                            <td width="25%">&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="2">Social Media</td>
                          </tr>
                          <tr style="background:#CCC">
                            <td>Facebook</td>
                            <td><em>
                            <span id="sprytextfield4">
                            <input type="text" name="facebook" id="facebook" value="<?php echo $facebook; ?>">
<span class="textfieldInvalidFormatMsg">Invalid format.</span></span> Facebook pAge URL</em></td>
                          </tr>
                          <tr>
                            <td>Flickr</td>
                            <td><em>
                            <span id="sprytextfield5">
                            <input type="text" name="flickr" id="flickr" value="<?php echo $flickr; ?>">
<span class="textfieldInvalidFormatMsg">Invalid format.</span></span> flickr Page Url</em></td>
                          </tr>
                          <tr style="background:#CCC">
                            <td>Google</td>
                            <td><em>
                            <span id="sprytextfield6">
                            <input type="text" name="google" id="google" value="<?php echo $google; ?>">
<span class="textfieldInvalidFormatMsg">Invalid format.</span></span> Google+ Page URL</em></td>
                          </tr>
                          <tr>
                            <td>LinkedIn</td>
                            <td><em>
                            <span id="sprytextfield7">
                            <input type="text" name="linkedin" id="linkedin" value="<?php echo $linkedin; ?>">
<span class="textfieldInvalidFormatMsg">Invalid format.</span></span> LinkedIn Page URL</em></td>
                          </tr>
                          <tr style="background:#CCC">
                            <td>RSS</td>
                            <td><em>
                            <span id="sprytextfield3">
                            <input type="text" name="rss" id="rss" value="<?php echo $rss; ?>">
<span class="textfieldInvalidFormatMsg">Invalid format.</span></span> RSS URL</em></td>
                          </tr>
                          <tr>
                            <td>Skype</td>
                            <td><em>
                            <input type="text" name="skype" id="skype" value="<?php echo $skype; ?>"> 
                            skype name</em></td>
                          </tr>
                          <tr style="background:#CCC">
                            <td>Twitter</td>
                            <td><em>
                            <span id="sprytextfield2">
                            <input type="text" name="twitter" id="twitter" value="<?php echo $twitter; ?>">
<span class="textfieldInvalidFormatMsg">Invalid format.</span></span> Twitter profile URL</em></td>
                          </tr>
                          <tr>
                            <td>Instagram</td>
                            <td><em> <span id="sprytextfield8">
                            <input type="text" name="instagram" id="instagram" value="<?php echo $instagram; ?>">
                            <span class="textfieldInvalidFormatMsg">Invalid format.</span></span> Instagram profile URL</em></td>
                          </tr>
                          <tr>
                            <td colspan="2">Contact Us</td>
                          </tr>
                          <tr style="background:#CCC">
                            <td>Email</td>
                            <td><input type="email" required name="email" id="email" value="<?php echo $email; ?>"></td>
                          </tr>
                          <tr>
                            <td>Phone</td>
                            <td><input type="text" required name="phone" id="phone" value="<?php echo $phone; ?>"></td>
                          </tr>
                          <tr style="background:#CCC">
                            <td>Address</td>
                            <td><input type="text" required name="address" id="address" value="<?php echo $address; ?>"></td>
                          </tr>
                          <tr>
                            <td>City</td>
                            <td><input type="text" required name="city" id="city" value="<?php echo $city; ?>"></td>
                          </tr>
                          <tr style="background:#CCC">
                            <td>State</td>
                            <td><input type="text" required name="state" id="state" value="<?php echo $state; ?>"></td>
                          </tr>
                          <tr>
                            <td>Country</td>
                            <td><input type="text" required name="country" id="country" value="<?php echo $country; ?>"></td>
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
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "real");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "url", {isRequired:false});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "url", {isRequired:false});
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "url", {isRequired:false});
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5", "url", {isRequired:false});
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6", "url", {isRequired:false});
var sprytextfield7 = new Spry.Widget.ValidationTextField("sprytextfield7", "url", {isRequired:false});
var sprytextfield8 = new Spry.Widget.ValidationTextField("sprytextfield8", "url", {isRequired:false});
    </script>
</body>
</html>
