<?php
	include_once("../includes/functions.php");
	//print_r($_SESSION);
	if ((isset($_REQUEST['redirect'])) && ($_REQUEST['redirect'] != "")) {
		$redirect = $common->get_prep($_REQUEST['redirect']);
		if ($redirect == "index") {
			$redirect = false;
		}
	} else {
		$redirect = false;
	}
	
	$urlParam = $common->getParam($_SERVER['REQUEST_URI']);
	$tagLink = $redirect."?".$urlParam;

	if (isset($_REQUEST['msg'])) {
		$er = $common->get_prep($_REQUEST['msg']);
	}
	
	if (isset($_GET['logout'])) {
		$logout = $clients->logout();
		header("location: login");
	} else if (isset($_POST['changePassword'])) {
		$password = $_POST['newPassword'];
		$activate = $clients->activate($password);
		
		header("location: ./");
	} else if (isset($_POST['Login2'])) {
		$check = $clients->passwordReset($_POST['reset_email']);
		
		if ($check) {
			header("location: ?loginDone=".urlencode("A new password has been sent to the email address you specified"));
		} else {
			header("location: ?reset&msg=".urlencode("We are sorry, no trace of this account was found in our records"));
		}
	} else if (isset($_POST['Login'])) {
		//print_r($_POST);
		$login = $clients->login($_POST);
		
		if ($login == 0) {
			$er = "Incorect username and password combination";
		} else if ($login == 1) {
			header("location: login?confirm");
		} else if ($login == 2) {
			if ($_SESSION['clients']['mainPage'] == "") {
				$mainPage = "index";
			} else {
				$mainPage = $_SESSION['clients']['mainPage'];
			}
			if ($redirect == false) {
				header("location: ".$mainPage);
			} else {
				header("location: ".$tagLink);
			}
		} else if ($login == 3) {
		} 
	}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>LegalLens | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="../management/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../management/dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="../management/plugins/iCheck/square/blue.css">
    <link href="../SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css">
    <link href="../SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  <script src="../SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
  <script src="../SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
  </head>
  <body class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="index2.html"><b>Legal</b>Lens</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>
        <?php if (isset($_GET['loginDone'])) { ?>
        <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-check"></i> Done!</h4>
        <?php echo $common->get_prep($_GET['loginDone']); ?>
        </div>
        <?php } else if (isset($_GET['confirm'])) { ?>
        <div class="alert alert-info alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-info"></i> Infrmation!</h4>
       Please create new password
        </div>
        <?php } else if (isset($er)) { ?>
        <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-ban"></i> Attention!</h4>
        <?php echo $common->get_prep($er); ?>
        </div>
        <?php } else if (isset($_REQUEST['msg'])) { ?>
        <div class="alert alert-warning alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-warning"></i> arning!</h4>
        <?php echo $common->get_prep($_GET['msg']); ?>
        </div>
        <?php } ?>
        
        <form action="" method="post">
        <?php if (isset($_GET['reset'])) { ?>
          <div class="form-group has-feedback">
            <input type="email" name="reset_email" id="reset_email" class="form-control" placeholder="Email Address" required>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="row">	<!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat" id="Login2"  value="Login2">Reset Password</button>
            </div><!-- /.col -->
          </div>
		<?php } else if (!isset($_GET['confirm'])) { ?>
         <div class="form-group has-feedback">
            <input type="email" name="username" id="username" class="form-control" placeholder="Username" required>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" id="password" name="password" required placeholder="Password">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">	<!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" name="Login" id="Login" class="btn btn-primary btn-block btn-flat">Sign In</button>
            </div><!-- /.col -->
          </div>
      <?php } else { ?>
         <div class="form-group has-feedback"><span id="sprypassword1">
           <input type="password" name="newPassword" id="newPassword" class="form-control" placeholder="New Password" required>
           <span class="passwordRequiredMsg">A value is required.</span></span><span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback"><span id="spryconfirm1">
            <input type="password" class="form-control" id="confirm" name="confirm" required placeholder="Password">
            <span class="confirmRequiredMsg">A value is required.</span><span class="confirmInvalidMsg">The values don't match.</span></span><span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">	<!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" name="changePassword" id="changePassword" class="btn btn-primary btn-block btn-flat">Change Password</button>
            </div><!-- /.col -->
          </div>
        <?php } ?>
        </form><!-- /.social-auth-links -->

        <a href="<?php echo URLClients; ?>login?reset">I forgot my password</a>

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- jQuery 2.1.4 -->
    <script src="../management/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="../management/bootstrap/js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="../management/plugins/iCheck/icheck.min.js"></script>
    <script>
$(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
<?php if (isset($_GET['confirm'])) { ?>
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1");
var spryconfirm1 = new Spry.Widget.ValidationConfirm("spryconfirm1", "newPassword");
<?php } ?>
    </script>
  </body>
</html>
