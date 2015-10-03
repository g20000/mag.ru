function initialize() {
    var myLatlngCentr = new google.maps.LatLng(57.59653533,51.38485408);
    var myLatlng1 = new google.maps.LatLng(55.7712970,37.6223995);
    var myLatlng2 = new google.maps.LatLng(59.938206,30.324035);
    var myLatlng3 = new google.maps.LatLng(55.0522212,82.9156158);
    var mapOptions = {
        zoom: 4,
        center: myLatlngCentr
    };
    var stylez = [
    {
      featureType: "all",
      elementType: "all",
      stylers: [
        { saturation: -100 } // <-- THIS
      ]
    }
];
    var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
    
    var mapType = new google.maps.StyledMapType(stylez, { name:"Grayscale" });    
map.mapTypes.set('tehgrayz', mapType);
map.setMapTypeId('tehgrayz');
//====================================================================
    var marker1 = new google.maps.Marker({
        position: myLatlng1,
        map: map,
        title: 'Richard Hampton Москва'
    });
    google.maps.event.addListener(marker1, 'click', function() {
        infowindow1.open(map,marker1);
    });
    var contentString1 = '<div id="content2">'+
    '<div id="siteNotice">'+
    '</div>'+
    '<h1 id="firstHeading" class="firstHeading" style="line-height: 29px;">Richard Hampton<br/> Москва</h1>'+
    '<div id="bodyContent">'+
        '<div class=\"adres\">127051, Москва, Цветной бульвар 26</div>'+
        '<strong>+7 (495) 508-01-15</strong>'+
    '</div>'+
    '</div>';
    var infowindow1 = new google.maps.InfoWindow({
        content: contentString1
    });
//====================================================================
    var marker2 = new google.maps.Marker({
        position: myLatlng2,
        map: map,
        title: 'Richard Hampton Санкт-Петербург'
    });
    google.maps.event.addListener(marker2, 'click', function() {
        infowindow2.open(map,marker2);
    });
    var contentString2 = '<div id="content2">'+
        '<div id="siteNotice">'+
        '</div>'+
        '<h1 id="firstHeading" class="firstHeading" style="line-height: 29px;">Richard Hampton <br>Санкт-Петербург</h1>'+
        '<div id="bodyContent">'+
        '<div class=\"adres\">Санкт-Петербург, <br> ул. Большая Конюшенная, д. 6</div>'+
        '<strong>+7 (812) 922-73-06</strong>'+
        '</div>'+
        '</div>';
    var infowindow2 = new google.maps.InfoWindow({
        content: contentString2
    });
//====================================================================
    var marker3 = new google.maps.Marker({
        position: myLatlng3,
        map: map,
        title: 'Richard Hampton Новосибирск'
    });
    google.maps.event.addListener(marker3, 'click', function() {
        infowindow3.open(map,marker3);
    });
    var contentString3 = '<div id="content2">'+
        '<div id="siteNotice">'+
        '</div>'+
        '<h1 id="firstHeading" class="firstHeading" style="line-height: 29px;">Richard Hampton Новосибирск</h1>'+
        '<div id="bodyContent">'+
        '<div class=\"adres\">Новосибирск, Красный <br> проспект, д. 92, ТЦ «Океан»</div>'+
        '<strong>+7 (383) 213-98-31</strong>'+
        '</div>'+
        '</div>';
    var infowindow3 = new google.maps.InfoWindow({ content: contentString3
    });
//====================================================================

}
google.maps.event.addDomListener(window, 'load', initialize);