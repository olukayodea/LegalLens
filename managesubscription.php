<?php
	$redirect = "managesubscription";
	include_once("includes/functions.php");
	include_once("includes/session.php");
	
	$sub_list = $users->sortAll($ref, "subscription_group_onwer", "subscription_group", 1);
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

                <title>LegalLens | Manage Subscription </title>

        <?php $pages->head(); ?>
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
                            </h2>
                            <hr>
                            <?php if (isset($_GET['error'])) { ?>
                            <p class="error">Y<?php echo $_GET['error']; ?></p>
                            <?php } ?>
                            <p>Hi <?php echo $last_name." ".$other_names; ?>, please review your current subscription data below</p>
                            <?php if (isset($_GET['renew'])) { ?>
                            <p class="error">You were redirected here because either your subscription has expired or you are yet to purchase a valid subscription</p>
                            <?php } ?>
                            <?php if ((time() > $subscription) && (!isset($_GET['renew']))) { ?>	
                            <p class="error">Your subscription expired <?php echo $common->get_time_stamp($subscription); ?>. Please renew your subscription now</p>
                            <?php } else if (((time()+(60*60*24*3)) > $subscription) && (!isset($_GET['renew']))) { ?>	
                            <p class="error">Your subscription expires in <?php echo $common->get_time_stamp($subscription); ?>. Please renew your subscription now</p>
                            <?php } ?>
                                
                        </article>
                        <div style="margin-left:35px">
                        <form id="subscribe-form" class="row" action="createOrder" method="post">				
                          <table width=300 class="table table-hover" align=center>
                                <thead>
                                  <tr>
                                <th colspan=2>Personal Details</th>
                                
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                <td width="25%">Surname</td>
                                <td><?php echo $last_name; ?></td>
                                
                                  </tr>
                                  <tr>
                                <td width="25%">Firstname</td>
                                <td><?php echo $other_names; ?></td>
                                
                                  </tr>
                                 <tr>
                                <td width="25%">Email address</td>
                                <td><?php echo $email; ?></td>
                                
                                  </tr>
                                 <tr>
                                <td width="25%">Mobile number</td>
                                <td><?php echo $phone; ?></td>
                                
                                  </tr>
                                </tbody>
                              </table>		
                            <table width=300 class="table table-hover" align=center>
                                <thead>
                                  <tr>
                                <th colspan=2>Subscription</th>
                                
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td colspan="2"><p>To make payments online through your card, please choose online payments.</p>
                                      <p>Alternatively, you can complete your subscription by making payment to our account provided below and contacting Legallens officials and send the name of payer and transaction referenced to us through our live chat to activate your subscription.</p>
                                      <p>Account details:<br>
                                      Name:&nbsp;&nbsp;<strong>Pearlhouse Legal Lens Limited</strong><br>
                                      Bank:&nbsp;&nbsp;<strong>United Bank For Africa</strong><br>
                                    Number:&nbsp;&nbsp;<strong>1019666896</strong></p></td>
                                  </tr>
                                  <tr>
                                <td width="25%">Current Subscription</td>
                                <td><?php echo $subscriptions->getOneField($subscription_type); ?></td>
                                
                                  </tr>
                              <?php if (($subscription == "") || ($subscription <  time()) || (isset($_GET['renew']))) { ?>
                                  <tr>
                                <td width="25%">User Type</td>
                                <td><select name="type" class="form-control" id="type" required onChange="fetchList(this.value), toggleStatus(this.value)">
                                    <option value="">Select User Type</option>
                                    <option value="single">Single User</option>
                                    <option value="group">Multiple User</option>
                                  </select></td>
                                
                                  </tr>
                                 <tr>
                                <td width="25%">Subscription Package</td>
                                <td><select name="package" class="form-control" id="package" required onChange="selectPackage(this.value)">
                                    <option value="">Select User Type First</option>
                                  </select></td>
                                  </tr>
                                 <tr>
                                <td width="25%">Number of Users</td>
                                <td><input type="number" name="num_user" id="num_user" onChange="enterUsers(this.value)" readonly required></td>
                                
                                  </tr>
                                 <tr>
                                <td width="25%">Payment Channel</td>
                                <td><select name="payment_type" id="payment_type" class="form-control" required onChange="getPayment(this.value)">
                                  <option value="">Select One</option>
                                  <option value="Online">Online Payment</option>
                                  <option value="Others">Other Channels</option>
                                </select></td>
                                  </tr>
                                 <tr>
                                <td width="25%">Payment Frequency</td>
                                <td><select name="payment_frequency" id="payment_frequency" class="form-control" required disabled>
                                  <option value="Single">Single Payment</option>
                                  <option value="Renew">Auto Renew</option>
                                </select></td>
                                  </tr>
                                 <div id="showCard" style="display:none;">
                                  <tr>
                                   <td>Subscription Fees</td>
                                   <td align="right"><span id="s_fee"><?php echo NGN; ?> 0.00</span></td>
                                 </tr>
                                 </div>
                                 <tr>
                                   <td>Subscription Fees</td>
                                   <td align="right"><span id="s_fee"><?php echo NGN; ?> 0.00</span></td>
                                 </tr>
                                 <tr>
                                   <td>Gross Total</td>
                                   <td align="right"><span id="g_total"><?php echo NGN; ?> 0.00</span></td>
                                 </tr>
                                 <tr>
                                   <td>Group Discount</td>
                                   <td align="right"><span id="d_disc">0%</span></td>
                                 </tr>
                                 <tr>
                                   <td>Net Total</td>
                                   <td align="right"><span id="n_total"><?php echo NGN; ?> 0.00</span></td>
                                 </tr>
                                 <tr>
                                   <td>&nbsp;</td>
                                   <td><input type="submit" name="submit" value="Subscribe" class="btn btn-inverse"></td>
                                 </tr>
                                 
                              <?php } else if ((time()+(60*60*24*3)) > $subscription) { ?>
                                  <tr>
                                <td width="25%">&nbsp;</td>
                                <td><a href="<?php echo URL; ?>managesubscription?renew">Renew Subscription</a></td>
                                
                                  </tr>
                              <?php } else { ?>
                                  <tr>
                                <td width="25%">Subscription Type</td>
                                <td><?php echo $subscriptions->getOneField($subscription_type, "ref", "type"); ?></td>
                                
                                  </tr>
                                  <tr>
                                <td width="25%">Expiry Date</td>
                                <td><?php echo date('l jS \of F Y h:i:s A', $subscription); ?></td>
                                
                                  </tr>
                                  <?php if (($subscription_group_onwer == $ref) && ($subscription_group == 1)) { ?>
                                  <tr>
                                <td width="25%">Subscription Users</td>
                                <td><?php echo number_format(count($sub_list)); ?> [ <a href="<?php echo URL; ?>managesubscriptionusers">Manage Users</a> ]</td>
                                
                                  </tr>
                                  <?php } ?>
                              <?php } ?>
                                </tbody>
                              </table>
                              <input name="total" id="total" type="hidden" value="">
                              <input name="order_owner" id="order_owner" type="hidden" value="<?php echo $ref; ?>">
                        </form>
                          </div>
                    </div>
                    <!-- end of page content -->


                    <!-- start of sidebar -->
                    <aside class="span4 page-sidebar">
                        <section class="widget">
                        	<div class="login-widget">Welcome, <?php echo $last_name." ".$other_names; ?><br>Last logged in: <?php echo date('l jS \of F Y h:i:s A', $loginTime); ?><br><a href="<?php echo URL; ?>managesubscription">Manage Subscription</a><br><a href="<?php echo URL; ?>support">Help and Support</a><br><a href="<?php echo URL; ?>userprofile">View profile</a><br><a href="<?php echo URL; ?>managesavedpages">Manage saved pages</a><br><a href="<?php echo URL; ?>?logout">Logout</a></div>
                            
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
                    <!-- DataTables -->
                <script src="management/plugins/datatables/jquery.dataTables.min.js"></script>
                <script src="management/plugin
                <script type='text/javascript' src='js/jquery.easing.1.34e44.js?ver=1.3'></script>s/datatables/dataTables.bootstrap.min.js"></script>
                <script type='text/javascript' src='js/prettyphoto/jquery.prettyPhotoaeb9.js?ver=3.1.4'></script>
                <script type='text/javascript' src='js/jquery.liveSearchd5f7.js?ver=2.0'></script>
				<script type='text/javascript' src='js/jflickrfeed.js'></script>
                <script type='text/javascript' src='js/jquery.formd471.js?ver=3.18'></script>
                <script type='text/javascript' src='js/jquery.validate.minfc6b.js?ver=1.10.0'></script>
                <script type='text/javascript' src="js/jquery-twitterFetcher.js"></script>
                <script type='text/javascript' src='js/custom5152.js?ver=1.0'></script>
				<script type='text/javascript' src="js/navAccordion.min.js"></script>
                <script type='text/javascript' src='js/frontEnd.js'></script>
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
	  </script>

        </body>


</html>

