<?php
	$redirect = "caselaw.read";
	include_once("includes/functions.php");
	include_once("includes/session.php");
	
	if (isset($_REQUEST['id'])) {
		$id = $common->get_prep($_REQUEST['id']);
		$tag = "Documents issued by ".$id;
	} else {
		header("location: caseLaw");
	}
	if (isset($_REQUEST['read'])) {
		$read = intval($common->get_prep($_REQUEST['read']));
	} else {
		header("location: caselaw.view?id=".$id);
	}
	
	if (isset($_REQUEST['return'])) {
		$s = $common->get_prep($_REQUEST['return']);
	}
	
	$data = $caselaw->getOne($id);
	$list = $caselaw_sections->getOne($read);
	$subList = $caselaw_sections->sortAll($read, "parent_issue", "status", "active");
	$common->updateCounter($id, $read, "caseLaw");
	
	$text_data = $caselaw_sections->turnClickable($read);
?>
<!DOCTYPE html>
        <!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en-US"> <![endif]-->
        <!--[if IE 7]>    <html class="lt-ie9 lt-ie8" lang="en-US"> <![endif]-->
        <!--[if IE 8]>    <html class="lt-ie9" lang="en-US"> <![endif]-->
        <!--[if gt IE 8]><!--> <html lang="en-US"> <!--<![endif]-->
<base href="<?php echo URL; ?>" />
<style>
	.decorate a{
		text-decoration:underline;
	}
</style>

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

                <title><?php echo $data['title']; ?></title>
    <?php $pages->head(); ?>
                <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
                <!--[if lt IE 9]>
                <script src="js/html5.js"></script>
                <![endif]-->

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
       <?php if ((isset($s)) && ($s != "")) { ?>
       <p align="left"><a href="home?q=<?php echo $s; ?>" style="text-decoration:underline">Back to Search</a></p>
       <?php } ?>
       <h3 style="" align="center"><?php echo $data['title']; ?></h3>
       <p align="center"><?php echo $common->getLine($list['section_content']); ?></p>
       <p class="decorate"><?php echo nl2br($text_data); ?><br><br>
       <cite><?php echo $list['citation']; ?></cite>
		<?php if ($data['file'] != "") { ?>
		<div id="page" style="width:100%">

			<div class="btn-group">	
			<button class="btn bt-default pull-left" onclick="openNextPage()">Next Page</button>
	
			<button class="btn bt-default pull-right" onclick="openPrevPage()">Previous Page</button>
  
			</div>  
			<canvas id="canvas"></canvas>
  
			<div class="btn-group">	
			<button class="btn bt-default pull-left" onclick="openNextPage()">Next Page</button>
	
			<button class="btn bt-default pull-right" onclick="openPrevPage()">Previous Page</button>
  
			</div>
		</div>
        <?php } ?>
       
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
		<script type='text/javascript' src="loader/pdf.js"></script>
<script type='text/javascript' src="loader/pdf.worker.js"></script>

<script>
if (!window.requestAnimationFrame) {
  window.requestAnimationFrame = (function() {
    return window.webkitRequestAnimationFrame ||
      window.mozRequestAnimationFrame ||
      window.oRequestAnimationFrame ||
      window.msRequestAnimationFrame ||
      function(callback, element) {
        window.setTimeout(callback, 1000 / 60);
      };
  })();
}

document.addEventListener('tizenhwkey', function(e) {
  if (e.keyName === 'back') {
    try {
      tizen.application.getCurrentApplication().exit();
    } catch (error) {}
  }
}); 

var canvas = document.getElementById('canvas');
var context = canvas.getContext('2d');
var pageElement = document.getElementById('page');

var reachedEdge = false;
var touchStart = null;
var touchDown = false;

var lastTouchTime = 0;

pageElement.addEventListener('touchstart', function(e) {
  touchDown = true;

  if (e.timeStamp - lastTouchTime < 500) {
    lastTouchTime = 0;
    toggleZoom();
  } else {
    lastTouchTime = e.timeStamp;
  }
});

pageElement.addEventListener('touchmove', function(e) {
  if (pageElement.scrollLeft === 0 ||
    pageElement.scrollLeft === pageElement.scrollWidth - page.clientWidth) {
    reachedEdge = true;
  } else {
    reachedEdge = false;
    touchStart = null;
  }

  if (reachedEdge && touchDown) {
    if (touchStart === null) {
      touchStart = e.changedTouches[0].clientX;
    } else {
      var distance = e.changedTouches[0].clientX - touchStart;
      if (distance < -100) {
        touchStart = null;
        reachedEdge = false;
        touchDown = false;
        openNextPage();
      } else if (distance > 100) {
        touchStart = null;
        reachedEdge = false;
        touchDown = false;
        openPrevPage();
      }
    }
  }
});

pageElement.addEventListener('touchend', function(e) {
  touchStart = null;
  touchDown = false;
});


var pdfFile;
var currPageNumber = 1;


var openNextPage = function() {
  var pageNumber = Math.min(pdfFile.numPages, currPageNumber + 1);
  if (pageNumber !== currPageNumber) {
    currPageNumber = pageNumber;
    openPage(pdfFile, currPageNumber);
  }
};


var openPrevPage = function() {
  var pageNumber = Math.max(1, currPageNumber - 1);
  if (pageNumber !== currPageNumber) {
    currPageNumber = pageNumber;
    openPage(pdfFile, currPageNumber);
  }
}; 

var zoomed = true;
var toggleZoom = function () {
  zoomed = !zoomed;
  openPage(pdfFile, currPageNumber);
};

var fitScale = 1;
var openPage = function(pdfFile, pageNumber) {
  var scale = zoomed ? fitScale : 1;

  pdfFile.getPage(pageNumber).then(function(page) {
    viewport = page.getViewport(1);

    if (zoomed) {
      var scale = pageElement.clientWidth / viewport.width;
      viewport = page.getViewport(scale);
    }

    canvas.height = viewport.height;
    canvas.width = viewport.width;

    var renderContext = {
      canvasContext: context,
      viewport: viewport
    };

    page.render(renderContext);
  });
};

PDFJS.disableStream = true;
PDFJS.getDocument('library/caselaws/<?php echo $data['file']; ?>').then(function(pdf) {
  pdfFile = pdf;
  openPage(pdf, currPageNumber);
});


</script>

        </body>


</html>

