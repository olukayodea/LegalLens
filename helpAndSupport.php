<?php
	$redirectLogin = "helpLogin";
	$redirect = "helpAndSupport";
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

                <title>Need help? Get in touch with Legal Lens for legal research support</title>
                <meta name="description" content="Need help with your subscription or legal research and agreement templates? Ask Legal Lens">

        <?php $pages->head(); ?>
        </head>

        <body>

                <!-- Start of Header -->
                <div class="header-wrapper">
                        <?php $pages->headerFiles(); ?>
                </div>
                <!-- End of Header -->

                <!-- Start of Search Wrapper -->
                <div class="search-area-wrapper">
                        <div class="search-area container">
                                <form id="search-form" class="search-form clearfix" method="get" action="<?php echo URL.$redirect; ?>" autocomplete="off">
                                        <input class="search-term required" type="text" id="s" name="s" placeholder="search knowledge bases" title="* search knowledge base!" />
                                        <input class="search-btn" type="submit" value="Search" />
                                        <div id="search-error-container"></div>
                          </form>
                  </div>
                </div>
                <!-- End of Search Wrapper -->

                <!-- Start of Page Container -->
                <div class="page-container">
                        <div class="container">
                                <div class="row">

                                        <!-- start of page content -->
                                  <div class="page-content">

                                            <h1 class="post-title">
                                                <?php if ($oneResult == true) { ?>
                                                <a href="#"><?php echo $data['title']; ?></a>
												<?php } else { ?>
                                                <a href="#">Help and Support</a>
                                                <?php } ?>
                                            </h1>
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
                                          <?php if ($oneResult == true) { echo $data['content']."<br><em>found in ".$knowledge_base_category->catToTexLink($data['category'])." Last modified ".$common->get_time_stamp($list[$i]['modify_time'])."</em><br><br>";
                                           } ?>
                                          <?php if (count($list) > 0) {
										  for ($i = 0; $i < count($list); $i++) { ?>
                                          <div id="easyPaginate">
                                          <p><a href="<?php echo $common->seo($list[$i]['ref'], "help"); ?>"><strong style="font-size:16px"><?php echo $list[$i]['title']; ?></strong></a><br>
                                          <?php echo $common->truncate($list[$i]['content'], 300); ?><br>
                                          <em>found in <?php echo $knowledge_base_category->catToTexLink($list[$i]['category']); ?> Last modified <?php echo $common->get_time_stamp($list[$i]['modify_time']); ?></em></p>
                                          <?php } 
										  } else { ?>
                                          <p>we cannot get a document in the knowledge base that matches your search query, you can send a support request to one of our support specialists and they will get back to you with an answer</p>
                                          <?php } ?>
                                          </div>
                                          <h2>Still not Satisfied?</h2>
                                          <?php if (isset($_SESSION['users']['ref'])) { ?>
                                          <form action="" method="post" enctype="multipart/form-data" name="form2">
                                            <table width="100%" border="0">
                                              <tr>
                                                <td>Support Category</td>
                                              </tr>
                                              <tr>
                                                <td><span id="spryselect1">
                                                  <select name="category" id="category">
                                                    <option>Select One</option>
                                                    <?php for ($i = 0; $i < count($listCat); $i++) { ?>
                                                    <option value="<?php echo $listCat[$i]['ref']; ?>"><?php echo $listCat[$i]['title']; ?></option>
                                                    <?php } ?>
                                                  </select>
                                                <span class="selectRequiredMsg">Please select an item.</span></span></td>
                                              </tr>
                                              <tr>
                                                <td>What can we help you with?</td>
                                              </tr>
                                              <tr>
                                                <td><span id="sprytextarea1">
                                                  <textarea name="content" id="content"></textarea>
                                                <span class="textareaRequiredMsg">A value is required.</span></span>
                                                  <input type="hidden" name="parent_id" id="parent_id" value="0">
                                                  <input type="hidden" name="user_id" id="user_id" value="<?php echo trim($_SESSION['users']['ref']); ?>">
                                                  <input type="hidden" name="admin_id" id="admin_id" value="0">
                                                  <input type="hidden" name="response_id" id="response_id" value="0"></td>
                                              </tr>
                                              <tr>
                                                <td>Attachment</td>
                                              </tr>
                                              <tr>
                                                <td><input type="file" name="media_file" id="media_file">
                                                <br>
                                                <em>you can upload file up to 2MB in size</em></td>
                                              </tr>
                                              <tr>
                                                <td><input type="submit" name="button2" id="button2" value="Submit" class="btn btn-inverse"></td>
                                              </tr>
                                            </table>
                                          </form>
                                          <?php } else { ?>
                                          <p>You must be a valid LegalLens users to open a support ticket, please click here to <a href="<?php echo URL."?register&redirect=".$redirectLogin."&msg=please+login"."&".$urlData; ?>">register</a> or <a href="<?php echo URL."?redirect=".$redirectLogin."&msg=please+login"."&".$urlData; ?>">login</a></p>
                                          <?php } ?></td>
                                          <td width="20s%" valign="top">
                                          

                                        <section class="widget"><h3 class="title">Categories</h3>
                                            <ul>
                                            <li><a href="<?php echo URL.$redirect; ?>">All</a></li>
                                            <?php for ($i = 0; $i < count($listCat); $i++) { ?>
                                            <li><a href="<?php echo URL.$redirect."?c=".$listCat[$i]['ref']; ?>"><?php echo $listCat[$i]['title']; ?></a></li>
                                            
                                            <?php } ?>
                                            </ul>
                                        </section>
                                        <section class="widget"><h3 class="title">Support Menu</h3>
                                            <ul>
                                            
                                          <li><a href="<?php echo URL; ?>support">My Ticket</a></li>
                                          <li><a href="<?php echo URL; ?>support?open">Open Tickets</a></li>
                                          <li><a href="<?php echo URL; ?>support?closed">closed Tickets</a></li>
                                            </ul>
                                        </section>
                                        <section class="widget">
                                            <?php $pages->advert(); ?>
                                        </section></td>
                                        </tr>
                                      </table>
                                  </div>
                                        <!-- end of page content -->
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

