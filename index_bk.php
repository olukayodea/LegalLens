<?php
	include_once("includes/functions.php");
	
	if ((isset($_REQUEST['redirect'])) && ($_REQUEST['redirect'] != "")) {
		$redirect = $_REQUEST['redirect'];
	} else {
		$redirect = "home";
	}
		
	$urlParam = $common->getParam($_SERVER['REQUEST_URI']);
	
	$er = false; 
	if (isset($_REQUEST['msg'])) {
		$er = $_REQUEST['msg'];
	}
	
	$tagLink = $redirect."?".$urlParam;
	if (isset($_GET['logout'])) {
		$users->logout();
		header("location: ./");
	}
	
	
	if ((!isset($_GET['confirm'])) && (isset($_SESSION['users']['ref'])) && (trim($_SESSION['users']['subscription']) < time())) {
		header("location: managesubscription?renew");
	} else if (isset($_GET['confirm'])) {
	} else if (isset($_SESSION['users']['ref']) ) {
		header("location: home");
	}
	
	if (isset($_POST['submit3'])) {
		$change = $users->activate($common->mysql_prep($_POST['newPassword']));
		if ($change) {
			header("location: home");
		} else {
			header("location: ?confirm&error=".urlencode("An error occured, please try again"));
		}
	} else if (isset($_POST['submit'])) {
		$login = $users->login($_POST);
		
		if ($login == 0) {
			$er = "Incorect username and password combination";
		} else if ($login == 1) {
			header("location: ?confirm");
		} else if ($login == 2) {
			header("location: ".$tagLink);
		} else if ($login == 3) {
		} else if ($login == 10) {
			$er = "you are signed in on up to 3 devices, please logout from one device to continue";
		}
	}
	
	if (isset($_POST['registerButton'])) {
		$add = $users->create($_POST);
		
		if ($add) {
			header("location: home");
		} else {
			$er = "there was an error creating this acount, if you are sure you dont have an account already, please try again in a few minutes or contact the administrtor";
		}
	}
?>
<!DOCTYPE html>
        <!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en-US"> <![endif]-->
        <!--[if IE 7]>    <html class="lt-ie9 lt-ie8" lang="en-US"> <![endif]-->
        <!--[if IE 8]>    <html class="lt-ie9" lang="en-US"> <![endif]-->
        <!--[if gt IE 8]><!--> <html lang="en-US"> <!--<![endif]-->
        
<!-- Mirrored from inspirythemes.biz/html-templates/knowledgebase-html/index.html by HTTrack Website Copier/3.x [XR&CO'2013], Sun, 27 Mar 2016 11:14:22 GMT -->
<head>
    <meta charset="utf-8">
                <!-- META TAGS -->
                <meta charset="UTF-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0">

                <title>Homepage </title>

                <link rel="shortcut icon" href="images/favicon.png" />


                <!-- Google Web Fonts-->
                <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
                <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
                <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>

                <!-- Style Sheet-->
                <link rel="stylesheet" href="style.css"/>
                <link rel='stylesheet' id='bootstrap-css-css'  href='css/bootstrap5152.css?ver=1.0' type='text/css' media='all' />
                <link rel='stylesheet' id='responsive-css-css'  href='css/responsive5152.css?ver=1.0' type='text/css' media='all' />
                <link rel='stylesheet' id='pretty-photo-css-css'  href='js/prettyphoto/prettyPhotoaeb9.css?ver=3.1.4' type='text/css' media='all' />
		<link rel='stylesheet' id='main-css-css'  href='css/main5152.css?ver=1.0' type='text/css' media='all' />
		<link rel='stylesheet' id='blue-skin-css'  href='css/blue-skin5152.css?ver=1.0' type='text/css' media='all' />
		<link rel='stylesheet' id='custom-css-css'  href='css/custom5152.html?ver=1.0' type='text/css' media='all' />
		<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
		<link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css">
		<link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css">
		<script type='text/javascript' src='js/jquery-1.8.3.min.js'></script>
		<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
		<script src="SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
		
		<!--<link rel="stylesheet" href="style.css"/>
                <link rel='stylesheet' id='bootstrap-css-css'  href='css/bootstrap5152.css?ver=1.0' type='text/css' media='all' />
                <link rel='stylesheet' id='responsive-css-css'  href='css/responsive5152.css?ver=1.0' type='text/css' media='all' />
                <link rel='stylesheet' id='pretty-photo-css-css'  href='js/prettyphoto/prettyPhotoaeb9.css?ver=3.1.4' type='text/css' media='all' />
                <link rel='stylesheet' id='main-css-css'  href='css/main5152.css?ver=1.0' type='text/css' media='all' />
                <link rel='stylesheet' id='blue-skin-css'  href='css/blue-skin5152.css?ver=1.0' type='text/css' media='all' />
                <link rel='stylesheet' id='custom-css-css'  href='css/custom5152.html?ver=1.0' type='text/css' media='all' /> -->


                <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
                <!--[if lt IE 9]>
                <script src="js/html5.js"></script>
                <![endif]-->
     <script type="text/javascript">
	$(document).ready(function (){
        $(window).scroll(function () {
            if ($(document).scrollTop() <= 40) {
                $('#header-full').removeClass('small');
                $('.tabs-blur').removeClass('no-blur');
                $('#main-header').removeClass('small');
            } else {
                $('#header-full').addClass('small');
                $('.tabs-blur').addClass('no-blur');
                $('#main-header').addClass('small');
            }
        });
        
        $("a[data-rel^='prettyPhoto']").prettyPhoto({
			default_width: 600,
			default_height: 420,
			social_tools: false
		});
        $('#slideshow-tabs').tabs({ show: { effect: "fade", duration: 200 }, hide: { effect: "fade", duration: 300 } });
        $('.slider-tabs.flexslider').flexslider({
            animation: "slide",
            pauseOnAction: true,
        });
		$('a[data-rel]').each(function() {
			$(this).attr('rel', $(this).data('rel'));
		});
		$('img[data-retina]').retina({checkIfImageExists: true});
		$(".open-menu").click(function(){
		    $("body").addClass("no-move");
		});
		$(".close-menu, .close-menu-big").click(function(){
		    $("body").removeClass("no-move");
		});
	});
	</script>

        </head>

        <body>

                <!-- Start of Header -->
                <div class="header-wrapper">
                        <?php $pages->headerFiles("home"); ?>
                </div>
                <!-- End of Header -->
		<!-- Top Blue bar -->
		<div class="search-area-wrapper_two">
                        <div class="search-area_two container">
                               
                        </div>
                </div>
		<!--Top blue bar end -->
                <!-- Start of Search Wrapper 
		
		
                <div class="search-area-wrapper">
                        <div class="search-area container">
                                <h3 class="search-header">Have a Question?</h3>
                                <p class="search-tag-line">If you have any question you can ask below or enter what you are looking for!</p>

                                <form id="search-form" class="search-form clearfix" method="get" action="#" autocomplete="off">
                                        <input class="search-term required" type="text" id="s" name="s" placeholder="Type your search terms here" title="* Please enter a search term!" />
                                        <input class="search-btn" type="submit" value="Search" />
                                        <div id="search-error-container"></div>
                                </form>
                        </div>
                </div>-->
                <!-- End of Search Wrapper -->
                <!-- slider starts-->
  <!-- This demo works with jquery library -->
<div style="margin-top:62px:">

    
<!--slider ends -->
                <!-- Start of Page Container -->
               <div class="page-container">
    <div class="container">
      <div class="row">
        <!-- start of page content -->
        <div class="span3">
					  <section class="widget">
                            <div class="login-widget">
								<?php if ($er == true) { ?>
                                <p class="error"><?php echo $er; ?></p>
                                <?php } ?>
                                <?php if (isset($_REQUEST['register'])) { ?>
                                <h3 class="title">New User Registration</h3>
                                <form action="" method="post" role="form" >
                                  <table>
                                        <tr>
                                        	<td colspan=2>Last Name</td>
                                        </tr>
                                        <tr>
                                        	<td><span id="sprytextfield1">
                                        	  <input type="text" name="last_name" id="last_name" class="input-large span2" value="">
                                       	    <span class="textfieldRequiredMsg"><br>A value is required.</span></span></td>
                                        </tr>
                                        <tr>
                                        	<td colspan=2>Other Names</td>
                                        </tr>
                                        <tr>
                                       	  <td><span id="sprytextfield2">
                                       	    <input type="text" name="other_names" id="other_names" class="input-large span2" value="">
                                   	      <span class="textfieldRequiredMsg"><br>A value is required.</span></span></td>
                                        </tr>
                                        <tr>
                                       	  <td colspan=2>Email address</td>
                                        </tr>
                                        <tr>
                                        	<td><span id="sprytextfield3">
                                        	  <input type="text" name="email" id="email" class="input-large span2" value="">
                                       	    <span class="textfieldRequiredMsg"><br>A value is required.</span></span></td>
                                        </tr>
                                        <tr>
                                        	<td colspan=2>Password</td>
                                        </tr>
                                        <tr>
                                        	<td colspan=2><span id="sprypassword1">
                                        	  <input type="password" name="password" id="password" class="input-large span2">
                                       	    <span class="passwordRequiredMsg"><br>A value is required.</span></span></td>
                                        </tr>
                                        <tr>
                                       	  <td colspan=2>Confirm Password</td>
                                        </tr>
                                        <tr>
                                        	<td colspan=2><span id="spryconfirm1">
                                        	  <input type="password" name="confirm" id="confirm" class="input-large span2">
                                       	    <span class="confirmRequiredMsg"><br>A value is required.</span><span class="confirmInvalidMsg"><br>The values don't match.</span></span></td>
                                        </tr>
                                        <tr>
                                        	<td><input type="submit" name="registerButton" value="Register" class="btn btn-inverse"></td>
                                        </tr>
                                        <tr>
                                        	<td colspan=2><a href="<?php echo URL; ?>">Login </a>| <a href="<?php echo URL; ?>?password">Forgot Password?</a></td>
                                        </tr>
                                    </table>
                                </form>
                                <?php } else if (isset($_REQUEST['confirm'])) { ?>
                                <h3 class="title">Change Password</h3>
                                <p class="success">We have noticed that you logged in with a system generated password, please creat your password now, once completed, this will be the password for this account</p>
                              <form action="?confirm" method="post" role="form" >
                                  <table>
                                        <tr>
                                        	<td colspan=2>New Password</td>
                                        </tr>
                                        <tr>
                                        	<td><span id="sprypassword3">
                                        	  <input type="password" name="newPassword" id="newPassword" class="span2">
                                       	    <span class="passwordRequiredMsg"><br>A value is required.</span></span></td>
                                        </tr>
                                        <tr>
                                        	<td colspan=2>Confirm Password</td>
                                        </tr>
                                        <tr>
                                        	<td colspan=2><span id="spryconfirm2">
                                        	  <input type="password" name="confirmPassword" id="confirmPassword" class="span2">
                                       	    <span class="confirmRequiredMsg"><br>A value is required.</span><span class="confirmInvalidMsg"><br>The values don't match.</span></span></td>
                                        </tr>
                                        <tr>
                                        	<td><input type="submit" name="submit3" id="submit3" value="Set New Password" class="btn btn-inverse"></td>
                                        </tr>
                                        <tr>
                                        	<td colspan=2><a href="<?php echo URL; ?>?logout">Logout</a></td>
                                        </tr>
                                    </table>
                                </form>
                                <?php } else if (isset($_REQUEST['password'])) { ?>
                                <h3 class="title">Forgot Password</h3>
                                <form action="" method="post" role="form" >
                                  <table>
                                        <tr>
                                        	<td colspan=2>Email address</td>
                                        </tr>
                                        <tr>
                                        	<td><input type="text" name="email" id="email" class="input-large span2" value=""></td>
                                        </tr>
                                        <tr>
                                        	<td colspan=2>Password</td>
                                        </tr>
                                        <tr>
                                        	<td colspan=2><input type="password" name="password" class="input-large span2"></td>
                                        </tr>
                                        <tr>
                                        	<td><input type="submit" name="submit" id="submit" value="Login" class="btn btn-inverse"></td>
                                        </tr>
                                        <tr>
                                        	<td colspan=2><a href="<?php echo URL; ?>?register">Not yet Registered? </a>| <a href="<?php echo URL; ?>">Login?</a></td>
                                        </tr>
                                    </table>
                                </form>
                                <?php } else { ?>
                                <h3 class="title">Membership Login</h3>
                                <form action="" method="post" role="form" >
                                  <table>
                                        <tr>
                                        	<td colspan=2>Email address</td>
                                        </tr>
                                        <tr>
                                        	<td><span id="sprytextfield4">
                                        	  <input type="text" name="email" id="email" class="input-large span2" value="">
                                       	    <span class="textfieldRequiredMsg"><br>A value is required.</span></span></td>
                                        </tr>
                                        <tr>
                                        	<td colspan=2>Password</td>
                                        </tr>
                                        <tr>
                                        	<td colspan=2><span id="sprypassword2">
                                        	  <input type="password" name="password" id="password" class="required input-large span2">
                                       	    <span class="passwordRequiredMsg"><br>A value is required.</span></span></td>
                                        </tr>
                                        <tr>
                                        	<td><input type="submit" name="submit" value="Login" class="btn btn-inverse"></td>
                                        </tr>
                                        <tr>
                                        	<td colspan=2><a href="<?php echo URL; ?>?register">Not yet Registered? </a>| <a href="<?php echo URL; ?>?password">Forgot Password?</a></td>
                                        </tr>
                                    </table>
                                </form>
                                <?php } ?>
						</div>
          </section>                                               
						 <section class="widget">
                            <div class="support-widget">
                                    <h3 class="title">Need Help?</h3>
                                    <p class="intro">Click here to <a id="mibew-agent-button" href="<?php echo URL; ?>helpAndSupport" >Contact Support</a></p>
                                  <!-- mibew button  <a id="mibew-agent-button" href="/aust_site/library/mibew/index.php/chat?locale=en" target="_blank" onclick="Mibew.Objects.ChatPopups['57005d88b82098d2'].open();return false;"><img src="/aust_site/library/mibew/index.php/b?i=mibew&amp;lang=en" border="0" alt="" /></a><script type="text/javascript" src="/aust_site/library/mibew/js/compiled/chat_popup.js"></script><script type="text/javascript">Mibew.ChatPopup.init({"id":"57005d88b82098d2","url":"\/aust_site\/library\/mibew\/index.php\/chat?locale=en","preferIFrame":true,"modSecurity":false,"width":640,"height":480,"resizable":true,"styleLoader":"\/aust_site\/library\/mibew\/index.php\/chat\/style\/popup"});</script> --><!-- mibew button --><a id="mibew-agent-button" href="<?php echo URL; ?>helpAndSupport" target="_blank" onclick="Mibew.Objects.ChatPopups['57019a75a1e0a73'].open();return false;"><img src="chatapp/b?i=mibew&amp;lang=en" border="0" alt="" /></a><script type="text/javascript" src="/library/chatapp/js/compiled/chat_popup.js"></script><script type="text/javascript">Mibew.ChatPopup.init({"id":"57019a75a1e0a73","url":"chatapp\/chat?locale=en","preferIFrame":true,"modSecurity":false,"width":640,"height":480,"resizable":true,"styleLoader":"\/library\/chatapp\/chat\/style\/popup"});</script><!-- / mibew button -->
                            </div>
          </section>
          <section class="widget">
                            <div class="quick-links-widget">
                                    <h3 class="title">Legal Lens Forum</h3>
                                    Do you wish to join the discussion on Legal Lens Forum?<br> <a href="#">Click here</a>
                            </div>
          </section>

					</div>
					<div class="span7">
					 <?php include "slider.php";?>
					<section>
					<div style="border:solid 1px #ccc;"><marquee behavior="scroll" scrollamount="1" direction="left" >This holds news ticker content       This holds news ticker content</marquee></div></section>
					 <div style="margin-top:10px"> Welcome to the offical site of Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat<p><p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat</div>
					</div>
                                         <div class="span2">
					  <div style="margin-bottom:10px;">
					   <image src="images/advertise_here270.png" title="advert">
					  </div>
<div style="margin-bottom:10px;">
					   <image src="images/advertise_here270.png" title="advert">
					  </div>
<div style="margin-bottom:10px;">
					   <image src="images/advertise_here270.png" title="advert">
					  </div>
<div style="margin-bottom:10px;">
					   <image src="images/advertise_here270.png" title="advert">
					  </div>
					</div>      
                        </div>
                </div>
                <!-- End of Page Container -->

                <!-- Start of Footer -->
                <footer id="footer-wrapper">
                        <?php $pages->footer(); ?>
                        <!-- end of #footer -->

                        <!-- Footer Bottom -->
                       <?php $pages->footerButtom(); ?>
                        <!-- End of Footer Bottom -->

                </footer>
                <!-- End of Footer -->

                <a href="#top" id="scroll-top"></a>

                <!-- script -->
                
                <script type='text/javascript' src='js/jquery.easing.1.3.js'></script>
                <script type='text/javascript' src='js/prettyphoto/jquery.prettyPhoto.js'></script>
                <script type='text/javascript' src='js/jflickrfeed.js'></script>
				<script type='text/javascript' src='js/jquery.liveSearch.js'></script>
                <script type='text/javascript' src='js/jquery.form.js'></script>
                <script type='text/javascript' src='js/jquery.validate.min.js'></script>
                <script type='text/javascript' src="js/jquery-twitterFetcher.js"></script>
                <script type='text/javascript' src='js/custom5152.js?ver=1.0'></script>
                <script type='text/javascript' src='js/custom.js'></script>
        <script type="text/javascript">
<?php if (isset($_REQUEST['register'])) { ?>
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1");
var spryconfirm1 = new Spry.Widget.ValidationConfirm("spryconfirm1", "password");
<?php } ?>
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4");
var sprypassword2 = new Spry.Widget.ValidationPassword("sprypassword2");
<?php if (isset($_REQUEST['confirm'])) { ?>
var sprypassword3 = new Spry.Widget.ValidationPassword("sprypassword3");
var spryconfirm2 = new Spry.Widget.ValidationConfirm("spryconfirm2", "newPassword");
<?php } ?>
        </script>
        </body>

<!-- Mirrored from inspirythemes.biz/html-templates/knowledgebase-html/index.html by HTTrack Website Copier/3.x [XR&CO'2013], Sun, 27 Mar 2016 11:14:22 GMT -->
</html>
