<?php
	$redirect = "mobilehome";
	include_once("includes/functions.php");
  include_once("includes/mobile_session.php");
  //include_once("includes/session.php");
  
	
	$list = $categories->sortAll(0, "parent_id", "status", "active");
  $result = false;
  
	if ((isset($_GET['page'])) && (isset($_GET['tab'])) && ($_GET['tab'] == "case_law")) {
    $page_count = 0;
    $case_law_page_count = $_GET['page'];
    $dic_page_count = 0;
    $reg_page_count = 0;
  } else if ((isset($_GET['page'])) && (isset($_GET['tab'])) && ($_GET['tab'] == "reg")) {
    $page_count = 0;
    $case_law_page_count = 0;
    $dic_page_count = 0;
    $reg_page_count = $_GET['page'];
  } else if ((isset($_GET['page'])) && (isset($_GET['tab'])) && ($_GET['tab'] == "dic")) {
    $page_count = 0;
    $case_law_page_count = 0;
    $dic_page_count = $_GET['page'];
    $reg_page_count = 0;
  } else if ((isset($_GET['page'])) && (isset($_GET['tab'])) && (intval($_GET['tab']) > 0)) {
    $page_count = $_GET['page'];
    $case_law_page_count = 0;
    $dic_page_count = 0;
    $reg_page_count = 0;
  } else {
    $page_count = 0;
    $case_law_page_count = 0;
    $dic_page_count = 0;
    $reg_page_count = 0;
  }

  $page_array['law'] = $page_count;
  $page_array['case_law'] = $case_law_page_count;
  $page_array['dic'] = $dic_page_count;
  $page_array['reg'] = $reg_page_count;

	if (isset($_POST['s'])) {
    $search_data = $_POST['s'];
		$curTime = microtime(true);
		$add = $search->create($_POST, $page_array);
		$doc = $add['doc'];
		$doc_count = $add['doc_count'];
		$reg = $add['reg'];
		$reg_count = $add['reg_count'];
		$case_law = $add['case_law'];
		$case_law_count = $add['case_law_count'];
		$dic = $add['dic'];
    $dic_count = $add['dic_count'];
    $total = $add['count'];
		$timeConsumed = round(microtime(true) - $curTime,3)*10; 
		$postData = base64_encode(json_encode($_POST));
		$result = true;
	} else if (isset($_GET['q'])) {
    $search_data = $_GET['q'];
    $_GET['s'] = $_GET['q'];
		$_GET['case_law'] = 1;
		$_GET['reg_circular'] = 1;
		$_GET['dic'] = 1;
		
		for ($i = 0; $i < count($list); $i++) {
      $_GET['parameter'][] =  $list[$i]['ref']; 
    }
		
		$curTime = microtime(true);
		$add = $search->create($_GET, $page_array);
		$doc = $add['doc'];
		$doc_count = $add['doc_count'];
		$reg = $add['reg'];
		$reg_count = $add['reg_count'];
		$case_law = $add['case_law'];
		$case_law_count = $add['case_law_count'];
		$dic = $add['dic'];
		$dic_count = $add['dic_count'];
    $total = $add['count'];
		$timeConsumed = round(microtime(true) - $curTime,3)*10; 
		$postData = base64_encode(json_encode($_GET));
		$result = true;
	} else if (isset($_GET['s'])) {
		$s = $common->get_prep($_GET['s']);
		$data = $search_result->getOne($s);
		$raw = json_decode(base64_decode($data['data']), true);
		$search_data = $raw['s'];
		$curTime = microtime(true);
    $add = $search->create($raw, $page_array);
		$doc = $add['doc'];
		$doc_count = $add['doc_count'];
		$reg = $add['reg'];
		$reg_count = $add['reg_count'];
		$case_law = $add['case_law'];
		$case_law_count = $add['case_law_count'];
		$dic = $add['dic'];
    $dic_count = $add['dic_count'];
    $total = $add['count'];
		$timeConsumed = round(microtime(true) - $curTime,3)*10; 
    $postData = $data['data'];
    $result = true;
  } else if (isset($_GET['query'])) {
		$s = $common->get_prep($_GET['query']);
		$raw = json_decode(base64_decode(urldecode(($_GET['query']))), true);
		$search_data = $raw['s'];
		$curTime = microtime(true);
    $add = $search->create($raw, $page_array);
		$doc = $add['doc'];
		$doc_count = $add['doc_count'];
		$reg = $add['reg'];
		$reg_count = $add['reg_count'];
		$case_law = $add['case_law'];
		$case_law_count = $add['case_law_count'];
		$dic = $add['dic'];
    $dic_count = $add['dic_count'];
    $total = $add['count'];
		$timeConsumed = round(microtime(true) - $curTime,3)*10; 
    $postData = $_GET['query'];
    $result = true;
  }
  $jsLisst = "";
  $listCat = $categories->sortAll("0", "parent_id", "status", "active");
	
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
                <script src="js/html5.js"></script>
                <![endif]-->

        <script src="SpryAssets/SpryValidationCheckbox.js" type="text/javascript"></script>
        <script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationCheckbox.css" rel="stylesheet" type="text/css">
<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css">
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
		<form id="search-form2" class="search-form2 clearfix" method="post" action="home" autocomplete="off">
      <input class="search-term2 required" type="text" id="s" name="s" placeholder="Enter matter of interest" title="* Enter matter of interest" onBlur="saveSearch(this.value)" value="<?php echo $search_data; ?>" required autofocus />
      <input class="search-btn" type="submit" value="Go" /><br>
          
      <div style="margin-left:-30px;margin-top:10px;"><b>Include in search:</b><br>
        <span id="sprycheckbox1">
        <?php for ($i = 0; $i < count($list); $i++) { ?>
        <input type="checkbox" name="parameter[]" value="<?php echo $list[$i]['ref']; ?>" checked />&nbsp;<?php echo $list[$i]['title']; ?> &nbsp;
        <?php } ?><br>
        <input type="checkbox" name="case_law" id="case_law" value="1" checked />&nbsp;Case Law &nbsp;
        <input type="checkbox" name="reg_circular" id="reg_circular" checked value="1" />&nbsp;Regulations and Circulars &nbsp;
        <input type="checkbox" name="dic" id="dic" checked value="1" />
        &nbsp;Dictionary&nbsp;
        <br>
        <span class="checkboxRequiredMsg">Please make a selection.</span></span>
      </div>
      <div id="search-error-container2"></div>
		</form>
   </div>
   <div id="search_result">
     <?php if ($result == true) { ?>
       <h4 style="" align="center">Search Result</h4>
       <p>Your search for "<?php echo $search_data; ?>" brought <?php echo number_format($total); ?> results in <?php echo number_format($timeConsumed, 3)." seconds"; ?>,<br>
       <a href="Javascript:void(0)" onClick="saveResult()"><i class="fa fa-floppy-o" aria-hidden="true"></i>
 click here to save search result</a>
        <ul class="nav nav-tabs">
            <li <?php if ((!isset($_GET['tab'])) || ($_GET['tab'] == 'case_law')) { ?>class="active"<?php } ?>><a href="#1" data-toggle="tab">Case Law (<?php echo number_format($case_law_count); ?>)</a></li>
            <?php foreach ($doc as $key => $value) { ?>
            <li <?php if ((isset($_GET['tab'])) && ($_GET['tab'] == $key)) { ?>class="active"<?php } ?>><a href="#2_<?php echo $key; ?>" data-toggle="tab"><?php echo $categories->getOneField($key); ?> (<?php echo number_format($doc_count[$key]); ?>)</a></li>
            <?php } ?>
            <li <?php if ((isset($_GET['tab'])) && ($_GET['tab'] == 'reg')) { ?>class="active"<?php } ?>><a href="#3" data-toggle="tab">Regulations (<?php echo number_format($reg_count); ?>)</a></li>
            <li <?php if ((isset($_GET['tab'])) && ($_GET['tab'] == 'dic')) { ?>class="active"<?php } ?>><a href="#4" data-toggle="tab">Dictionary (<?php echo number_format($dic_count); ?>)</a></li>
        </ul>
    
        <div class="tab-content">
            <div class="tab-pane<?php if ((!isset($_GET['tab'])) || ($_GET['tab'] == 'case_law')) { ?> active<?php } ?>" id="1">
				<?php if (count($case_law) > 0) { ?>
                    <table width="100%" border="0" id="example4">
                    <thead>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                    </thead>
                    <tbody>
					  <?php 
					  $count = $case_law_page_count*page_list;
					  for ($i = 0; $i < count($case_law); $i++) {
						  $count++ ?>
                       <tr>
                       <td valign="top"><strong><?php echo $count; ?></strong></td>
                       <td><p>
                  <a href="<?php echo URL; ?>caselaw.read?id=<?php echo $case_law[$i]['ref']; ?>&read=<?php echo $case_law[$i]['section_ID']; ?>&return=<?php echo $search_data; ?>">
                   <cite><strong><?php echo $case_law[$i]['citation']; ?></strong></cite><br>
                   <strong style="color:#00F"><?php echo $common->getLine($case_law[$i]['section_content']); ?></strong><br></a>
                   <?php echo nl2br($common->truncate($case_law[$i]['section_content'], 250)); ?><br>
                   <a href="<?php echo URL; ?>caselaw.read?id=<?php echo $case_law[$i]['ref']; ?>&read=<?php echo $case_law[$i]['section_ID']; ?>&return=<?php echo $search_data; ?>">read more</a></p>
                       </td>
                          </tr>
                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                    </tfoot>
                    </table>
                    <?php $pagination->draw($case_law_page_count, $postData, $case_law_count, $redirect, 'case_law'); ?>
                <?php } else { ?>
                    <p>No result for this item now</p>
                <?php } ?>
            </div>
            <?php foreach ($doc as $key => $value) { ?>
            <div class="tab-pane<?php if ((isset($_GET['tab'])) && ($_GET['tab'] == $key)) { ?> active<?php } ?>" id="2_<?php echo $key; ?>">
				<?php if (count($doc[$key]) > 0) {
          $jsLisst .= "#table_".$key.","; ?>
                    <table width="100%" border="0" id="table_<?php echo $key; ?>">
                    <thead>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                    </thead>
                    <tbody>
					  <?php 
					  $count = $page_count*page_list;
					  for ($i = 0; $i < count($doc[$key]); $i++) {
						  $count++ ?>
                       <tr>
                       <td valign="top"><strong><?php echo $count; ?></strong></td>
                       <td><p><strong style="color:#006"><a href="<?php echo URL; ?>document.read?id=<?php echo $doc[$key][$i]['ref']; ?>&read=<?php echo $doc[$key][$i]['section_ref']; ?>&return=<?php echo $search_data; ?>"><?php echo $doc[$key][$i]['title']; ?></a></strong><br>
                  <strong style="color:#00F"><?php echo nl2br($common->getLine($doc[$key][$i]['section_no'])); ?></strong><br>
                   <?php 
                       echo nl2br($common->truncate($doc[$key][$i]['section_content'], 150));
                    ?></p></td>
                          </tr>
                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                    </tfoot>
                    </table>
                    <?php $pagination->draw($page_count, $postData, $doc_count[$key], $redirect, $key); ?>
                <?php } else { ?>
                    <p>No result for this item now</p>
                <?php } ?>
            </div>
            <?php } ?>
            <div class="tab-pane<?php if ((isset($_GET['tab'])) && ($_GET['tab'] == 'reg')) { ?> active<?php } ?>" id="3">
				<?php if (count($reg) > 0) { ?>
                    <table width="100%" border="0" id="example4">
                    <thead>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                    </thead>
                    <tbody>
					  <?php 
					  $count = $reg_page_count*page_list;
					  for ($i = 0; $i < count($reg); $i++) {
						  $count++ ?>
                          <tr>
                       <td valign="top"><strong><?php echo $count; ?></strong></td>
                       <td><p><strong style="color:#006"><a href="<?php echo URL; ?>regulations.view?id=<?php echo $reg[$i]['ref']; ?>&read=<?php echo $reg[$i]['section_ref']; ?>&return=<?php echo $search_data; ?>"><?php echo $reg[$i]['title']; ?></a></strong><br>
                       
                   <?php 
                   if ($reg[$i]['section_no'] == "") {
                       echo nl2br($common->truncate($reg[$i]['section_content'], 200));
                   } else {
                       echo nl2br($common->getLine($reg[$i]['section_no']));
					   echo "<br>";
                       echo nl2br($common->truncate($reg[$i]['section_content'], 200));
                   } ?></p>
                       </td>
                          </tr>
                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                    </tfoot>
                    </table>
                    <?php $pagination->draw($reg_page_count, $postData, $reg_count, $redirect, 'reg'); ?>
                <?php } else { ?>
                    <p>No result for this item now</p>
                <?php } ?>
            </div>
            <div class="tab-pane<?php if ((isset($_GET['tab'])) && ($_GET['tab'] == 'dic')) { ?> active<?php } ?>" id="4">
				<?php if (count($dic) > 0) { ?>
                    <table width="100%" border="0" id="example5">
                    <thead>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                    </thead>
                    <tbody>
					  <?php 
					  $count = $dic_page_count*page_list;
					  for ($i = 0; $i < count($dic); $i++) {
						  $count++ ?>
                          <tr>
                       <td valign="top"><strong><?php echo $count; ?></strong></td>
                       <td><p><strong><?php echo $dic[$i]['title']; ?></strong><br> <?php echo $dic[$i]['details']; ?></p>
                       </td>
                          </tr>
                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                    </tfoot>
                    </table>
                    <?php $pagination->draw($dic_page_count, $postData, $dic_count, $redirect, 'dic'); ?>
                <?php } else { ?>
                    <p>No result for this item now</p>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
   </div>
</div>
</div>
</div>
                
                <a href="#top" id="scroll-top"></a>
               <!-- <a href="#top" id="scroll-top"></a> -->

                <!-- <script type='text/javascript' src='js/jquery-1.8.3.min.js'></script> -->
			   	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
                <script type='text/javascript' src='js/jquery.easing.1.34e44.js?ver=1.3'></script>
                <script type='text/javascript' src='js/prettyphoto/jquery.prettyPhotoaeb9.js?ver=3.1.4'></script>
                <script type='text/javascript' src='js/jquery.liveSearchd5f7.js?ver=2.0'></script>
				<script type='text/javascript' src='js/jflickrfeed.js'></script>
                <script type='text/javascript' src='js/jquery.formd471.js?ver=3.18'></script>
                <script type='text/javascript' src='js/jquery.validate.minfc6b.js?ver=1.10.0'></script>
                <script type='text/javascript' src="js/jquery-twitterFetcher.js"></script>
                <script type='text/javascript' src='js/custom5152.js?ver=1.0'></script>
                <script type='text/javascript' src='js/frontEnd.js'></script>
				<script type='text/javascript' src="js/navAccordion.min.js"></script>
                
                <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
                <script language="javascript" src="js/bootstrap.min.js"></script>
                <script src="js/pagination.js"></script>
				<link rel="stylesheet" href="css/jquery.ui.datatables.css">
                <script src="management/plugins/datatables/jquery.dataTables.min.js"></script>
				<script type="text/javascript">
                    var sprycheckbox1 = new Spry.Widget.ValidationCheckbox("sprycheckbox1");
					$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
						//something
					});
                    $(function() {
        				$("#example1,#example3,#example4,#example5").dataTable( {
						  "pageLength": 50,
						  "bLengthChange": false,
						  "bFilter":false
						  
						} );
                        $( "#s" ).autocomplete({
                          source: "includes/scripts/auto_home.php?type=SAN"
                        });
                    });
					
					function saveResult() {
						var data = '<?php echo $postData; ?>';
						if (data != "") {
							var person = prompt("Please enter a name for the search result", "");
							if ((person != null) && (person.length > 0)) {
								$.post( "includes/scripts/search_result.php", { data: data, title: person } );
								alert("Search result saved as "+person);
							} else {
								saveResult();
							}
						}else {
							alert("You cannot save this page");
						}
					}
				</script>
        </body>


</html>

