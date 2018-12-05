<?php
	$redirect = "support";
	include_once("includes/functions.php");
	include_once("includes/session.php");
	if (isset($_REQUEST['open'])) {
		$list = $help->sortAll(0, "parent_id", "user_id", $ref, "status", 1);
		$tag = "Showing Opened Support Tickets";
	} else if (isset($_REQUEST['closed'])) {
		$list = $help->sortAll(0, "parent_id", "user_id", $ref, "status", 2);
		$tag = "Showing Closed Support Tickets";
	} else {
		$list = $help->sortAll(0, "parent_id", "user_id", $ref);
		$tag = "Showing all Support Tickets";
	}
	$listCat = $knowledge_base_category->listAll();
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

                <title>LegalLens | Help and Support</title>

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
                            <h2 class="post-title">Help and Support</h2>
                            <hr>
                            <?php if (isset($_GET['error'])) { ?>
                            <p class="error">Y<?php echo $_GET['error']; ?></p>
                            <?php } ?>
                          <h4><?php echo $tag; ?></h4>
                          <p>Search Help and support category <a href="<?php echo URL.$redirect; ?>">All</a>
                                          <?php for ($i = 0; $i < count($listCat); $i++) { ?>
                                           | <a href="<?php echo URL.$redirect."?c=".$listCat[$i]['ref']; ?>"><?php echo $listCat[$i]['title']; ?></a>
                                          
                                          <?php } ?>
                          </p>
                                          <p>Need help? Read through <a href="helpAndSupport">our knowledgebase</a> for tips on self help on various topics or create a <a href="helpAndSupport">new support ticket</a></p>
                        </article>
                        <div style="margin-left:35px">
                          <table id="example1" class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                <th>&nbsp;</th>
                                <th>Category</th>
                                <th>Customer Name</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Updated</th>
                                <th>Last Modified By</th>
                                <th>&nbsp;</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php 
                            $amount = 0; 
                            for ($i = 0; $i < count($list); $i++) {
                            $sn++;?>
                              <tr>
                                <td><?php echo $sn; ?></td>
                                <td><?php echo $common->highlight($knowledge_base_category->getOneField($list[$i]['category']), $list[$i]['status']); ?></td>
                                <td><?php echo $common->highlight($users->getOneField($list[$i]['user_id'], "ref", "last_name")." ".$users->getOneField($list[$i]['user_id'], "ref", "other_names"), $list[$i]['status']); ?></td>
                                <td><?php echo $common->highlight($help->status($list[$i]['status']), $list[$i]['status']); ?></td>
                                <td><?php echo $common->highlight(date('l jS \of F Y h:i:s A', $list[$i]['create_time']), $list[$i]['status']); ?></td>
                                <td><?php echo $common->highlight(date('l jS \of F Y h:i:s A', $list[$i]['modify_time']), $list[$i]['status']); ?></td>
                                <td><?php echo $common->highlight($admin->getOneField($list[$i]['admin_id']), $list[$i]['status']); ?></td>
                                <td><a href="support.read?open&ref=<?php echo $list[$i]['ref']; ?>"> <?php echo $common->highlight("Open", $list[$i]['status']); ?> </a></td>
                              </tr>
                              <?php } ?>
                            </tbody>
                            <tfoot>
                              <tr>
                                <th>&nbsp;</th>
                                <th>Category</th>
                                <th>Customer Name</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Updated</th>
                                <th>Last Modified By</th>
                                <th>&nbsp;</th>
                              </tr>
                            </tfoot>
                          </table>
                          </div>
                    </div>
                    <!-- end of page content -->


                    <!-- start of sidebar -->
                    <aside class="span4 page-sidebar">
                        <section class="widget">
                        	<div class="login-widget">Welcome, <?php echo $last_name." ".$other_names; ?><br>Last logged in: <?php echo date('l jS \of F Y h:i:s A', $loginTime); ?><br>
                            
                                          <a href="<?php echo URL; ?>support">My Ticket</a><br>
                                          <a href="<?php echo URL; ?>support?open">Open Tickets</a><br>
                                          <a href="<?php echo URL; ?>support?closed">closed Tickets</a><br>
                                          <a href="<?php echo URL; ?>managesubscription">Manage Subscription</a><br>
                                          <a href="<?php echo URL; ?>support">Help and Support</a><br>
                                          <a href="<?php echo URL; ?>userprofile">View profile</a><br>
                                          <a href="<?php echo URL; ?>managesavedpages">Manage saved pages</a><br>
                                          <a href="<?php echo URL; ?>?logout">Logout</a></div>
                            
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
                <script type='text/javascript' src='js/jquery.easing.1.34e44.js?ver=1.3'></script>
                <script type='text/javascript' src='js/prettyphoto/jquery.prettyPhotoaeb9.js?ver=3.1.4'></script>
                <script type='text/javascript' src='js/jquery.liveSearchd5f7.js?ver=2.0'></script>
				<script type='text/javascript' src='js/jflickrfeed.js'></script>
                <script type='text/javascript' src='js/jquery.formd471.js?ver=3.18'></script>
                <script type='text/javascript' src='js/jquery.validate.minfc6b.js?ver=1.10.0'></script>
                <script type='text/javascript' src="js/jquery-twitterFetcher.js"></script>
                <script type='text/javascript' src='js/custom5152.js?ver=1.0'></script>
				<script type='text/javascript' src="js/navAccordion.min.js"></script>
                <script type='text/javascript' src='js/frontEnd.js'></script>

        </body>


</html>

