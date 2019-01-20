<?php
	$redirect = "clause";
	include_once("includes/functions.php");
	include_once("includes/session.php");
	
	if (isset($_REQUEST['s'])) {
		$s = $common->get_prep($_REQUEST['s']);
		$list = $library->fullSearch($s);
	} else if (isset($_REQUEST['q'])) {
		$q = $common->get_prep($_REQUEST['q']);
		$list = $drafting->indexSearch($q, 'clause');
	} else {
		$list = $drafting->listAllHome("clause");
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
    
    <title>Legal Drafting >> Draft Clauses </title>
    <?php $pages->head(); ?>
<style>
.ui-autocomplete-category {
	font-weight: bold;
	padding: .2em .4em;
	margin: .8em 0 .2em;
	line-height: 1.5;
}
/* Cosmetic only */
.easyPaginateNav a {padding:5px;}
.easyPaginateNav a.current {font-weight:bold;text-decoration:underline;}
</style>

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
    <section class="widget">
      <div class="login-widget">
        Current session started: <?php echo date('l jS \of F Y h:i:s A', $loginTime); ?><br>
        Last logged in: <?php echo @date('l jS \of F Y h:i:s A', $last_login); ?>
      </div>
    </section>
                	</div>

<div class="span7">
<div style="border:1px solid #ccc; padding:10px">
<div style="margin-top:30px">
<h4 style="" align="center">Draft Clauses </h4>
<form id="search-form2" class="search-form2 clearfix" method="post" action="clause.data" autocomplete="off">
    <input class="search-term2 required" type="text" id="s" name="s" placeholder="Type your search terms here" title="* Please enter a search term!" />
    <input class="search-btn" type="submit" value="Search" />
    <div id="search-error-container2"></div>
</form>
<hr>
<p><a href="?q=a">A</a> | <a href="?q=b">B</a> | <a href="?q=c">C</a> | <a href="?q=d">D</a> | <a href="?q=e">E</a> | <a href="?q=f">F</a> | <a href="?q=g">G</a> | <a href="?q=h">H</a> | <a href="?q=i">I</a> | <a href="?q=j">J</a> | <a href="?q=k">K</a> | <a href="?q=l">L</a> | <a href="?q=m">M</a> | <a href="?q=n">N</a> | <a href="?q=o">O</a> | <a href="?q=p">P</a> | <a href="?q=q">Q</a> | <a href="?q=r">R</a> | <a href="?q=s">S</a> | <a href="?q=t">T</a> | <a href="?q=u">U</a> | <a href="?q=v">V</a> | <a href="?q=w">W</a> | <a href="?q=x">X</a> | <a href="?q=y">Y</a> | <a href="?q=z">Z</a> </p>
<div id="easyPaginate">
<?php foreach ($list as $key => $value) {
	for ($i = 0; $i < count($list[$key]); $i++) { ?>
    <p><strong><a href="<?php echo URL; ?>clause.data?view=Clauses&id=<?php echo $list[$key][$i]['ref']; ?>"><?php echo $list[$key][$i]['title']; ?></a></strong></p>
    <?php }
} ?>
</div>
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
    <script type='text/javascript' src='js/frontEnd.js'></script>
	<script type='text/javascript' src="js/navAccordion.min.js"></script>
    
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<script src="js/pagination.js"></script>
    <script>
        $(function() {
			$('#easyPaginate').easyPaginate({
				paginateElement: 'p',
				elementsPerPage: 20,
				effect: 'climb'
			});
            $( "#s" ).catcomplete({
      		  delay: 0,
              source: "includes/scripts/auto_complete_clause.php?type=Clause",
				select: function( event, ui ) {
					window.location='clause.data?view='+ui.item.type+"&id="+ui.item.code;
				}
            });
        });
    </script>
	<script>
    $.widget( "custom.catcomplete", $.ui.autocomplete, {
    _create: function() {
      this._super();
      this.widget().menu( "option", "items", "> :not(.ui-autocomplete-category)" );
    },
    _renderMenu: function( ul, items ) {
      var that = this,
        currentCategory = "";
      $.each( items, function( index, item ) {
        var li;
        if ( item.category != currentCategory ) {
          ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
          currentCategory = item.category;
        }
        li = that._renderItemData( ul, item );
        if ( item.category ) {
          li.attr( "aria-label", item.category + " : " + item.label );
        }
      });
    }
    });
    </script>

</body>


</html>

