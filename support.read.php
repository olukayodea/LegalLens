<?php
	$redirect = "support.read";
	include_once("includes/functions.php");
	include_once("includes/session.php");
	
	if (isset($_REQUEST['ref'])) {
		$id = $common->get_prep($_REQUEST['ref']);
	} else {
		header("location: support?error=".urlencode("Select an items"));
	}
	
	if (isset($_POST['editButton'])) {
		$_FILES["media_file"]["error"] = 4;
		$add = $help->add($_POST, $_FILES, true);
		
		if ($add) {
			$help->modifyOne("admin_id", $ref, $id);
			header("location: ?done&ref=".$id);
		} else {
			header("location: ".$redirect."?error=".urlencode("An error occured&ref=".$id));
		}
	}
	
	$data = $help->getOne($id);
	if ($data['parent_id'] != 0) {
		header("location: ".$redirect."?ref=".$data['parent_id']);
	}
	
	$list = $help->sortAll($data['ref'], "parent_id");
	$listCat = $knowledge_base_category->listAll();
?>
<!doctype html>
        <!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en-US"> <![endif]-->
        <!--[if IE 7]>    <html class="lt-ie9 lt-ie8" lang="en-US"> <![endif]-->
        <!--[if IE 8]>    <html class="lt-ie9" lang="en-US"> <![endif]-->
        <!--[if gt IE 8]><!--> <html lang="en-US"> <!--<![endif]-->
        

<head>
    <meta charset="utf-8">
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
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
                          <table width="100%" border="1" cellpadding="1">
                            <tr bordercolor="#CCCCCC">
                              <td width="25%"><?php echo $users->getOneField($data['user_id'], "ref", "last_name")." ".$users->getOneField($data['user_id'], "ref", "other_names"); ?></td>
                              <td width="25%"><?php echo $common->get_time_stamp($data['create_time']); ?></td>
                              <td width="25%"><?php echo $knowledge_base_category->catToTex($data['category']); ?></td>
                              <td width="25%"><?php if ($data['file'] != "") { ?>
                                <a href="<?php echo URL; ?>library/helpfiles/<?php echo $data['file']; ?>" target="_blank">View File</a>
                                <?php } ?></td>
                            </tr>
                            <tr>
                              <td colspan="4"><?php echo $data['content']; ?></td>
                            </tr>
                            <tr>
                              <td colspan="4" align="right"><em>Status: <?php echo $help->status($data['status']); ?><br>
                                Last modified on <?php echo date('l jS \of F Y h:i:s A', $data['modify_time']); ?><br>
                                Last Modified By <?php echo $admin->getOneField($data['admin_id']); ?></em></td>
                            </tr>
                            <tr>
                              <td colspan="4"><hr></td>
                            </tr>
                            <?php for ($i = 0; $i < count($list); $i++) { ?>
                            <tr bordercolor="#CCCCCC">
                              <td width="25%"><?php echo $users->getOneField($list[$i]['user_id'], "ref", "last_name")." ".$users->getOneField($list[$i]['user_id'], "ref", "other_names"); ?></td>
                              <td width="25%"><?php echo $common->get_time_stamp($list[$i]['create_time']); ?></td>
                              <td width="25%"><?php echo $knowledge_base_category->catToTex($list[$i]['category']); ?></td>
                              <td width="25%"><?php if ($list[$i]['file'] != "") { ?>
                                <a href="<?php echo URL; ?>library/helpfiles/<?php echo $list[$i]['file']; ?>" target="_blank">View File</a>
                                <?php } ?></td>
                            </tr>
                            <tr>
                              <td colspan="4"><?php echo $list[$i]['content']; ?></td>
                            </tr>
                            <tr>
                              <td colspan="4" align="right"><em>Status: <?php echo $help->status($list[$i]['status']); ?><br>
                                Last modified on <?php echo date('l jS \of F Y h:i:s A', $list[$i]['modify_time']); ?><br>
                                Last Modified By <?php echo $admin->getOneField($list[$i]['admin_id']); ?></em></td>
                            <tr>
                              <td colspan="4"><hr></td>
                            </tr>
                            <?php } ?>
                            <?php if ($data['status'] != 2) { ?>
                            <form method="post" action="" name="form1">
                            <tr>
                              <td colspan="4"><h2>Reply</h2></td>
                            </tr>
                            <tr>
                              <td colspan="4"><span id="sprytextarea1">
                                <textarea name="content" id="content" class="form-control"></textarea>
                                <span class="textareaRequiredMsg">A value is required.</span></span>
                                <input type="hidden" name="category" id="category" value="<?php echo $data['category']; ?>">
                                <input type="hidden" name="parent_id" id="parent_id" value="<?php echo $data['ref']; ?>">
                                <input type="hidden" name="user_id" id="user_id" value="<?php echo $data['user_id']; ?>">
                                <input type="hidden" name="admin_id" id="admin_id" value="<?php echo $data['admin_ids']; ?>">
                                <input type="hidden" name="response_id" id="response_id" value="<?php echo $list[$i]['ref']; ?>">
                                <input type="hidden" name="ref" id="ref" value="<?php echo $id; ?>"></td>
</tr>
                            <tr>
                              <td colspan="4"><button class="btn btn-primary" name="editButton" id="editButton" type="submit"  data-icon-primary="ui-icon-circle-check">Post Reply</button></td>
                            </tr>
                            </form>
                            <?php } ?>
                          </table>
                        </div>
                    </div>
                    <!-- end of page content -->


                    <!-- start of sidebar -->
                    <aside class="span4 page-sidebar">
                        <section class="widget">                            
                                          <a href="<?php echo URL; ?>support">My Ticket</a><br>
                                          <a href="<?php echo URL; ?>support?open">Open Tickets</a><br>
                                          <a href="<?php echo URL; ?>support?closed">closed Tickets</a><br>
                                          <a href="<?php echo URL; ?>support">Help and Support</a><br>
                            
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
        <script type="text/javascript">
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1");
                </script>
        </body>


</html>