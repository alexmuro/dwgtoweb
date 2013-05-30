<?php 
	if(isset($map_id) && !empty($map_id)){
		echo "Map $map_id";
?>
<script src='partials/getFloorPlan.js'></script>
<script src='partials/getBooths.js'></script>
<script>
	var map_id = <?php echo $map_id;?>;
	floor_layer = getFloorPlan(map_id);
	booth_layer = getBooths(map_id);
	map.addLayer(floor_layer);
	map.addLayer(booth_layer);
	
	booth_layer.events.register("loadend", booth_layer, function (e) {
		//console.log(booth_layer);
		booth_layer.StyleMap = getDefaultStyle();
		booth_layer.redraw();
		map.zoomToExtent(booth_layer.getDataExtent());
	});


	floor_layer.events.register("loadend", floor_layer, function (e) {

		floor_layer.StyleMap = getDefaultStyle();
		floor_layer.redraw();
		console.log('default style redrawn;')
		console.log(getDefaultStyle());
		map.zoomToExtent(floor_layer.getDataExtent());
		
	});

</script>

<?php
	}else{
		echo "No Map Selected.";
	}
?>