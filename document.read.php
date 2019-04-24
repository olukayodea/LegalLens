<?php
	$redirect = "document.read";
	include_once("includes/functions.php");
	include_once("includes/session.php");
	
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
	$subList = $sections->sortAll($id, "document", "status", "active");
	$common->updateCounter($id, $read, "document");
	$prev = $sections->gettPrevNext($id, $read, "-");
  $next = $sections->gettPrevNext($id, $read, "+");
  
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
</script><?php 
// Program to display URL of current page. 
  
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') 
    $link = "https"; 
else
    $link = "http"; 
  
// Here append the common URL characters. 
$link .= "://"; 
  
// Append the host(domain name, ip) to the URL. 
$link .= $_SERVER['HTTP_HOST']; 
  
// Append the requested resource location to the URL 
$link .= $_SERVER['REQUEST_URI']; 
?> 
                <!-- META TAGS -->
                <meta charset="UTF-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0">

                <title><?php echo $data['title']; ?></title>
                <meta name="description" content="<?php echo $common->getLine($list['section_no']); ?>" />
                <meta property="og:title" content="<?php echo $data['title']; ?>" />
                <meta property="og:description" content="<?php echo $common->getLine($list['section_no']); ?>" />
                <meta property="og:url" content="<?php echo $link; ?>" />

                <meta name="twitter:card" content="summary_large_image">
                <meta name="twitter:site" content="<?php echo $link; ?>">
                <meta name="twitter:title" content="<?php echo $data['title']; ?>">
                <meta name="twitter:description" content="<?php echo $common->getLine($list['section_no']); ?>">
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
       <?php if ((isset($s)) && ($s != "")) { ?>
       <p align="left"><a href="home?q=<?php echo $s; ?>" style="text-decoration:underline">Back to Search</a></p>
       <?php } ?>
       <table width="100%" border="0">
         <tr>
           <td align="left"><a href="<?php echo URL."document.view"."?id=".$id."&view=Document&jump=0"; ?>" style="text-decoration:underline">Section List</a></td>
           <td align="right">&nbsp;</td>
         </tr>
         <tr>
           <td align="left"><?php if (intval($prev) > 0) { ?><a href="<?php echo URL."document.read"."?id=".$id."&read=".$prev; ?>" style="text-decoration:underline">&lt; &lt; Previous Section</a><?php } ?></td>
           <td align="right"<?php if (intval($next) > 0) { ?>><a href="<?php echo URL."document.read"."?id=".$id."&read=".$next; ?>" style="text-decoration:underline">Next Section &gt; &gt;</a><?php } ?></td>
        </tr>
       </table>
<h3 style="" align="center"><?php echo $data['title']; ?></h3>
       <p align="center"><strong><?php echo $list['section_no']; ?></strong></p>
       <p><?php echo nl2br(html_entity_decode($list['section_content'])); ?></p>
       <?php if (count($subList) > 0) { ?>
           <h3 style="" align="center">Other Sections in This Document</h3>
           <?php for ($i = 0; $i < count($subList); $i++) {
             if (($i+1 % 10) == false) { ?>          
              <ins class="adsbygoogle"
              style="display:inline-block;width:728px;height:90px"
              data-ad-client="ca-pub-4142286148495329"
              data-ad-slot="9218590698"></ins>
              <script>
              (adsbygoogle = window.adsbygoogle || []).push({});
              </script>
              <br>
             <?php } ?>
           <a href="<?php echo URL; ?>document.read?id=<?php echo $data['ref']; ?>&read=<?php echo $subList[$i]['ref']; ?>"><strong><?php echo $subList[$i]['section_no']; ?></strong></a><br>
           <?php } ?>
       <?php } ?>
       
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

