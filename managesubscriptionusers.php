<?php
	$redirect = "managesubscriptionusers";
	include_once("includes/functions.php");
	include_once("includes/session.php");
	
	if (($subscription_group_onwer != $ref) || ($subscription_group != 1)) {
		header("location managesubscription?error=".urlencode("You don't have permission to view this page"));
	}
	
	if (isset($_POST['submit'])) {
		$add = $users->createGroup($_POST);
		if ($add) {
			header("location: ?done");
		} else {
			header("location: ?error=".urlencode("This user has not been added. Seems the user already exisit in our database, if you are sure this user does not exist please try again or contact the admin for further assistance"));
		}
	}
	
	$sub_total = $orders->getOneField($users->getOneField($ref, "ref", "subscription_order"), "ref", "order_users");
	
	$sub_list = $users->sortAll($ref, "subscription_group_onwer", "subscription_group", 1);
	$sn = 0;
	
	if (isset($_GET['error_upload'])) {
		$error = unserialize(urldecode(stripslashes($_SESSION['error'])));
	}
?>
<!doctype html>
        <!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en-US"> <![endif]-->
        <!--[if IE 7]>    <html class="lt-ie9 lt-ie8" lang="en-US"> <![endif]-->
        <!--[if IE 8]>    <html class="lt-ie9" lang="en-US"> <![endif]-->
        <!--[if gt IE 8]><!--> <html lang="en-US"> <!--<![endif]-->
        

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

                <title>LegalLens | Manage Subscription </title>

        <?php $pages->head(); ?>
		<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">

                <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
                <!--[if lt IE 9]>
                <script src="js/html5.js"></script>
                <![endif]-->

        <script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
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
                        <!-- start of page content -->
                        <div class="span8 page-content">
                        <article class="type-page hentry clearfix">
                            <h2 class="post-title">
                                    Manage Subscription
                            Users</h2>
                            <hr>
                            <?php if (isset($error)) { ?>
                            <p class="error">There were <?php echo count($error); ?> error(s) please check the errors and re-upload file</p>
                            <p><ul>
                                <?php foreach ($error as $item) { ?>
                                <li style="color:#F00"><?php echo $item; ?></li>
                                <?php } ?>
                            </ul></p>
                        <?php } ?>
                            <?php if (isset($_GET['done'])) { ?>
                            <p class="success">New user added to your subscription successfully. This user will get an email containing the activation instruction</p>
                            <?php } else if (isset($_GET['error'])) { ?>
                            <p class="error"><?php echo $_GET['error']; ?></p>
                            <?php } ?>
							<p>You have currently used up <?php echo number_format(count($sub_list))."/".number_format($sub_total); ?> of your total prchased subscription user limit</p>
                        </article>
                        <div style="margin-left:35px">
                          <table width=300 class="table table-hover" align=center>
                            <thead>
                              <tr>
                                <th colspan=2>Subscription</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td width="25%">Current Subscription</td>
                                <td><?php echo $subscriptions->getOneField($subscription_type); ?></td>
                              </tr>
                              <tr>
                                <td width="25%">Subscription Type</td>
                                <td><?php echo $subscriptions->getOneField($subscription_type, "ref", "type"); ?></td>
                              </tr>
                              <tr>
                                <td width="25%">Expiry Date</td>
                                <td><?php echo date('l jS \of F Y h:i:s A', $subscription); ?></td>
                              </tr>
                            </tbody>
                          </table>
                          <table width=300 class="table table-hover" align=center>
                            <thead>
                              <tr>
                                <th colspan=4>Subscription Users</th>
                              </tr>
                              <tr>
                                <td>S/N</td>
                                <td>Name</td>
                                <td>Email Address</td>
                                <td>&nbsp;</td>
                              </tr>
                            </thead>
                            <tbody>
                            <?php for ($i = 0; $i < count($sub_list); $i++) {
								$sn++; ?>
                              <tr>
                                <td><?php echo $sn; ?></td>
                                <td><?php echo $sub_list[$i]['last_name']." ".$sub_list[$i]['other_names']; ?></td>
                                <td><?php echo $sub_list[$i]['email']; ?></td>
                                <td>remove from list</td>
                              </tr>
                            <?php } ?>
                            </tbody>
                          </table>
                          <?php if (count($sub_list) < $sub_total) { ?>
                          <form name="form1" id="form1" class="row"  method="post" action="">
                          <table width=300 class="table table-hover" align=center>
                            <thead>
                              <tr>
                                <th colspan=2>Add Single User</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td width="25%">Surname</td>
                                <td><span id="sprytextfield1">
                                <input type="text" name="last_name" id="last_name" class="input-large" value="">
                                <span class="textfieldRequiredMsg"><br>
                                A value is required.</span></span></td>
                              </tr>
                              <tr>
                                <td width="25%">Other Names</td>
                                <td><span id="sprytextfield2">
                                <input type="text" name="other_names" id="other_names" class="input-large" value="">
                                <span class="textfieldRequiredMsg"><br>
                                A value is required.</span></span></td>
                              </tr>
                              <tr>
                                <td width="25%">Email Address</td>
                                <td><span id="sprytextfield3">
                                <input type="text" name="email" id="email" class="input-large" value="">
                                <span class="textfieldRequiredMsg"><br>
                                A value is required.</span></span></td>
                              </tr>
                              <tr>
                                <td><input type="hidden" name="subscription_type" id="subscription_type" value="<?php echo $subscription_type; ?>">
                                <input type="hidden" name="subscription_group" id="subscription_group" value="1">
                                <input type="hidden" name="subscription_group_onwer" id="subscription_group_onwer" value="<?php echo $subscription_group_onwer; ?>">
                                <input type="hidden" name="subscription" id="subscription" value="<?php echo $subscription; ?>">
                                <input type="hidden" name="sub_total" id="sub_total" value="<?php echo $sub_total; ?>">
                                <input type="hidden" name="sub_list" id="sub_list" value="<?php echo count($sub_list); ?>"></td>
                                <td><input type="submit" name="submit" value="Add User" class="btn btn-inverse"></td>
                              </tr>
                            </tbody>
                          </table>
                          </form>
                          <form action="csv"  method="post" enctype="multipart/form-data" name="form2" class="row" id="form2">
                          <table width=300 class="table table-hover" align=center>
                            <thead>
                              <tr>
                                <th colspan=2>Upload Bulk Users</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td colspan="2">Once upload is completed, please confirm if all entries has been uploaded successfully</td>
                              </tr>
                              <tr>
                                <td width="25%">File</td>
                                <td><input type="file" name="filename" id="filename" accept=".csv"></td>
                              </tr>
                              <tr>
                                <td width="25%"><input type="hidden" name="subscription_type" id="subscription_type" value="<?php echo $subscription_type; ?>">
                                <input type="hidden" name="subscription_group" id="subscription_group" value="1">
                                <input type="hidden" name="subscription_group_onwer" id="subscription_group_onwer" value="<?php echo $subscription_group_onwer; ?>">
                                <input type="hidden" name="subscription" id="subscription" value="<?php echo $subscription; ?>">
                                <input type="hidden" name="sub_total" id="sub_total" value="<?php echo $sub_total; ?>">
                                <input type="hidden" name="sub_list" id="sub_list" value="<?php echo count($sub_list); ?>"></td>
                                <td><input type="submit" name="submit2" value="Upload File" class="btn btn-inverse"></td>
                              </tr>
                              <tr>
                                <td colspan="2"><a href="<?php echo URL; ?>Format">Click here to download the specified format file</a></td>
                              </tr>
                            </tbody>
                          </table>
                          </form>
                          <?php } ?>
                          </div>
                    </div>
                    <!-- end of page content -->


                    <!-- start of sidebar -->
                    <aside class="span4 page-sidebar">
                        <section class="widget">
                        	<div class="login-widget">Welcome, <?php echo $last_name." ".$other_names; ?><br>Last logged in: <?php echo date('l jS \of F Y h:i:s A', $loginTime); ?><br><a href="<?php echo URL; ?>managesubscription">Manage Subscription</a><br><a href="<?php echo URL; ?>support">Help and Support</a><br><a href="<?php echo URL; ?>userprofile">View profile</a><br><a href="<?php echo URL; ?>managesavedpages">Manage saved pages</a><br>
                        	<a href="<?php echo URL; ?>support">Help and Support</a><br><a href="<?php echo URL; ?>?logout">Logout</a></div>
                            
                            <div align="center">
                                    <a href="<?php echo URL; ?>helpAndSupport" ><img src="<?php echo URL; ?>/images/help.png" width="100" /></a><br />
                                    <h3 class="title">Need Help?</h3>
                                    <p class="intro">Click here to<br /><a id="mibew-agent-button" href="<?php echo URL; ?>helpAndSupport" >Contact Support</a></p>
                                </div>
                                <?php $pages->advert(); ?>
                        </section>
                        <section class="widget">

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
        <script type='text/javascript' src='js/jquery.easing.1.34e44.js?ver=1.3'></script>
        <script type='text/javascript' src='js/prettyphoto/jquery.prettyPhotoaeb9.js?ver=3.1.4'></script>
        <script type='text/javascript' src='js/jquery.liveSearchd5f7.js?ver=2.0'></script>
		<script type='text/javascript' src='js/jflickrfeed.js'></script>
        <script type='text/javascript' src='js/jquery.formd471.js?ver=3.18'></script>
        <script type='text/javascript' src='js/jquery.validate.minfc6b.js?ver=1.10.0'></script>
        <script type='text/javascript' src="js/jquery-twitterFetcher.js"></script>
        <script type='text/javascript' src='js/custom5152.js?ver=1.0'></script>
        <script type='text/javascript' src='js/frontEnd.js'></script>
				<script type='text/javascript' src="js/navAccordion.min.js"></script>
        <script type="text/javascript">
		<?php if (count($sub_list) < $sub_total) { ?>
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
		<?php } ?>
        </script>
        </body>


</html>

