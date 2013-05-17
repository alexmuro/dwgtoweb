

<?php
 $booths = ['28180'];//$_GET['booths']
 $floor = ['28178'];//$_GET['floor']
 $event_cycle_id = '333';//$_GET['event_cycle_id']
?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src='../resources/js/topojson_parser/topojson_parser.js'></script>
<script src="logic.js"></script>

<script>
var max_square = 2800;
var padding = 20;
var maxX,maxY,minX,minY;
var translateX,translateY;
var scale;
var floor_data = {};
var booth_data = {};

booths = <?php echo json_encode($booths);?>;
floor = <?php echo json_encode($floor);?>;
event_cycle_id= <?php echo json_encode($event_cycle_id);?>;

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


// console.log(floor_data);
scaleFloor(floor_data,scale,translateX,translateY);
console.log(floor_data.features[0].geometry.coordinates[0][0]);
topofloor = geo2topo(floor_data);

console.log(topofloor);
//condensed = topoparser.convert(topofloor);
//console.log(condensed);

map_id = createMap(event_cycle_id,JSON.stringify(topofloor));
//booth_output = geo2Booths(booth_data);
// sendToImport(booth_output,event_cycle_id,map_id);
console.log('map created');
console.log(map_id);

// test = JSON.parse(topofloor);
// console.log(test);




</script>
