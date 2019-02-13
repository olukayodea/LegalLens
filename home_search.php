<?php
$redirect = "home";
include_once("includes/functions.php");


$data = $_REQUEST;
$post['parameter'] = $_REQUEST['parameter'];
$other_data = $_REQUEST['other_data'];
$first = explode( ":", $other_data);

$search_query = explode("_", $first[0]);
$post['s'] = $search_query[1];
$dic = explode("_", $first[1]);
$post['dic'] = $dic[1];
$case_law = explode("_", $first[3]);
$post['case_law'] = $case_law[1];
$reg_circular = explode("_", $first[2]);
$post['reg_circular'] = $reg_circular[1];

$page_array['law'] = 0;
$page_array['case_law'] = 0;
$page_array['dic'] = 0;
$page_array['reg'] = 0;


$search_data = $post['s'];
$curTime = microtime(true);
$add = $search->create($post, $page_array);
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
$postData = base64_encode(json_encode($post));
?>

<h4 style="" align="center">Search Result</h4>
       <p>Your search for "<?php echo $search_data; ?>" brought <?php echo number_format($total); ?> results in <?php echo number_format($timeConsumed, 3)." seconds"; ?>,<br>
       <a href="Javascript:void(0)" onClick="saveResult()"><i class="fa fa-floppy-o" aria-hidden="true"></i>
 click here to save search result</a>
        <ul class="nav nav-tabs">
            <li <?php if ((!isset($_GET['tab'])) || ($_GET['tab'] == 'case_law')) { ?>class="active"<?php } ?>><a href="#1" data-toggle="tab">Case Law (<?php echo number_format($case_law_count); ?>)</a></li>
            <?php if (count($doc) > 0) {
            foreach ($doc as $key => $value) { ?>
            <li <?php if ((isset($_GET['tab'])) && ($_GET['tab'] == $key)) { ?>class="active"<?php } ?>><a href="#2_<?php echo $key; ?>" data-toggle="tab"><?php echo $categories->getOneField($key); ?> (<?php echo number_format($doc_count[$key]); ?>)</a></li>
            <?php }
            } ?>
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
            </div><?php if (count($doc) > 0) {
            foreach ($doc as $key => $value) { ?>
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
            <?php }
            } ?>
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