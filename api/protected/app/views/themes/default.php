<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="generator" content="PelitaCMS, Pelita Framework">
    <meta name="author" content="Cahaya Software Solutions">
    <title><?php echo "{$title} | PosBrother"; ?></title>
    <link href="<?php echo base_url('assets/images/favicon.png'); ?>" rel="shortcut icon">
    <?php
    foreach ($css as $file) {
        echo "\n    ";
        echo '<link href="'.$file.'" rel="stylesheet" type="text/css" />';
    } echo "\n";
    ?>

    <script type="text/javascript">
        var base_url = '<?php echo base_url(); ?>';
        var current_url = '<?php echo current_url(); ?>';
    </script>
    <?php
    foreach ($js as $file) {
        echo "\n    ";
        echo '<script src="'.$file.'"></script>';
    } echo "\n";
    ?>
</head>

<body class="navbar-top">
	<div class="navbar navbar-default navbar-fixed-top">
		<div class="navbar-header">
<!-- 			<a class="logo" href="<?php echo site_url(); ?>">
                <img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="">
            </a>
 -->
			<ul class="nav navbar-nav visible-xs-block">
				<li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
				<li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
			</ul>
		</div>

		<div class="navbar-collapse collapse" id="navbar-mobile">
			<ul class="nav navbar-nav">
				<li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a></li>
			</ul>

			<div class="navbar-right">
				<p class="navbar-text">Howdy, <?php echo $this->session->userdata('name'); ?>!</p>
			</div>
		</div>
	</div>

    <div class="page-container">
		<div class="page-content">
			<div class="sidebar sidebar-main">
				<div class="sidebar-content">
					<div class="sidebar-user-material">
						<div class="category-content">
							<div class="sidebar-user-material-content">
								<a href="#" class=""><img src="<?php echo base_url('assets/images/avatar.png'); ?>" class="img-circle img-responsive" alt=""></a>
                                <h6><?php echo $this->session->userdata('name'); ?></h6>
								<span class="text-size-small"><?php echo $this->session->userdata('email'); ?></span>
							</div>
														
							<div class="sidebar-user-material-menu">
								<a href="#user-nav" data-toggle="collapse"><span>My account</span> <i class="caret"></i></a>
							</div>
						</div>
						
						<div class="navigation-wrapper collapse" id="user-nav">
							<ul class="navigation">
								<li><a href="<?php echo site_url('auth/logout'); ?>"><i class="icon-user"></i> <span>My profile</span></a></li>
								<li class="divider"></li>
								<li><a href="<?php echo site_url('auth/logout'); ?>"><i class="icon-switch2"></i> <span>Logout</span></a></li>
							</ul>
						</div>
					</div>

                    <div class="sidebar-category sidebar-category-visible">
						<div class="category-content no-padding">
							<?php include 'default/navigation.php'; ?>
						</div>
					</div>
				</div>
			</div>

            <div class="content-wrapper">
                <div id="content">
                    <?php //echo $output; ?>
                    <ng-view></ng-view>
                </div>
                <div class="ajax-loader">
                    <div class="ajax-content-loader">
                        <i class="icon-spinner10 spinner"></i>
                        <span class="text-semibold display-block">Loading</span>
                    </div>
                </div>
			</div>
		</div>
	</div>
</body>
</html>