<?php
	include_once("../includes/functions.php");
	
	$redirect = "administrator.right";
	include_once("session.php");
	//administrator
	
	if ((isset($_POST['addAdmin'])) || (isset($_POST['editButton']))) {
		$add = $admin->createAdminType($_POST);
		if ($add) {
			header("location: ?done");
		} else {
			header("location: ?error");
		}
	}
	
	if (isset($_GET['editAdmin'])) {
		$getData = $admin->listOneType($common->get_prep($_GET['id']));
		
		$editID = $getData['id'];
		$editName = $getData['title'];
		$editRead = $getData['read'];
		$editWrite = $getData['write'];
		$editLevel = $getData['level'];
		$editModify = $getData['modify'];
		$editMain = $getData['mainPage'];
		$editPagesRaw = explode(",", $getData['pages']);
		$editAdmin = true;
				
	} else if (isset($_GET['deactivateAdmin'])) {
		$edit = $admin->deactivate($common->get_prep($_GET['id']));
		if ($edit) {
			header("location: ?done");
		} else {
			header("location: ?error");
		} 
	} else if (isset($_GET['deleteAdmin'])) {
		$edit = $admin->delete($common->get_prep($_GET['id']));
		if ($edit) {
			header("location: ?done");
		} else {
			header("location: ?error");
		}
	}

	$listAdmin = $admin->listAdmintypes();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>LegalLens | Administrator Manager</title>
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
            Administrators Right
            <small>Manage new and existing system administrators rights</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo URLAdmin; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo URLAdmin; ?>administrators.right">Administrators</a></li>
            <li class="active">Manage Administrators' Right</li>
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
            <?php if (($write == 1) || ($modify ==1)) { ?>
            <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Add New Admin' Right</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form role="form" action="" method="post">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="title">Title <em>*</em><p class="help-block">Enter administrator type name</p></label>
                      <span id="sprytextfield1">
                      <input type="text" class="form-control" name="title"<?php if (isset($_REQUEST['view'])) { ?> disabled<?php } ?> id="title" value="<?php echo $editName; ?>" required>
                      <span class="textfieldRequiredMsg">A value is required.</span></span></div>
                    <div class="form-group">
                      <label for="read">List <em>*</em><p class="help-block">Enable permission for users to list entries</p></label><br>
                      <input name="read" type="checkbox"<?php if (isset($_REQUEST['view'])) { ?> disabled<?php } ?> id="read" value="1" checked readonly>
                    </div>
                    <div class="form-group">
                      <label for="write">Create <em>*</em><p class="help-block">Enable permissions for users to create entries</p></label><br>
                      <input type="checkbox" name="write"<?php if (isset($_REQUEST['view'])) { ?> disabled<?php } ?> id="write"<?php if ($editWrite == 1) { ?> checked<?php } ?> value="1">
                    </div>
                    <div class="form-group">
                      <label for="modify">Modify <em>*</em><p class="help-block">Enable permissions for users to modify entries</p></label><br>
                      <input type="checkbox" name="modify"<?php if (isset($_REQUEST['view'])) { ?> disabled<?php } ?> id="modify"<?php if ($editModify == 1) { ?> checked<?php } ?> value="1">
                    </div>
                  <div class="form-group">
                    <label for="level">Level <em>*</em><p class="help-block">Define access level for user type </p></label>
                    <span id="spryselect1">
                    <select name="level" id="level"<?php if (isset($_REQUEST['view'])) { ?> disabled<?php } ?> class="form-control">
                      <option>Select</option>
                      <option value="1">Normal User</option>
                      <option value="2">Admin Users</option>
                      <option value="3">Power Users</option>
                      <option value="4">System Users</option>
                    </select>
                    <span class="selectRequiredMsg">Please select an item.</span></span></div>
                    <div class="form-group">
                      <label for="mainPage">Main Page<em>*</em><p class="help-block">Landing page for user type</p></label>
                      <span id="spryselect2">
                      <select name="mainPage" id="mainPage" <?php if (isset($_REQUEST['view'])) { ?> disabled<?php } ?> class="form-control">
                        <option value="index">Dashboard</option>
                        <option value="notifications">Notification</option>
                        <optgroup label="Documents">
                          <option value="documents">Documents</option>
                          <option value="documents.sections">Documents Sections</option>
                        </optgroup>
                        <optgroup label="Forum">
                          <option value="forum.category">Categories</option>
                          <option value="forum.topic">Topics</option>
                          <option value="forum.post">Post</option>
                          <option value="forum.users">Users</option>
                        </optgroup>
                        <option value="news">News</option>
                        <optgroup label="Help and Support">
                          <option value="help">Tickets</option>
                          <option value="help.view">View Tickets</option>
                          <option value="help.faq"> FAQs</option>
                          <option value="knowledgeBase">Knowledge Base</option>
                          <option value="knowledgeBase.categories">Knowledge base category</option>
                        </optgroup>
                        <optgroup label="Categories">
                          <option value="categories">Category</option>
                          <option value="CaseLaw.subject">Areas of Law</option>
                          <option value="CaseLaw">Case Law</option>
                          <option value="CaseLaw.sections">Case Law Sections</option>
                          <option value="CaseLaw.view">Case Law Report</option>
                          <option value="library">Law Dictionary</option>
                          <option value="drafting">Legal Drafting</option>
                          <option value="drafting.sections">Legal Drafting Section</option>
                          <option value="list">Listings</option>
                          <option value="categories.priority">Manage Category Priority</option>
                        </optgroup>
                        <optgroup label="Settings">
                          <option value="advert">Manage Advert</option>
                          <option value="news">Manage News</option>
                          <option value="pages">Manage Pages</option>
                          <option value="slider">Manage Slider</option>
                          <option value="settings">Manage Other Settings</option>
                          <option value="subscriptions">Subscription</option>
                          <option value="subscriptions.volume">Volume Sub. Setup</option>
                          <option value="devices">List Users and Devices</option>
                          <option value="devices.users">Manage Users and Devices</option>
                        </optgroup>
                        <optgroup label="Articles and Journals">
                          <option value="articles">Manage</option>
                          <option value="article.sections">Manage Sections</option>
                        </optgroup>
                        <optgroup label="Regulations and Circular">
                          <option value="regulations">Manage</option>
                          <option value="regulations.sections">Manage Sections</option>
                          <option value="regulations.create">Regulators</option>
                        </optgroup>
                        <optgroup label="Users">
                          <option value="users">Customers</option>
                          <option value="administrators">Manage Administrators</option>
                          <option value="clients">Manage Document Owners</option>
                          <option value="profile">Manage Profile</option>
                          <option value="account">Manage Profile Password</option>
                          <option value="administrator.right">Manage Administrator Rights</option>
                        </optgroup>
                        <optgroup label="Reports">
                          <option value="subscriptionReport">Subscrivers</option>
                          <option value="order">Orders</option>
                          <option value="order.view">View Orders</option>
                          <option value="transactions">Transactions</option>
                          <option value="transactions.view">View Transactions</option>
                          <option value="subscriptionReport">Subscription Report</option>
                          <option value="system">System Log</option>
                          <option value="visitors">Visitors Log</option>
                        </optgroup>
                        <option value="notifications">Notifications</option>
                      </select>
                      <span class="selectRequiredMsg">Please select an item.</span></span></div>
                    <div class="form-group">
                      <label for="pages">Allowable Pages<em>*</em><p class="help-block">Pages the user can access. To select multiple vlue press and hold [CRTL] for windows or [cmd] for Mac while clicking</p></label>
                      <span id="spryselect3">
                      <select name="pages[]" size="9" multiple="multiple" class="form-control" id="pages"<?php if (isset($_REQUEST['view'])) { ?> disabled<?php } ?>>
                        <option value="index">Dashboard</option>
                        <option value="notifications">Notification</option>
                        <optgroup label="Documents">
                          <option value="documents">Documents</option>
                          <option value="documents.sections">Documents Sections</option>
                        </optgroup>
                        <optgroup label="Forum">
                          <option value="forum.category">Categories</option>
                          <option value="forum.topic">Topics</option>
                          <option value="forum.post">Post</option>
                          <option value="forum.users">Users</option>
                        </optgroup>
                        <option value="news">News</option>
                        <optgroup label="Help and Support">
                          <option value="help">Tickets</option>
                          <option value="help.view">View Tickets</option>
                          <option value="help.faq"> FAQs</option>
                          <option value="knowledgeBase">Knowledge Base</option>
                          <option value="knowledgeBase.categories">Knowledge base category</option>
                        </optgroup>
                        <optgroup label="Categories">
                          <option value="categories">Category</option>
                          <option value="CaseLaw.subject">Areas of Law</option>
                          <option value="CaseLaw">Case Law</option>
                          <option value="CaseLaw.sections">Case Law Sections</option>
                          <option value="CaseLaw.view">Case Law Report</option>
                          <option value="library">Law Dictionary</option>
                          <option value="drafting">Legal Drafting</option>
                          <option value="drafting.sections">Legal Drafting Section</option>
                          <option value="list">Listings</option>
                          <option value="categories.priority">Manage Category Priority</option>
                        </optgroup>
                        <optgroup label="Settings">
                          <option value="advert">Manage Advert</option>
                          <option value="news">Manage News</option>
                          <option value="pages">Manage Pages</option>
                          <option value="slider">Manage Slider</option>
                          <option value="settings">Manage Other Settings</option>
                          <option value="subscriptions">Subscription</option>
                          <option value="subscriptions.volume">Volume Sub. Setup</option>
                          <option value="devices">List Users and Devices</option>
                          <option value="devices.users">Manage Users and Devices</option>
                        </optgroup>
                        <optgroup label="Articles and Journals">
                          <option value="articles">Manage</option>
                          <option value="article.sections">Manage Sections</option>
                        </optgroup>
                        <optgroup label="Regulations and Circular">
                          <option value="regulations">Manage</option>
                          <option value="regulations.sections">Manage Sections</option>
                          <option value="regulations.create">Regulators</option>
                        </optgroup>
                        <optgroup label="Users">
                          <option value="users">Customers</option>
                          <option value="administrators">Manage Administrators</option>
                          <option value="clients">Manage Document Owners</option>
                          <option value="profile">Manage Profile</option>
                          <option value="account">Manage Profile Password</option>
                          <option value="administrator.right">Manage Administrator Rights</option>
                        </optgroup>
                        <optgroup label="Reports">
                          <option value="subscriptionReport">Subscrivers</option>
                          <option value="order">Orders</option>
                          <option value="order.view">View Orders</option>
                          <option value="transactions">Transactions</option>
                          <option value="transactions.view">View Transactions</option>
                          <option value="subscriptionReport">Subscription Report</option>
                          <option value="system">System Log</option>
                          <option value="visitors">Visitors Log</option>
                        </optgroup>
                        <option value="notifications">Notifications</option>
                      </select>
                      <span class="selectRequiredMsg">Please select an item.</span></span></div>
                  </div><!-- /.box-body -->

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
                  <h3 class="box-title">Showing All Administarors</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>&nbsp;</td>
                        <th><strong>Title</strong></th>
                        <th><strong>Level</strong></th>
                        <th><strong>Read</strong></th>
                        <th><strong>Write</strong></th>
                        <th><strong>Modify</strong></th>
                        <th><strong>Creation Date</strong></th>
                        <th><strong>Modified Last</strong></th>
                        <th>&nbsp;</th>
                      </tr>
                    </thead>
                    <tbody>
					  <?php for ($i = 0; $i < count($listAdmin); $i++) {
                    $sn++; ?>
                    <tr>
                        <td><?php echo $sn; ?></td>
                        <td><?php echo $listAdmin[$i]['title']; ?></td>
                        <td><?php echo $admin->getReadable($listAdmin[$i]['level'], "level"); ?></td>
                        <td><?php echo $admin->getReadable($listAdmin[$i]['read']); ?></td>
                        <td><?php echo $admin->getReadable($listAdmin[$i]['write']); ?></td>
                        <td><?php echo $admin->getReadable($listAdmin[$i]['modify']); ?></td>
                        <td><?php echo $common->get_time_stamp($listAdmin[$i]['createTime']); ?></td>
                        <td><?php echo $common->get_time_stamp($listAdmin[$i]['modifyTime']); ?></td>
                        <td><?php if ($modify == 1) { ?><a href="?view&editAdmin&id=<?php echo $listAdmin[$i]['id']; ?>">view</a> | <a href="?editAdmin&id=<?php echo $listAdmin[$i]['id']; ?>">edit</a> | <a href="?copy&editAdmin&id=<?php echo $listAdmin[$i]['id']; ?>">copy</a><?php } ?></td>
                    </tr>
                    <?php }
                    unset($i); 
                    unset($sn); ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>&nbsp;</td>
                        <th><strong>Title</strong></th>
                        <th><strong>Level</strong></th>
                        <th><strong>Read</strong></th>
                        <th><strong>Write</strong></th>
                        <th><strong>Modify</strong></th>
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
var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1");
var spryselect2 = new Spry.Widget.ValidationSelect("spryselect2");
var spryselect3 = new Spry.Widget.ValidationSelect("spryselect3");
    </script>
  </body>
</html>
