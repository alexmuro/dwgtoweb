<html>
<head>
<style>
.rounded-corners {
     -moz-border-radius: 20px;
    -webkit-border-radius: 20px;
    -khtml-border-radius: 20px;
    border-radius: 20px;
}
</style>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=geometry"></script>
<script type="text/javascript">
var infoWindow;
var poly = [];
var boothdata ={};
var labels = [];
var curZoom = 3;

$("body").ready(function() {load()});
$("body").unload(function() {GUnload();});

function load() {
    var domMap = $("#map")[0];
    var myLatLng = new google.maps.LatLng(0,0);
    var myOptions = {
            zoom: curZoom,
            center: myLatLng,
            mapTypeControlOptions: {
                mapTypeIds: ['TControl']
            },
            StreetViewControl: false,
            //scaleControl: true,
            zoomControl: true,
            zoomControlOptions: {
            style: google.maps.ZoomControlStyle.LARGE,
            position: google.maps.ControlPosition.LEFT_BOTTOM
            },
            panControl:true,
            panControlOptions:{
              position: google.maps.ControlPosition.LEFT_BOTTOM
            }
    };

    var map = new google.maps.Map(domMap, myOptions);

    createBlankCanvas(map, 100, 100);
    //draw_poly(map,4,'#000');
    draw_dwg(map,4,'#00f');
    //Map is intialized

    var marker = new google.maps.Marker({
       position: myLatLng,
       draggable: true,
       map: map
     });

    var label = new Label({
       map: map
     });
     label.bindTo('position', marker, 'position');
     label.bindTo('text', marker, 'position');

    google.maps.event.addListener(map,"zoom_changed", function(event) {
              
              var newZoom = map.getZoom();
             /*
              if(curZoom == 7 && newZoom == 8)
              {
                for(i= 0; i < labels.length; i++)
                {
                  labels[i].show();

                }
              }
              else if(curZoom == 8 && newZoom == 7)
              {
                for(i= 0; i < labels.length; i++)
                {
                  labels[i].hide();

                }

              }
              */
              document.getElementById("names").innerHTML='<b>Booth Number:</b>'+this.name+'<br><b>Zoom:</b> '+newZoom+'<br><b>'
              curZoom = newZoom;
             
            });
     
     //label.bindTo('position', marker, 'position');
     //label.bindTo('text', marker, 'position');
     //var ctaLayer = new google.maps.KmlLayer('12i  aee.kml');
    //ctaLayer.setMap(map);
   
}

function createBlankCanvas(map, width, height) {
    var minZoomLevel = 0;
    var maxZoomLevel = 15;

    // Crea un fons en blanc
    var ubicaciones = new google.maps.ImageMapType({
            getTileUrl: function(coord, zoom) {
                return "";
            },
        tileSize: new google.maps.Size(width, height),
        maxZoom:maxZoomLevel,
        minZoom:minZoomLevel,
        isPng: true,
        name: 'MarketArt',
        opacity: 0.5
    });

    map.mapTypes.set('TControl', ubicaciones);
    map.setMapTypeId('TControl');
    map.setZoom(8);
    var ctaLayer = new google.maps.KmlLayer('test/12iaee.kml');
    ctaLayer.setMap(map);
}
</script>
<script src="js/GM_LoadMap.js"></script>
<script src="js/label.js"></script>


<?php
include 'config/db.php';
$showid = 95;
$mapid = 3;

include 'functions/drawDWG.php';
?>
</head>
<body>
<div id="stuff"  
class="rounded-corners" style="position:absolute;top:40px;right:10px;background-color:#fff;width:300px;height:525px; z-index: 99; ">
  <div style='padding:10px;'>
    <div id="menu"><b><?php //echo $curShow->name();?></b></div>
    <div id="names"></div>
    <div id='data'>
      <pre>
        <?php print_r($results); ?>
      </pre>
    </div>
  </div>
  </div>
  <div id="legend" class="rounded-corners" style="position:absolute;top:10px;left:10px;background-color:#fff;width:200px;height:160px; z-index: 99; ">
  </div>
  <div id="map" style="width:100%;height:100%;"></div>
</body>
</html>
