<h1 class="page-header">dPanel</h1>


	<div class="row" style="font-size:1.2em;font-weight:200;">
		<div class="col-xs-12 col-sm-3">
			<object type="image/svg+xml" data="<?php echo $cfg['options']['siteurl'] ?>/design/img/w_screen.svg" width="100%">Your browser does not support SVG</object>
		</div>
		<div class="col-xs-12 col-sm-9">
			<p>Добро пожаловать в "dPanel" - СРМ.</p>
			<p>Если Вы работаете на компьютере то сверху и слева будет меню с доступными страницами.</p>
			<p>Если вы пользуетесь смартфоном то меню вызывается путем нажатия на иконку сверху.</p>
			<p>Ниже есть ссылки быстрого доступа.</p>
			<ul class="list-unstyled">
				<?php getNavMenu(); ?>
			</ul>
			<p>Воспользуйтесь системой тикетов для поддержки <a href="<?php echo $cfg['options']['siteurl']; ?>/newchat/<?php echo getAdminId(); ?>/new"><i class="fa fa-envelope"></i> ТУТ</a>.</p>
			
		</div>
	</div>
