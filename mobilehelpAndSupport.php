<?php
	$redirectLogin = "helpLogin";
	$redirect = "mobilehelpAndSupport";
	include_once("includes/functions.php");
	$urlData = "";
	$oneResult = false;
	$memo = false;
	if (isset($_REQUEST['t'])) {
		$t = $common->get_prep($_REQUEST['t']);
		$data = $knowledge_base->getOne($t);
		$oneResult = true;
		$urlData .= "&t=".$t;
	}
	
	if (isset($_POST['button2'])) {
		$add = $help->add($_POST, $_FILES);
		
		if ($add) {
			header("location: support?done");
		} else {
			header("location: ".$redirect."?error=".urlencode("An error occured"));
		}
	}
	
	if (isset($_REQUEST['c'])) {
		$c = $common->get_prep($_REQUEST['c']);
		$list = $knowledge_base->searchCategory($c);
		$urlData .= "&c=".$c;
	} else if (isset($_REQUEST['s'])) {
		$s = $common->get_prep($_REQUEST['s']);
		$list = $knowledge_base->search($c);
		$memo = "Your search for <strong>".$s."</strong> brought <strong>".count($list)."</strong> result(s)";
		$urlData .= "&s=".$s;
	} else {
		$list = $knowledge_base->sortAll("active", "status");
	}
	$listCat = $knowledge_base_category->listAll();
?>
<!doctype html>
        <!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en-US"> <![endif]-->
        <!--[if IE 7]>    <html class="lt-ie9 lt-ie8" lang="en-US"> <![endif]-->
        <!--[if IE 8]>    <html class="lt-ie9" lang="en-US"> <![endif]-->
        <!--[if gt IE 8]><!--> <html lang="en-US"> <!--<![endif]-->
        

<base href="<?php echo URL; ?>" />
<script src="SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css">
<link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
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

                <title>LegalLens | Help and Support</title>

        <?php $pages->head(); ?>
        </head>

        <body>


                
                
                <!-- End of Search Wrapper -->

                <!-- Start of Page Container -->
                <div >
                        <div>
                                <div>

                                        <!-- start of page content -->
                                     <div class="title"> <h5>  Categories | <a href="<?php echo URL.$redirect; ?>">All</a> | 
                                            <?php for ($i = 0; $i < count($listCat); $i++) { ?>
                                            <a href="<?php echo URL.$redirect."?c=".$listCat[$i]['ref']; ?>"><?php echo $listCat[$i]['title']; ?></a> | 
                                            
                                            <?php } ?>
                                            </h5>
                                          </div>  
                                  <div>

                                        
											<?php if (isset($_GET['error'])) { ?><br>
                                            <p class="error"><?php echo $_GET['error']; ?></p>
                                            <?php } ?>
                                            <hr>
                                            <?php if ($memo != false) {
												echo $memo."<br><br>";
											} ?>
                                      <table width="100%" border="0">
                                        <tr>
                                          <td width="80%s" valign="top">
                                          <?php if ($oneResult == true) { echo $data['content']."<br><em>found in ".$knowledge_base_category->catToTexLink($data['category'],true)." Last modified ".$common->get_time_stamp($list[$i]['modify_time'])."</em><br><br>";
                                           } ?>
                                          <?php if (count($list) > 0) {
										  for ($i = 0; $i < count($list); $i++) { ?>
                                          <div id="easyPaginate">
                                          <!--<?php //echo $common->seo($list[$i]['ref'], "help"); ?>-->
                                          <p><strong style="font-size:16px"><?php echo $list[$i]['title']; ?></strong><br>
                                          <!--echo $common->truncate($list[$i]['content'], 300); -->
                                          <?php echo $list[$i]['content']; ?><br>
                                          <em>found in <?php echo $knowledge_base_category->catToTexLink($list[$i]['category'],true); ?> Last modified <?php echo $common->get_time_stamp($list[$i]['modify_time']); ?></em></p>
                                          <?php } 
										  } else { ?>
                                          <p>we cannot get a document in the knowledge base that matches your search query, you can send a support request(on web portal) to one of our support specialists and they will get back to you with an answer</p>
                                          <?php } ?>
                                          </div>
                                          <h4>Still not Satisfied? Log in to your web portal to access support tickets area</h4>
                                          
                                      
                                        </tr>
                                      </table>
                                  </div>
                                        <!-- end of page content -->
                                        <!-- end of sidebar -->
                                </div>
                        </div>
                </div>
                <!-- End of Page Container -->

                
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
        <script src="js/pagination.js"></script>
                
        <script>
$('#easyPaginate').easyPaginate({
					paginateElement: 'p',
					elementsPerPage: 10,
					effect: 'climb'
				});

var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1");
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1");
        </script>

        </body>


</html>

