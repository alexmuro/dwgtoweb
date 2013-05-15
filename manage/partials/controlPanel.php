<?php 
	if(isset($map_id) && !empty($map_id)){
		echo "Map $map_id";
?>
<script src='partials/getFloorPlan.js'></script>
<script>
	var map_id = <?php echo $map_id;?>;
	newLayer = getFloorPlan(map_id);
	map.addLayer(newLayer);
	
	newLayer.events.register("loadend", newLayer, function (e) {
		console.log('projection:');
		console.log(map.getProjectionObject());
		console.log('load end and move- Extent:');
		console.log(newLayer);
		console.log(newLayer.getDataExtent());
		map.zoomToExtent(newLayer.getDataExtent());
	});

</script>

<?php
	}else{
		echo "No Map Selected.";
	}
?>