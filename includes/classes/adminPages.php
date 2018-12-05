<?php
	class adminPages extends common {
		function headerFiles() { ?>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport" />
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="<?php echo URLAdmin; ?>bootstrap/css/bootstrap.min.css" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" />
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" />
    <!-- daterange picker -->
    <link rel="stylesheet" href="<?php echo URLAdmin; ?>plugins/daterangepicker/daterangepicker-bs3.css" />
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="<?php echo URLAdmin; ?>plugins/iCheck/all.css" />
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="<?php echo URLAdmin; ?>plugins/colorpicker/bootstrap-colorpicker.min.css" />
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="<?php echo URLAdmin; ?>plugins/timepicker/bootstrap-timepicker.min.css" />
    <!-- Select2 -->
    <link rel="stylesheet" href="<?php echo URLAdmin; ?>plugins/select2/select2.min.css" />
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo URLAdmin; ?>plugins/datatables/dataTables.bootstrap.css" />
    <!-- jvectormap -->
    <link rel="stylesheet" href="<?php echo URLAdmin; ?>plugins/jvectormap/jquery-jvectormap-1.2.2.css" />
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo URLAdmin; ?>dist/css/AdminLTE.min.css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo URLAdmin; ?>dist/css/skins/_all-skins.min.css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
		<?php }
		
		function topHeader() {
			$admin = new admin;
			$notification = new notification;
			$frmt = $notification->notificationCountGroup("forum_topic");
			$frm = $notification->notificationCountGroup("forum");
			$ord = $notification->notificationCountGroup("orders");
			$hel = $notification->notificationCountGroup("help");
			$words = explode(" ", trim($_SESSION['admin']['name']));
			$default = "mm";
			$size = 35;
			$grav_url = "http://www.gravatar.com/avatar/".md5(strtolower(trim($_SESSION['admin']['name'])))."?d=".$default."&s=".$size; ?>
        <header class="main-header">

        <!-- Logo -->
        <a href="<?php echo URLAdmin; ?>" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>Legal</b>Lens</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>Legal</b>Lens</span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- Notifications: style can be found in dropdown.less -->
              <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-bell-o"></i>
                  <span class="label label-warning"><?php echo $notification->notificationCount(); ?></span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have <?php echo $notification->notificationCount(); ?> notification<?php echo $this->addS($notification->notificationCount()); ?></li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      <li>
                        <a href="<?php echo URLAdmin; ?>notifications?view=forum">
                          <i class="fa fa-users text-aqua"></i> <?php echo $frm; ?> new notification<?php echo $this->addS($frm); ?> from Posts in Forums
                        </a>
                      </li><!--
                      <li>
                        <a href="#">
                          <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the page and may cause design problems
                        </a>
                      </li>-->
                      <li>
                        <a href="<?php echo URLAdmin; ?>notifications?view=help">
                          <i class="fa fa-users text-aqua"></i> <?php echo $hel; ?> new notification<?php echo $this->addS($frmt); ?> from Helps and Support
                        </a>   
                      </li>
                      <li>
                        <a href="<?php echo URLAdmin; ?>notifications?view=forum_topic">
                          <i class="fa fa-users text-aqua"></i> <?php echo $frmt; ?> new notification<?php echo $this->addS($frmt); ?> from Topics in Forums
                        </a>   
                      </li>
                      <li>
                        <a href="<?php echo URLAdmin; ?>notifications?view=orders">
                          <i class="fa fa-shopping-cart text-green"></i> <?php echo $ord; ?> new Order Approval<?php echo $this->addS($ord); ?>
                        </a>
                      </li><!--
                      <li>
                        <a href="#">
                          <i class="fa fa-user text-red"></i> You changed your username
                        </a>
                      </li>-->
                    </ul>
                  </li>
                  <li class="footer"><a href="<?php echo URLAdmin; ?>notifications">View all</a></li>
                </ul>
              </li>
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="<?php echo $grav_url; ?>" class="user-image" alt="User Image" />
                  <span class="hidden-xs"><?php echo trim($_SESSION['admin']['name']); ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="<?php echo $grav_url; ?>" class="img-circle" alt="User Image" />
                    <p>
                      <?php echo $words[0]." ".$this->initials($words[1]); ?> - <?php echo $admin->getOneTypeField(trim($_SESSION['admin']['adminType'])); ?>
                      <small>Member since Nov. <?php echo date("M, Y", trim($_SESSION['admin']['timeStamp'])); ?></small>
                      <small>logged in <?php echo $this->get_time_stamp(trim($_SESSION['admin']['loginTime'])); ?></small>
                    </p>
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="<?php echo URLAdmin; ?>profile" class="btn btn-default btn-flat">Profile</a>
                    </div>
                    <div class="pull-right">
                      <a href="<?php echo URLAdmin; ?>login?logout" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>

        </nav>
      </header>
		<?php }
		
		function topHeaderClient() {
			$admin = new admin;
			$words = explode(" ", trim($_SESSION['clients']['name']));
			$default = "mm";
			$size = 35;
			$grav_url = "http://www.gravatar.com/avatar/".md5(strtolower(trim($_SESSION['clients']['name'])))."?d=".$default."&s=".$size; ?>
        <header class="main-header">

        <!-- Logo -->
        <a href="<?php echo URLClients; ?>" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>Legal</b>Lens</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>Legal</b>Lens</span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="<?php echo $grav_url; ?>" class="user-image" alt="User Image" />
                  <span class="hidden-xs"><?php echo trim($_SESSION['clients']['name']); ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="<?php echo $grav_url; ?>" class="img-circle" alt="User Image" />
                    <p>
                      <?php echo $words[0]." ".$this->initials($words[1]); ?> - (Document Admin)
                      <small>Member since <?php echo date("M, Y", trim($_SESSION['clients']['create_time'])); ?></small>
                      <small>logged in <?php echo $this->get_time_stamp(trim($_SESSION['clients']['loginTime'])); ?></small>
                    </p>
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="<?php echo URLClients; ?>profile" class="btn btn-default btn-flat">Profile</a>
                    </div>
                    <div class="pull-right">
                      <a href="<?php echo URLClients; ?>login?logout" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>

        </nav>
      </header>
		<?php }
		
		function sidebar($active) {
			$notification = new notification;
			$default = "mm";
			$size = 35;
			$grav_url = "http://www.gravatar.com/avatar/".md5(strtolower(trim($_SESSION['admin']['name'])))."?d=".$default."&s=".$size; ?>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="<?php echo $grav_url; ?>" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
              <p><?php echo trim($_SESSION['admin']['name']); ?></p>
              <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>
          <!-- search form -->
          <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
              <input type="text" name="q" class="form-control" placeholder="Search..." />
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
          </form>
          <!-- /.search form -->
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li class="<?php if ($active == "home") { ?>active<?php } ?>"><a href="<?php echo URLAdmin; ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            <li class="<?php if ($active == "categories") { ?>active <?php } ?>treeview">
              <a href="<?php echo URLAdmin; ?>categories"><i class="fa fa-table"></i> <span>Categories</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo URLAdmin; ?>categories?add"><i class="fa fa-keyboard-o"></i> <span>Create</span></a></li>
                <li><a href="<?php echo URLAdmin; ?>categories"><i class="fa fa-folder-open"></i> <span>List All</span></a></li>
                <li><a href="<?php echo URLAdmin; ?>categories.priority?id=0"><i class="fa fa-folder-open"></i> <span>Set Priority</span></a></li>
              </ul>
            </li>
            <li class="<?php if ($active == "documents") { ?>active <?php } ?>treeview">
              <a href="<?php echo URLAdmin; ?>documents"><i class="fa fa-book"></i> <span>Documents</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo URLAdmin; ?>documents?add"><i class="fa fa-keyboard-o"></i> <span>Create</span></a></li>
                <li><a href="<?php echo URLAdmin; ?>documents"><i class="fa fa-folder-open"></i> <span>List All</span></a></li>
              </ul>
            </li>
            <li class="<?php if ($active == "library") { ?>active<?php } ?>"><a href="<?php echo URLAdmin; ?>library"><i class="fa fa-list"></i> <span>Law Dictionary</span></a></li>
            <li class="<?php if ($active == "drafting") { ?>active<?php } ?>"><a href="<?php echo URLAdmin; ?>drafting"><i class="fa fa-list"></i> <span>Legal Draftings</span></a></li>
            <li class="<?php if ($active == "list") { ?>active <?php } ?>treeview">
              <a href="<?php echo URLAdmin; ?>list"><i class="fa fa-list"></i> <span>Listings</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo URLAdmin; ?>list?courts"><i class="fa fa-keyboard-o"></i> <span>Courts</span></a></li>
                <li><a href="<?php echo URLAdmin; ?>list?judges"><i class="fa fa-keyboard-o"></i> <span>Judges</span></a></li>
                <li><a href="<?php echo URLAdmin; ?>list?sans"><i class="fa fa-keyboard-o"></i> <span>SANs</span></a></li>
                <li><a href="<?php echo URLAdmin; ?>CaseLaw"><i class="fa fa-keyboard-o"></i> <span>Case Law</span></a></li>
                <li><a href="<?php echo URLAdmin; ?>articles"><i class="fa fa-keyboard-o"></i> <span>Articles and Journals</span></a></li>
                <li><a href="<?php echo URLAdmin; ?>regulations"><i class="fa fa-keyboard-o"></i> <span>Circulars and Regulations</span></a></li>
                <li><a href="<?php echo URLAdmin; ?>regulations.create"><i class="fa fa-keyboard-o"></i> <span>Create Regulators</span></a></li>
                <li><a href="<?php echo URLAdmin; ?>CaseLaw.subject"><i class="fa fa-keyboard-o"></i> <span>Create Areas of Laws</span></a></li>
              </ul>
            </li>
            <li class="<?php if ($active == "forum") { ?>active <?php } ?>treeview">
              <a href="#">
                <i class="fa fa-cog"></i>
                <span>Forum</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo URLAdmin; ?>forum.category?new"><i class="fa fa-user-plus"></i> <span>Categories</span></a></li>
                <li><a href="<?php echo URLAdmin; ?>forum.topic"><i class="fa fa-user-plus"></i> <span>Topic</span></a></li>
                <li><a href="<?php echo URLAdmin; ?>forum.post"><i class="fa fa-user-plus"></i> <span>Post</span></a></li>
                <li><a href="<?php echo URLAdmin; ?>forum.users"><i class="fa fa-user-plus"></i> <span>Users</span></a></li>
              </ul>
            </li>
            <li class="<?php if ($active == "help") { ?>active <?php } ?>treeview">
              <a href="#">
                <i class="fa fa-cog"></i>
                <span>Help and Support</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo URLAdmin; ?>help?new"><i class="fa fa-user-plus"></i> <span>New Tickets</span></a></li>
                <li><a href="<?php echo URLAdmin; ?>help?open"><i class="fa fa-user-plus"></i> <span>Open Tickets</span></a></li>
                <li><a href="<?php echo URLAdmin; ?>help?closed"><i class="fa fa-user-plus"></i> <span>Closed Tickets</span></a></li>
                <li><a href="<?php echo URLAdmin; ?>help"><i class="fa fa-user-plus"></i> <span>All Ticket</span></a></li>
                <li><a href="<?php echo URLAdmin; ?>help.faq"><i class="fa fa-user-plus"></i> <span>FAQs</span></a></li>
                <li><a href="<?php echo URLAdmin; ?>knowledgeBase"><i class="fa fa-user-plus"></i> <span>Knowledge Base</span></a></li>
                <li><a href="<?php echo URLAdmin; ?>knowledgeBase.categories"><i class="fa fa-user-plus"></i> <span>Help and Support Categories</span></a></li>
              </ul>
            </li>
            <li class="<?php if ($active == "settings") { ?>active <?php } ?>treeview">
              <a href="#">
                <i class="fa fa-cog"></i>
                <span>Settings</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo URLAdmin; ?>advert"><i class="fa fa-cog"></i> <span>Manage Advert</span></a></li>
                <li><a href="<?php echo URLAdmin; ?>news"><i class="fa fa-cog"></i> <span>Manage News Ticker</span></a></li>
                <li><a href="<?php echo URLAdmin; ?>pages"><i class="fa fa-cog"></i> <span>Manage Pages</span></a></li>
                <li><a href="<?php echo URLAdmin; ?>slider"><i class="fa fa-cog"></i> <span>Manage Slider</span></a></li>
                <li><a href="<?php echo URLAdmin; ?>settings"><i class="fa fa-cog"></i> <span>Manage Other Settings</span></a></li>
                <li><a href="<?php echo URLAdmin; ?>administrators?new"><i class="fa fa-user-plus"></i> <span>Create Administrators</span></a></li>
                <li><a href="<?php echo URLAdmin; ?>administrators"><i class="fa fa-user-plus"></i> <span>List Administrators</span></a></li>
                <li><a href="<?php echo URLAdmin; ?>clients"><i class="fa fa-user-plus"></i> <span>Manage Document Owners</span></a></li>
                <li><a href="<?php echo URLAdmin; ?>administrator.right"><i class="fa fa-folder-open"></i> <span>Administrator Right</span></a></li>
                <li><a href="<?php echo URLAdmin; ?>subscriptions"><i class="fa fa-calendar"></i> <span>Subscriptions</span></a></li>
                <li><a href="<?php echo URLAdmin; ?>subscriptions.volume"><i class="fa fa-calendar"></i> <span>Volume Subscription Setup</span></a></li>
              </ul>
            </li>
            <li class="<?php if ($active == "report") { ?>active <?php } ?>treeview">
              <a href="#">
                <i class="fa fa-files-o"></i>
                <span>Reports</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?php echo URLAdmin; ?>CaseLaw.view"><i class="fa fa-users"></i> <span>Document View Reports</span></a></li>
                <li><a href="<?php echo URLAdmin; ?>users"><i class="fa fa-users"></i> <span>Users</span></a></li>
                <li><a href="<?php echo URLAdmin; ?>subscriptionReport"><i class="fa fa-cart-plus"></i>Subscriptions</a></li>
                <li><a href="<?php echo URLAdmin; ?>order"><i class="fa fa-cart-plus"></i>Orders</a></li>
                <li><a href="<?php echo URLAdmin; ?>transactions"><i class="fa fa-cart-plus"></i>Transactions</a></li>
                <li><a href="<?php echo URLAdmin; ?>devices"><i class="fa fa-cloud"></i>Online Users and Devices</a></li>
                <li><a href="<?php echo URLAdmin; ?>visitors"><i class="fa fa-cloud"></i>Visitors' Log</a></li>
                <li><a href="<?php echo URLAdmin; ?>system"><i class="fa fa-database"></i> System Log</a></li>
              </ul>
            </li>
            <li class="<?php if ($active == "notifications") { ?>active<?php } ?>"><a href="<?php echo URLAdmin; ?>notifications"><i class="fa fa-table"></i> <span>Notifications</span>
                <span class="label label-primary pull-right"><?php echo $notification->notificationCount(); ?></span></a></li>
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>
		<?php }
		
		function sidebarClient($active) {
			$default = "mm";
			$size = 35;
			$grav_url = "http://www.gravatar.com/avatar/".md5(strtolower(trim($_SESSION['admin']['name'])))."?d=".$default."&s=".$size; ?>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="<?php echo $grav_url; ?>" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
              <p><?php echo trim($_SESSION['clients']['name']); ?></p>
              <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>
          <!-- search form -->
          <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
              <input type="text" name="q" class="form-control" placeholder="Search..." />
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
          </form>
          <!-- /.search form -->
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li class="<?php if ($active == "home") { ?>active<?php } ?>"><a href="<?php echo URLAdmin; ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            <li class="<?php if ($active == "notifications") { ?>active<?php } ?>"><a href="<?php echo URLAdmin; ?>notifications"><i class="fa fa-table"></i> <span>Notifications</span>
                <span class="label label-primary pull-right">4</span></a></li>
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>
		<?php }
	}
?>