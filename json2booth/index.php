<?php
 $booths = ['28180'];//$_GET['booths']
 $floor = ['28178','28177'];//$_GET['floor']
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="logic.js"></script>
<script>
var max_square = 2800;
var padding = 20;
var maxX,maxY,minX,minY;
var translateX,translateY;
var scale;


booths = <?php echo json_encode($booths);?>;
floor = <?php echo json_encode($floor);?>;
var floor_data = {};
var booth_data = {};

console.log(floor.length + " " + floor[0]);
/*
for(var i =0;i<floor.length;i++){
	var layerurl = "../models/getLayerGeoJSON.php?layer="+String(floor[i]);
	$.ajax( {url:layerurl,async:false} )
    .done(function(data) { 
    	floor_data[i] = JSON.parse(data);
    })
    .fail(function() { console.error("loading error"); });
}
for(var i =0;i<booths.length;i++){
	var layerurl = "../models/getLayerGeoJSON.php?layer="+String(booths[i]);
	$.ajax( {url:layerurl,async:false} )
    .done(function(data) { 
    	booth_data[i] = JSON.parse(data);
    })
    .fail(function() { console.error("loading error"); });
}*/

booth_data = loadData(booths);
floor_data = loadData(floor);

findMinMax(floor_data);
findMinMax(booth_data);

width = Math.abs(maxX+(minX*-1));
height = Math.abs(maxY+(minY*-1));

if(width >= height){
	scale = (width-(padding*2)) / max_square;
}else{
	scale = (height-(padding*2)) / max_square;
}

translateX = (minX*-1)+padding;
translateY = (maxY*-1)+padding;

console.log("max_X:"+maxX);
console.log("Min X:"+minX);
console.log("max_y:"+maxY);
console.log("Min Y:"+minY);
console.log("width:"+width);
console.log("height:"+height);
console.log("scale:"+scale);
console.log("translateX:"+ translateX);
console.log("translateY:"+ translateY);
console.log("maxY/scale:"+ (maxY/scale));
console.log("maxX/scale:"+ (maxX/scale));
console.log("scaled width:"+ ((maxX/scale)-(minX/scale)));
console.log("scaled height:"+ ((maxY/scale)-(minY/scale)));


booth_output = createBooths(booth_data);
console.log(booth_output);
//sendToImport(booth_output,492);
//testImport(booth_output,492);
var mapid = createMap(492);
console.log(mapid);
sendToImport(booth_output,492,mapid);

</script>
