<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" href="<?php echo $cfg['options']['siteurl'];?>/design/img/icons/icon32.png">

		<title>dPanel</title>

		<script type='text/javascript' src="<?php echo $cfg['options']['siteurl'];?>/design/js/jquery-2.1.4.min.js"></script>
		
		<script type='text/javascript' src="<?php echo $cfg['options']['siteurl'];?>/design/js/jquery_confirm.js"></script>
		
		<!-- Bootstrap core CSS -->
		<link href="<?php echo $cfg['options']['siteurl'];?>/design/css/bootstrap.slate.min.css" rel="stylesheet">

		<link href="<?php echo $cfg['options']['siteurl'];?>/design/css/font-awesome.min.css" rel="stylesheet">
		
		<link href="<?php echo $cfg['options']['siteurl'];?>/design/css/jquery_confirm.css" rel="stylesheet">

		<!-- Custom styles for this template -->
		<link href="<?php echo $cfg['options']['siteurl'];?>/design/css/dashboard.css" rel="stylesheet">

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		  <script src="<?php echo $cfg['options']['siteurl'];?>/design/js/html5shiv.min.js"></script>
		  <script src="<?php echo $cfg['options']['siteurl'];?>/design/js/respond.min.js"></script>
		<![endif]-->

		<link rel="stylesheet" href="<?php echo $cfg['options']['siteurl'];?>/design/css/animated.css"/>    

		<link href="<?php echo $cfg['options']['siteurl'];?>/design/css/el-custom.css" rel="stylesheet">
		
		<!--	для нотифая -->
		<link rel="stylesheet" href="<?php echo $cfg['options']['siteurl'];?>/design/css/toastr.min.css"/>
		<link rel="stylesheet" href="<?php echo $cfg['options']['siteurl'];?>/design/css/toastr-responsive.css"/>

		<link href="<?php echo $cfg['options']['siteurl'];?>/design/css/jquery-ui.css" rel="stylesheet">
		
		<script>
			<?php
				//echo $_COOKIE[$cfg["cookiename"]];
				echo isset($_page_head_scripts) ? $_page_head_scripts : '';
			?>
		</script>		
	</head>

	<body>
		<?php
		if ($user['rankname']=='drop') {
			//include($cfg['realpath'].'/gears/dropCheckNewPkg.include.php');
		} elseif($user['rankname']=='labler') {
			//include($cfg['realpath'].'/gears/lablerCheckNewPkg.include.php');
		} elseif($user['rankname']=='admin') {
			//include($cfg['realpath'].'/gears/adminCheckNewPkg.include.php');
		}
		?>

		

		
		<div id="el-sidebar-nav"></div>
		<div id="el-sidebar-wrapper">


		<div class="navbar navbar-default navbar-fixed-top" role="navigation">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Навигация</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav">
						<li class="phone_top_menu">+7 (999) 777-55-55</li>
						<?php getNavMenu(); ?>
					</ul>
					<form class="navbar-form navbar-right" action="<?php echo $cfg['options']['siteurl']; ?>/trackSearch" method="POST">
						<input type="text" name="s" class="form-control input-sm search_input" placeholder="Поиск...">&nbsp;&nbsp;
					</form>
				</div>
			</div>
		</div>

		<div class="container-fluid">
			<div class="row">
				
				<div class="pull-right" style="margin:1em;z-index: 1000;position: relative;cursor: pointer;"><a class=" el-sidebar-btn visible-xs"><span class="fa fa-chevron-right fa-2x"></span></a></div>
				
				<div class="col-sm-3 col-md-2 sidebar hidden-xs" id="sidebar">
					<div class="text-right" style="margin:1em;cursor: pointer;"><a class="visible-xs el-sidebar-btn"><span class="fa fa-chevron-left fa-2x"></span></a></div>
					<?php getSideMenu(); ?>
				</div>
				<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
					
					
										
					<?php
						echo $mainHTML;
					?>					
					



				</div>
			</div>
		</div>

	</div>
	<!-- /el-sidebar-wrapper -->				
				
		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->

		<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
		<script type='text/javascript' src="<?php echo $cfg['options']['siteurl'];?>/design/js/ie10-viewport-bug-workaround.js"></script>
		
		<!--	для нотифая -->
		<script type='text/javascript' src="<?php echo $cfg['options']['siteurl'];?>/design/js/toastr.js"></script>		
		
		<script type='text/javascript' src='<?php echo $cfg['options']['siteurl']; ?>/design/js/el-offsidebar.js?ver=0.0.1'></script>

		<script type='text/javascript' src="<?php echo $cfg['options']['siteurl'];?>/design/js/scripts.js"></script>		

		<script src="<?php echo $cfg['options']['siteurl'];?>/design/js/jquery-ui.min.js"></script>
		<script type='text/javascript' src="<?php echo $cfg['options']['siteurl'];?>/design/js/bootstrap.min.js"></script>	
		<!-- 	для чекбоксов -->
		<script src="<?php echo $cfg['options']['siteurl'];?>/design/js/jquery.icheck.min.js"></script>
		
		<!-- 	для placeholred у inputов -->
		<script src="<?php echo $cfg['options']['siteurl'];?>/design/js/placeholders.min.js"></script>		

		<!--	для загрузки	-->
		<script type="text/javascript" src="<?php echo $cfg['options']['siteurl'] ?>/design/js/jquery.damnUploader.js"></script>
		<script type="text/javascript" src="<?php echo $cfg['options']['siteurl'] ?>/design/js/uploader-interafce.js"></script>
		<script type="text/javascript" src="<?php echo $cfg['options']['siteurl'] ?>/design/js/uploader-setup.js"></script>

		
		<?php
			if (isset($scriptsNeedToLoading) && !empty($scriptsNeedToLoading)) {
				foreach($scriptsNeedToLoading as $jsURL) {
					echo '<script src="'.$jsURL.'"></script>'."\n";
				}
			}
 		?>		
		
		<script>
			<?php 
				echo isset($_page_scripts) ? $_page_scripts : '';
			?>
		</script>
		

		
	</body>
</html>
