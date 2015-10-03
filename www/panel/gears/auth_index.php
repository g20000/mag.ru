<?php
	if (!defined('REQ')) define('REQ','ok');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>dPanel</title>

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


<!-- 	<meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
	<meta name="viewport" content="width=device-width, user-scalable=no" />
	<meta name="apple-mobile-web-app-capable" content="yes" />


	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link href="<?php echo $cfg['options']['siteurl'];?>/design/css/bootstrap.min.css" rel="stylesheet" />
	<script src="<?php echo $cfg['options']['siteurl'];?>/design/js/jquery-1.11.1.min.js"></script>
	<script src="<?php echo $cfg['options']['siteurl'];?>/design/js/bootstrap.min.js"></script>

	<link href="<?php echo $cfg['options']['siteurl'];?>/design/css/font-awesome.min.css" rel="stylesheet" />
	<link href="<?php echo $cfg['options']['siteurl'];?>/design/css/animated.css" rel="stylesheet" />
	<link href="<?php echo $cfg['options']['siteurl'];?>/design/css/login.fonts.css" rel="stylesheet" />
	<link href="<?php echo $cfg['options']['siteurl'];?>/design/css/login.styles.css" rel="stylesheet" />
	<link href="<?php echo $cfg['options']['siteurl'];?>/design/css/toastr.min.css" rel="stylesheet" />
	<link href="<?php echo $cfg['options']['siteurl'];?>/design/css/toastr-responsive.css" rel="stylesheet" />


	<!-- для слайдера -->
	<link href="<?php echo $cfg['options']['siteurl'];?>/design/css/slider.css" rel="stylesheet" />
	<link href="<?php echo $cfg['options']['siteurl'];?>/design/css/jquery-ui.css" rel="stylesheet" />

	
	<!-- 	для чекбоксов -->
	<script src="<?php echo $cfg['options']['siteurl'];?>/design/js/jquery.icheck.min.js"></script>

	<!--	для нотифая -->
	<script src="<?php echo $cfg['options']['siteurl'];?>/design/js/toastr.js"></script>

	<!-- 	для placeholred у inputов -->
	<script src="<?php echo $cfg['options']['siteurl'];?>/design/js/placeholders.min.js"></script>
	
	<script src="<?php echo $cfg['options']['siteurl'];?>/design/js/modernizr.js"></script>
	
	<!-- 	to remember sound status -->
	<script src="<?php echo $cfg['options']['siteurl'];?>/design/js/jquery.cookie.js"></script>
	

</head>
<body>
   
	<div id="authtip" style="display:none;"><h1><?php ln('wrong_login_or_password'); ?></h1></div>


	<section class="eternity-form dark colorBg" data-panel="fourth" id="loginPanel" style="display:none;">
		<div class=" container">

			<div class="login-form-section">
				<div class="login-content " data-animation="fadeInDown" data-animation-delay="0.3s" data-animation-duration="1.5s">
					<form onsubmit="return false;">
						<div class="section-title">
							<div class="text-center"><a href="<?php echo $cfg['options']['siteurl']; ?>"><img src="<?php echo $cfg['options']['siteurl'];?>/design/img/triangle.png" id="logo" srcset="/design/img/triangle@2x.png 2x" data-at2x="/design/img/triangle@2x.png"></a></div>
							<h3 class="text-center" style="text-transform:uppercase">dPanel</h3>
							
						</div>
						<div class="textbox-wrap focused">
							<div class="input-group">
								<span class="input-group-addon "><i class="fa fa-user icon-color"></i></span>
								<input type="text" required="required" class="form-control" name="login_name" placeholder="username" autofocus />
							</div>
						</div>
						<div class="textbox-wrap">
							<div class="input-group">
								<span class="input-group-addon "><i class="fa fa-key icon-color"></i></span>
								<input type="password" required="required" class="form-control" name="login_password" placeholder="пароль" />
							</div>
						</div>
						<div class="login-form-action clearfix">
							<div class="checkbox pull-left">
								<div class="custom-checkbox">
									<input type="checkbox" checked name="login_remember" id="login_remember">
								</div>
								<label for="login_remember"><span class="checkbox-text pull-left">&nbsp;Запомнить меня</span></label>
							</div>
							<button class="btn btn-success pull-right green-btn" onclick="checkAuth();">Войти &nbsp; <i class="fa fa-chevron-right"></i></button>
							
						</div>
						
						<div id="iphone-scrollcontainer">
							<div id="iphone-inside">

								<div id="unlock-bottom">
									<div id="slide-to-unlock">Slide to login</div>
									<div id="unlock-slider-wrapper">
										<div id="unlock-slider"></div>
									</div>
								</div>
							</div>
						</div>
						
					</form>
				</div>

			</div>



		</div>
	</section>



	<script type="text/javascript" src="<?php echo $cfg['options']['siteurl'];?>/design/js/retina.min.js"></script>


	<script type="text/javascript" src="<?php echo $cfg['options']['siteurl'];?>/design/js/jquery-ui.min.js"></script>	
	


	
	<script type="text/javascript">


		// текущая панель
		// activePanel смотрим куда наш хеш ведет
		if (location.hash.replace("#","")) var activePanel = location.hash.replace("#",""); else var activePanel = 'loginPanel';


		// скрываем панель панели
		function testAnim(id, x, closureFunc) {
			$('#'+id).addClass(x + ' animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
				$(this).removeClass();
				$(this).addClass('eternity-form colorBg');
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
			if ($('#loginPanel [name=login_name]').val()=='') { notify('error','Error','Fill Login'); return false; }
			if ($('#loginPanel [name=login_password]').val()=='') { notify('error','Error','Fill Password'); return false; }
			$.ajax({
				type: "POST",
				dataType: "JSON",
				url: "<?php echo $cfg['options']['siteurl']; ?>/index.php",
				data: {
					act: 'login',
					login: $('#loginPanel [name=login_name]').val(),
					password: $('#loginPanel [name=login_password]').val(),
					remember: $('#loginPanel [name=login_remember]').parent().hasClass('checked')
				},
				success: function(msg){
					if (msg.error==1) {
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


			$("#unlock-slider").slider({

				animate:true,
				slide: function(e,ui) {
					$("#slide-to-unlock").css("opacity", 1-(parseInt($(".ui-slider-handle").css("left"))/120));

				},
				stop: function(e,ui) {
					if($(".ui-slider-handle").position().left >= $("#unlock-slider").width()) {
						//document.location.reload();
						$(".ui-slider-handle").animate({left: 0}, 200 );
						$("#slide-to-unlock").animate({opacity: 1}, 200 );
						checkAuth();
					} else {
						$(".ui-slider-handle").animate({left: 0}, 200 );
						$("#slide-to-unlock").animate({opacity: 1}, 200 );
					}
				}
			});


			// если большой экран это не мобильник
			if ($(window).width() >= 968 && !Modernizr.touch && Modernizr.cssanimations) {
				// отступ
				$('.eternity-form').css({'padding-top': '50px'});
			} else {
				toggleLinks();
			};

			// делаем красивые инпуты
			$(".eternity-form input[type=checkbox]").iCheck({
				checkboxClass: 'icheckbox_square-blue',
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
