<?php
	include_once("includes/functions.php");
	$redirect = "Forum";
	if (isset($_REQUEST['s'])) {
		$s = $common->get_prep($_REQUEST['s']);
		$list = $forum_categories->search($s);
	} else {
		$list = $forum_categories->sortAll("active", "status");
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
                                <input class="search-term required" type="text" id="s" name="s" placeholder="earch  forum categories" title="* search forum categories!" />
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
                                                        <h1 class="post-title">
                                                                <a href="#">Forum Categories</a></h1>
                                                        <hr>
                                                        
                                                        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                                                        <!-- forum -->
                                                        <ins class="adsbygoogle"
                                                             style="display:inline-block;width:728px;height:90px"
                                                             data-ad-client="ca-pub-4142286148495329"
                                                             data-ad-slot="9218590698"></ins>
                                                        <script>
                                                        (adsbygoogle = window.adsbygoogle || []).push({});
                                                        </script>
                                                        <br>
                                                        <table class="table table-hover">
                                                          <thead>
                                                            <tr>
                                                              <th>Title</th>
                                                              <th>Topics</th>
                                                            </tr>
                                                          </thead>
                                                          <tbody>
                                                          <?php for ($i = 0; $i < count($list); $i++) { ?>
                                                            <tr>
                                                              <td><a href="<?php echo $common->seo($list[$i]['cat_id'], "category"); ?>"><?php echo $list[$i]['cat_name']; ?></a></td>
                                                              <td><?php echo count($forum_topics->sortAll($list[$i]['cat_id'], "topic_cat", "status", "active")); ?></td>
                                                            </tr>
                                                          <?php } ?>
                                                          </tbody>
                                                        </table>
                                                        
                                                        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                                                        <!-- forum -->
                                                        <ins class="adsbygoogle"
                                                             style="display:inline-block;width:728px;height:90px"
                                                             data-ad-client="ca-pub-4142286148495329"
                                                             data-ad-slot="9218590698"></ins>
                                                        <script>
                                                        (adsbygoogle = window.adsbygoogle || []).push({});
                                                        </script>
                                                </article></div>
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

