<?php
	$redirect = "friendzone";
	include_once("includes/functions.php");
	include_once("includes/session.php");
	if (isset($_GET['addNew'])) {
		$data['ref'] = $ref;
		$data['id'] = $common->get_prep($_GET['id']);
		$add = $friendzone->add($data);
		
		if($add) {
			header("location: ?done=".urlencode("Friend request sent"));
		} else {
			header("location: ?error=".urlencode("Friend request not sent"));
		}
	}
	
	if (isset($_GET['approve'])) {
		$id = $common->get_prep($_GET['id']);
		$add = $friendzone->approve($id);
		
		if($add) {
			header("location: ?done=".urlencode("Friend request approved"));
		} else {
			header("location: ?error=".urlencode("Friend request not sent"));
		}
	} else if (isset($_GET['deny'])) {
		$id = $common->get_prep($_GET['id']);
		$add = $friendzone->deny($id);
		
		if($add) {
			header("location: ?done=".urlencode("Friend request denied"));
		} else {
			header("location: ?error=".urlencode("Friend request not sent"));
		}
	}
	
	
	//0 == recieved
	//1 = sent
	//2 = friends
	$list = $friendzone->sortAll(0,"status", "user", $ref);
	$list2 = $friendzone->sortAll(1,"status", "user", $ref);
	$friendList = $friendzone->sortAll(2,"status", "user", $ref);
?>
<!DOCTYPE html>
        <!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en-US"> <![endif]-->
        <!--[if IE 7]>    <html class="lt-ie9 lt-ie8" lang="en-US"> <![endif]-->
        <!--[if IE 8]>    <html class="lt-ie9" lang="en-US"> <![endif]-->
        <!--[if gt IE 8]><!--> <html lang="en-US"> <!--<![endif]-->
        

<base href="<?php echo URL; ?>" />
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

                <title>Connect with your friends on legallens friendzone</title>
                <meta name="description" content="Looking to connect with fellow lawyers and legal practitioners? Find them on Legal Lens Friendzone">

        <?php $pages->head(); ?>
        </head>

        <body>

                <!-- Start of Header -->
                <div class="header-wrapper">
                        <?php $pages->headerFiles(); ?>
                </div>
                <!-- End of Header -->

                <!-- Start of Search Wrapper -->
                <div class="search-area-wrapper">
                        <div class="search-area container">
                                <form id="search-form" class="search-form clearfix" method="get" action="<?php echo URL.$redirect; ?>" autocomplete="off">
                                  <input class="search-term required" type="text" id="s" name="s" placeholder="enter friend's name, email or phone number to search" title="* enter friend's name, email or phone number to search!" />
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
                                  <div class="page-content">

                                            <h1 class="post-title">
                                                <a href="#">Friendzone</a>
                                            </h1>
											<?php if (isset($_GET['done'])) { ?><br>
                                            <p class="success">Action completed Successfully.<?php echo $_GET['done']; ?> </p>
                                            <?php } ?>
                                            <hr>
											<?php if (isset($_GET['error'])) { ?><br>
                                            <p class="error">An error occured: <?php echo $_GET['error']; ?></p>
                                            <?php } ?>
                                            <hr>
                                            <?php if ($memo != false) {
												echo $memo."<br><br>";
											} ?>
                                      <table width="100%" border="0">
                                        <tr>
                                          <td width="80%s" valign="top">
                                          <h2>Pending Request</h2>
                                          <?php if (count($list) > 0) {
										  for ($i = 0; $i < count($list); $i++) { ?>
                                          <p><strong style="font-size:16px"><?php echo $users->getOneField($list[$i]['friend_id'])." ".$users->getOneField($list[$i]['friend_id'], "ref", "other_names"); ?></strong><br>
                                          <a href="friendzone?approve&id=<?php echo $list[$i]['ref']; ?>">Approve Request</a> | <a href="friendzone?deny&id=<?php echo $list[$i]['ref']; ?>">Deny Request</a>
                                          </p>
                                          <?php }
										  } else { ?>
                                          <p>You have no pending request
										  <?php } ?>
                                          <h2>Sent Request</h2>
                                          <?php if (count($list2) > 0) {
										  for ($i = 0; $i < count($list2); $i++) { ?>
                                          <p><strong style="font-size:16px"><?php echo $users->getOneField($list2[$i]['friend_id'])." ".$users->getOneField($list2[$i]['friend_id'], "ref", "other_names"); ?></strong></p>
                                          <?php }
										  } else { ?>
                                          <p>You have no pending sent request
										  <?php } ?>
                                          </td>
                                          <td width="20s%" valign="top">
                                          

                                        <section class="widget">
                                          <h3 class="title">Friends' List</h3>
                                            <ul id="main_container">
                                            <?php if (count($friendList) > 0) {
											for ($i = 0; $i < count($friendList); $i++) { ?>
                                            <li><a href="javascript:void(0)" onclick="javascript:chatWith('<?php echo $users->getOneField($friendList[$i]['friend_id'], "ref", "username"); ?>');"><?php echo $users->getOneField($friendList[$i]['friend_id'])." ".$users->getOneField($friendList[$i]['friend_id'], "ref", "other_names"); ?></a></li>
                                            <?php }
											} else { ?>
                                            <p>no friend on your list yet</p>
											<?php } ?>
                                            </ul>
                                        </section>
                                        <section class="widget"></section>
                                        <section class="widget">
                                            <?php $pages->advert(); ?>
                                        </section></td>
                                        </tr>
                                      </table>
                                  </div>
                                        <!-- end of page content -->
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
        <script src="js/pagination.js"></script>
		<script type='text/javascript' src="js/navAccordion.min.js"></script>
        
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        
		<script>
            $(function() {
                $( "#s" ).autocomplete({
                  source: "includes/scripts/auto_complete_friendzone.php",
                    select: function( event, ui ) {
						window.location='friendzone?addNew&id='+ui.item.ref+"&value="+ui.item.value;
                    }
                });
            });
        </script>

        </body>


</html>

