<?php
        include_once("includes/functions.php");
        $message = "";
        $name = "";
        $reason = "";
        $email = "";
	if (isset($_POST['submit'])) {
                if (strtolower($_POST['captcha']) == strtolower($_SESSION['code'])) {
                        $common->sendContact($_POST);
                        header("location: ?done");
                } else {
                        $name = $_POST['name'];
                        $reason = $_POST['reason'];
                        $message = $_POST['message'];
                        $email = $_POST['email'];
                        $er = "The seciurity code you entered does not match";
                }
	}
	
	$random = rand(1000, 9999);
?>
<!DOCTYPE html>
        <!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en-US"> <![endif]-->
        <!--[if IE 7]>    <html class="lt-ie9 lt-ie8" lang="en-US"> <![endif]-->
        <!--[if IE 8]>    <html class="lt-ie9" lang="en-US"> <![endif]-->
        <!--[if gt IE 8]><!--> <html lang="en-US"> <!--<![endif]-->
        

<head>
        <meta charset="utf-8">
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
        <script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
        <script src="SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
        <script>
        (adsbygoogle = window.adsbygoogle || []).push({
        google_ad_client: "ca-pub-4142286148495329",
        enable_page_level_ads: true
        });
        </script>
        <!-- META TAGS -->
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Contact legallens</title>
        <meta name="description" content="Get in touch with the Legal Lens team and we'd respond within 24hrs">

        <?php $pages->head(); ?>
        <link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
        <link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
        <link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css">
</head>

        <body>

                <!-- Start of Header -->
                <div class="header-wrapper">
                        <?php $pages->headerFiles("contact"); ?>
                </div>
                <!-- End of Header -->

                <!-- Start of Search Wrapper -->
                <div class="search-area-wrapper">
                </div>
                <!-- End of Search Wrapper -->

                <!-- Start of Page Container -->
                <div class="page-container">
                        <div class="container">
                                <div class="row">

                                        <!-- start of page content -->
                                        <div class="span8 page-content">

                                                <article class="type-page hentry clearfix">
                                                        <h1 class="post-title">
                                                                <a href="#">Contact</a>
                                                        </h1>
                                                        <hr>
														<?php if (isset($_REQUEST['done'])) { ?>
                                                        <p class="success">Message Sent!</p>
                                                        <?php } ?>
                                                        <p>Drop a line and we will get in touch with you </p>
                                                </article>


                                                <form id="contact-form" class="row" action="" method="post">

                                                        <div class="span2">
                                                                <label for="name">Your Name <span>*</span> </label>
                                                        </div>
                                                        <div class="span6"><span id="sprytextfield1">
                                                          <input type="text" name="name" id="name" class="required input-xlarge" value="" title="* Please provide your name" value="<?php echo $name; ?>">
                                                        <span class="textfieldRequiredMsg">A value is required.</span></span></div>

                                                        <div class="span2">
                                                                <label for="email">Your Email <span>*</span></label>
                                                        </div>
                                                        <div class="span6"><span id="sprytextfield2">
                                                        <input type="text" name="email" id="email" class="email required input-xlarge" value="" title="* Please provide a valid email address" value="<?php echo $email; ?>">
                                                        <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></div>

                                                        <div class="span2">
                                                                <label for="reason">Subject <span>*</span></label>
                                                        </div>
                                                        <div class="span6"><span id="sprytextfield3">
                                                          <input type="text" name="reason" id="reason" class="input-xlarge" value="<?php echo $reason; ?>">
                                                        <span class="textfieldRequiredMsg">A value is required.</span></span></div>

                                                        <div class="span2">
                                                                <label for="message">Your Message <span>*</span> </label>
                                                        </div>
                                                        <div class="span6"><span id="sprytextarea1">
                                                          <textarea name="message" id="message" class="required span6" rows="6" title="* Please enter your message"><?php echo $message; ?></textarea>
                                                        <span class="textareaRequiredMsg">A value is required.</span></span></div>

                                                        <div class="span2">
                                                                <label for="captcha">
                                                                        <img src="<?php echo URL; ?>includes/scripts/captcha.php" />
                                                                </label>
                                                        </div>
                                                  <div class="span6"><span id="spryconfirm1">
                                                    <input type="text" name="captcha" id="captcha" class="input-large span3" required />
                                                    <span class="confirmRequiredMsg">A value is required.</span></span>
                                                        </div>

                                                        <div class="span6 offset2 bm30">
                                                                <input type="submit" name="submit" value="Send Message" class="btn btn-inverse">
                                                        </div>

                                                </form>
                                                        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                                                        <!-- forum -->
                                                        <ins class="adsbygoogle"
                                                             style="display:inline-block;width:728px;height:90px"
                                                             data-ad-client="ca-pub-4142286148495329"
                                                             data-ad-slot="9218590698"></ins>
                                                        <script>
                                                        (adsbygoogle = window.adsbygoogle || []).push({});
                                                        </script>
                                        </div>
                                        <!-- end of page content -->


                                        <!-- start of sidebar -->
                                        <aside class="span4 page-sidebar">

                                                <section class="widget">
                                                    <div align="center">
                                                        <a href="<?php echo URL; ?>helpAndSupport" ><img src="<?php echo URL; ?>/images/help.png" width="100" /></a><br />
                                                        <h3 class="title">Need Help?</h3>
                                                        <p class="intro">Click here to<br /><a id="mibew-agent-button" href="<?php echo URL; ?>helpAndSupport" >Contact Support</a></p>
                                                    </div>
													<?php $pages->advert(); ?>
                                                </section>
                                        </aside>
                                        <!-- end of sidebar -->
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
		<script type='text/javascript' src="js/navAccordion.min.js"></script>
        <script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "email");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1");
        </script>
          <script src="https://www.google.com/recaptcha/api.js?render=6LcWZo8UAAAAABUYrbZ_lqFVn_qvvjcwTP2BWUaF"></script>
  <script>
grecaptcha.ready(function() {
      grecaptcha.execute('6LcWZo8UAAAAABUYrbZ_lqFVn_qvvjcwTP2BWUaF', {action: 'homepage'}).then(function(token) {
         ...
      });
  });
  </script>
        </body>


</html>

