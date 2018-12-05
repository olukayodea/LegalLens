<?php
	$redirect = "CaseLaw.view";
	include_once("../includes/functions.php");
	include_once("session.php");
	
	$page_view = $settings->getOne("page_view");
	
	if (isset($_REQUEST['id'])) {
		$id = $common->get_prep($_GET['id']);
		$data = $clients->listOne($id);
		
		if ($data['company'] != "") {
			$tag = "for ".$data['company'];
		} else {
			$tag = "for all Clients";
		}
		
		if (isset($_POST['button'])) {
			$from = strtotime($common->get_prep($_POST['from']));
			$to = strtotime($common->get_prep($_POST['to']));
			$tag .= " betweem ".$_POST['from']." and ".$_POST['to'];
		} else {
			$from = false;
			$to = false;
		}
		
		$lisCaseLaw = $caselaw->counter($id, $from, $to);
		$totalCaseLaw = $caselaw->total($id, $from, $to);
		
		$listArticle = $articles->counter($id, $from, $to);
		$totalArticle = $articles->total($id, $from, $to);
		
		$listDocument = $documents->counter($id, $from, $to);
		$totalDocument = $documents->total($id, $from, $to);
		
		$listRegulation = $regulations->counter($id, $from, $to);
		$totalRegulation = $regulations->total($id, $from, $to);
		$viewAccess = true;
	} else {
		$viewAccess = false;
	}
	
	$cleintList = $clients->listAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>LegalLens | Documen Owner</title>
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
	  $adminPages->sidebar("report"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1> Document Owner<small>Manage View Report</small> </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo URLAdmin; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Document Owner Report</li>
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
              <!-- general form elements --><!-- /.box -->
              
            <div class="col-xs-12">
                <div class="box">
                  <div class="box-header">
                    <h3 class="box-title">Client</h3>
                    <table width="100%" border="0">
                      <tr>
                        <td width="20%">Client</td>
                        <td><select name="jumpMenu" id="jumpMenu" onChange="MM_jumpMenu('parent',this,0)" class="form-control select2" required style="width: 100%;">
                          <option value="">Select One</option>
                          <option value="?id=0">Default</option>
                          <?php for ($i = 0; $i < count($cleintList); $i++) { ?>
                          <option value="?id=<?php echo  $cleintList[$i]['id']; ?>"><?php echo  $cleintList[$i]['company']; ?></option>
                          <?php } ?>
                        </select></td>
                      </tr>
                    </table>
                  </div>
                </div>
            </div>
              <?php if ($viewAccess == true) { ?>
            <div class="col-xs-12">
                <div class="box">
                  <div class="box-header">
                    <form role="form" method="post" action="">
                    <h3 class="box-title">Filter Report</h3>
                    <table width="100%" border="0">
                      <tr>
                        <td>From</td>
                        <td><input type="date" name="from" id="from" required class="form-control" max='<?php  echo date("Y-m-d", time()-(60*60*24)); ?>'></td>
                        <td>To</td>
                        <td><input type="date" name="to" required class="form-control" id="to" max='<?php  echo date("Y-m-d", time()); ?>'></td>
                        <td><input type="submit" name="button" id="button" value="Filter"></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td><input name="id" type="hidden" id="id" value="<?php echo $id; ?>"></td>
                        <td>&nbsp;</td>
                      </tr>
                    </table>
                    <h3 class="box-title">Summary <?php echo $tag; ?></h3>
                    <table width="100%" border="0">
                      <tr>
                        <td width="20%">Total Document Views</td>
                        <td><?php echo number_format($totalDocument); ?></td>
                        <td width="20%">Amount Due</td>
                        <td><?php echo NGN." ".number_format(($totalDocument*$page_view), 2); ?></td>
                      </tr>
                      <tr>
                        <td>Total Article Views</td>
                        <td><?php echo number_format($totalArticle); ?></td>
                        <td>Amount Due</td>
                        <td><?php echo NGN." ".number_format(($totalArticle*$page_view), 2); ?></td>
                      </tr>
                      <tr>
                        <td>Total Csse Law Views</td>
                        <td><?php echo number_format($totalCaseLaw); ?></td>
                        <td>Amount Due</td>
                        <td><?php echo NGN." ".number_format(($totalCaseLaw*$page_view), 2); ?></td>
                      </tr>
                      <tr>
                        <td>Total Regulation and circular Views</td>
                        <td><?php echo number_format($totalRegulation); ?></td>
                        <td>Amount Due</td>
                        <td><?php echo NGN." ".number_format(($totalRegulation*$page_view), 2); ?></td>
                      </tr>
                    </table>
                    </form>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body"></div>
                </div>
              </div>
              <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Showing All Document Reports <?php echo $tag; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                <div align="right">
                  <form name="form1" method="post" action="CaseLaw.view.excel">
                    <input type="hidden" name="to" id="to" value="<?php echo $to; ?>">
                    <input type="hidden" name="from" id="from" value="<?php echo $from; ?>">
                    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
                    <input type="hidden" name="type" id="type" value="doc">
<input type="submit" name="button2" id="button2" value="Download">
                  </form>
                </div>
<table id="example1" class="table table-bordered table-striped">
                  <thead>
                      <tr>
                        <th>&nbsp;</td>
                        <th><strong>Document</strong></th>
                        <th><strong>Section</strong></th>
                        <th><strong>User</strong></th>
                        <th><strong>Date</strong></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php for ($i = 0; $i < count($listDocument); $i++) {
						$sn++; ?>
                      <tr>
                        <td><?php echo $sn; ?></td>
                        <td><?php echo $listDocument[$i]['title']; ?></td>
                        <td><?php echo $listDocument[$i]['section']; ?></td>
                        <td><?php echo $users->getOneField($listDocument[$i]['user_id'], "ref", "kast_name")." ".$users->getOneField($listDocument[$i]['user_id'], "ref", "other_names"); ?></td>
                        <td><?php echo date('l jS \of F Y h:i:s A', $listDocument[$i]['date_time']); ?></td>
                      </tr>
                      <?php }
						unset($i); 
						unset($sn); ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>&nbsp;</td>
                        <th><strong>Document</strong></th>
                        <th><strong>Section</strong></th>
                        <th><strong>User</strong></th>
                        <th><strong>Date</strong></th>
                      </tr>
                    </tfoot>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Showing All Case Law Reports <?php echo $tag; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                <div align="right">
                  <form name="form1" method="post" action="CaseLaw.view.excel">
                    <input type="text" name="to" id="to" value="<?php echo $to; ?>">
                    <input type="text" name="from" id="from" value="<?php echo $from; ?>">
                    <input type="text" name="id" id="id" value="<?php echo $id; ?>">
                    <input type="text" name="type" id="type" value="case">
                    <input type="submit" name="button2" id="button2" value="Donload">
                  </form>
                </div>
                  <table id="example2" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>&nbsp;</th>
                        <th><strong>Document</strong></th>
                        <th><strong>User</strong></th>
                        <th><strong>Date</strong></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php for ($i = 0; $i < count($lisCaseLaw); $i++) {
						$sn++; ?>
                      <tr>
                        <td><?php echo $sn; ?></td>
                        <td><?php echo $lisCaseLaw[$i]['title']; ?></td>
                        <td><?php echo $users->getOneField($lisCaseLaw[$i]['user_id'], "ref", "kast_name")." ".$users->getOneField($lisCaseLaw[$i]['user_id'], "ref", "other_names"); ?></td>
                        <td><?php echo date('l jS \of F Y h:i:s A',$lisCaseLaw[$i]['date_time']); ?></td>
                      </tr>
                      <?php }
						unset($i); 
						unset($sn); ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>&nbsp;</td>
                        <th><strong>Document</strong></th>
                        <th><strong>User</strong></th>
                        <th><strong>Date</strong></th>
                      </tr>
                    </tfoot>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Showing All Article Reports <?php echo $tag; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                <div align="right">
                  <form name="form1" method="post" action="CaseLaw.view.excel">
                    <input type="hidden" name="to" id="to" value="<?php echo $to; ?>">
                    <input type="hidden" name="from" id="from" value="<?php echo $from; ?>">
                    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
                    <input type="hidden" name="type" id="type" value="article">
                    <input type="submit" name="button2" id="button2" value="Download">
                  </form>
                </div>
                  <table id="example3" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>&nbsp;</td>
                        <th><strong>Document</strong></th>
                        <th><strong>User</strong></th>
                        <th><strong>Date</strong></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php for ($i = 0; $i < count($listArticle); $i++) {
						$sn++; ?>
                      <tr>
                        <td><?php echo $sn; ?></td>
                        <td><?php echo $listArticle[$i]['title']; ?></td>
                        <td><?php echo $users->getOneField($listArticle[$i]['user_id'], "ref", "kast_name")." ".$users->getOneField($listArticle[$i]['user_id'], "ref", "other_names"); ?></td>
                        <td><?php echo date('l jS \of F Y h:i:s A',$listArticle[$i]['date_time']); ?></td>
                      </tr>
                      <?php }
						unset($i); 
						unset($sn); ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>&nbsp;</td>
                        <th><strong>Document</strong></th>
                        <th><strong>User</strong></th>
                        <th><strong>Date</strong></th>
                      </tr>
                    </tfoot>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Showing All Regulations and Circular Reports <?php echo $tag; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                <div align="right">
                  <form name="form1" method="post" action="CaseLaw.view.excel">
                    <input type="hidden" name="to" id="to" value="<?php echo $to; ?>">
                    <input type="hidden" name="from" id="from" value="<?php echo $from; ?>">
                    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
                    <input type="hidden" name="type" id="type" value="reg">
                    <input type="submit" name="button2" id="button2" value="Download">
                  </form>
                </div>
                  <table id="example4" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>&nbsp;</td>
                        <th><strong>Document</strong></th>
                        <th><strong>User</strong></th>
                        <th><strong>Date</strong></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php for ($i = 0; $i < count($listRegulation); $i++) {
						$sn++; ?>
                      <tr>
                        <td><?php echo $sn; ?></td>
                        <td><?php echo $listRegulation[$i]['title']; ?></td>
                        <td><?php echo $users->getOneField($listRegulation[$i]['user_id'], "ref", "kast_name")." ".$users->getOneField($listRegulation[$i]['user_id'], "ref", "other_names"); ?></td>
                        <td><?php echo date('l jS \of F Y h:i:s A', $listRegulation[$i]['date_time']); ?></td>
                      </tr>
                      <?php }
						unset($i); 
						unset($sn); ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>&nbsp;</td>
                        <th><strong>Document</strong></th>
                        <th><strong>User</strong></th>
                        <th><strong>Date</strong></th>
                      </tr>
                    </tfoot>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
              <?php } ?>
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
        <strong>Copyright &copy; <?php echo date("Y"); ?> <a href="<?php echo URL; ?>">SkrinAd</a>.</strong> All rights reserved.
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
        $("#example2").DataTable();
        $("#example3").DataTable();
        $("#example4").DataTable();
      });
	  
		function pasteValue(val) {
			document.getElementById('cat').value = val;
		}
    </script>
</body>
</html>
