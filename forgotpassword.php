<?php
	include_once("includes/functions.php");
?>
<!DOCTYPE html>
        <!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en-US"> <![endif]-->
        <!--[if IE 7]>    <html class="lt-ie9 lt-ie8" lang="en-US"> <![endif]-->
        <!--[if IE 8]>    <html class="lt-ie9" lang="en-US"> <![endif]-->
        <!--[if gt IE 8]><!--> <html lang="en-US"> <!--<![endif]-->
        
<!-- Mirrored from inspirythemes.biz/html-templates/knowledgebase-html/contact.html by HTTrack Website Copier/3.x [XR&CO'2013], Sun, 27 Mar 2016 11:14:26 GMT -->
<head>
    <meta charset="utf-8">
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

                <title>LegalLens | New User Registration </title>

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

                <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
                <!--[if lt IE 9]>
                <script src="js/html5.js"></script>
                <![endif]-->

        </head>

        <body>

                <!-- Start of Header -->
                <div class="header-wrapper">
                        <?php $pages->headerFiles(); ?>
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
                                                        <h2 class="post-title">
                                                                Forgot Password
                                                        </h2>
                                                        <hr>
                                                        <p>Forgot your password? Don't worry! Fill in the following details to get your password retrieved</p>
                                                </article>

					<div style="margin-left:35px">
                                                <form id="forgotpassword-form" class="row" action="" method="post">
															
								<table width="767">
									  
									  <tr>
										<td colspan=3>Registered Email address(username)</td>
									   </tr>
									<tr>
										<td colspan=3><input type=email name="email" id="lastName" class="required input-xlarge" value="" ></td>
									   </tr>
 									    
									  <tr>
										<td colspan=3>Last Remembered Password(not required)</td>

									   </tr>
									   
										<tr>
										<td colspan="3"><input type=password name="password" id="password" value="" class="input-xlarge"></td>
									   </tr>
									<tr>
										<td>Security Question</td><td>Security Question</td><td>Security Question</td>
									   </tr>
										<tr>
										<td>
										<select name="sec_que1">
											<option value="pet_name">What is your favourite pet name</option>
											<option value="car">What is your favourite car</option>
											<option value="colour">What is your favourite colour</option>
										    </select></br>
										<input type=text name="sec_ans1" id="sec_ans1" class="required" value="">
										</td>
										<td><select name="sec_que2">
											<option value="pet_name">What is your favourite pet name</option>
											<option value="car">What is your favourite car</option>
											<option value="colour">What is your favourite colour</option>
										    </select></br>
										<input type=text name="sec_ans2" id="sec_ans2" class="required" value=""></td>
										<td><select name="sec_que3">
											<option value="pet_name">What is your favourite pet name</option>
											<option value="car">What is your favourite car</option>
											<option value="colour">What is your favourite colour</option>
										    </select></br>
										<input type=text name="sec_ans3" id="sec_ans3" class="required" value="">
										</td>
									   </tr>
		
									 <tr>
										<td>&nbsp;</td>
									   </tr>
									 <tr>
										<td><input type="submit" name="submit" value="Submit" class="btn btn-inverse"></td>
									   </tr>
									
									
					        </table>
									
									  
								
                                                </form>
                                                </div>
                                        </div>
                                        <!-- end of page content -->


                                        <!-- start of sidebar -->
                                        <aside class="span4 page-sidebar">

                                                <section class="widget">
                                                        <div class="support-widget">
                                                                <h3 class="title">Need Help?</h3>
                                                                <p class="intro">Click here to <a id="mibew-agent-button" href="<?php echo URL; ?>helpAndSupport" >Contact Support</a></p>
                                                        </div>
                                                </section>
												<?php $pages->advert(); ?>


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
                <script type='text/javascript' src='js/jquery-1.8.3.min.js'></script>
                <script type='text/javascript' src='js/jquery.easing.1.34e44.js?ver=1.3'></script>
                <script type='text/javascript' src='js/prettyphoto/jquery.prettyPhotoaeb9.js?ver=3.1.4'></script>
                <script type='text/javascript' src='js/jquery.liveSearchd5f7.js?ver=2.0'></script>
				<script type='text/javascript' src='js/jflickrfeed.js'></script>
                <script type='text/javascript' src='js/jquery.formd471.js?ver=3.18'></script>
                <script type='text/javascript' src='js/jquery.validate.minfc6b.js?ver=1.10.0'></script>
                <script type='text/javascript' src="js/jquery-twitterFetcher.js"></script>
                <script type='text/javascript' src='js/custom5152.js?ver=1.0'></script>
                <script type='text/javascript' src='js/custom5152.js?ver=1.0'></script>

        </body>

<!-- Mirrored from inspirythemes.biz/html-templates/knowledgebase-html/contact.html by HTTrack Website Copier/3.x [XR&CO'2013], Sun, 27 Mar 2016 11:14:26 GMT -->
</html>

