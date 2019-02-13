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
	
	if (isset($_GET['confirm'])) {
		$c_login = $users->loginCookie();
	} else if (isset($_COOKIE['hash'])) {
		$c_login = $users->loginCookie();
		if ($c_login) {
			header("location: ".$tagLink);
		} else {
			$users->logout();
		}
	} else if ((!isset($_GET['confirm'])) && (isset($_SESSION['users']['ref'])) && (trim($_SESSION['users']['subscription']) < time())) {
		header("location: managesubscription?renew");
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
	} else if (isset($_POST['submit4'])) {
		$change = $users->passwordReset($common->mysql_prep($_POST['email']));
		if ($change) {
			header("location: ?done=".urlencode("Please check ".$_POST['email']." for details on how to create a new password"));
		} else {
			header("location: ?confirm&error=".urlencode("we can not verify this email address, or the user with this email address does not exist on our system. If you believe this email address is correct, please contact the administrator"));
		}
	} else if (isset($_POST['submit'])) {
		$login = $users->login($_POST);
		
		if ($login == 0) {
			$er = "Incorect username and password combination";
			header("location: ./?error=".urlencode($er));
		} else if ($login == 1) {
			header("location: ?confirm");
		} else if ($login == 2) {
           header("location: ".$tagLink);
		} else if ($login == 3) {
		} else if ($login == 10) {
			$er = "you are signed in on up to 3 devices, please logout from one device to continue";
			
			header("location: ./?error=".urlencode($er));
		}
    } else if (isset($_POST['registerButton'])) {
		$add = $users->create($_POST);
		
		if ($add) {
			header("location: home");
		} else {
			$er = "there was an error creating this acount, if you are sure you dont have an account already, please try again in a few minutes or contact the administrtor";
		}
	}
	
	$content = nl2br($page_content->getOneField("home", "title", "content"));
?>
<!DOCTYPE html>
        <!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en-US"> <![endif]-->
        <!--[if IE 7]>    <html class="lt-ie9 lt-ie8" lang="en-US"> <![endif]-->
        <!--[if IE 8]>    <html class="lt-ie9" lang="en-US"> <![endif]-->
        <!--[if gt IE 8]><!--> <html lang="en-US"> <!--<![endif]-->
        
<!-- Mirrored from inspirythemes.biz/html-templates/knowledgebase-html/index.html by HTTrack Website Copier/3.x [XR&CO'2013], Sun, 27 Mar 2016 11:14:22 GMT -->
<head>
    <meta charset="utf-8">
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-5XKJCSJ');</script>
<!-- End Google Tag Manager -->

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5XKJCSJ"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-4142286148495329",
    enable_page_level_ads: true
  });
</script>

    <!-- META TAGS -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="LegalLens offers fast legal research, Agreement Templates, Nigerian laws, Law dictionary & more">

    <title>Search laws, judgments &amp; Agreement templates digitally - LegalLens</title>

    <link rel="shortcut icon" href="images/favicon.png" />

    <!-- Google Web Fonts-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>

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
								<?php if (isset($_REQUEST['error'])) { ?>
                                <p class="error"><?php echo $_REQUEST['error']; ?></p>
                                <?php } ?>
								<?php if (isset($_REQUEST['done'])) { ?>
                                <p class="success"><?php echo $_REQUEST['done']; ?></p>
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
                                        	  <input type="text" name="last_name" id="last_name" class="input-large span3" value="">
                                       	    <span class="textfieldRequiredMsg"><br>A value is required.</span></span></td>
                                        </tr>
                                        <tr>
                                        	<td colspan=2>Other Names</td>
                                        </tr>
                                        <tr>
                                       	  <td><span id="sprytextfield2">
                                       	    <input type="text" name="other_names" id="other_names" class="input-large span3" value="">
                                   	      <span class="textfieldRequiredMsg"><br>A value is required.</span></span></td>
                                        </tr>
                                        <tr>
                                       	  <td colspan=2>Email address</td>
                                        </tr>
                                        <tr>
                                        	<td><span id="sprytextfield3">
                                        	  <input type="text" name="email" id="email" class="input-large span3" value="">
                                       	    <span class="textfieldRequiredMsg"><br>A value is required.</span></span></td>
                                        </tr>
                                        <tr>
                                        	<td colspan=2>Password</td>
                                        </tr>
                                        <tr>
                                        	<td colspan=2><span id="sprypassword1">
                                        	  <input type="password" name="password" id="password" class="input-large span3">
                                       	    <span class="passwordRequiredMsg"><br>A value is required.</span></span></td>
                                        </tr>
                                        <tr>
                                       	  <td colspan=2>Confirm Password</td>
                                        </tr>
                                        <tr>
                                        	<td colspan=2><span id="spryconfirm1">
                                        	  <input type="password" name="confirm" id="confirm" class="input-large span3">
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
                                        	  <input type="password" name="newPassword" id="newPassword" class="span3">
                                       	    <span class="passwordRequiredMsg"><br>A value is required.</span></span></td>
                                        </tr>
                                        <tr>
                                        	<td colspan=2>Confirm Password</td>
                                        </tr>
                                        <tr>
                                        	<td colspan=2><span id="spryconfirm2">
                                        	  <input type="password" name="confirmPassword" id="confirmPassword" class="span3">
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
                                        	<td><input type="text" name="email" id="email" class="input-large span3" value=""></td>
                                        </tr>
                                        <tr>
                                        	<td><input type="submit" name="submit4" id="submit4" value="Request New Password" class="btn btn-inverse"></td>
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
                                        	  <input type="text" name="email" id="email" class="input-large span3" value="">
                                       	    <span class="textfieldRequiredMsg"><br>A value is required.</span></span></td>
                                        </tr>
                                        <tr>
                                        	<td colspan=2>Password</td>
                                        </tr>
                                        <tr>
                                        	<td colspan=2><span id="sprypassword2">
                                        	  <input type="password" name="password" id="password" class="required input-large span3">
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
                                <div align="center"><a href="https://play.google.com/store/apps/details?id=mobile.linnkstec.mansur.com.legallens" target="_blank"><img src="images/en_badge_web_generic.png" width="200"></a></div>
						</div>
                        <ul class="social-nav clearfix">
                                
                                
                        <?php if (linkedin != "") { ?>
                            <li class="linkedin"><a target="_blank" href="<?php echo linkedin; ?>"></a></li>
                        <?php } if (google != "") { ?>
                            <li class="google"><a target="_blank" href="<?php echo google; ?>"></a></li>
                        <?php } if (flickr != "") { ?>
                            <li class="flickr"><a target="_blank" href="<?php echo flickr; ?>"></a></li>
                        <?php } if (skype != "") { ?>
                            <li class="skype"><a target="_blank" href="skype:<?php echo skype; ?>?call"></a></li>
                        <?php } if (rss != "") { ?>
                            <li class="rss"><a target="_blank" href="<?php echo rss; ?>"></a></li>
                        <?php } if (twitter != "") { ?>
                            <li class="twitter"><a target="_blank" href="<?php echo twitter; ?>"></a></li>
                        <?php } if (facebook != "") { ?>
                            <li class="facebook"><a target="_blank" href="<?php echo facebook; ?>"></a></li>
                        <?php } ?>
                        </ul>
          </section>                                               
            <section class="widget">
             <div align="center">
                <a href="<?php echo URL; ?>helpAndSupport" ><img src="<?php echo URL; ?>/images/help.png" width="100" /></a><br />
                <h3 class="title">Need Help?</h3>
                <p class="intro">Click here to<br /><a id="mibew-agent-button" href="<?php echo URL; ?>helpAndSupport" >Contact Support</a></p>
            </div>
          </section>
          <section class="widget">
            <div class="quick-links-widget">
                    <h3 class="title" align="center">Legal Lens Forum</h3>
                    <div align="center">Do you wish to join the discussion on Legal Lens Forum?<br> <a href="<?php echo URL; ?>/Forum">Click here</a></div>
            </div>
          </section>

					</div>
					<div class="span7">
					 <?php include "slider.php";?>
					<?php $news->ticker(); ?>
					 <div style="margin-top:10px"> 
                     <p><?php echo $content; ?></p>
                     </div>
					</div>
                    
					<?php $pages->advertHome(); ?>      
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
