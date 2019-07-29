<?php
	$redirect = "mobile_document_home";
	include_once("includes/functions.php");
  include_once("includes/mobile_session.php");
  //include_once("includes/session.php");
	
	if (isset($_REQUEST['sort'])) {
		$id = $common->get_prep($_REQUEST['sort']);
		$tag = "Document Categories in ".$categories->getOneField($id);
		$tag2 = " in ".$id;
	} else {
		$id = false;
		$tag = "All Documents Categories";
		$tag2 = " in ".$tag;
	}
	
	if (isset($_REQUEST['filter'])) {
		$filter = $common->get_prep($_REQUEST['filter']);
	} else {
		$filter = "title";
    }
    
	$listHidden = $categories->sortAll(0, "parent_id", "status", "active");
    $list = $categories->sortAll($id, "parent_id");
?>
<!doctype html>
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

                <title>Quick Find </title>
    <?php $pages->head(); ?>

                <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
                <!--[if lt IE 9]>
                <script src="<?php echo URL; ?>js/html5.js"></script>
                <![endif]-->

        <script src="<?php echo URL; ?>SpryAssets/SpryValidationCheckbox.js" type="text/javascript"></script>
        <script src="<?php echo URL; ?>SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="<?php echo URL; ?>SpryAssets/SpryValidationCheckbox.css" rel="stylesheet" type="text/css">
<link href="<?php echo URL; ?>SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css">
<script>
window.onload = function() {
  var input = document.getElementById("s").focus();
}
</script>
<style>
	/* Cosmetic only */
	.easyPaginateNav a {padding:5px;}
	.easyPaginateNav a.current {font-weight:bold;text-decoration:underline;}
</style>
        <?php $pages->chatHeader(); ?>
</head>

        <body>
        
              
                <div >
                <div >
                <div >
                   

<div >
<div style="border:1px solid #ccc; padding:10px">
<div style="margin-top:30px">
       <h4 style="" align="center">Quick Find </h4>
		<form id="search-form2" class="search-form2 clearfix" method="post" action="mobilehome" autocomplete="off">
		        <input class="search-term2 required" type="text" id="s" name="s" placeholder="Enter matter of interest" title="* Enter matter of interest" onBlur="saveSearch(this.value)" value="<?php echo $search_data; ?>" required autofocus />
		        <input class="search-btn" type="submit" value="Go" /><br>
		        <span style="margin-left:-30px;margin-top:10px;">
            </span>
            

<ins class="adsbygoogle"
          style="display:inline-block;width:728px;height:90px"
          data-ad-client="ca-pub-4142286148495329"
          data-ad-slot="9218590698"></ins>
    <script>
    (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
                
            <?php for ($i = 0; $i < count($listHidden); $i++) { ?>
            <input type="hidden" name="parameter[]" value="<?php echo $listHidden[$i]['ref']; ?>" />
            <?php } ?><br>
            <input type="hidden" name="case_law" id="case_law" value="1" />
            <input type="hidden" name="reg_circular" id="reg_circular" value="1" />
            <input type="hidden" name="dic" id="dic" value="1" />
		</form>
        <hr>
        <h4><?php echo $tag; ?></h4>
		<?php if (isset($_REQUEST['s'])) { ?>
        <p><?php echo count($list); ?> record(s) found [<a href="<?php echo URL."/".$redirect."?sort=".$id; ?>">show all</a> 	]</p>
        <?php } ?>
            <?php for ($i = 0; $i < count($list); $i++) { ?>
                <span>
                    <strong>
                    <a href="<?php echo URL; ?>mobile_document?sort=<?php echo $list[$i]['ref']; ?>"><?php echo $list[$i]['title']; ?></a>
                    </strong><br>
                </span>
            <?php } ?>
	 </div>
</div>
</div>
</div>
                
                <a href="#top" id="scroll-top"></a>
               <!-- <a href="#top" id="scroll-top"></a> -->

                <!-- script -->
               <!-- <script type='text/javascript' src='<?php echo URL; ?>js/jquery-1.8.3.min.js'></script> -->
               <script type='text/javascript' src='<?php echo URL; ?>js/jquery.easing.1.34e44.js?ver=1.3'></script>
                <script type='text/javascript' src='<?php echo URL; ?>js/prettyphoto/jquery.prettyPhotoaeb9.js?ver=3.1.4'></script>
                <script type='text/javascript' src='<?php echo URL; ?>js/jquery.liveSearchd5f7.js?ver=2.0'></script>
				<script type='text/javascript' src='<?php echo URL; ?>js/jflickrfeed.js'></script>
                <script type='text/javascript' src='<?php echo URL; ?>js/jquery.formd471.js?ver=3.18'></script>
                <script type='text/javascript' src='<?php echo URL; ?>js/jquery.validate.minfc6b.js?ver=1.10.0'></script>
                <script type='text/javascript' src="<?php echo URL; ?>js/jquery-twitterFetcher.js"></script>
                <script type='text/javascript' src='<?php echo URL; ?>js/custom5152.js?ver=1.0'></script>
                <script type='text/javascript' src='<?php echo URL; ?>js/frontEnd.js'></script>
				<script type='text/javascript' src="<?php echo URL; ?>js/navAccordion.min.js"></script>
                
                <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
                <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script language="javascript" src="<?php echo URL; ?>js/bootstrap.min.js"></script>
                
                
	<script>
        $(function() {
            $( "#s" ).catcomplete({
      		  delay: 0,
              source: "includes/scripts/auto_complete_doc.php?sort=<?php echo $id; ?>&type=Law",
				select: function( event, ui ) {
					window.location='mobiledocument.view?id='+ui.item.code+"&view="+ui.item.type;
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

