<?php
	$redirect = "editprofile";
	include_once("includes/functions.php");
	include_once("includes/session.php");
	
	if (isset($_POST['updateInfo'])) {
		$add = $users->update($_POST);
		if ($add) {
			header("location: ?done");
		} else {
			header("location: ?error");
		}
	}
	if (isset($_POST['updatePass'])) {
		$add = $users->updatePassword($_POST);
		if ($add) {
			header("location: ?done");
		} else {
			header("location: ?error");
		}
	}
?>
<!doctype html>
        <!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en-US"> <![endif]-->
        <!--[if IE 7]>    <html class="lt-ie9 lt-ie8" lang="en-US"> <![endif]-->
        <!--[if IE 8]>    <html class="lt-ie9" lang="en-US"> <![endif]-->
        <!--[if gt IE 8]><!--> <html lang="en-US"> <!--<![endif]-->
        

<head>
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

                <title>LegalLens | Membership Area </title>

		<!--Sidelinks -->
		<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
		<link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css">
		<link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css">
		<link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
		<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
		<script src="SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
		<script src="SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
		<script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
    <?php $pages->head(); ?>

                <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
                <!--[if lt IE 9]>
                <script src="js/html5.js"></script>
                <![endif]-->

        <?php $pages->chatHeader(); ?>
        </head>

        <body>

                <!-- Start of Header -->
                <div class="header-wrapper">
                        <?php $pages->headerFiles(); ?>
                </div>
                <!-- End of Header -->

                <!-- Start of Search Wrapper -->
                <div class="search-area-wrapper_two">
                </div>
                <!-- End of Search Wrapper -->

                <!-- Start of Page Container -->
                <div class="page-container">
                        <div class="container">
                                <div class="row">
					<div class="span3">
				   <section class="widget">
                        <div class="login-widget">Welcome, <?php echo $last_name." ".$other_names; ?><br>
                       Current session started: <?php echo date('l jS \of F Y h:i:s A', $loginTime); ?><br>
                        Last logged in: <?php echo @date('l jS \of F Y h:i:s A', $last_login); ?><br>
                        <?php $pages->sideMenu(); ?></div></section>
<section>
<?php $pages->sidelinks(); ?>
            </section>
</div>

<div class="span7">
   <div style="border:1px solid #e9e6c4; padding:10px 10px 10px 10px; min-height:800px">
       <!-- <div style="width:630px;height:130px;" > -->
          <div align="right" style="margin-botton:30px;">Last updated : <?php echo date('l jS \of F Y h:i:s A', $modify_time); ?></div>
          <h3>Edit your profile</h3>   

        <?php if (isset($_GET['done'])) { ?>
        <p class="success">Action completed</p>
        <?php } else if (isset($_GET['error'])) { ?>
        <p class="error">An error occured, please try again</p>
        <?php } ?>        
<div style="margin-left:35px">
<form id="forgotpassword-form" class="row" action="" method="post">
                        
<h4><u>Basic information</u></h4>							
<table style="width:inherit">
  
  <tr>
    <td width="40%" valign="top"><strong>Lastname</strong></td> <td><span id="sprytextfield1">
      <input type=text name="last_name" id="last_name" class="required" value="<?php echo $last_name; ?>" >
      <span class="textfieldRequiredMsg">A value is required.</span></span></td>
   </tr>
<tr>
    <td width="40%" valign="top"><strong>Firstname</strong></td> <td><span id="sprytextfield2">
      <input type=text name="other_names" id="other_names" class="required" value="<?php echo $other_names; ?>" >
      <span class="textfieldRequiredMsg">A value is required.</span></span></td>
   </tr>
    
  <tr>
    <td width="40%" valign="top"><strong>Email</strong></td> <td>
      <input type="text" name="email" id="email" class="required " readonly value="<?php echo $email; ?>"></td>

   </tr>
   
    <tr>
    <td width="40%" valign="top"><strong>Phone number</strong></td> <td><span id="sprytextfield3">
    <input type=text name="phone" id="phone" class="required" value="<?php echo $phone; ?>" >
<span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
   </tr>
    <tr>
      <td valign="top"><strong>Address</strong></td>
      <td><span id="sprytextarea1">
        <textarea name="address" id="address"><?php echo $users->getOneField($ref, "ref", "address"); ?></textarea>
        <span class="textareaRequiredMsg">A value is required.</span></span></td>
    </tr>
    <tr>
    <td width="40%" valign="top"><input type="hidden" name="ref" id="ref" value="<?php echo $ref; ?>"></td>
    <td><input type="submit" name="updateInfo" value="Update Profile" class="btn btn-inverse"></td>
   </tr>
</table>
</form>
<form id="forgotpassword-form2" class="row" action="" method="post">
<p>
<h4><u>Security information</u></h4>
<table style="width:inherit">
<tr>
    <td width="47%" valign="top"><strong>New Password</strong></td> <td><span id="sprypassword1">
      <input type=password name="password" id="password" class="required" value="">
      <span class="passwordRequiredMsg">A value is required.</span></span></td>
   </tr>
<tr>
    <td width="40%" valign="top"><strong>Confirm Password</strong></td> <td><span id="spryconfirm1">
      <input type=password name="confirmPassword" id="confirmPassword" class="required" value="">
      <span class="confirmRequiredMsg">A value is required.</span><span class="confirmInvalidMsg">The values don't match.</span></span></td>
   </tr>
 <tr>
    <td width="40%" valign="top">&nbsp;</td>
    <td><input type="submit" name="updatePass" value="Update Password" class="btn btn-inverse"></td>
   </tr>
</table>
</form>
</div>
	</div>

   </div>
<!-- </div> -->
                                 </div> <!--end row -->      
			</div><!-- end container-->
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
               <!-- <script type='text/javascript' src='js/jquery-1.8.3.min.js'></script> -->
                <script type='text/javascript' src='js/jquery.easing.1.34e44.js?ver=1.3'></script>
                <script type='text/javascript' src='js/prettyphoto/jquery.prettyPhotoaeb9.js?ver=3.1.4'></script>
                <script type='text/javascript' src='js/jquery.liveSearchd5f7.js?ver=2.0'></script>
				<script type='text/javascript' src='js/jflickrfeed.js'></script>
                <script type='text/javascript' src='js/jquery.formd471.js?ver=3.18'></script>
                <script type='text/javascript' src='js/jquery.validate.minfc6b.js?ver=1.10.0'></script>
                <script type='text/javascript' src="js/jquery-twitterFetcher.js"></script>
                <script type='text/javascript' src='js/custom5152.js?ver=1.0'></script>
				<script type='text/javascript' src="js/navAccordion.min.js"></script>
        <script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "integer", {isRequired:false});
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1");
var spryconfirm1 = new Spry.Widget.ValidationConfirm("spryconfirm1", "password");
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1");
        </script>
        </body>


</html>

