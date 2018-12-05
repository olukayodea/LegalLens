<?php
	$redirect = "mobiledocument.read";
	include_once("includes/functions.php");
	//include_once("includes/session.php");
	
	if (isset($_REQUEST['id'])) {
		$id = $common->get_prep($_REQUEST['id']);
	} else {
		header("location: document");
	}
	if (isset($_REQUEST['read'])) {
		$read = intval($common->get_prep($_REQUEST['read']));
	} else {
		header("location: document.view?id=".$id);
	}
	if (isset($_REQUEST['return'])) {
		$s = $common->get_prep($_REQUEST['return']);
		$tag = "&return=".$s;
	}
	
	$data = $documents->getOne($id);
	$list = $sections->getOne($read);
	$subList = $sections->sortAll($read, "document", "status", "active");
	$common->updateCounter($id, $read, "document");
	$prev = $sections->gettPrevNext($read, "-");
	$next = $sections->gettPrevNext($read, "+");
?>
<!doctype html>
        <!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en-US"> <![endif]-->
        <!--[if IE 7]>    <html class="lt-ie9 lt-ie8" lang="en-US"> <![endif]-->
        <!--[if IE 8]>    <html class="lt-ie9" lang="en-US"> <![endif]-->
        <!--[if gt IE 8]><!--> <html lang="en-US"> <!--<![endif]-->
<base href="<?php echo URL; ?>" />

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

                <title><?php echo $data['title']; ?></title>
    <?php $pages->head(); ?>
                <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
                <!--[if lt IE 9]>
                <script src="js/html5.js"></script>
                <![endif]-->

        <?php $pages->chatHeader(); ?>
        </head>

        <body>

              
                <!-- End of Header -->

               

                <!-- Start of Page Container -->
                <div >
                <div >
                <div >
                    

<div >
   <div style="border:1px solid #ccc; padding:10px">
     <div style="margin-top:30px">
       <?php if ((isset($s)) && ($s != "")) { ?>
       <p align="left"><a href="mobilehome?q=<?php echo $s; ?>" style="text-decoration:underline">Back to Search</a></p>
       <?php } ?>
       <table width="100%" border="0">
         <tr>
           <td align="left"><a href="<?php echo URL."mobiledocument.view"."?id=".$id."&view=Document&jump=0"; ?>" style="text-decoration:underline">Section List</a></td>
           <td align="right">&nbsp;</td>
         </tr>
       </table>
<h3 style="" align="center"><?php echo $data['title']; ?></h3>
       <p align="center"><?php echo $list['section_no']; ?></p>
       <p><?php echo nl2br($list['section_content']); ?></p>
       <?php if (count($subList) > 0) { ?>
           <h3 style="" align="center">Other Sections in This Document</h3>
           <?php for ($i = 0; $i < count($subList); $i++) { ?>
           <p><strong><?php echo $subList['section_no']; ?></strong><br>
		   <?php echo nl2br($common->truncate($subList[$i]['section_content'], 500)); ?><br>
           <a href="<?php echo URL; ?>document.read?id=<?php echo $data['ref']; ?>&read=<?php echo $subList[$i]['ref']; ?>">Read More</a></p>
           <?php } ?>
       <?php } ?>
       
	 </div>

   </div>
</div>

                                 </div> <!--end row -->      
			</div><!-- end container-->
                </div>
                <!-- End of Page Container -->

            

              //  <a href="#top" id="scroll-top"></a>

                <!-- script -->
               <!-- <script type='text/javascript' src='js/jquery-1.8.3.min.js'></script> -->
                <script type='text/javascript' src='js/jquery.easing.1.34e44.js?ver=1.3'></script>
                <script type='text/javascript' src='js/prettyphoto/jquery.prettyPhotoaeb9.js?ver=3.1.4'></script>
                <script type='text/javascript' src='js/jquery.liveSearchd5f7.js?ver=2.0'></script>
				<script type='text/javascript' src='js/jflickrfeed.js'></script>
                <script type='text/javascript' src='js/jquery.formd471.js?ver=3.18'></script>
                <script type='text/javascript' src='js/jquery.validate.minfc6b.js?ver=1.10.0'></script>
                <script type='text/javascript' src="js/jquery-twitterFetcher.js"></script>
                <script type='text/javascript' src='js/frontEnd.js'></script>
				<script type='text/javascript' src="js/navAccordion.min.js"></script>

        </body>


</html>

