<?php
	include_once("includes/functions.php");
	$listCat = $knowledge_base_category->listAll();
	$list = $faq->sortAll("active", "status");
?>
<!DOCTYPE html>
        <!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en-US"> <![endif]-->
        <!--[if IE 7]>    <html class="lt-ie9 lt-ie8" lang="en-US"> <![endif]-->
        <!--[if IE 8]>    <html class="lt-ie9" lang="en-US"> <![endif]-->
        <!--[if gt IE 8]><!--> <html lang="en-US"> <!--<![endif]-->
        
<!-- Mirrored from inspirythemes.biz/html-templates/knowledgebase-html/faq.html by HTTrack Website Copier/3.x [XR&CO'2013], Sun, 27 Mar 2016 11:14:25 GMT -->
<head>
    <meta charset="utf-8">
                <!-- META TAGS -->
                <meta charset="UTF-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0">

                <title>Need help on legal lens services, products and partnerships</title>
                <meta name="description" content="Get answers to all your questions about our services on the Legal Lens FAQ page">

        <?php $pages->head(); ?>
        </head>

        <body>

                <!-- Start of Header -->
                <div class="header-wrapper">
                        
                        <?php $pages->headerFiles("faq"); ?>
              
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

                                                <article class=" page type-page hentry clearfix">
                                                        <h1 class="post-title"><a href="#">FAQs</a></h1>
                                                        <hr>
                                                </article>

                                                <div class="faqs clearfix">
                                                        <?php for ($i = 0; $i < count($list); $i++) { ?>
                                                        <article class="faq-item<?php if ($i == 0) { ?> active<?php } ?>">
                                                                <span class="faq-icon"></span>
                                                                <h3 class="faq-question">
                                                                        <a href="#"><?php echo $list[$i]['title']; ?>?</a>
                                                                </h3>
                                                                <div class="faq-answer">
                                                                        <p><?php echo nl2br($list[$i]['content']); ?></p>
                                                                </div>
                                                        </article>
                                                        <?php } ?>
                                                </div>

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
                                                </section>
                                                <section class="widget"><h3 class="title">Help Categories</h3>
                                                        <ul>
                                                            <li><a href="<?php echo URL."helpAndSupport"; ?>">All</a></li>
                                                            <?php for ($i = 0; $i < count($listCat); $i++) { ?>
                                                            <li><a href="<?php echo URL."helpAndSupport?c=".$listCat[$i]['ref']; ?>"><?php echo $listCat[$i]['title']; ?></a></li>
                                            
                                            				<?php } ?>
                                                        </ul>
                                                </section>
                                                <section class="widget">
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
                <script type='text/javascript' src='js/jquery.easing.1.3.js'></script>
                <script type='text/javascript' src='js/prettyphoto/jquery.prettyPhoto.js'></script>
                <script type='text/javascript' src='js/jflickrfeed.js'></script>
                <script type='text/javascript' src='js/jquery.liveSearch.js'></script>
                <script type='text/javascript' src='js/jquery.form.js'></script>
                <script type='text/javascript' src='js/jquery.validate.min.js'></script>
                <script type='text/javascript' src="js/jquery-twitterFetcher.js"></script>
                <script type='text/javascript' src='js/custom.js'></script>
				<script type='text/javascript' src="js/navAccordion.min.js"></script>

        </body>

<!-- Mirrored from inspirythemes.biz/html-templates/knowledgebase-html/faq.html by HTTrack Website Copier/3.x [XR&CO'2013], Sun, 27 Mar 2016 11:14:25 GMT -->
</html>
