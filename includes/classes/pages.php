<?php
	class pages extends common {
		function head($home = false) { ?>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-5XKJCSJ');</script>

<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5XKJCSJ"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
         
            
            
            
			<link rel="shortcut icon" href="images/favicon.png" />
			
			<!-- Google Web Fonts-->
			<link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css' />
			<link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css' />
			<link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css' />
			
			<!-- Style Sheet-->
			<link rel="stylesheet" href="style.css"/>
			<link rel='stylesheet' id='bootstrap-css-css'  href='<?php echo URL; ?>css/bootstrap5152.css?ver=1.0' type='text/css' media='all' />
			<link rel='stylesheet' id='responsive-css-css'  href='<?php echo URL; ?>css/responsive5152.css?ver=1.0' type='text/css' media='all' />
			<link rel='stylesheet' id='pretty-photo-css-css'  href='<?php echo URL; ?>js/prettyphoto/prettyPhotoaeb9.css?ver=3.1.4' type='text/css' media='all' />
			<link rel='stylesheet' id='main-css-css'  href='<?php echo URL; ?>css/main5152.css?ver=1.0' type='text/css' media='all' />
			<link rel='stylesheet' id='blue-skin-css'  href='<?php echo URL; ?>css/blue-skin5152.css?ver=1.0' type='text/css' media='all' />
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" />
			
			<!--<script type='text/javascript' src='js/jquery-1.8.3.min.js'></script>
			
			Sidelinks -->
            
            <?php $this->chatHeader(); ?>

			<script type='text/javascript' src='<?php echo URL; ?>js/jquery-2.1.3.min.js'></script>
            <?php if ($home == false) { ?>
			<link rel="stylesheet" type="text/css" href="<?php echo URL; ?>css/sidelinks.css" />
			<script>
			
			jQuery(document).ready(function(){
			
			//Accordion Nav
			jQuery('.mainNavSide').navAccordion({
			expandButtonText: '<i class="fa fa-plus"></i>',  //Text inside of buttons can be HTML
			collapseButtonText: '<i class="fa fa-minus"></i>'
			}, 
			function(){
			console.log('Callback')
			});
			
			});
			</script>
            <?php } ?>
		<?php }
		
		function chatHeader() { ?>
            
            <!-- Chat CSS -->
            
            <link type="text/css" rel="stylesheet" media="all" href="<?php echo URL; ?>css/chat.css" />
		<?php }
		
		function headerFiles($tag="home") {
			$friendzone = new friendzone;
			$users = new users;
			$list = $friendzone->sortAll(0,"status", "user", $_SESSION['users']['ref']);
			$friendList = $friendzone->sortAll(2,"status", "user", $_SESSION['users']['ref']); ?>
        <script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
		
		  ga('create', 'UA-85453718-1', 'auto');
		  ga('send', 'pageview');
		
		</script>
        <header>
            <div class="container">
                <div class="logo-container">
                <!-- Website Logo -->
                <a href="<?php echo URL; ?>"  title="LegalLens"><img src="images/ll_logo.png" alt="LegalLens" style="display:block; width:350px;" /></a>
                </div> <!-- Start of Main Navigation -->
                <nav class="main-nav">
                    <div class="menu-top-menu-container navbar">
                        <ul id="menu-top-menu nav navbar-nav" class="clearfix">
                            <li<?php if ($tag == "home") { ?> class="current-menu-item"<?php } ?>><a href="<?php echo URL; ?>">Home</a></li>
                            <li<?php if ($tag == "about") { ?> class="current-menu-item"<?php } ?>><a href="<?php echo URL; ?>about">About us</a></li>
                            <li<?php if ($tag == "userguide") { ?> class="current-menu-item"<?php } ?>><a href="<?php echo URL; ?>userguide">User guide</a></li>
                            <li<?php if ($tag == "career") { ?> class="current-menu-item"<?php } ?>><a href="<?php echo URL; ?>career">Career</a></li>
                            <li<?php if ($tag == "faq") { ?> class="current-menu-item"<?php } ?>><a href="<?php echo URL; ?>faq">FAQs</a></li>
                            <li<?php if ($tag == "Forum") { ?> class="current-menu-item"<?php } ?>><a href="<?php echo URL; ?>Forum">Forum</a></li>
                            <li class="dropdown<?php if ($tag == "friendzone") { ?> current-menu-item<?php } ?>"><a href="<?php echo URL; ?>friendzone" class="dropdown-toggle"s>Friendzone (<?php echo count($list); ?>)</a>
                            
                                <ul class="dropdown-menu">
									<?php if (count($friendList) > 0) {
                                    for ($i = 0; $i < count($friendList); $i++) { ?>
                                    <li><a href="javascript:void(0)" onclick="javascript:chatWith('<?php echo $users->getOneField($friendList[$i]['friend_id'], "ref", "username"); ?>');"><?php echo $users->getOneField($friendList[$i]['friend_id'])." ".$users->getOneField($friendList[$i]['friend_id'], "ref", "other_names"); ?></a></li>
                                    <?php }
                                    } else { ?>
                                    <li><a href="<?php echo URL; ?>friendzone">no friend on your list yet</a></li>
                                    <?php } ?>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="<?php echo URL; ?>friendzone">Pending Request (<?php echo count($list); ?>)</a></li>
                                </ul>
                            
                            </li>
                            <li<?php if ($tag == "contact") { ?> class="current-menu-item"<?php } ?>><a href="<?php echo URL; ?>contact">Contact</a></li>
                <!--<li><a class="show-popup" href="#" data-showpopup="1" >Login</a></li>-->
                        </ul>
                    </div>
                </nav>
            <!-- End of Main Navigation -->
			</div>
		</header>
		<?php } 
 //I just added this
 		function advert() {
			$advert = new advert;
			$list = $advert->showAd(6);?>
            
            	<?php for ($i = 0; $i < count($list); $i++) { ?>
                <div align="center">
                <a href="<?php echo $list[$i]['url']; ?>" target="_blank"><img width="300" src="<?php echo URL; ?>advert/<?php echo $list[$i]['media_file']; ?>" title="advert"></a>
                </div>
                <?php } ?>
                
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
		<?php }
		
 		function advertHome() {
			$advert = new advert;
			$list = $advert->showAd(6);?>
            
            <div class="span2">
                <section class="widget">
                    <div align="center">
                        <?php for ($i = 0; $i < count($list); $i++) { ?>
                            <a href="<?php echo $list[$i]['url']; ?>" target="_blank"><image src="<?php echo URL; ?>advert/<?php echo $list[$i]['media_file']; ?>" title="advert"></a>
                        <?php } ?>
                    </div>
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
            </div>
		<?php }
        
		function rightColumnAdvert(){
			$advert = new advert;
			$list = $advert->showAd(6);?>
            
            <div class="span2">
                <section class="widget">
                    <div align="center">
                        <a href="<?php echo URL; ?>helpAndSupport" ><img src="<?php echo URL; ?>/images/help.png" width="100" /></a><br />
                        <h3 class="title">Need Help?</h3>
                        <p class="intro">Click here to<br /><a id="mibew-agent-button" href="<?php echo URL; ?>helpAndSupport" >Contact Support</a></p>
                        <?php for ($i = 0; $i < count($list); $i++) { ?>
                            <a href="<?php echo $list[$i]['url']; ?>" target="_blank"><image src="<?php echo URL; ?>advert/<?php echo $list[$i]['media_file']; ?>" title="advert"></a>
                        <?php } ?>
                    </div>
                    <div align="center"><a href="https://play.google.com/store/apps/details?id=mobile.linnkstec.mansur.com.legallens" target="_blank"><img src="images/en_badge_web_generic.png" width="200"></a></div>
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
            </div>
		<?php }

		function sidelinks($login=false) {
			$categories = new categories;
			$listItem = new listItem;
			$regulators = new regulators;
			$caselaw = new caselaw;
			$list = $categories->sortAll("0", "parent_id", "status", "active");
			$listReg = $regulators->sortAll("active", "status");
			$courtList = $listItem->listCourt();
			$caseCOurt = $caselaw->listCourt();
			 ?>

<!--my new link -->
<div id="outers-wrap">
  	
	<!-- Navigation -->
	<nav class="mainNavSide">
<ul>
<li><a href="<?php echo URL; ?>home">Quick Find</a></li>
 <?php for ($i = 0; $i < count($list); $i++) {
		echo "<li><a href='".URL."documentHome?sort=".$list[$i]['ref']."'>".$list[$i]['title']."</a>";
		echo $categories->gettreeHome($list[$i]['ref']);
		echo "</li>";
	} ?>
<li><a href="<?php echo URL; ?>caseLawHome">Case law</a>
  <ul>
  <?php for ($i = 0; $i < count($caseCOurt); $i++) { ?>
  <li><a href="<?php echo URL; ?>caseLaw?sort=<?php echo urlencode($caseCOurt[$i]['title']); ?>"><?php echo $caseCOurt[$i]['title']; ?></a></li>
  <?php } ?>
  </ul>
</li>
<li><a href="<?php echo URL; ?>regulations">Regulations /Circular</a>
  <ul>
  <?php for ($i = 0; $i < count($listReg); $i++) { ?>
  <li><a href="<?php echo URL; ?>regulations/<?php echo urlencode($listReg[$i]['title']); ?>"><?php echo $listReg[$i]['title']; ?></a></li>
  <?php } ?>
  </ul>
</li>
<!--<li><a href="<?php echo URL; ?>books">Books</a></li>-->
<!--<li><a href="<?php echo URL; ?>articles">Articles and Journals</a>
  <ul>
  <li><a href="<?php echo URL; ?>articles/Article">Article</a></li>
  <li><a href="<?php echo URL; ?>articles/Journals">Journals</a></li>
  </ul>
</li>-->
<li><a href="Javascript:void(0);">Legal Drafting</a>
      <ul>
      <li><a href="<?php echo URL; ?>clause">Draft Clauses</a></li>
      <li><a href="<?php echo URL; ?>agreements">Draft Agreement</a></li>
      <li><a href="<?php echo URL; ?>forms">Forms</a></li>
      </ul>
</li>
 <li><a href="<?php echo URL; ?>dictionary">Law Dictionary</a></li>
<!--<li><a href="<?php echo URL; ?>courts">List of Courts</a>
<?php if (count($courtList) > 0) { ?>
  <ul>
  <?php for ($i = 1; $i < count($courtList); $i++) { ?>
  <li><a href="<?php echo URL; ?>courts?sort=<?php echo urlencode($courtList[$i]['title']); ?>"><?php echo $courtList[$i]['title']; ?></a></li>
  <?php } ?>
  </ul>
<?php } ?>
</li>
<li><a href="<?php echo URL; ?>judges">List of Judges in Nigeria</a>
<?php if (count($courtList) > 0) { ?>
  <ul>
  <?php for ($i = 1; $i < count($courtList); $i++) { ?>
  <li><a href="<?php echo URL; ?>judges?sort=<?php echo urlencode($courtList[$i]['title']); ?>"><?php echo $courtList[$i]['title']; ?></a></li>
  <?php } ?>
  </ul>
<?php } ?>
</li>
<li><a href="<?php echo URL; ?>SANS">List of SANS</a></li>-->
</ul>
	</nav>
</div>
		<?php }
		
		function footer() {
			$page_content = new page_content;
			$content = $this->truncate($page_content->getOneField("guide", "title", "content"), 200); ?>
        <div id="footer" class="container">
<div class="row">

        <div class="span3">
                <section class="widget">
                        <h3 class="title">How it works</h3>
                        <div class="textwidget">
                                <p><?php echo $content; ?></p>
                        </div>
                </section>
        </div>

        <div class="span3">
                <section class="widget"><h3 class="title">Quick Links</h3>
                        <ul>
                                <li><a href="<?php echo URL; ?>home">Members' Home</a> </li>
                                <li><a href="<?php echo URL; ?>userprofile">User's Profile</a></li>
                                <li><a href="<?php echo URL; ?>managesavedpages">My Saved Pages</a></li>
                                <li><a href="<?php echo URL; ?>managesubscription">My Subscription</a></li>
                              
                             
                        </ul>
                </section>
        </div>

        <div class="span3">
                <section class="widget">
                        <h3 class="title">Need Help?</h3>
                       <!-- <div id="twitter_update_list"> -->
                                <ul>
                                       <!-- <li>No Tweets loaded !</li>-->
					<li><a href="<?php echo URL; ?>helpAndSupport">Customer Care Center</a> </li>
                                <li><a href="<?php echo URL; ?>Forum">Forum</a></li>
                                <li><a href="<?php echo URL; ?>contact">Connect with us</a></li>
                                </ul>
                       <!-- </div>
                        <script src="http://twitterjs.googlecode.com/svn/trunk/src/twitter.min.js" type="text/javascript"></script>
                        <script type="text/javascript" >
                                getTwitters("twitter_update_list", {
                                        id: "960development",
                                        count: 3,
                                        enableLinks: true,
                                        ignoreReplies: true,
                                        clearContents: true,
                                        template: "%text% <span>%time%</span>"
                                });
                        </script>-->
                </section>
        </div>

        <div class="span3">
                <section class="widget">
                        <h3 class="title">Company</h3>
                        <!--<div class="flickr-photos" id="basicuse">
                        </div>-->
    			<ul>
                                       <!-- <li>No Tweets loaded !</li>-->
					<li><a href="<?php echo URL; ?>about">About Us</a> </li>
                                <li><a href="<?php echo URL; ?>faq">FAQ</a></li>
                                <li><a href="<?php echo URL; ?>career">Career</a></li>
				<li><a href="#">Contact Us</a></li>
                <li>
                                <img src="<?php echo URL; ?>img/isw_logo_new_combined.png" /></li>
                                </ul>
                </section>
        </div>

</div>
</div>
		<?php }
		
		function footerButtom() { ?>
        <div id="footer-bottom-wrapper">
        <div id="footer-bottom" class="container">
                <div class="row">
                        <div class="span6">
                                <p class="copyright">
                                        Copyright © <?php echo date('Y');?>. All Rights Reserved by Legal Lens.
                                </p>
                        </div>
                        <div class="span6">
                                <!-- Social Navigation -->
                                <ul class="social-nav clearfix">
                                <?php if (linkedin != "") { ?>
                                    <li class="linkedin"><a target="_blank" href="<?php echo linkedin; ?>"></a></li>
                                <?php } if (google != "") { ?>
                                    <li class="google"><a target="_blank" href="<?php echo google; ?>"></a></li>
                                <?php } if (flickr != "") { ?>
                                    <li class="flickr"><a target="_blank" href="<?php echo flickr; ?>"></a></li>
                                <?php } if (skype != "") { ?>
                                    <li class="skype"><a target="_blank" href="skype:<?php echo skype; ?>?call"></a></li>
                                <?php } if (rss != "") { ?>
                                    <li class="rss"><a target="_blank" href="<?php echo rss; ?>"></a></li>
                                <?php } if (twitter != "") { ?>
                                    <li class="twitter"><a target="_blank" href="<?php echo twitter; ?>"></a></li>
                                <?php } if (facebook != "") { ?>
                                    <li class="facebook"><a target="_blank" href="<?php echo facebook; ?>"></a></li>
                                <?php } if (instagram != "") { ?>
                                    <li class="instagram"><a target="_blank" href="<?php echo instagram; ?>"></a></li>
                                <?php } ?>
                                </ul>
                        </div>
                </div>
        </div>
</div>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo URL; ?>js/chat.js"></script>
		<?php }
		
		function sideMenu() { ?>
			<a href="<?php echo URL; ?>managesubscription">Manage Subscription</a><br />
			<a href="<?php echo URL; ?>support">Help and Support</a><br />
			<a href="<?php echo URL; ?>userprofile">View profile</a><br />
			<a href="<?php echo URL; ?>managesavedpages">Manage Saved Pages</a><br />
			<a href="<?php echo URL; ?>managesearch">Manage Recent Search</a><br />
			<!--<a href="<?php echo URL; ?>managesession">Manage Sessions</a><br />-->
			<a href="<?php echo URL; ?>?logout">Logout</a>
		<?php }
	}
?>
