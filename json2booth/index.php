

<?php
 if(isset($_POST['bounds'])){$bounds = $_POST['bounds'];}else{$floor = array();}
 if(isset($_POST['booth_num'])){$booth_num = $_POST['booth_num'];}else{$floor = array();}
 if(isset($_POST['booths'])){$booths = $_POST['booths'];}else{$booths =array();}
 if(isset($_POST['floor'])){$floor = $_POST['floor'];}else{$floor = array();}
 
 $event_cycle_id = '496';//$_GET['event_cycle_id']
?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src='../resources/js/topojson_parser/topojson_parser.js'></script>
<script src="../json2booth/logic.js"></script>

<script>
console.log('starting');
var max_square = 2800;
var padding = 20;
var maxX,maxY,minX,minY;
var translateX,translateY;
var scale;
var floor_data = {};
var booth_data = {};

booths = <?php echo json_encode($booths);?>;
floor = <?php echo json_encode($floor);?>;
booth_num =  <?php echo json_encode($booth_num);?>;
bounds =  <?php echo json_encode($bounds);?>;
event_cycle_id= <?php echo json_encode($event_cycle_id);?>;


if(typeof floor[0] != 'undefined'){
	floor_data = loadData(floor);
}else{
	floor_data={};
}
booth_data = loadData(booths);
booth_num_data = loadData(booth_num);

if(bounds.length == 4){
	minX = bounds[0]*1;
	maxX = bounds[1]*1;
	minY = bounds[2]*1;
	maxY = bounds[3]*1;	
	crop(floor_data);
}
else{
	findMinMax(floor_data);
	findMinMax(booth_data);
}


width = Math.abs(maxX+(minX*-1));
height = Math.abs(maxY+(minY*-1));

if(width >= height){
	scale = (width) / max_square;
}else{
	scale = (height) / max_square;
}

translateX = (minX*-1);
translateY = (maxY*-1);


// console.log("max_X:"+maxX);
// console.log("Min X:"+minX);
// console.log("max_y:"+maxY);
// console.log("Min Y:"+minY);
// console.log("width:"+width);
// console.log("height:"+height);
// console.log("scale:"+scale);
// console.log("translateX:"+ translateX);
// console.log("translateY:"+ translateY);
// console.log("maxY/scale:"+ (maxY/scale));
// console.log("maxX/scale:"+ (maxX/scale));
// console.log("scaled width:"+ ((maxX/scale)-(minX/scale)));
// console.log("scaled height:"+ ((maxY/scale)-(minY/scale)));

scaleGeoJSON(floor_data,scale,translateX,translateY);
topofloor = geo2topo(floor_data);




topofloor = topoparser.convert(topofloor);


map_id = createMap(event_cycle_id,JSON.stringify(topofloor));
scaleGeoJSON(booth_data,scale,translateX,translateY);
scaleGeoJSON(booth_num_data,scale,translateX,translateY);
booth_output = geo2Booths(booth_data);
sendToImport(booth_output,event_cycle_id,map_id);

$('<p/>').html('Conversion complete.<a href="http://admin.marketart.us/floormanager/?k=1&showid=496&mid='+map_id+'&sid=496">Manage</a>').insertAfter('#export')



</script>
