<?php
	if (!defined('REQ')) define('REQ','ok');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>31337</title>

	<!-- non-retina iPhone pre iOS 7 -->
	<link rel="apple-touch-icon" href="<?php echo $cfg['options']['siteurl']; ?>/design/img/icons/icon57.png" sizes="57x57">
	<!-- non-retina iPad pre iOS 7 -->
	<link rel="apple-touch-icon" href="<?php echo $cfg['options']['siteurl']; ?>/design/img/icons/icon72.png" sizes="72x72">
	<!-- non-retina iPad iOS 7 -->
	<link rel="apple-touch-icon" href="<?php echo $cfg['options']['siteurl']; ?>/design/img/icons/icon76.png" sizes="76x76">
	<!-- retina iPhone pre iOS 7 -->
	<link rel="apple-touch-icon" href="<?php echo $cfg['options']['siteurl']; ?>/design/img/icons/icon114.png" sizes="114x114">
	<!-- retina iPhone iOS 7 -->
	<link rel="apple-touch-icon" href="<?php echo $cfg['options']['siteurl']; ?>/design/img/icons/icon120.png" sizes="120x120">
	<!-- retina iPad pre iOS 7 -->
	<link rel="apple-touch-icon" href="<?php echo $cfg['options']['siteurl']; ?>/design/img/icons/icon144.png" sizes="144x144">
	<!-- retina iPad iOS 7 -->
	<link rel="apple-touch-icon" href="<?php echo $cfg['options']['siteurl']; ?>/design/img/icons/icon152.png" sizes="152x152">
	<!-- Win8 tile -->
	<meta name="msapplication-TileImage" content="<?php echo $cfg['options']['siteurl']; ?>/design/img/icons/favicon-144.png">
	<meta name="msapplication-TileColor" content="#B20099"/>
	<meta name="application-name" content="name" />
	
	<!-- IE11 tiles -->
	<meta name="msapplication-square70x70logo" content="<?php echo $cfg['options']['siteurl']; ?>/design/img/icons/tile-tiny.png"/>
	<meta name="msapplication-square150x150logo" content="<?php echo $cfg['options']['siteurl']; ?>/design/img/icons/tile-square.png"/>
	<meta name="msapplication-wide310x150logo" content="<?php echo $cfg['options']['siteurl']; ?>/design/img/icons/tile-wide.png"/>
	<meta name="msapplication-square310x310logo" content="<?php echo $cfg['options']['siteurl']; ?>/design/img/icons/tile-large.png"/>
	
	<link rel="icon" href="<?php echo $cfg['options']['siteurl']; ?>/design/img/icons/icon32.png">


	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link href="/design/css/bootstrap.min.css" rel="stylesheet" />
	<script src="/design/js/jquery-2.0.0.min.js"></script>
	<script src="/design/js/bootstrap.js"></script>

	<link href="/design/css/font-awesome.min.css" rel="stylesheet" />
	<link href="/design/css/animated.css" rel="stylesheet" />
	<link href="/design/css/login.fonts.css" rel="stylesheet" />
	<link href="/design/css/login.styles.css" rel="stylesheet" />
	<link href="/design/css/toastr.css" rel="stylesheet" />
	<link href="/design/css/toastr-responsive.css" rel="stylesheet" />

	
	<!-- 	для чекбоксов -->
	<script src="/design/js/jquery.icheck.js"></script>

	<!--	для нотифая -->
	<script src="/design/js/toastr.js"></script>

	<!-- 	для placeholred у inputов -->
	<script src="/design/js/placeholders.min.js"></script>
	
	<script src="/design/js/modernizr.js"></script>
	
	<!-- 	to remember sound status -->
	<script src="/design/js/jquery.cookie.js"></script>
	

</head>
<body>
   
	<div id="authtip" style="display:none;"><h1><?php ln('wrong_login_or_password'); ?></h1></div>

	<audio preload style="position:fixed;z-index:2000;" id="music">
		<source src="/design/snd/sndc.mp3" type="audio/mpeg">
	</audio>

	<div id="mute">
		<div style="position:relative;">
			<span class="fa fa-volume-up fa-lg"></span><span class="fa fa-volume-off fa-lg"></span>
		</div>
	</div>


	<section class="eternity-form colorBg dark" id="banPanel">
		<div class=" container">
			<div class="login-form-section">
				<form>
					<div class="section-title reg-header " data-animation="fadeInDown">
						<h3><?php ln('ban_title'); ?></h3>
						<p><?php ln('ban_text'); ?></p>
						<p><?php ln('ban_date'); ?><div class="text-right"> <?php ln('months',date("m",$user['ban_time'])-1); echo " ".date("d Y H:i", $user['ban_time']); ?></div></p>
						<div class="text-left clearfix">
							<a href="<?php echo $cfg['options']['siteurl']; ?>/?exit=1" class="blue cursor-pointer"><?php ln('logout_btn'); ?><i class="fa fa-chevron-right"></i></a>
						</div>
					</div>
				</form>
			</div>



		</div>
	</section>



	<div id="bg_overlay"></div>

	<script type="text/javascript" src="/design/js/retina.min.js"></script>
	<script type="text/javascript">
		// текущая панель
		// activePanel смотрим куда наш хеш ведет
		if (location.hash.replace("#","")) var activePanel = location.hash.replace("#",""); else var activePanel = 'banPanel';


		// скрываем панель панели
		function testAnim(id, x, closureFunc) {
			$('#'+id).addClass(x + ' animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
				$(this).removeClass();
				$(this).addClass('eternity-form colorBg dark');
				$(this).hide();
				closureFunc();
			});
		};

		// вклюаем - отключаем линки перехода
		function toggleLinks() {
			$('body').find('.show-panel-link').each(function(){ $(this).toggleClass('dis'); });
		}
		
		function panelLoad(panel) {
			toggleLinks();
			// меняем хеш у страницы (только новые браузеры)
			history.pushState({foo: panel},panel,'/#'+panel);
			var oldPanel = activePanel;
			activePanel = panel;
			$('#'+panel).stop(true, true);
			testAnim(oldPanel, 'fadeOutUp', function(){
				// после скрытия панели показываем новую
				$('#'+panel).show().one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
					$('#'+panel).find('input')[0].focus();
					toggleLinks();
				});
			});
		}

		function notify(shortCutFunction,title,msg) {
			// success  info  warning  error
			toastr.options = {
				title: title,
				fadeIn: 300,
				fadeOut: 1000,
				timeOut: 5000,
				extendedTimeOut: 1000,
				debug: false,
				positionClass: 'toast-top-right',
				onclick: function(){ alert(1); }
			};
			
			var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
			
		}
		

		// проверяем логин с паролем
		function checkAuth() {
			if ($('#loginPanel [name=login_name]').val()==='') { return false; }
			if ($('#loginPanel [name=login_password]').val()==='') { return false; }
			$.ajax({
				type: "POST",
				dataType: "JSON",
				url: "<?php echo $cfg['options']['siteurl']; ?>",
				data: {
					act: 'login',
					login: $('#loginPanel [name=login_name]').val(),
					password: $('#loginPanel [name=login_password]').val(),
					remember: $('#loginPanel [name=login_remember]').parent().hasClass('checked')
				},
				success: function(msg){
					if (msg.error===1) {
						notify('error',msg.title,msg.text);
						$('#loginPanel [name=login_password]').val('');
						$('#loginPanel [name=login_password]').focus();
					} else {
						$('#loginPanel').fadeOut(function(){
							document.location.href='<?php echo $cfg['options']['siteurl']; ?>'; // если делать reload() то может остаться хеш
						});
						
					} 
				},
				error: function (jqXHR, textStatus, errorThrown) {
					alert(jqXHR+' '+textStatus+' '+errorThrown);
				}
			});
		}
		
		// при загрузке
		
		$(function () {

			// если большой экран это не мобильник
			if ($(window).width() >= 968 && !Modernizr.touch && Modernizr.cssanimations) {
				// отступ
				$('.eternity-form').css({'padding-top': '50px'});
				
				// если не хочет слушать
				if ($.cookie('mute_music')==='false') {
					$('#mute').show();
					var audioIsPlay = true;
					$('#mute .fa-volume-up').show();
					$('#mute .fa-volume-off').hide();
					$('#music').prop({'volume': 0});
					document.getElementById('music').play();
					$('#music').animate({volume: 1}, 5000); // потихоньку
				} 

				// если первый раз или звук включен
				if ($.cookie('mute_music')==='true' || $.cookie('mute_music')===undefined) {
					$('#mute').show();
					$('#mute .fa-volume-up').hide();
					$('#mute .fa-volume-off').show();
				}
			};

			// делаем красивые инпуты
			$(".dark input[type=checkbox]").iCheck({
				checkboxClass: 'icheckbox_polaris',
				increaseArea: '20%' // optional
			});


			// задаем параметры анимации
			$('[data-animation-delay]').each(function () {
				var animationDelay = $(this).data("animation-delay");
				$(this).css({
					"-webkit-animation-delay": animationDelay,
					"-moz-animation-delay": animationDelay,
					"-o-animation-delay": animationDelay,
					"-ms-animation-delay": animationDelay,
					"animation-delay": animationDelay
				});

			});

			// задаем параметры анимации
			$('[data-animation-duration]').each(function () {
				var animationDuration = $(this).data("animation-duration");
				$(this).css({
					"-webkit-animation-duration": animationDuration,
					"-moz-animation-duration": animationDuration,
					"-o-animation-duration": animationDuration,
					"-ms-animation-duration": animationDuration,
					"animation-duration": animationDuration
				});

			});


			// при первом входе показываем нужную панель
			$('#'+activePanel).css({'display':'block'});
			$('#'+activePanel).show().one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
				$('#'+activePanel).find('input')[0].focus();
				toggleLinks();
			});


			// при клике по ссылке смены панели
			$('.show-panel-link').click(function(){
				if ($(this).hasClass('dis')) { return };
				panelLoad($(this).data('panel'));
			});


			// показываем красивую палочку слева при фокусе на поле
			$(".form-control").focus(function () {
				$(this).closest(".textbox-wrap").addClass("focused");
			}).blur(function () {
				$(this).closest(".textbox-wrap").removeClass("focused");
			});


			// Анимируем вылет форм если не на мобильнике
			if ($(window).width() >= 968 && !Modernizr.touch && Modernizr.cssanimations) {
				$('[data-animation]').each(function() {
					$(this).addClass("animated " + $(this).data("animation"));
				});
			}


			// управляем звуком
			$('#mute').click(function(){
				if (audioIsPlay==true) {
					$.cookie('mute_music', 'true', { expires: 365, path: '/' });
					$('#mute .fa-volume-off').fadeIn(3000);
					$('#mute .fa-volume-up').fadeOut(3000);
					$('#music').animate({volume: 0}, 3000, function(){
						document.getElementById('music').pause();
					});
					
					audioIsPlay = false;
				} else {
					$.cookie('mute_music', 'false', { expires: 365, path: '/' });
					$('#mute .fa-volume-off').fadeOut(5000);
					$('#mute .fa-volume-up').fadeIn(5000);
					$('#music').prop({'volume': 0});
					document.getElementById('music').play();
					$('#music').animate({volume: 1}, 5000);
					audioIsPlay = true;
				}
			});

		
			// выключаем автокомплит на всех полях
			$('input').attr('autocomplete','off');
			
			// если нажали enter то проверяем логин и пароль
			$('#password').bind('keyup',function(e){
				if (e.keyCode==13) checkAuth();
			});

			// фокус на логине
			$('#login').focus();

		});		
	</script>

</body>
</html>
