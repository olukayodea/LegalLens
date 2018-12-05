<?php
	$redirect = "categories";
	include_once("../includes/functions.php");
	include_once("session.php");
	
	$list = $categories->sortAll("0", "parent_id");
?>
<!doctype html>

<html lang="en">
<head>
<title>jQuery-Menu Plugin Examples</title>
    <meta charset="UTF-8">
    
    <script src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="../js/fg.menu.js"></script>
    
    <link type="text/css" href="../css/fg.menu.css" media="screen" rel="stylesheet">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css">
    
    <!-- styles for this example page only -->
	<style type="text/css">
	body { font-size:62.5%; margin:0; padding:0; }
	#menuLog { font-size:1.4em; margin:20px; }
	.hidden { position:absolute; top:0; left:-9999px; width:1px; height:1px; overflow:hidden; }
	
	.fg-button { clear:left; margin:0 4px 40px 20px; padding: .4em 1em; text-decoration:none !important; cursor:pointer; position: relative; text-align: center; zoom: 1; }
	.fg-button .ui-icon { position: absolute; top: 50%; margin-top: -8px; left: 50%; margin-left: -8px; }
	a.fg-button { float:left;  }
	button.fg-button { width:auto; overflow:visible; } /* removes extra button width in IE */
	
	.fg-button-icon-left { padding-left: 2.1em; }
	.fg-button-icon-right { padding-right: 2.1em; }
	.fg-button-icon-left .ui-icon { right: auto; left: .2em; margin-left: 0; }
	.fg-button-icon-right .ui-icon { left: auto; right: .2em; margin-left: 0; }
	.fg-button-icon-solo { display:block; width:8px; text-indent: -9999px; }	 /* solo icon buttons must have block properties for the text-indent to work */	
	
	.fg-button.ui-state-loading .ui-icon { background: url(spinner_bar.gif) no-repeat 0 0; }
	</style>
	
	<!-- style exceptions for IE 6 -->
	<!--[if IE 6]>
	<style type="text/css">
		.fg-menu-ipod .fg-menu li { width: 95%; }
		.fg-menu-ipod .ui-widget-content { border:0; }
	</style>
	<![endif]-->	
    
    <script type="text/javascript">    
    $(function(){
		$('#hierarchybreadcrumb').menu({
			content: $('#hierarchybreadcrumb').next().html(),
			maxHeight: 300,
			flyout: true,
			backLink: false
		});
    });
    </script>
</head>

<body>
<h1>Select Category</h1>
<p id="menuLog">You chose: <span id="menuSelection"></span></p>

<a tabindex="0" href="#news-items-2" class="fg-button fg-button-icon-right ui-widget ui-state-default ui-corner-all" id="hierarchybreadcrumb"><span class="ui-icon ui-icon-triangle-1-s"></span>Select Parent category</a>
<div id="news-items-2" class="hidden">
<ul>
	<?php for ($i = 0; $i < count($list); $i++) {
		echo "<li><a href='#'>".strtolower($list[$i]['title']."_".$list[$i]['ref'])."</a>";
		echo $categories->gettree($list[$i]['ref']);
		echo "</li>";
	} ?>
</ul>
</div>

</body>
</html>
