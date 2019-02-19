<?php
        include_once("includes/functions.php");
        $redirect = "libraryAndDocuments";
        if ((isset($_GET['page']))) {
                $page_count = $_GET['page'];
        } else {
                $page_count = 0;
        }
        $showDic = false;
        $showCaselaw = false;
        $showReg = false;
        $showDoc = false;
        if (isset($_GET['show']) && ($_GET['show'] == "dic")) {
                $list = $searchHome->listDictionar($page_count);
                $listCount = $searchHome->listDictionarCount();
                $tag = "Dictionary";
                $show = $_GET['show'];
                $showDic = true;
        } else if (isset($_GET['show']) && ($_GET['show'] == "caselaw")) {
                $list = $searchHome->listCase($page_count);
                $listCount = $searchHome->listCaseCount();
                $tag = "Caselaw";
                $show = $_GET['show'];
                $showCaselaw = true;
        } else if (isset($_GET['show']) && ($_GET['show'] == "reg")) {
                $list = $searchHome->listRegulation($page_count);
                $listCount = $searchHome->listRegulationCount();
                $tag = "Regulations";
                $show = $_GET['show'];
                $showReg = true;
        } else if (isset($_GET['show']) && ($_GET['show'] == "clause")) {
                $list = $searchHome->listClause($page_count);
                $listCount = $searchHome->listClauseCount();
                $tag = "Draft Clauses";
                $show = $_GET['show'];
                $showClause = true;
        } else if (isset($_GET['show']) && ($_GET['show'] == "agreement")) {
                $list = $searchHome->listAgreement($page_count);
                $listCount = $searchHome->listAgreementCount();
                $tag = "Draft Agreement";
                $show = $_GET['show'];
                $showAgreement = true;
        } else if (isset($_GET['show']) && ($_GET['show'] == "form")) {
                $list = $searchHome->listForm($page_count);
                $listCount = $searchHome->listFormCount();
                $tag = "Forms";
                $show = $_GET['show'];
                $showForm = true;
        } else {
                $list = $searchHome->listCategory($page_count);
                $listCount = $searchHome->listCategoryCount();
                $tag = "Laws";
                $show = false;
                $showDoc = true;
        }
        $jsLisst = "";
        $listCat = $categories->sortAll("0", "parent_id", "status", "active");
?>
<!DOCTYPE html>
        <!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en-US"> <![endif]-->
        <!--[if IE 7]>    <html class="lt-ie9 lt-ie8" lang="en-US"> <![endif]-->
        <!--[if IE 8]>    <html class="lt-ie9" lang="en-US"> <![endif]-->
        <!--[if gt IE 8]><!--> <html lang="en-US"> <!--<![endif]-->
        

<head>
    <meta charset="utf-8">
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script><script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-4142286148495329",
    enable_page_level_ads: true
  });
</script>
                <!-- META TAGS -->
                <meta charset="UTF-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0">

                <title>Search the LegalLens Library</title>
                <meta name="description" content="For lawyers, compliance officers, business professionals and students who want immediate access to provisions of laws and regulations, Legal Lens is a digital tool that enables quick, accurate and comprehensive legal research. Unlike other libraries Legal Lens allows you conduct legal research anytime, anywhere and from any device at the click of a search button">

        <?php $pages->head(); ?>
        </head>

        <body>

                <!-- Start of Header -->
                <div class="header-wrapper">
                        <?php $pages->headerFiles("libraryAndDocuments"); ?>
                </div>
                <!-- End of Header -->

                <!-- Start of Search Wrapper -->
                <div class="search-area-wrapper">
                        <div class="search-area container">
                        <form id="search-form" class="search-form clearfix" method="get" action="<?php echo URL.$redirect; ?>" autocomplete="off">
                                <input class="search-term required" type="text" id="s" name="s" placeholder="earch LegalLens Library" title="* search LegalLens Library!" />
                                <input class="search-btn" type="submit" value="Search" />
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

                                          <a href="?show=doc">Laws</a> | <a href="?show=caselaw">Case Law</a> | <a href="?show=reg">Regulation</a> | <a href="?show=dic">Dictionary</a> | <a href="?show=clause">Draft Clause</a> | <a href="?show=agreement">Draft Agreements</a> | <a href="?show=form">Forms</a>
                                                        <h1 class="post-title">
                                                                <a href="#"><?php echo $tag; ?></a></h1>
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
                                                        <?php if ($showCaselaw == true) {
                                                                if (count($list) > 0) { ?>
                                                                        <table width="100%" border="0" cellspacing="0" id="example4">
                                                                                              <?php 
                                                                                              $count = $page_count*page_list;
                                                                                              for ($i = 0; $i < count($list); $i++) {
                                                                                                      $count++ ?>
                                                                           <tr>
                                                                           <td valign="top"><strong><?php echo $count; ?></strong></td>
                                                                           <td>
                                                                      <a href="libraryDocumentPreviewCase?id=<?php echo $list[$i]['ref']; ?>&read=<?php echo $list[$i]['section_ID']; ?>&data=<?php echo urlencode(URL."caselaw.read?id=".$list[$i]['ref']."&read=".$list[$i]['section_ID']."&return=". $search_data); ?>">
                                                                       <cite><strong><?php echo $list[$i]['citation']; ?></strong></cite><br>
                                                                       <strong style="color:#00F"><?php echo $common->getLine($list[$i]['section_content']); ?></strong><br></a>
                                                                       <?php echo nl2br($common->truncate($list[$i]['section_content'], 250)); ?><br>
                                                                       <a href="libraryDocumentPreviewCase?id=<?php echo $list[$i]['ref']; ?>&read=<?php echo $list[$i]['section_ID']; ?>&data=<?php echo urlencode(URL."caselaw.read?id=".$list[$i]['ref']."&read=".$list[$i]['section_ID']."&return=". $search_data); ?>">read more</a>
                                                                           </td>
                                                                              </tr>
                                                                          <?php } ?>
                                                                        </table>
                                                                        <?php $pagination->draw($page_count, $postData, $listCount, $redirect, false, $show); ?>
                                                                    <?php } else { ?>
                                                                        <p>No result for this item now</p>
                                                                    <?php } ?>

                                                        <?php } elseif ($showDoc == true) {
                                                                if (count($list) > 0) { ?>
                                                                <table width="100%" border="0" cellspacing="0" id="table_<?php echo $key; ?>">
                                                                        <?php 
                                                                        $count = $page_count*page_list;
                                                                        for ($i = 0; $i < count($list); $i++) {
                                                                                $count++ ?>
                                                                <tr>
                                                                <td valign="top"><strong><?php echo $count; ?></strong></td>
                                                                <td><strong style="color:#006"><a href="libraryDocumentPreviewDoc?id=<?php echo $list[$i]['ref']; ?>&read=<?php echo $list[$i]['section_ref']; ?>&id=<?php echo $list[$i]['ref']; ?>&data=<?php echo urlencode(URL."document.read?id=".$list[$i]['ref']."&read=".$list[$i]['section_ref']."&return=".$search_data); ?>"><?php echo $list[$i]['title']; ?></a></strong><br>
                                                                <strong style="color:#00F"><?php echo nl2br($common->getLine($list[$i]['section_no'])); ?></strong><br>
                                                                <?php 
                                                                echo nl2br($common->truncate($list[$i]['section_content'], 150));
                                                                ?></td>
                                                                        </tr>
                                                                <?php } ?>
                                                                </table>
                                                                <?php $pagination->draw($page_count, $postData, $listCount, $redirect, false, $show); ?>
                                                                <?php } else { ?>
                                                                <p>No result for this item now</p>
                                                                <?php } ?>
                                                        <?php } elseif ($showReg == true) {
                                                                if (count($list) > 0) { ?>
                                                                        <table width="100%" border="0" cellspacing="0" id="example4">
                                                                                        <?php 
                                                                                        $count = $page_count*page_list;
                                                                                        for ($i = 0; $i < count($list); $i++) {
                                                                                                $count++ ?>
                                                                        <tr>
                                                                        <td valign="top"><strong><?php echo $count; ?></strong></td>
                                                                        <td><strong style="color:#006"><a href="libraryDocumentPreviewReg?id=<?php echo $list[$i]['ref']; ?>&read=<?php echo $list[$i]['section_ref']; ?>&data=<?php echo urlencode(URL."regulations.view?id=".$list[$i]['ref']."&read=".$list[$i]['section_ref']."&return=". $search_data); ?>"><?php echo $list[$i]['title']; ?></a></strong><br>
                                                                        
                                                                <?php 
                                                                if ($list[$i]['section_no'] == "") {
                                                                        echo nl2br($common->truncate($list[$i]['section_content'], 200));
                                                                } else {
                                                                        echo nl2br($common->getLine($list[$i]['section_no']));
                                                                                        echo "<br>";
                                                                        echo nl2br($common->truncate($list[$i]['section_content'], 200));
                                                                } ?>
                                                                        </td>
                                                                        </tr>
                                                                        <?php } ?>
                                                                        </table>
                                                                        <?php $pagination->draw($page_count, $postData, $listCount, $redirect, false, $show); ?>
                                                                <?php } else { ?>
                                                                        <p>No result for this item now</p>
                                                                <?php } ?>
                                                       <?php } elseif ($showDic == true) {
                                                               if (count($list) > 0) { ?>
                                                                <table width="100%" border="0" id="example5">
                                                                                      <?php 
                                                                                      $count = $page_count*page_list;
                                                                                      for ($i = 0; $i < count($list); $i++) {
                                                                                              $count++ ?>
                                                                      <tr>
                                                                   <td valign="top"><strong><?php echo $count; ?></strong></td>
                                                                   <td><strong><?php echo $list[$i]['title']; ?></strong><br> <?php echo $list[$i]['details']; ?>
                                                                   </td>
                                                                      </tr>
                                                                  <?php } ?>
                                                                </table>
                                                                <?php $pagination->draw($page_count, $postData, $listCount, $redirect, false, $show); ?>
                                                            <?php } else { ?>
                                                                <p>No result for this item now</p>
                                                            <?php } ?>
                                                       <?php } elseif ($showClause == true) {
                                                               if (count($list) > 0) { ?>
                                                                <table width="100%" border="0" id="example5">
                                                                                      <?php 
                                                                                      $count = $page_count*page_list;
                                                                                      for ($i = 0; $i < count($list); $i++) {
                                                                                              $count++ ?>
                                                                      <tr>
                                                                   <td valign="top"><strong><?php echo $count; ?></strong></td>
                                                                   <td><strong><a href="libraryDocumentPreviewDrafting?id=<?php echo $list[$i]['ref']; ?>&view=Clauses&data=<?php echo urlencode(URL."clause.data?view=Clauses&id=".$list[$i]['ref']); ?>"><?php echo $list[$i]['title']; ?></a></strong></td>
                                                                      </tr>
                                                                  <?php } ?>
                                                                </table>
                                                                <?php $pagination->draw($page_count, $postData, $listCount, $redirect, false, $show); ?>
                                                            <?php } else { ?>
                                                                <p>No result for this item now</p>
                                                            <?php } ?>
                                                       <?php } elseif ($showAgreement == true) {
                                                               if (count($list) > 0) { ?>
                                                                <table width="100%" border="0" id="example5">
                                                                                      <?php 
                                                                                      $count = $page_count*page_list;
                                                                                      for ($i = 0; $i < count($list); $i++) {
                                                                                              $count++ ?>
                                                                      <tr>
                                                                   <td valign="top"><strong><?php echo $count; ?></strong></td>
                                                                   <td><strong><a href="libraryDocumentPreviewDrafting?id=<?php echo $list[$i]['ref']; ?>&view=Agreement&data=<?php echo urlencode(URL."clause.data?view=Agreement&id=".$list[$i]['ref']); ?>"><?php echo $list[$i]['title']; ?></a></strong></td>
                                                                      </tr>
                                                                  <?php } ?>
                                                                </table>
                                                                <?php $pagination->draw($page_count, $postData, $listCount, $redirect, false, $show); ?>
                                                            <?php } else { ?>
                                                                <p>No result for this item now</p>
                                                            <?php } ?>
                                                       <?php } elseif ($showForm == true) {
                                                               if (count($list) > 0) { ?>
                                                                <table width="100%" border="0" id="example5">
                                                                                      <?php 
                                                                                      $count = $page_count*page_list;
                                                                                      for ($i = 0; $i < count($list); $i++) {
                                                                                              $count++ ?>
                                                                      <tr>
                                                                   <td valign="top"><strong><?php echo $count; ?></strong></td>
                                                                   <td><strong><a href="libraryDocumentPreviewDrafting?id=<?php echo $list[$i]['ref']; ?>&view=Forms&data=<?php echo urlencode(URL."clause.data?view=Forms&id=".$list[$i]['ref']); ?>"><?php echo $list[$i]['title']; ?></a></strong></td>
                                                                      </tr>
                                                                  <?php } ?>
                                                                </table>
                                                                <?php $pagination->draw($page_count, $postData, $listCount, $redirect, false, $show); ?>
                                                            <?php } else { ?>
                                                                <p>No result for this item now</p>
                                                            <?php } ?>

                                                        <?php } ?>
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

