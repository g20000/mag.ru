
	<div class="row" style="font-size:15x;">
		<div class="col-xs-12 col-sm-9">
			<p>Добро пожаловать в "dPanel" - СРМ.</p>
			<p>Если Вы работаете на компьютере то сверху и слева будет меню с доступными страницами.<br>
			Если вы пользуетесь смартфоном то меню вызывается путем нажатия на иконку сверху.</p>
			<p>Ниже есть ссылки быстрого доступа.</p>
			<ul class="list-unstyled">
				<?php getNavMenu(); ?>
			</ul>
			<p>Воспользуйтесь системой тикетов для поддержки <a href="<?php echo $cfg['options']['siteurl']; ?>/newchat/<?php echo getAdminId(); ?>/new" class="tickets_links">здесь</a></p>
		</div>
	</div>
	<hr class="border-top-news">
	<div class="row">
		<div class="col-xs-12 col-sm-9">
			<h1>Новости</h1>
				<?php echo getNewsForMainPage(); ?>
		</div>
	</div>
