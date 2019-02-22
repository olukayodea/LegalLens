<?php
	$redirect = "courts";
	include_once("includes/functions.php");
	include_once("includes/session.php");
	
	 if ((isset($_REQUEST['sort'])) && ($_REQUEST['sort'] == true)) {
		 $sort = urldecode($_REQUEST['sort']);
		 $sortType = "court";
		 $urlPara = "&sort=".urldecode($sort);
		 $title = $sort;
	 } else {
		 $sort = false;
		 $sortType = false;
		 $urlPara = "";
		 $title = "Courts";
	 }
	 
	 if (isset($_REQUEST['s'])) {
		$s = $common->get_prep($_REQUEST['s']);
		$list = $listItem->fullSearch($s, "Court", $court, $sortType);
		$tag = "Search Result for <strong>'".$s."'</strong>";
	 } else if (isset($_REQUEST['q'])) {
		$q = $common->get_prep($_REQUEST['q']);
		$list = $listItem->indexSearch($q, "Court", $sort, $sortType);
	} else {
		$list = $listItem->listAllHome("Court", $sort, $sortType);
	}
?>
<!DOCTYPE html>
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

                <title>List of <?php echo $title; ?> </title>
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
       <h4 style="" align="center">List of <?php echo $title; ?> </h4>
       	<i>Click on the table headers to sort in acceding and defending order</i>
            <table width="100%" border="0" id="example1">
            <thead>
              <tr>
                <td>&nbsp;</td>
                <td>Court</td>
                <td>Court Type</td>
                <td>State</td>
              </tr>
            </thead>
            <tbody>
            <?php for ($i = 0; $i < count($list); $i++) { ?>
              <?php if ($i % 2) { ?>
              <tr bgcolor="#CCCCCC">
              <?php } else { ?>
              <tr>
              <?php } ?>
                <td>&nbsp;</td>
                <td><?php echo $list[$i]['title'];?></td>
                <td><?php echo $list[$i]['court']; ?></td>
                <td><?php echo $list[$i]['state']; ?></td>
              </tr>
            <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <td>&nbsp;</td>
                <td>Court</td>
                <td>Court Type</td>
                <td>State</td>
              </tr>
            </tfoot>
            </table>
            <p>&nbsp;</p>
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
                
                <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
                <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
				<link rel="stylesheet" href="css/jquery.ui.datatables.css">
                <script src="management/plugins/datatables/jquery.dataTables.min.js"></script>
                <script>
					$(function() {
        				$("#example1").DataTable();
						$( "#s" ).autocomplete({
						  source: "includes/scripts/auto_complete_list.php?type=Court"
						});
					});
				</script>

        </body>


</html>

