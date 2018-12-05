<?php
	$redirect = "Forum.topics";
	include_once("includes/functions.php");
	
	if (isset($_REQUEST['s'])) {
		$s = $common->get_prep($_REQUEST['s']);
		$list = $forum_topics->search($s);
		
		$tag = "Search results for '".$s."'";
	} else if ((isset($_REQUEST['id'])) && ($_REQUEST['id'] != "")) {		
		$id = $common->get_prep($_GET['id']);
		$tag = "All Topics in ".$forum_categories->getOneField($id);
		$desc = $forum_categories->getOneField($id, "cat_id", "cat_description");
		$list = $forum_topics->sortAll("active", "status", "topic_cat", $id);
		$link = "&id=".$id;
	} else {
		$tag = "All Topics";
		$desc = "";
		$list = $forum_topics->sortAll("active", "status");
		$link = "";
	}
	
	
	if ((isset($_POST['editButton'])) || (isset($_POST['addAdmin']))) {
		$add = $forum_topics->add($_POST);
		if ($add) {
			header("location: ?done".$link);
		} else {
			header("location: ?error".$link);
		}
	}
	
	$listReg = $forum_categories->sortAll("active", "status");
?>
<!doctype html>
<base href="<?php echo URL; ?>" />
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

                <title>Share thoughts, questions and comments on legal issues in Nigeria</title>
                <meta name="description" content="Share views and comments on legal issues with other professionals on the LegalLens Forum">

        <?php $pages->head(); ?>
        </head>

        <body>

                <!-- Start of Header -->
                <div class="header-wrapper">
                        <?php $pages->headerFiles("Forum"); ?>
                </div>
                <!-- End of Header -->

                <!-- Start of Search Wrapper -->
                <div class="search-area-wrapper">
                        <div class="search-area container">
                                <form id="search-form" class="search-form clearfix" method="get" action="<?php echo URL.$redirect; ?>" autocomplete="off">
                                        <input class="search-term required" type="text" id="s" name="s" placeholder="search forum topics" title="* search forum topics!" />
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
                                  <div class="span8 page-content">
                                          <article class="type-page hentry clearfix">
                                            <h1 class="post-title"> <a href="#"><?php echo $tag; ?></a></h1>
                                            
                            <?php if (isset($_GET['done'])) { ?>
                            <p class="success">Your topic has been created. Please find the topic in list below to post your comments</p>
                            <?php } else if (isset($_GET['error'])) { ?>
                            <p class="error">An error occured</p>
                            <?php } ?>
                            <p>
                                                        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                                                        <!-- forum -->
                                                        <ins class="adsbygoogle"
                                                             style="display:inline-block;width:728px;height:90px"
                                                             data-ad-client="ca-pub-4142286148495329"
                                                             data-ad-slot="9218590698"></ins>
                                                        <script>
                                                        (adsbygoogle = window.adsbygoogle || []).push({});
                                                        </script>
                                                        </p>
                            <p><?php echo $desc; ?></p>
                                            <table class="table table-hover" id="example1">
                                              <thead>
                                                <tr>
                                                  <th>Topic</th>
                                                  <th>Category</th>
                                                  <th>Owner</th>
                                                  <th>Created</th>
                                                  <th>Post</th>
                                                </tr>
                                              </thead>
                                              <tbody>
                                                <?php for ($i = 0; $i < count($list); $i++) { ?>
                                                <tr>
                                                  <td><a href="<?php echo $common->seo($list[$i]['topic_id'], "topic"); ?>"><?php echo $list[$i]['topic_subject']; ?></a></td>
                                                  <td><?php echo $forum_categories->getOneField($list[$i]['topic_cat']); ?></td>
                                                  <td><?php echo $list[$i]['topic_by']; ?></td>
                                                  <td><?php echo $common->get_time_stamp($list[$i]['create_time']); ?></td>
                                                  <td><?php echo $list[$i]['title']; ?></td>
                                                </tr>
                                                <?php } ?>
                                              </tbody>
                                            </table>
                                          </article>
                                          <article class="type-page hentry clearfix">
                                            <form id="subscribe-form" class="row" action="<?php echo $redirect; ?>" method="post">
                                              <table width=300 class="table table-hover" align=center>
                                                <thead>
                                                  <tr>
                                                    <th colspan=2>Create New Topic</th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                <?php if ($forum_login->checkLogin() == true) { ?>
                                                  <tr>
                                                    <td width="25%">Post As</td>
                                                    <td><?php echo $_SESSION['forum']['username']; ?>
                                                    <input type="hidden" name="topic_by" id="topic_by" value="<?php echo $_SESSION['forum']['ref']; ?>">
                                                    <input type="hidden" name="status" id="status" value="active">
                                                    <input type="hidden" name="is_user" id="is_user" value="yes"><input type="hidden" name="id" id="id" value="<?php echo $id; ?>"></td>
                                                  </tr>
                                                  <tr>
                                                    <td width="25%">Title</td>
                                                    <td><input type="text" name="topic_subject" class="form-control" id="topic_subject" placeholder="Title" value="<?php echo $getData['topic_subject']; ?>"></td>
                                                  </tr>
                                                  <tr>
                                                    <td width="25%">Category</td>
                                                    <td><select name="topic_cat" id="topic_cat" class="form-control select2" style="width: 100%;" required>
                                                      <option value="">Select One</option>
                                                      <?php for ($i = 0; $i < count($listReg); $i++) { ?>
                                                      <option value="<?php echo $listReg[$i]['cat_id']; ?>" selected="selected"<?php if ($getData['topic_cat'] == $listReg[$i]['cat_id']) { ?> selected<?php } ?>><?php echo $listReg[$i]['cat_name']; ?></option>
                                                      <?php } ?>
                                                    </select></td>
                                                  </tr>
                                                  <tr>
                                                    <td width="25%">&nbsp;</td>
                                                    <td><button class="btn btn-primary" name="addAdmin" id="addAdmin" type="submit" data-icon-primary="ui-icon-circle-check">Create New Topic</button></td>
                                                  </tr>
                                                  <?php } else { ?>
                                                  <tr>
                                                    <td colspan="2" align="center"><a href="Javascript:void(0)" onClick="window.open('loginHelper?redirect=<?php echo $redirect; ?><?php echo $link; ?>','Select Properties','width=500,height=500,left=0,top=0,toolbar=0,location=0,statusbar=0,menubar=0,');" id="pic2">You must be logged in as a valid forum user. Click here to login</a></td>
                                                  </tr>
                                                  <?php } ?>
                                                </tbody>
                                              </table>
                                            </form>
                                            <br>
                                                        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                                                        <!-- forum -->
                                                        <ins class="adsbygoogle"
                                                             style="display:inline-block;width:728px;height:90px"
                                                             data-ad-client="ca-pub-4142286148495329"
                                                             data-ad-slot="9218590698"></ins>
                                                        <script>
                                                        (adsbygoogle = window.adsbygoogle || []).push({});
                                                        </script>
                                          </article>
                                        </div>
                                        <!-- end of page content -->


                                        <!-- start of sidebar -->
                                        <aside class="span4 page-sidebar">

                                                <section class="widget">
                                                    <div align="center">
                                                        <a href="<?php echo URL; ?>helpAndSupport" ><img src="<?php echo URL; ?>/images/help.png" width="100" /></a><br />
                                                        <h3 class="title">Need Help?</h3>
                                                        <p class="intro">Click here to<br /><a id="mibew-agent-button" href="<?php echo URL; ?>helpAndSupport" >Contact Support</a></p>
                                                    </div>
													<?php $pages->advert(); ?>
                                                </section>
                                                <section class="widget">
                                                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                                                <!-- Ad_Campaign -->
                                                <ins class="adsbygoogle"
                                                     style="display:block"
                                                     data-ad-client="ca-pub-4142286148495329"
                                                     data-ad-slot="7741857492"
                                                     data-ad-format="auto"></ins>
                                                <script>
                                                (adsbygoogle = window.adsbygoogle || []).push({});
                                                </script>
                                                </section>

                                                <section class="widget">
                                                        <h3 class="title" align="center">Latest Posts</h3>
                                                        <ul class="articles">
														<?php $forum_posts->recent(); ?>
                                                        </ul>
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

        </body>


</html>

