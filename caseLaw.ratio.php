<?php
	$redirect = "caseLaw.ratio";
	include_once("includes/functions.php");
	include_once("includes/session.php");
	
	if (isset($_REQUEST['id'])) {
		$id = $common->get_prep($_REQUEST['id']);
		$tag = "Documents issued by ".$id;
	} else {
		header("location: caseLaw");
	}
	if (isset($_REQUEST['jump'])) {
		$jump = intval($common->get_prep($_REQUEST['jump']));
	} else {
		$jump = 0;
	}
	
	$data = $caselaw->getOne($id);
	if ($jump == 0) {
		$list = $caselaw->sortAll($id, "areas", "status", "active", false, false, 'title');
	} else {
		$list = $caselaw->sortAll($id, "areas", "status", "active", "ref", $jump);
	}
?>
<!DOCTYPE html>
        <!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en-US"> <![endif]-->
        <!--[if IE 7]>    <html class="lt-ie9 lt-ie8" lang="en-US"> <![endif]-->
        <!--[if IE 8]>    <html class="lt-ie9" lang="en-US"> <![endif]-->
        <!--[if gt IE 8]><!--> <html lang="en-US"> <!--<![endif]-->
<base href="<?php echo URL; ?>" />

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

                <title><?php echo $tag; ?></title>
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
                    	<?php $pages->sidelinks(); ?>
    
                	</div>

<div class="span7">
   <div style="border:1px solid #ccc; padding:10px">
     <div style="margin-top:30px">
       <h3 style="" align="center"><?php echo ucfirst(strtolower($id)); ?></h3>
       <?php for ($i = 0; $i < count($list); $i++) { ?>
        <h4><?php echo $i+1; ?>&nbsp;&nbsp;&nbsp;<strong><a href="<?php echo URL; ?>caselaw.view?id=<?php echo $list[$i]['ref']; ?>"><?php echo $list[$i]['title']; ?></a></strong></h4>
        <?php $listCase = $caselaw_sections->sortAll($list[$i]['ref'], "caselaw", "status", "active");
                for ($j = 0; $j < count($listCase); $j++) { ?>
                <span style="color: blue;"><?php echo nl2br($common->truncateLine($listCase[$j]['section_content'])); ?><span>
               <a href="<?php echo URL; ?>caselaw.read?id=<?php echo $list[$i]['ref']; ?>&read=<?php echo $listCase[$j]['ref']; ?>">Read More</a>
                <br>
                <cite style="font-size: 9px; color: black;"><?php echo $listCase[$j]['citation']; ?></cite><br>
               <?php } ?>
       <?php } ?>
       <br><br>
	 </div>

   </div>
</div>
<?php $pages->rightColumnAdvert(); ?>
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
                <script type='text/javascript' src='js/frontEnd.js'></script>
				<script type='text/javascript' src="js/navAccordion.min.js"></script>

        </body>


</html>

