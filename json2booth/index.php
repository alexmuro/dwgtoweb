

<?php
 if(isset($_POST['bounds'])){$bounds = $_POST['bounds'];}else{$floor = array();}
 if(isset($_POST['booth_num'])){$booth_num = $_POST['booth_num'];}else{$floor = array();}
 if(isset($_POST['booths'])){$booths = $_POST['booths'];}else{$booths =array();}
 if(isset($_POST['floor'])){$floor = $_POST['floor'];}else{$floor = array();}
 
 //$floor = $_POST['floor'];
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

console.log(bounds,bounds.length);
console.log(booth_num);

if(typeof floor[undefined] != '0'){
	floor_data = loadData(floor);
}else{
	floor_data={};
}
booth_data = loadData(booths);
booth_num_data = loadData(booth_num);

console.log(floor_data);
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

scaleFloor(floor_data,scale,translateX,translateY);
topofloor = geo2topo(floor_data);


map_id = createMap(event_cycle_id,JSON.stringify(topofloor));
scaleFloor(booth_data,scale,translateX,translateY);
scaleFloor(booth_num_data,scale,translateX,translateY);

booth_output = geo2Booths(booth_data);
console.log(booth_output);

sendToImport(booth_output,event_cycle_id,map_id);

console.log('map created',map_id);
$('<p/>').html('Conversion complete.<a href="../manage/?mid='+map_id+'">Manage</a>').insertAfter('#export')





</script>
