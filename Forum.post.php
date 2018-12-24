<?php
	$redirect = "Forum.post";
	include_once("includes/functions.php");
	if (isset($_REQUEST['id'])) {	
		$id = $common->get_prep($_REQUEST['id']);
		$tag = "All Posts in ".$forum_topics->getOneField($id);
		$list = $forum_posts->sortAll("active", "status", "post_topic", $id);
	} else {
		header("location: Forum");
	}
	
	if ((isset($_POST['editButton'])) || (isset($_POST['addAdmin']))) {
		$add = $forum_posts->add($_POST);
		if ($add) {
			header("location: ?done&id=".$id);
		} else {
			header("location: ?error&id=".$id);
		}
	}
?>
<!DOCTYPE html>
<base href="<?php echo URL; ?>" />
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

<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
<script src="js/html5.js"></script>
<![endif]-->
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>tinymce.init({ selector:'textarea',
height: 200,
menubar: false,
plugins: [
    'advlist autolink lists link image charmap print preview anchor'
  ],
toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image', });</script>
</head>

        <body>

                <!-- Start of Header -->
                <div class="header-wrapper">
                        <?php $pages->headerFiles("Forum"); ?>
                </div>
                <!-- End of Header -->

                <!-- Start of Search Wrapper -->
                <div class="search-area-wrapper">
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
                                            <a href="#"><?php echo $tag; ?></a></h1>
                                                        
                                            <?php if (isset($_GET['done'])) { ?>
                                            <p class="success">Posted; Awaiting approval</p>
                                            <?php } else if (isset($_GET['error'])) { ?>
                                            <p class="error">An error occured</p>
                                            <?php } ?>
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
											<ul class="articles">
                                            <?php for ($i = 0; $i < count($list); $i++) { ?>
                                                <li class="article-entry standard">
                                                	<a name="<?php echo $list[$i]['ref']; ?>"></a>
                                                    <?php echo html_entity_decode($list[$i]['post_content']); ?>
                                                    <br>
                                                    <br>
                                                    <br>
                                                    <span class="like-count"><?php echo date("j M, Y", $list[$i]['post_date']); ?> by <?php echo $forum_users->getOneField($list[$i]['post_by']); ?></span>
                                                </li> 
                                            <?php } ?>
                                            </ul>
                                            </article>
                                          <article class="type-page hentry clearfix">
                                            <form id="subscribe-form" class="row" action="<?php echo $redirect; ?>" method="post">
                                              <table width=300 class="table table-hover" align=center>
                                                <thead>
                                                  <tr>
                                                    <th colspan=2>Create new Post as <?php echo $_SESSION['forum']['username']; ?></th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                <?php if ($forum_login->checkLogin() == true) { ?>
                                                  <tr colspan="2">
                                                    <td ><textarea name="post_content" cols="50" rows=" " id="post_content" style="width:100%"></textarea>
                                                    <br>
                                                    <button class="btn btn-primary" name="addAdmin" id="addAdmin" type="submit" data-icon-primary="ui-icon-circle-check">Submit Post</button>
                                                    <input type="hidden" name="post_topic" id="post_topic" value="<?php echo $id; ?>">
                                                    <input type="hidden" name="post_by" id="post_by" value="<?php echo $_SESSION['forum']['ref']; ?>">
                                                    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>"></td>
                                                  </tr>
                                                  <?php } else { ?>
                                                  <tr>
                                                    <td colspan="2" align="center"><a href="Javascript:void(0)" onClick="window.open('loginHelper?redirect=<?php echo $redirect; ?>&id=<?php echo $id; ?>','Select Properties','width=500,height=500,left=0,top=0,toolbar=0,location=0,statusbar=0,menubar=0,');" id="pic2">You must be logged in as a valid forum user. Click here to login</a></td>
                                                  </tr>
                                                  <?php } ?>
                                                </tbody>
                                              </table>
                                            </form><br>
                                            
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

