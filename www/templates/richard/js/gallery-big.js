$(function(){
    var slide_num = 0;
    var total = $(".gallery-tab .gallery-carousel > div").each(function(){
        $(this).data('slide-num',++slide_num);
    }).size();
    $('.gallery-tab .counter').text(1+'/'+total);
    $(".gallery-tab .gallery-carousel").carouFredSel({
        circular: false,
        infinite: false,
        width: 934,
        align: false,
        height: 640,
        items: {
            visible: 1,
            width: 934,
            height: 640
        },
        scroll: {
            fx: "crossfade",
            duration: 800,
            onBefore: function(a){
                var next = $(a.items.visible[0]);
                $('.gallery-tab .info .name').stop().fadeOut(380,function(){
                    $('.gallery-tab .counter').text(next.data('slide-num')+'/'+total);
                    $(this).text(next.data('text')).fadeIn(380);
                });
                $('.gallery-tab .info .price').stop().fadeOut(380,function(){
                    $(this).text(next.data('price')).fadeIn(380);
                });
            }
        },
        onCreate: function(a){
            a = $(a.items[0]);
            $('.gallery-tab .info .name').text(a.data('text'));
            $('.gallery-tab .info .price').text(a.data('price'));
        },
        prev: ".gallery-tab .arrow-left",
        next: ".gallery-tab .arrow-right",
        auto: false
    });
});