<?php
	$redirect = "caseLaw";
	include_once("includes/functions.php");
	include_once("includes/session.php");
	
	if (isset($_REQUEST['sort'])) {
		$id = $common->get_prep($_REQUEST['sort']);
		$tag = "Case Law in ".$id;
		$tag2 = " in ".$id;
	} else {
		$id = false;
		$tag = "All Case Law";
		$tag2 = " in ".$tag;
	}
	
	if (isset($_REQUEST['filter'])) {
		$filter = $common->get_prep($_REQUEST['filter']);
	} else {
		$filter = false;
	}
	
	$filter = "title";
	if (isset($_REQUEST['s'])) {
		$s = $common->get_prep($_REQUEST['s']);
		$list = $caselaw->fullSearch($s, $id, $filter);
		$tag = "Search Result for <strong>'".$s."'</strong>".$tag2."";
	} else if (isset($_REQUEST['q'])) {
		$q = strtoupper($common->get_prep($_REQUEST['q']));
		$list = $caselaw->indexSearch($q, $id, $filter);
	} else {
		$list = $caselaw->listAllHome($id, $filter);
	}
?>
<!doctype html>
        <!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en-US"> <![endif]-->
        <!--[if IE 7]>    <html class="lt-ie9 lt-ie8" lang="en-US"> <![endif]-->
        <!--[if IE 8]>    <html class="lt-ie9" lang="en-US"> <![endif]-->
        <!--[if gt IE 8]><!--> <html lang="en-US"> <!--<![endif]-->
<base href="<?php echo URL; ?>" />

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

                <title>Case Law</title>
    <?php $pages->head(); ?>
                <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
                <!--[if lt IE 9]>
                <script src="js/html5.js"></script>
                <![endif]-->
				<style>
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
				   <section class="widget">
                        <div class="login-widget">Welcome, <?php echo $last_name." ".$other_names; ?><br>
                       Current session started: <?php echo date('l jS \of F Y h:i:s A', $loginTime); ?><br>
                        Last logged in: <?php echo @date('l jS \of F Y h:i:s A', $last_login); ?><br>
                        <?php $pages->sideMenu(); ?></div>
                   	  </section>
<section>
                    	<?php $pages->sidelinks(); ?>
            </section>
                	</div>

<div class="span7">
   <div style="border:1px solid #ccc; padding:10px">
     <div style="margin-top:30px">
       <h4 style="" align="center">Case Law </h4>
		<form id="search-form2" class="search-form2 clearfix" method="post" action="" autocomplete="off">
		        <input class="search-term2 required" type="text" id="s" name="s" placeholder="Type your search terms here" title="* Please enter a search term!" />
		        <input class="search-btn" type="submit" value="Search" />
		        <span style="margin-left:-30px;margin-top:10px;">
		        </span>
		        <div id="search-error-container2"></div>
		</form>
        <hr>
        <h4><?php echo $tag; ?></h4>
		<?php if (isset($_REQUEST['s'])) { ?>
        <p><?php echo count($list); ?> record(s) found [<a href="<?php echo URL."/".$redirect."?sort=".$id; ?>">show all</a> 	]</p>
        <?php } ?>
        <p><a href="<?php echo URL.$redirect."?sort=".urlencode($id); ?>&q=a">A</a> | <a href="<?php echo URL.$redirect."?sort=".urlencode($id); ?>&q=b">B</a> | <a href="<?php echo URL.$redirect."?sort=".urlencode($id); ?>&q=c">C</a> | <a href="<?php echo URL.$redirect."?sort=".urlencode($id); ?>&q=d">D</a> | <a href="<?php echo URL.$redirect."?sort=".urlencode($id); ?>&q=e">E</a> | <a href="<?php echo URL.$redirect."?sort=".urlencode($id); ?>&q=f">F</a> | <a href="<?php echo URL.$redirect."?sort=".urlencode($id); ?>&q=g">G</a> | <a href="<?php echo URL.$redirect."?sort=".urlencode($id); ?>&q=h">H</a> | <a href="<?php echo URL.$redirect."?sort=".urlencode($id); ?>&q=i">I</a> | <a href="<?php echo URL.$redirect."?sort=".urlencode($id); ?>&q=j">J</a> | <a href="<?php echo URL.$redirect."?sort=".urlencode($id); ?>&q=k">K</a> | <a href="<?php echo URL.$redirect."?sort=".urlencode($id); ?>&q=l">L</a> | <a href="<?php echo URL.$redirect."?sort=".urlencode($id); ?>&q=m">M</a> | <a href="<?php echo URL.$redirect."?sort=".urlencode($id); ?>&q=n">N</a> | <a href="<?php echo URL.$redirect."?sort=".urlencode($id); ?>&q=o">O</a> | <a href="<?php echo URL.$redirect."?sort=".urlencode($id); ?>&q=p">P</a> | <a href="<?php echo URL.$redirect."?sort=".urlencode($id); ?>&q=q">Q</a> | <a href="<?php echo URL.$redirect."?sort=".urlencode($id); ?>&q=r">R</a> | <a href="<?php echo URL.$redirect."?sort=".urlencode($id); ?>&q=s">S</a> | <a href="<?php echo URL.$redirect."?sort=".urlencode($id); ?>&q=t">T</a> | <a href="<?php echo URL.$redirect."?sort=".urlencode($id); ?>&q=u">U</a> | <a href="<?php echo URL.$redirect."?sort=".urlencode($id); ?>&q=v">V</a> | <a href="<?php echo URL.$redirect."?sort=".urlencode($id); ?>&q=w">W</a> | <a href="<?php echo URL.$redirect."?sort=".urlencode($id); ?>&q=x">X</a> | <a href="<?php echo URL.$redirect."?sort=".urlencode($id); ?>&q=y">Y</a> | <a href="<?php echo URL.$redirect."?sort=".urlencode($id); ?>&q=z">Z</a></p>
        <div id="easyPaginate">
			<?php foreach ($list as $key => $value) { ?>
                    
                    <?php for ($i = 0; $i < count($list[$key]); $i++) {
						$p = $i - 1;
                        $section_list = $caselaw_sections->sortAll($list[$key][$i]['ref'], "caselaw", "status", "active");
                        $count = count($section_list); ?>
                <dataGrid>
                <!--<h4><?php echo $key; ?></h4>-->
                    <p>
                    <?php if ($list[$key][$i]['areas'] != $list[$key][$p]['areas']) { ?>
                    <strong><a href="<?php echo URL; ?>caselaw.view?id=<?php echo $list[$key][$i]['ref']; ?>&jump=<?php echo $list[$key][$i]['section_ref']; ?>"><?php echo $list[$key][$i]['areas']; ?></a></strong><br>
                    <?php } ?>
                    <?php for ($j= 0; $j < count($section_list); $j++) { ?>
                    <a href="<?php echo URL; ?>caselaw.read?id=<?php echo $list[$key][$i]['ref']; ?>&read=<?php echo $section_list[$j]['ref']; ?>"><?php echo nl2br($common->getLine($section_list[$j]['section_content'])); ?>
                    <br>
                     <span style="color:#00F"><?php echo $section_list[$j]['citation']; ?></span></a><br>
                    <?php } ?>
                    <!--<br>
                    <a href="<?php echo URL; ?>caselaw.view?id=<?php echo $list[$key][$i]['ref']; ?>&jump=<?php echo $list[$key][$i]['section_ref']; ?>">view all <?php echo $count; ?> section(s)</a>--></p>
                </dataGrid>
                    <?php } ?>
			<?php } ?>
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
				paginateElement: 'dataGrid2',
				elementsPerPage: 3,
				effect: 'climb'
			});
            $( "#s" ).catcomplete({
      		  delay: 0,
              source: "includes/scripts/auto_complete_case.php?type=<?php echo $id; ?>",
				select: function( event, ui ) {
					window.location='caselaw.view?id='+ui.item.code+"&jump="+ui.item.section;
				}
            });
		});
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

