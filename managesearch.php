<?php
	$redirect = "managesearch";
	include_once("includes/functions.php");
	include_once("includes/session.php");
	
	if (isset($_GET['del'])) {
		$edit = $searchUsers->remove($common->get_prep($_GET['del']));
		if ($edit) {
			header("location: ?done");
		} else {
			header("location: ?error");
		}
	}
	
	$list = $searchUsers->sortAll($ref, "users");
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

                <title>LegalLens |Manage Recent Search </title>
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
   <div style="border:1px solid #ccc; padding:10px">
      <!--  <div style="width:630px;height:130px;" >-->
      <h3>Manage Recent Search</h3>   

    <?php if (isset($_GET['done'])) { ?>
    <p class="success">Action completed successfully</p>
    <?php } ?>        
<table class="table table-hover">
   <thead>
      <tr>
	<th>S/N</td>
        <th>Page Name</th>
        <th>Date</th>
        <th>&nbsp;</th>
      </tr>
    </thead>
    <tbody>
    <?php for ($i = 0; $i < count($list); $i++) { ?>
      <tr>
        <td><?php echo $i+1; ?></td>
        <td><strong><?php echo $list[$i]['title']; ?></strong></td>
      	<td><?php echo date('l jS \of F Y h:i:s A', $list[$i]['create_time']); ?></td>
	<td><a href="?del=<?php echo $list[$i]['ref']; ?>" onClick="return confirm('this action will remove this record. are you sure you want to continue ?')">delete</a></td>
      </tr>
      <?php } ?>
    </tbody>
  </table></p>

<h5>Total number of saved pages: <?php echo number_format(count($list)); ?></h5>
	</div>

   </div>
<!--</div>-->

<div class="span2 page-content">
<?php $pages->rightColumnAdvert();?>
					</div>
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

        </body>


</html>

