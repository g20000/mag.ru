$(document).ready(function(){
	<!--==============================================-->
	$('#slides').slidesjs({
		width: 1000,
		height: 472,
		play: {
			auto: true,
			interval: 4000,
			effect: "fade"
		},
        pagination: {
          effect: "fade"
        },
		effect: {
			slide: {
				speed: 1200
			},
      fade: {
        speed: 500
      }
		}
	});
	<!--==============================================-->
	$(".slidesjs-pagination").appendTo(".slid-nav");
	$(".slid-nav").appendTo(".slidesjs-container");
	<!--==============================================-->
	$(".catalog .tabs-wrapp .item").filter(":nth-child(2n+2)").after('<div class="clear"> </div>');
	<!--==============================================-->
	$('.lemail').change(function() {
    	var textg = $('.lemail').val();
		$('#remail').val(textg);
    });
	$('.auth-div .reg').click(function(){
        var mode;
        if($('.auth-div').hasClass('auth-mode'))
        {
            $('.auth-div').removeClass('reg-mode auth-mode');
            $('.auth-div').addClass('reg-mode');
            $('.auth-div .auth-form').fadeOut(300,function(){
                $('.auth-div').stop().animate({
                    height: '354px'
                },300,function(){
                    $('.auth-div .reg-form').fadeIn(300);
                    $('.auth-div').css('overflow','visible');
                });
            });
        }
        else if($('.auth-div').hasClass('reg-mode'))
        {
            $('.auth-div').removeClass('reg-mode auth-mode');

            $('.auth-div .reg-form').fadeOut(300,function(){
                $('.auth-div').stop().animate({
                    height: '34px'
                },300,function(){
                    $('.auth-div').css('overflow','visible');
                });
            });
        }
        else
        {
            $('.auth-div').removeClass('reg-mode auth-mode');
            $('.auth-div').addClass('reg-mode');
            $('.auth-div').stop().animate({
                height: '354px'
            },300,function(){
                $('.auth-div .reg-form').fadeIn(300);
                $('.auth-div').css('overflow','visible');
            });
        }
    });

    $('.auth-div .auth').click(function(){
        var mode;
        if($('.auth-div').hasClass('reg-mode'))
        {
            $('.auth-div').removeClass('reg-mode auth-mode');
            $('.auth-div').addClass('auth-mode');
            $('.auth-div .reg-form').fadeOut(300,function(){
                $('.auth-div').stop().animate({
                    height: '205px'
                },300,function(){
                    $('.auth-div .auth-form').fadeIn(300);
                });
            });
        }
        else if($('.auth-div').hasClass('auth-mode'))
        {
            $('.auth-div').removeClass('reg-mode auth-mode');

            $('.auth-div .auth-form').fadeOut(300,function(){
                $('.auth-div').stop().animate({
                    height: '34px'
                },300,function(){
                    $('.auth-div').css('overflow','visible');
                });
            });
        }
        else
        {
            $('.auth-div').removeClass('reg-mode auth-mode');
            $('.auth-div').addClass('auth-mode');
            $('.auth-div').stop().animate({
                height: '205px'
            },300,function(){
                $('.auth-div .auth-form').fadeIn(300);
            });
        }
    });
});