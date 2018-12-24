<?php
	$redirect = "mobile_regulations.view";
	include_once("includes/functions.php");
	//include_once("includes/session.php");
	
	if (isset($_REQUEST['id'])) {
		$id = $common->get_prep($_REQUEST['id']);
		$read = $common->get_prep($_REQUEST['read']);
		$tag = "Documents issued by ".$id;
	} else {
		header("location: regulations");
	}
	
	if (isset($_REQUEST['return'])) {
		$s = $common->get_prep($_REQUEST['return']);
	}
	$data = $regulations->getOne($id);
	$list = $regulations_sections->sortAll($id, "regulations", "status", "active");
	$list_one = $regulations_sections->getOne($read);
	$common->updateCounter($id, $read, "regulations");
?>
<!doctype html>
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

    <title><?php echo $data['title']; ?></title>
<?php $pages->head(); ?>
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="js/html5.js"></script>
    <![endif]-->
			<style>
            /* Cosmetic only */
            .easyPaginateNav a {padding:5px;}
            .easyPaginateNav a.current {font-weight:bold;text-decoration:underline;}
            </style>

        <?php $pages->chatHeader(); ?>
</head>

<body>

                <!-- Start of Page Container -->
                <div>
                <div>
                

<div>
   <div style="border:1px solid #ccc; padding:10px">
     <div style="margin-top:30px">
       <?php if ((isset($s)) && ($s != "")) { ?>
       <p align="left"><a href="mobilehome?q=<?php echo $s; ?>" style="text-decoration:underline">Back to Search</a></p>
       <?php } ?>
       <h3 style="" align="center"><?php echo $data['title']; ?></h3>
       <p><?php if ($list_one['section_no'] != "") { ?>
       <strong><?php echo $list_one['section_no']; ?></strong><br>
       <?php } ?>
       <?php echo nl2br($list_one['section_content']); ?></p>
      <?php if ($read == true) { ?>
       <hr>
       <h4><strong>Also in <?php echo $data['title']; ?></strong></h4>
       <?php } ?>
        <div id="easyPaginate">
		   <?php for ($i = 0; $i < count($list); $i++) { ?>
               <p><?php if ($list[$i]['section_no'] != "") { ?>
               <strong><?php echo $list[$i]['section_no']; ?></strong><br>
               <?php } ?>
               <?php echo nl2br($list[$i]['section_content']); ?></p>
           <?php } ?>
       </div>
       
	 </div>

   </div>
</div>
                  </div> <!--end row -->      
			</div><!-- end container-->
                </div>
                <!-- End of Page Container -->

                <a href="#top" id="scroll-top"></a>
                <!-- script -->
               <!-- <script type='text/javascript' src='js/jquery-1.8.3.min.js'></script> -->
			   	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
                <script type='text/javascript' src='js/jquery.easing.1.34e44.js?ver=1.3'></script>
                <script type='text/javascript' src='js/prettyphoto/jquery.prettyPhotoaeb9.js?ver=3.1.4'></script>
                <script type='text/javascript' src='js/jquery.liveSearchd5f7.js?ver=2.0'></script>
				<script type='text/javascript' src='js/jflickrfeed.js'></script>
                <script type='text/javascript' src='js/jquery.formd471.js?ver=3.18'></script>
                <script type='text/javascript' src='js/jquery.validate.minfc6b.js?ver=1.10.0'></script>
                <script type='text/javascript' src="js/jquery-twitterFetcher.js"></script>
                <script type='text/javascript' src='js/frontEnd.js'></script>
				<script type='text/javascript' src="js/navAccordion.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="js/pagination.js"></script>
                
                
    <script>
        $(function() {
			$('#easyPaginate').easyPaginate({
				paginateElement: 'p',
				elementsPerPage: 20,
				effect: 'climb'
			});
        });
    </script>

        </body>


</html>

